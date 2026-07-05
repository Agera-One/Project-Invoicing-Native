<?php
require_once '../../connection.php';

$number = 1;
$limit = 10;

$active_page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($active_page - 1) * $limit;

$rows = count($database->select("user", "*"));
$total_page = ceil($rows / $limit);

$users = $database->select('user', '*', [
    'ORDER' => [
        'id' => 'DESC'
    ],
    'LIMIT' => [$offset, $limit]
]);

if (isset($_POST['search'])) {
    $search = $_POST['search'];

    $users = $database->select('user', '*', [
        'OR' => [
            'name[~]' => $search,
            'phone[~]' => $search,
            'email[~]' => $search,
            'position[~]' => $search,
        ],
        'ORDER' => [
            'id' => 'DESC'
        ],
        'LIMIT' => [$offset, $limit]
    ]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../layouts/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">

                <!-- Page Title -->
                <div class="mb-3">
                    <h3 class="fw-bold h4 m-0 text-white">User & PIC Management</h3>
                    <p class="text-muted small m-0">Manage platform administrators, system users, and assignment privileges</p>
                </div>

                <!-- Header halaman & Tombol navigasi -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="user-add.php" class="btn btn-primary shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New User
                        </a>
                    </div>

                    <!-- Kolom Pencarian -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <form action="" method="POST" class="m-0">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input name="search" id="table-filter" type="search" class="form-control border-start-0 ps-0" placeholder="Filter rows…" aria-label="Filter rows" autofocus autocomplete="off">
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card Pembungkus Tabel -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase fs-7 tracking-wider">
                                    <tr>
                                        <th scope="col" class="ps-4" width="60">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Position</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="pe-4" width="160">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= ++$offset ?></th>
                                            <td><?= $user['name'] ?></td>
                                            <td><?= $user['phone'] ?></td>
                                            <td><?= $user['email'] ?></td>
                                            <td><?= $user['position'] ?></td>
                                            <?= ($user['status'] == 'active') ? '<td><span class="badge text-bg-success"> Active </span></td>' : '<td><span class="badge text-bg-danger"> Inactive </span></td>'; ?>
                                            <td class="pe-4">
                                                <div class="d-flex gap-1">
                                                    <a class="btn btn-sm btn-success px-3" href="user-edit.php?id=<?= $user['id'] ?>">Edit</a>
                                                    <a class="btn btn-sm btn-danger px-2" href="user-delete.php?id=<?= $user['id'] ?>"
                                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Navigasi Halaman / Pagination -->
                    <div class="card-footer bg-transparent border-top d-flex justify-content-end p-3">
                        <nav aria-label="Page navigation example" class="m-0">
                            <ul class="pagination pagination-sm m-0">
                                <?php if ($active_page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $active_page - 1 ?>">Previous</a></li>
                                <?php else: ?>
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_page; $i++): ?>
                                    <li class="page-item <?= ($i == $active_page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($active_page < $total_page): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $active_page + 1 ?>">Next</a></li>
                                <?php else: ?>
                                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../../../assets/admin-lte/dist/js/adminlte.js"></script>
    <script src="../../../assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.js"></script>
    <script>
        (() => {
            'use strict';
            const STORAGE_KEY = 'lte-theme';
            const getStoredTheme = () => localStorage.getItem(STORAGE_KEY);
            const setStoredTheme = (theme) => localStorage.setItem(STORAGE_KEY, theme);
            const prefersDark = () => globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
            const getPreferredTheme = () => {
                const stored = getStoredTheme();
                if (stored) return stored;
                return prefersDark() ? 'dark' : 'light';
            };
            const setTheme = (theme) => {
                const resolved = theme === 'auto' ? (prefersDark() ? 'dark' : 'light') : theme;
                document.documentElement.setAttribute('data-bs-theme', resolved);
            };
            setTheme(getPreferredTheme());
            const showActiveTheme = (theme) => {
                document.querySelectorAll('[data-bs-theme-value]').forEach((el) => {
                    el.classList.remove('active');
                    el.setAttribute('aria-pressed', 'false');
                    const check = el.querySelector('.bi-check-lg');
                    if (check) check.classList.add('d-none');
                });
                const active = document.querySelector(`[data-bs-theme-value="${theme}"]`);
                if (active) {
                    active.classList.add('active');
                    active.setAttribute('aria-pressed', 'true');
                    const check = active.querySelector('.bi-check-lg');
                    if (check) check.classList.remove('d-none');
                }
                document.querySelectorAll('[data-lte-theme-icon]').forEach((icon) => {
                    icon.classList.toggle('d-none', icon.dataset.lteThemeIcon !== theme);
                });
            };
            globalThis.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                const stored = getStoredTheme();
                if (!stored || stored === 'auto') setTheme(getPreferredTheme());
            });
            document.addEventListener('DOMContentLoaded', () => {
                showActiveTheme(getPreferredTheme());
                document.querySelectorAll('[data-bs-theme-value]').forEach((toggle) => {
                    toggle.addEventListener('click', () => {
                        const theme = toggle.getAttribute('data-bs-theme-value');
                        setStoredTheme(theme);
                        setTheme(theme);
                        showActiveTheme(theme);
                    });
                });
            });
        })();
    </script>
</body>

</html>