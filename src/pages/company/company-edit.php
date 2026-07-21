<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['info'])) {

    $id = $_GET['id'];

    $company = $database->get('company', [
        'name',
        'business_entity',
        'sector',
        'website_url',
        'description',
        'country',
        'province',
        'city_or_regency',
        'subdistrict',
        'address'
    ], [
        'id' => $id
    ]);
}

if (isset($_GET['contact'])) {

    $id = $_GET['id'];

    $company = $database->get('company', [
        'email',
        'phone'
    ], [
        'id' => $id
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['info'])) {
        $data = [
            'name'             => $_POST['name'],
            'business_entity'  => $_POST['business_entity'],
            'sector'           => $_POST['sector'],
            'website_url'      => $_POST['website_url'],
            'description'      => $_POST['description'],
            'country'          => $_POST['country'],
            'province'         => $_POST['province'],
            'city_or_regency'  => $_POST['city_or_regency'],
            'subdistrict'      => $_POST['subdistrict'],
            'address'          => $_POST['address']
        ];
    } elseif (isset($_GET['contact'])) {
        $data = [
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ];
    }

    if (isset($data)) {
        $database->update('company', $data, [
            'id' => $id
        ]);
    }

    header('Location: company.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company Profile</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Edit Company Profile</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="../company/company.php">Company Profile Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Company Profile</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Edit Company Information</div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <?php if (isset($_GET['info'])): ?>
                                <div class="mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="<?= $company['name'] ?>"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Business Entity</label>
                                    <select name="business_entity" class="form-select" required>
                                        <option value="" disabled <?= empty($company['business_entity']) ? 'selected' : '' ?>>
                                            Select Business Entity
                                        </option>
                                        <option value="PT" <?= ($company['business_entity'] == 'PT') ? 'selected' : '' ?>>
                                            PT
                                        </option>
                                        <option value="CV" <?= ($company['business_entity'] == 'CV') ? 'selected' : '' ?>>
                                            CV
                                        </option>
                                        <option value="Firma" <?= ($company['business_entity'] == 'Firma') ? 'selected' : '' ?>>
                                            Firma
                                        </option>
                                        <option value="Koperasi" <?= ($company['business_entity'] == 'Koperasi') ? 'selected' : '' ?>>
                                            Koperasi
                                        </option>
                                        <option value="Perorangan" <?= ($company['business_entity'] == 'Perorangan') ? 'selected' : '' ?>>
                                            Perorangan
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Business Sector</label>
                                    <input
                                        type="text"
                                        name="sector"
                                        class="form-control"
                                        value="<?= $company['sector'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Website</label>
                                    <input
                                        type="url"
                                        name="website_url"
                                        class="form-control"
                                        value="<?= $company['website_url'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Business Description</label>
                                    <textarea
                                        name="description"
                                        class="form-control"
                                        rows="4"><?= $company['description'] ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Country</label>
                                    <input
                                        type="text"
                                        name="country"
                                        class="form-control"
                                        value="<?= $company['country'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Province</label>
                                    <input
                                        type="text"
                                        name="province"
                                        class="form-control"
                                        value="<?= $company['province'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City / Regency</label>
                                    <input
                                        type="text"
                                        name="city_or_regency"
                                        class="form-control"
                                        value="<?= $company['city_or_regency'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subdistrict</label>
                                    <input
                                        type="text"
                                        name="subdistrict"
                                        class="form-control"
                                        value="<?= $company['subdistrict'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea
                                        name="address"
                                        class="form-control"
                                        rows="3"><?= $company['address'] ?></textarea>
                                </div>

                            <?php elseif (isset($_GET['contact'])): ?>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        value="<?= $company['email'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control"
                                        value="<?= $company['phone'] ?>">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="company.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include '../../components/scripts.php'; ?>
</body>

</html>