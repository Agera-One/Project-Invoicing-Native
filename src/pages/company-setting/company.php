<?php
require_once '../../connection.php';

$company = $database->get('company', '*');
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Profile Settings</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #222e3c;
        }

        .app-main {
            background-color: #2c3034 !important;
            color: #ffffff;
        }

        .custom-dark-card {
            background-color: #212529 !important;
            border: 1px solid #373b3e !important;
            border-radius: 6px;
            margin-bottom: 24px;
        }

        .custom-dark-card .card-header {
            border-bottom: 1px solid #373b3e !important;
            padding: 12px 20px;
        }

        .custom-dark-card .card-title {
            color: #ced4da;
            font-size: 1rem;
        }

        .custom-dark-card .card-body {
            padding: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .info-value {
            color: #adb5bd;
            margin-bottom: 15px;
        }

        .btn-custom-warning {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: #000000 !important;
            font-weight: 500;
            font-size: 14px;
        }

        .btn-custom-warning:hover {
            background-color: #e0a800 !important;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg">
    <div class="app-wrapper">
        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4 min-vh-100">
            <div class="container-fluid px-4">
                <div class="mb-4">
                    <h3 class="fw-bold h4 m-0 text-white">Company Profile Settings</h3>
                    <p class="text-muted small m-0">Manage your company information, contact details, branding, and business identity</p>
                </div>

                <div class="content-wrapper">
                    <div class="app-content">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card custom-dark-card">
                                    <div class="card-header">
                                        <span class="card-title mb-0">Company Information</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="info-label">Company Name</div>
                                                <div class="info-value"><?= $company['name'] ?? '-' ?></div>
                                            </div>
                                            <div class="col-6">
                                                <div class="info-label">Business Entity</div>
                                                <div class="info-value"><?= $company['business_entity'] ?? '-' ?></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="info-label">Business Sector</div>
                                                <div class="info-value"><?= $company['sector'] ?? '-' ?></div>
                                            </div>
                                            <div class="col-6">
                                                <div class="info-label">Business Website</div>
                                                <div class="info-value"><?= $company['website_url'] ?? '-' ?></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="info-label">Business Description</div>
                                                <div class="info-value"><?= $company['description'] ?? '-' ?></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="info-label">Country</div>
                                                <div class="info-value"><?= $company['country'] ?? '-' ?></div>
                                            </div>
                                            <div class="col-6">
                                                <div class="info-label">Province</div>
                                                <div class="info-value"><?= $company['province'] ?? '-' ?></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="info-label">City/Regency</div>
                                                <div class="info-value"><?= $company['city_or_regency'] ?? '-' ?></div>
                                            </div>
                                            <div class="col-6">
                                                <div class="info-label">Subdistrict</div>
                                                <div class="info-value"><?= $company['subdistrict'] ?? '-' ?></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="info-label">Business Address</div>
                                                <div class="info-value mb-0"><?= $company['address'] ?? '-' ?></div>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <a href="company-edit.php?info&id=<?= $company['id'] ?>" class="btn btn-custom-warning btn-sm px-3">
                                                <i class="bi bi-pencil-square me-1"></i> Change
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="card custom-dark-card">
                                    <div class="card-header">
                                        <span class="card-title mb-0">Company Contact Information</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="info-label">Company Email</div>
                                                <div class="info-value"><?= $company['email'] ?? '-' ?></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="info-label">Company Phone Number</div>
                                                <div class="info-value mb-0"><?= $company['phone'] ?? '-' ?></div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <a href="company-edit.php?contact&id=<?= $company['id'] ?>" class="btn btn-custom-warning btn-sm px-3">
                                                <i class="bi bi-pencil-square me-1"></i> Change
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>