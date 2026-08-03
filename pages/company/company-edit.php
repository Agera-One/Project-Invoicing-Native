<?php
session_start();
require_once "../../config/database.php";
require_once "../../classes/Company.php";

$db = (new Database())->getConnection();
$company = new Company($db);

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];

if (!isset($user_id)) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];
$section = isset($_GET['info']) ? 'info' : 'contact';

if ($section === 'info') {
    $data = $company->find([
        'name',
        'business_entity',
        'sector',
        'website',
        'description',
        'country',
        'province',
        'city',
        'subdistrict',
        'address'
    ], $id);

} elseif ($section === 'contact') {
    $data = $company->find(['email', 'phone'], $id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email_exists = $db->has('company', [
        'AND' => [
            'email' => $_POST['email'],
            'id[!]' => $id
        ]
    ]);

    $phone_exists = $db->has('company', [
        'AND' => [
            'phone' => $_POST['phone'],
            'id[!]' => $id
        ]
    ]);

    if ($email_exists) {
        echo '<script>alert("Email already exists")</script>';
    } elseif ($phone_exists) {
        echo '<script>alert("phone already exists")</script>';
    } else {
        $company->update($id, $_POST, $section);
    
        header('Location: company.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company Profile</title>
    <link rel="stylesheet" href="../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once '../../src/components/navbar.php' ?>
        <?php include_once '../../src/components/sidebar.php' ?>

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
                            <?php if ($section === 'info'): ?>
                                <div class="mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="<?= $data['name'] ?>"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Business Entity</label>
                                    <select name="business_entity" class="form-select" required>
                                        <option value="" disabled <?= empty($data['business_entity']) ? 'selected' : '' ?>>
                                            Select Business Entity
                                        </option>
                                        <option value="PT" <?= ($data['business_entity'] == 'PT') ? 'selected' : '' ?>>
                                            PT
                                        </option>
                                        <option value="CV" <?= ($data['business_entity'] == 'CV') ? 'selected' : '' ?>>
                                            CV
                                        </option>
                                        <option value="Firma" <?= ($data['business_entity'] == 'Firma') ? 'selected' : '' ?>>
                                            Firma
                                        </option>
                                        <option value="Koperasi" <?= ($data['business_entity'] == 'Koperasi') ? 'selected' : '' ?>>
                                            Koperasi
                                        </option>
                                        <option value="Perorangan" <?= ($data['business_entity'] == 'Perorangan') ? 'selected' : '' ?>>
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
                                        value="<?= $data['sector'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Website</label>
                                    <input
                                        type="url"
                                        name="website"
                                        class="form-control"
                                        value="<?= $data['website'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Business Description</label>
                                    <textarea
                                        name="description"
                                        class="form-control"
                                        rows="4"><?= $data['description'] ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Country</label>
                                    <input
                                        type="text"
                                        name="country"
                                        class="form-control"
                                        value="<?= $data['country'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Province</label>
                                    <input
                                        type="text"
                                        name="province"
                                        class="form-control"
                                        value="<?= $data['province'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City / Regency</label>
                                    <input
                                        type="text"
                                        name="city"
                                        class="form-control"
                                        value="<?= $data['city'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subdistrict</label>
                                    <input
                                        type="text"
                                        name="subdistrict"
                                        class="form-control"
                                        value="<?= $data['subdistrict'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea
                                        name="address"
                                        class="form-control"
                                        rows="3"><?= $data['address'] ?></textarea>
                                </div>

                            <?php elseif ($section === 'contact'): ?>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        value="<?= $data['email'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control"
                                        value="<?= $data['phone'] ?>">
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

    <script src="../../assets/js/company.js"></script>
    <script src="../../assets/js/lte-theme.js"></script>
    <script src="../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
</body>

</html>