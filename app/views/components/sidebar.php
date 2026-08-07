<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="<?= BASEURL . '/public/css/sidebar.css' ?>">
</head>

<aside class="app-sidebar shadow-sm">
    <div class="sidebar-brand text-uppercase">
        <span class="brand-text"><?= $current_company_name ?></span>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                data-accordion="false">

                <li class="nav-item">
                    <a href="<?= BASEURL . 'dashboard' ?>" class="nav-link">
                        <i class="nav-icon bi bi-grid-1x2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item" data-menu="master">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-database"></i>
                        <p>
                            Master Data
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'item' ?>" class="nav-link" data-page="item">
                                <i class="bi bi-box-seam nav-icon"></i>
                                <p>Items</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'customer' ?>" class="nav-link" data-page="customer">
                                <i class="bi bi-people nav-icon"></i>
                                <p>Customers</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'pic' ?>" class="nav-link" data-page="pic">
                                <i class="bi bi-person-check nav-icon"></i>
                                <p>Company PIC</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item" data-menu="sales">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-cart-check"></i>
                        <p>
                            Sales
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'invoice' ?>" class="nav-link" data-page="invoice">
                                <i class="bi bi-receipt-cutoff nav-icon"></i>
                                <p>Invoices</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'payment' ?>" class="nav-link" data-page="payment">
                                <i class="bi bi-credit-card nav-icon"></i>
                                <p>Payments</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'outstanding' ?>" class="nav-link" data-page="outstanding">
                                <i class="bi bi-hourglass-split nav-icon text-warning"></i>
                                <p>Outstanding</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'overdue' ?>" class="nav-link" data-page="overdue">
                                <i class="bi bi-exclamation-triangle nav-icon text-warning"></i>
                                <p>Overdue</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item" data-menu="reports">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-bar-chart"></i>
                        <p>
                            Reports
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'revenue' ?>" class="nav-link" data-page="revenue">
                                <i class="bi bi-graph-up-arrow nav-icon"></i>
                                <p>Revenue</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'best-seller' ?>" class="nav-link" data-page="best-seller">
                                <i class="bi bi-trophy nav-icon"></i>
                                <p>Best Seller</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item" data-menu="admin">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>
                            Administration
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'company' ?>" class="nav-link" data-page="company">
                                <i class="bi bi-building-gear nav-icon"></i>
                                <p>Company Setting</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= BASEURL . 'user' ?>" class="nav-link" data-page="user">
                                <i class="bi bi-person-gear nav-icon"></i>
                                <p>User Management</p>
                            </a>
                        </li>

                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<script src="<?= BASEURL . 'public/js/sidebar.js' ?>"></script>