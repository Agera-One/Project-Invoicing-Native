<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company Profile</title>
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/adminlte.min.css' ?>">
    <link rel="stylesheet" href="<?= BASEURL . 'public/css/bootstrap.css' ?>">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include_once __DIR__ . '/../../components/navbar.php' ?>
        <?php include_once __DIR__ . '/../../components/sidebar.php' ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">Edit Company Profile</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'dashboard' ?>">Dashboard</a></li>
                            <li class="breadcrumb-item text-decoration-none"><a href="<?= BASEURL . 'company' ?>">Company Profile Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Company Profile</li>
                        </ol>
                    </div>
                </div>

                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">Edit Company Information</div>
                    </div>
                    <?php if ($section === 'info'): ?>
                        <form action="<?= BASEURL . 'company/info' ?>" method="POST">
                        <?php elseif ($section === 'contact'): ?>
                            <form action="<?= BASEURL . 'company/contact' ?>" method="POST">
                            <?php endif; ?>
                            <div class="card-body">
                                <?php if ($section === 'info'): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input
                                            type="text"
                                            name="name"
                                            class="form-control"
                                            value="<?= $name ?? '' ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Business Entity</label>
                                        <select name="business_entity" class="form-select" required>
                                            <option value="" disabled <?= empty($business_entity) ? 'selected' : '' ?>>
                                                Select Business Entity
                                            </option>
                                            <option value="PT" <?= (($business_entity ?? '') == 'PT') ? 'selected' : '' ?>>
                                                PT
                                            </option>
                                            <option value="CV" <?= (($business_entity ?? '') == 'CV') ? 'selected' : '' ?>>
                                                CV
                                            </option>
                                            <option value="UD" <?= (($business_entity ?? '') == 'UD') ? 'selected' : '' ?>>
                                                UD
                                            </option>
                                            <option value="Firma" <?= (($business_entity ?? '') == 'Firma') ? 'selected' : '' ?>>
                                                Firma
                                            </option>
                                            <option value="Koperasi" <?= (($business_entity ?? '') == 'Koperasi') ? 'selected' : '' ?>>
                                                Koperasi
                                            </option>
                                            <option value="Perorangan" <?= (($business_entity ?? '') == 'Perorangan') ? 'selected' : '' ?>>
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
                                            value="<?= $sector ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Website</label>
                                        <input
                                            type="url"
                                            name="website"
                                            class="form-control"
                                            value="<?= $website ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Business Description</label>
                                        <textarea
                                            name="description"
                                            class="form-control"
                                            rows="4"><?= $description ?? '' ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <input
                                            type="text"
                                            name="country"
                                            class="form-control"
                                            value="<?= $country ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Province</label>
                                        <input
                                            type="text"
                                            name="province"
                                            class="form-control"
                                            value="<?= $province ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">City / Regency</label>
                                        <input
                                            type="text"
                                            name="city"
                                            class="form-control"
                                            value="<?= $city ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Subdistrict</label>
                                        <input
                                            type="text"
                                            name="subdistrict"
                                            class="form-control"
                                            value="<?= $subdistrict ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea
                                            name="address"
                                            class="form-control"
                                            rows="3"><?= $address ?? '' ?></textarea>
                                    </div>

                                <?php elseif ($section === 'contact'): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            value="<?= $email ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input
                                            type="text"
                                            name="phone"
                                            class="form-control"
                                            value="<?= $phone ?? '' ?>">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="<?= BASEURL . 'company' ?>" class="btn btn-danger">Cancel</a>
                            </div>
                            </form>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= BASEURL . 'public/js/company.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/lte-theme.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/adminlte.js' ?>"></script>
    <script src="<?= BASEURL . 'public/js/bootstrap.bundle.js' ?>"></script>
</body>

</html>