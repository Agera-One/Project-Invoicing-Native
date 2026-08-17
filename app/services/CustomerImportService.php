<?php

class CustomerImportService
{
    private const REQUIRED_HEADER = 'NAME';
    private const CODE_PREFIX = 'INV';
    private const CODE_PATTERN = '/^INV-\d{4}-\d{4}$/';
    private const TEMPLATE_COLUMNS = ['CUSTOMER CODE', 'NAME', 'EMAIL', 'PHONE', 'ADDRESS'];

    private $customer;
    private $db;
    private $companyId;

    public function __construct(Customer $customer, $companyId)
    {
        $this->customer = $customer;
        $this->db = $customer->getConnection();
        $this->companyId = $companyId;
    }

    /**
     * @return array{errors: array, skipped: array, imported_count: int, updated_count: int}
     */
    public function importFromFile(string $filePath): array
    {
        $result = [
            'errors'         => [],
            'skipped'        => [],
            'imported_count' => 0,
            'updated_count'  => 0,
        ];

        $handle = fopen($filePath, 'r');

        if (!$handle) {
            $result['errors'][] = 'Unable to read the uploaded file.';
            return $result;
        }

        $header = $this->readHeader($handle);

        if (!$header || !in_array(self::REQUIRED_HEADER, $header, true)) {
            $result['errors'][] = 'Invalid CSV header format. The required "NAME" column was not found. '
                . 'Make sure the column order follows the template: ' . implode(', ', self::TEMPLATE_COLUMNS) . '.';
            fclose($handle);
            return $result;
        }

        $rowNumber = 1;
        $usedCodesThisBatch = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $rowNumber++;

            if ($this->isBlankRow($row)) {
                continue;
            }

            $data = $this->mapRowToData($header, $row);

            $skipReason = $this->validateRow($data);
            if ($skipReason !== null) {
                $result['skipped'][] = ['row' => $rowNumber, 'reason' => $skipReason];
                continue;
            }

            $code = $this->resolveCustomerCode($data, $usedCodesThisBatch, $skipReason);
            if ($code === null) {
                $result['skipped'][] = ['row' => $rowNumber, 'reason' => $skipReason];
                continue;
            }

            $usedCodesThisBatch[] = $code;
            $this->saveRow($data, $code, $result);
        }

        fclose($handle);

        return $result;
    }

    private function readHeader($handle): ?array
    {
        $header = fgetcsv($handle, 0, ',');

        if (!$header) {
            return null;
        }

        return array_map(function ($column) {
            return trim(strtoupper(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $column)));
        }, $header);
    }

    private function isBlankRow(array $row): bool
    {
        return $row === [null] || (count($row) === 1 && trim((string) $row[0]) === '');
    }

    private function mapRowToData(array $header, array $row): array
    {
        $headerCount = count($header);
        $rowCount = count($row);

        if ($rowCount < $headerCount) {
            $row = array_pad($row, $headerCount, '');
        } elseif ($rowCount > $headerCount) {
            $row = array_slice($row, 0, $headerCount);
        }

        $raw = array_combine($header, $row);

        return [
            'customer_code' => trim($raw['CUSTOMER CODE'] ?? ''),
            'name'          => trim($raw['NAME'] ?? ''),
            'email'         => trim($raw['EMAIL'] ?? ''),
            'phone'         => trim($raw['PHONE'] ?? ''),
            'address'       => trim($raw['ADDRESS'] ?? ''),
        ];
    }

    private function validateRow(array $data): ?string
    {
        if ($data['name'] === '' && $data['email'] === '' && $data['phone'] === '' && $data['address'] === '') {
            return 'Empty row, no data found in any column.';
        }

        if ($data['name'] === '') {
            return 'NAME column is empty. Row skipped because name is required.';
        }

        $missing = [];
        foreach (['email' => 'EMAIL', 'phone' => 'PHONE', 'address' => 'ADDRESS'] as $key => $label) {
            if ($data[$key] === '') {
                $missing[] = $label;
            }
        }

        if (!empty($missing)) {
            $plural = count($missing) > 1 ? 's are' : ' is';
            return implode(', ', $missing) . " column{$plural} empty. Row skipped because it is required.";
        }

        return null;
    }

    /**
     * Mengembalikan customer_code final, atau null jika gagal (reason dikirim lewat &$errorOut).
     */
    private function resolveCustomerCode(array $data, array $usedCodesThisBatch, ?string &$errorOut): ?string
    {
        $customerCode = $data['customer_code'];

        if ($customerCode === '') {
            return $this->customer->generateCode($this->db, 'customer', 'customer_code', self::CODE_PREFIX);
        }

        if (!preg_match(self::CODE_PATTERN, $customerCode)) {
            $errorOut = "Invalid CUSTOMER CODE format (must be INV-YYYY-XXXX): \"{$customerCode}\".";
            return null;
        }

        if (in_array($customerCode, $usedCodesThisBatch, true)) {
            $errorOut = "CUSTOMER CODE \"{$customerCode}\" duplicates another row in the same file.";
            return null;
        }

        $checkCondition = $data['email'] !== '' ? ['email' => $data['email']] : ['name' => $data['name']];

        if ($this->customer->isCodeTakenByOther($customerCode, $checkCondition)) {
            $errorOut = "CUSTOMER CODE \"{$customerCode}\" is already used by another customer.";
            return null;
        }

        return $customerCode;
    }

    private function saveRow(array $data, string $customerCode, array &$result): void
    {
        $customerData = [
            'customer_code' => $customerCode,
            'name'          => $data['name'],
            'email'         => $data['email'] !== '' ? $data['email'] : null,
            'phone'         => $data['phone'],
            'address'       => $data['address'],
            'company_id'    => $this->companyId,
        ];

        $checkCondition = $data['email'] !== '' ? ['email' => $data['email']] : ['name' => $data['name']];

        if ($this->customer->has('customer', $checkCondition)) {
            $this->customer->update($customerData, $checkCondition);
            $result['updated_count']++;
        } else {
            $this->customer->create($customerData);
            $result['imported_count']++;
        }
    }
}
