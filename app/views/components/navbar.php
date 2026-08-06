<?php
$logged_in_user = $db->get('user', '*', ['id' => $user_id]);
?>

<head>
  <link rel="stylesheet" href="<?= BASEURL ?>/css/navbar.css">
</head>

<nav class="app-header navbar navbar-expand custom-navbar">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link"
          data-lte-toggle="sidebar"
          href="#"
          role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto">
      <li class="nav-item dropdown user-menu">
        <a href="#"
          class="nav-link dropdown-toggle"
          data-bs-toggle="dropdown">
          <img
            src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=160"
            class="user-image rounded-circle shadow"
            alt="User">
          <span class="d-none d-md-inline">
            <?= $logged_in_user['name'] ?>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
          <li class="user-header">
            <img
              src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=160"
              alt="User">
            <p><?= $logged_in_user['name'] ?><small>Admin</small></p>
          </li>
          <li class="user-footer">
            <a href="../../pages/auth/logout.php" class="btn btn-outline-danger w-100">
              Logout
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>