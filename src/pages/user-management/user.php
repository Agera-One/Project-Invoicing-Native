<?php
session_start();
require_once '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$number = 1;
$limit = 10;

$active_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($active_page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$where_condition = [];
if ($search !== '') {
    $where_condition['OR'] = [
        'name[~]' => $search,
        'email[~]' => $search,
    ];
}

$rows = count($database->select("user", "*", $where_condition));
$total_page = ceil($rows / $limit);

$query_options = $where_condition;
$query_options['ORDER'] = ['id' => 'DESC'];
$query_options['LIMIT'] = [$offset, $limit];

$users = $database->select('user', '*', $query_options);

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../../../assets/admin-lte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../../assets/bootstrap-5.3.8-dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
        crossorigin="anonymous" />
</head>

<body class="layout-fixed fixed-header sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php include '../../components/navbar.php'; ?>

        <?php include '../../components/sidebar.php'; ?>

        <main class="app-main py-4">
            <div class="container-fluid px-4">
                <div class="row">
                    <div class="col-sm-6 mb-4">
                        <h3 class="fw-bold h4 m-0 text-white">User Management</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item text-decoration-none"><a href="../dashboard/dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User Management</li>
                        </ol>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="user-add.php" class="btn btn-primary shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New User
                        </a>
                    </div>

                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <form action="" method="GET" class="flex-grow-1">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input name="search" id="table-filter" type="search" class="form-control border-start-0 ps-0" placeholder="Filter rows…" aria-label="Filter rows" autofocus autocomplete="off" value="<?= $_GET['search'] ?? ''; ?>">
                            </div>
                        </form>
                        <a href="user.php" class="btn btn-outline-secondary w-25">
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
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Updated At</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="pe-4" width="160">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user):
                                        $updated_at = new DateTime($user['updated_at']); ?>
                                        <tr>
                                            <th scope="row" class="ps-4 text-muted fw-normal"><?= ++$offset ?></th>
                                            <td><?= $user['name'] ?></td>
                                            <td><?= $user['email'] ?></td>
                                            <td><?= date('d F Y', strtotime($user['created_at'])) ?></td>
                                            <td><?= $updated_at->format('d F Y H:i') ?></td>
                                            <?= ($user['id'] == $user_id) ? '<td class="text-center"><span class="badge text-bg-success"> Online </span></td>' : '<td class="text-center"><span class="badge text-bg-secondary"> Offline </span></td>'; ?>
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

                    <div class="card-footer bg-transparent border-top d-flex justify-content-end p-3">
                        <nav aria-label="Page navigation example" class="m-0">
                            <ul class="pagination pagination-sm m-0">
                                <?php if ($active_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $active_page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">Previous</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_page; $i++): ?>
                                    <li class="page-item <?= ($i == $active_page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($active_page < $total_page): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $active_page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">Next</a>
                                    </li>
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

    <?php include '../../components/scripts.php'; ?>
</body>

</html>