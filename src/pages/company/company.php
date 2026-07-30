<?php
session_start();
require_once '../../connection.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$company_id = $database->get('user', 'company_id', [
    'id' => $user_id
]);

$company = $database->get('company', '*', [
    'id' => $company_id
]);
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
    <link rel="stylesheet" href="../../../assets/css/company.css">
</head>

<body class="layout-fixed fixed-header sidebar-expand-lg">
    <div class="app-wrapper">
        <?php include_once '../../components/navbar.php'; ?>

        <?php include_once '../../components/sidebar.php'; ?>

        <main class="app-main py-4 min-vh-100">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Company Profile Settings</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Company Profile Settings</li>
                        </ol>
                    </div>
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
                                                <div class="info-value"><?= empty($company['website']) ? '-' : $company['website'] ?></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="info-label">Business Description</div>
                                                <div class="info-value"><?= empty($company['description']) ? '-' : $company['description'] ?></div>
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
                                                <div class="info-value"><?= $company['city'] ?? '-' ?></div>
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

                                <div class="card custom-dark-card mt-3">
                                    <div class="card-header">
                                        <span class="card-title mb-0">Document Branding</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-6 text-center">
                                                <div class="text-muted small mb-2">Company Logo</div>
                                                <form action="company-upload.php?id=<?= $company['id'] ?>" method="POST" enctype="multipart/form-data" id="form-logo">
                                                    <input type="file" id="logo-input" name="logo" accept=".png,.jpg,.jpeg,.svg" class="d-none" onchange="document.getElementById('form-logo').submit();">
                                                    <div class="border border-dashed rounded p-4 text-center cursor-pointer bg-dark d-flex flex-column align-items-center justify-content-center"
                                                        style="border-style: dashed !important; border-color: #6c757d !important; min-height: 140px; cursor: pointer;"
                                                        onclick="document.getElementById('logo-input').click();">

                                                        <?php if (!empty($company['logo'])) : ?>
                                                            <img src="../../../storage/<?= htmlspecialchars($company['logo']) ?>" class="img-fluid rounded mb-2" style="max-height: 60px; object-fit: contain;">
                                                            <span class="text-muted small text-truncate w-100 px-2"><?= htmlspecialchars($company['logo']) ?></span>
                                                        <?php else : ?>
                                                            <i class="bi bi-cloud-arrow-up text-secondary h2 mb-2"></i>
                                                            <span class="text-secondary small">Select a logo (1:1)</span>
                                                        <?php endif; ?>

                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-6 text-center">
                                                <div class="text-muted small mb-2">Signature</div>
                                                <form action="company-upload.php?id=<?= $company['id'] ?>" method="POST" enctype="multipart/form-data" id="form-signature">
                                                    <input type="file" id="signature-input" name="signature" accept=".png,.jpg,.jpeg" class="d-none" onchange="document.getElementById('form-signature').submit();">
                                                    <div class="border border-dashed rounded p-4 text-center cursor-pointer bg-dark d-flex flex-column align-items-center justify-content-center"
                                                        style="border-style: dashed !important; border-color: #6c757d !important; min-height: 140px; cursor: pointer;"
                                                        onclick="document.getElementById('signature-input').click();">

                                                        <?php if (!empty($company['signature'])) : ?>
                                                            <div class="bg-white p-1 rounded mb-2 d-flex align-items-center justify-content-center" style="width: 100%; max-width: 120px; height: 60px;">
                                                                <img src="../../../storage/<?= htmlspecialchars($company['signature']) ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                                            </div>
                                                            <span class="text-muted small text-truncate w-100 px-2"><?= htmlspecialchars($company['signature']) ?></span>
                                                        <?php else : ?>
                                                            <i class="bi bi-pencil text-secondary h3 mb-2"></i>
                                                            <span class="text-secondary small">Select a signature (PNG)</span>
                                                        <?php endif; ?>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="text-center mt-3">
                                            <small class="text-secondary" style="font-size: 0.75rem;">
                                                Recommended format: Transparent PNG. Max 2MB.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
    </div>

    <script src="../../../assets/js/company.js"></script>
    <script src="../../../assets/js/lte-theme.js"></script>
    <script src="../../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>