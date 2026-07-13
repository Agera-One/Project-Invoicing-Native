<?php
$user_id = $_SESSION['user_id'];

$user = $database->get('user', '*', ['id' => $user_id]);
?>

<style>
  .custom-navbar {
    background-color: #15171a !important;
    border-bottom: 1px solid #313842;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .25);
  }

  .custom-navbar .nav-link {
    color: #d6d6d6;
    transition: .2s;
  }

  .custom-navbar .nav-link:hover {
    color: #ffffff;
  }

  .custom-navbar .dropdown-toggle::after {
    color: #d6d6d6;
  }

  .profile-dropdown {
    background: #252a31;
    border: 1px solid #3a4048;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, .35);
    margin-top: 10px;
    padding: 0;
    min-width: 260px;
  }

  .profile-dropdown .user-header {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    text-align: center;
    padding: 25px 20px;
  }

  .profile-dropdown .user-header img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, .35);
    margin-bottom: 10px;
  }

  .profile-dropdown .user-header p {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
  }

  .profile-dropdown .user-header small {
    display: block;
    color: rgba(255, 255, 255, .8);
    margin-top: 4px;
  }

  .profile-dropdown .user-footer {
    background: #252a31;
    border-top: 1px solid #3a4048;
    padding: 12px;
  }

  .profile-dropdown .btn {
    border-radius: 6px;
  }

  .profile-dropdown .btn-outline-secondary {
    color: #d6d6d6;
    border-color: #565f69;
  }

  .profile-dropdown .btn-outline-secondary:hover {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
  }

  .profile-dropdown .btn-outline-danger:hover {
    color: white;
  }

  .profile-dropdown {
    animation: dropdownFade .18s ease;
  }

  @keyframes dropdownFade {
    from {
      opacity: 0;
      transform: translateY(8px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<nav class="app-header navbar navbar-expand custom-navbar">
  <div class="container-fluid">
    <!-- Left Navbar -->
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

    <!-- Right Navbar -->
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
            <?= $user['name'] ?>
          </span>
        </a>
        <!-- Dropdown -->
        <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
          <li class="user-header">
            <img
              src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=160"
              alt="User">
            <p><?= $user['name'] ?><small>Admin</small></p>
          </li>
          <li class="user-footer">
            <div class="row g-2">
              <div class="col-6">
                <a href="#" class="btn btn-outline-secondary w-100">
                  Profil
                </a>
              </div>
              <div class="col-6">
                <a href="../auth/logout.php" class="btn btn-outline-danger w-100">
                  Logout
                </a>
              </div>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>