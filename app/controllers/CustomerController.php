<?php

class CustomerController extends BaseController
{
    private $customer;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->customer = $this->model('customer');
        $this->db = $this->customer->getConnection();
    }

    public function index()
    {
        unset($_SESSION['old']);
        $where_condition['company_id'] = $this->companyId;

        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;

        $where_condition = $this->search($search, $where_condition, ['customer_code', 'name', 'email', 'phone', 'address']);
        $pagination = $this->customer->pagination($this->db, $page, 'customer', 'id', $where_condition);

        $customers = $this->customer->getAll($where_condition, $pagination['offset'], $pagination['limit']);

        $this->view('customer/index', [
            'search' => $search,
            'page' => $page,
            'pagination' => $pagination,
            'customers' => $customers,
        ]);
    }

    public function add()
    {
        $customer_code = $this->customer->generateCode($this->db, "customer", "customer_code", "CUST");

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('customer/add', ['customer_code' => $customer_code]);
            return;
        }

        $_POST['company_id'] = $this->companyId;
        $duplicateError = $this->findDuplicateContactField($_POST['email'], $_POST['phone']);

        if ($duplicateError) {
            $_SESSION['error'] = $duplicateError;
            $_SESSION['old'] = $_POST;
            $this->redirect(BASEURL . 'customer/add');
            return;
        }

        unset($_SESSION['old']);
        $this->customer->create($_POST);
        $this->redirect(BASEURL . 'customer');
    }

    public function edit($id)
    {
        $datas = $this->customer->find($id);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('customer/edit', $datas);
            return;
        }

        $duplicateError = $this->findDuplicateContactField($_POST['email'], $_POST['phone'], $id);

        if ($duplicateError) {
            $_SESSION['error'] = $duplicateError;
            $this->view('customer/edit', $datas);
            return;
        }

        $this->customer->update($_POST, ['id' => $id]);
        $this->redirect(BASEURL . 'customer');
    }

    public function delete($id)
    {
        $isUsedByInvoice = $this->db->has('invoice', ['customer_id' => $id]);

        if ($isUsedByInvoice) {
            echo '<script>
                alert("The customer cannot be deleted because it is still being used by another table.");
                window.location.href = "' . BASEURL . 'customer";
            </script>';
            return;
        }

        $this->customer->delete($id);
        $this->redirect(BASEURL . 'customer');
    }

    public function exportCsv()
    {
        $customers = $this->customer->getAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Customer_Report_' . date('Ymd_His') . '.csv"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['NO', 'CUSTOMER CODE', 'NAME', 'EMAIL', 'PHONE', 'ADDRESS'], ',');

        $no = 1;
        foreach ($customers as $customer) {
            fputcsv($output, [
                $no++,
                $customer['customer_code'],
                $customer['name'],
                $customer['email'],
                $customer['phone'],
                $customer['address'],
            ], ',');
        }

        fclose($output);
        exit;
    }

    public function importCsv()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('customer/import');
            return;
        }

        $upload = $this->storeUploadedCsv($_FILES['file_name'] ?? null);

        if (isset($upload['error'])) {
            $this->view('customer/import', ['import_errors' => [$upload['error']]]);
            return;
        }

        $importer = new CustomerImportService($this->customer, $this->companyId);
        $result = $importer->importFromFile($upload['path']);

        unlink($upload['path']);

        if (!empty($result['errors'])) {
            $this->view('customer/import', ['import_errors' => $result['errors']]);
            return;
        }

        $this->view('customer/import', [
            'imported_count' => $result['imported_count'],
            'updated_count'  => $result['updated_count'],
            'skipped_rows'   => $result['skipped'],
        ]);
    }

    private function findDuplicateContactField(string $email, string $phone, $excludeId = null): ?string
    {
        $emailCondition = ['email' => $email];
        $phoneCondition = ['phone' => $phone];

        if ($excludeId !== null) {
            $emailCondition = ['AND' => ['email' => $email, 'id[!]' => $excludeId]];
            $phoneCondition = ['AND' => ['phone' => $phone, 'id[!]' => $excludeId]];
        }

        if ($this->db->has('customer', $emailCondition)) {
            return 'Email already exists';
        }

        if ($this->db->has('customer', $phoneCondition)) {
            return 'Phone number already exists';
        }

        return null;
    }

    private function storeUploadedCsv(?array $file): array
    {
        if (!$file || $file['error'] > 0) {
            $code = $file['error'] ?? 'unknown';
            return ['error' => "Error uploading file (code: {$code})."];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($extension !== 'csv') {
            return ['error' => 'Invalid file format. Only .csv files are allowed!'];
        }

        $targetDir = __DIR__ . '/../../public/uploads/customer/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetFile = $targetDir . 'import_customer_' . time() . '.' . $extension;

        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            return ['error' => 'Failed to move uploaded file to storage directory.'];
        }

        return ['path' => $targetFile];
    }
}
