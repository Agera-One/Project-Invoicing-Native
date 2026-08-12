<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Invoices</title>
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/adminlte.min.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/bootstrap.css' ?>">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body class="layout-fixed fixed-header sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once __DIR__ . '/../../components/navbar.php' ?>
        <?php include_once __DIR__ . '/../../components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Overdue Invoices</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'dashboard' ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Overdue Invoices</li>
                        </ol>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="col-md-4 d-flex gap-2">
                        <form action="" method="GET" class="flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input name="search" id="table-filter" type="search"
                                    class="form-control border-start-0 ps-0" placeholder="Filter rows…"
                                    aria-label="Filter rows" autofocus autocomplete="off"
                                    value="<?= $search ?? '' ?>">
                            </div>
                        </form>
                        <a href="<?= BASEURL . 'overdue' ?>" class="btn btn-outline-secondary w-25">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 tracking-wider">
                                    <tr>
                                        <th scope="col" class="ps-4" width="60">#</th>
                                        <th scope="col">Invoice Code</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Total Bill</th>
                                        <th scope="col">Amount Paid</th>
                                        <th scope="col">Remaining Unpaid</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $invoice):
                                        $remaining_unpaid = $invoice['total_bill'] - $invoice['total_amount_paid'] ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= ++$pagination['offset'] ?></th>
                                            <td class="fw-medium"><?= $invoice['invoice_code'] ?></td>
                                            <td><?= $invoice['customer_name'] ?></td>
                                            <td><?= $invoice['date'] ?></td>
                                            <td><?= $invoice['due_date'] ?></td>
                                            <td>Rp<?= number_format($invoice['total_bill'], 0, ',', '.') ?></td>
                                            <td>Rp<?= number_format($invoice['total_amount_paid'], 0, ',', '.') ?></td>
                                            <td class="text-danger">Rp<?= number_format($remaining_unpaid, 0, ',', '.') ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-success" href="../payment/payment-add.php?invoice_id=<?= $invoice['id'] ?>">Pay</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php include_once __DIR__ . '/../../components/pagination.php' ?>
                </div>

            </div>
        </main>
    </div>

    <script src="<?= BASEURL . 'public/js/lte-theme.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/adminlte.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/bootstrap.bundle.js' ?>"></script>
</body>

</html>