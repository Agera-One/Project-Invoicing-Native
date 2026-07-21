<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
    <style>
        /* ── Seamless & High-Contrast Sidebar Refinement ── */
        .app-sidebar {
            /* Menyamakan warna latar dengan main page dark mode agar menyatu sempurna */
            background-color: #1a1d20 !important;
            border-right: 1px solid #2b3035 !important;
            /* Border tipis untuk pemisah halus */
            font-family: system-ui, -apple-system, sans-serif;
        }

        /* Bagian Brand / Logo */
        .app-sidebar .sidebar-brand {
            background-color: #15171a !important;
            border-bottom: 1px solid #2b3035 !important;
            height: 3.5rem;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }

        .app-sidebar .brand-text {
            color: #ffffff !important;
            /* Putih solid agar kontras tinggi */
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.05em;
        }

        /* Nav Header (Tulisan DATA, dll) */
        .app-sidebar .nav-header {
            color: #adb5bd !important;
            /* Abu-abu terang agar tetap terbaca jelas */
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.12em !important;
            padding: 1.25rem 1.5rem 0.5rem 1.5rem !important;
        }

        /* Elemen Menu / Link */
        .app-sidebar .nav-item {
            padding: 0 0.75rem;
            margin-bottom: 3px;
        }

        .app-sidebar .nav-link {
            border-left: 3px solid transparent;
        }

        /* Efek Hover: Lebih terang dan bersih */
        .app-sidebar .nav-link:hover {
            color: #ffffff !important;
            background-color: #2b3035 !important;
            /* Warna hover standar Bootstrap dark */
        }

        /* Status Aktif: Menonjol dengan background kontras */
        .app-sidebar .nav-link.active {
            background: #2b3035;
            border-left: 3px solid #9ca3af;
            color: #fff;
        }

        /* Icon Styling */
        .app-sidebar .nav-icon {
            color: #ced4da !important;
            /* Icon terang agar kontras dengan background */
            margin-right: 0.6rem;
            font-size: 1.1rem !important;
        }

        /* Saat di-hover atau aktif, icon otomatis ikut putih bersih */
        .app-sidebar .nav-link:hover .nav-icon,
        .app-sidebar .nav-link.active .nav-icon {
            color: #ffffff !important;
        }

        .nav-treeview {
            padding-left: 1rem;
        }

        .nav-treeview .nav-link {
            font-size: .84rem !important;
        }

        .nav-arrow {
            margin-left: auto;
            transition: .25s;
        }

        .menu-open>.nav-link .nav-arrow {
            transform: rotate(90deg);
        }
    </style>
</head>

<aside class="app-sidebar shadow-sm">
    <div class="sidebar-brand">
        <span class="brand-text">RED HAT</span>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="../dashboard/dashboard.php" class="nav-link">
                        <i class="nav-icon bi bi-grid-1x2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Master Data -->
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
                            <a href="../item/item.php" class="nav-link" data-page="item">
                                <i class="bi bi-box-seam nav-icon"></i>
                                <p>Items</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../customer/customer.php" class="nav-link" data-page="customer">
                                <i class="bi bi-people nav-icon"></i>
                                <p>Customers</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../pic-management/pic.php" class="nav-link" data-page="pic">
                                <i class="bi bi-person-check nav-icon"></i>
                                <p>Company PIC</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Sales -->
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
                            <a href="../invoice/invoice.php" class="nav-link" data-page="invoice">
                                <i class="bi bi-receipt-cutoff nav-icon"></i>
                                <p>Invoices</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../payment/payment.php" class="nav-link" data-page="payment">
                                <i class="bi bi-credit-card nav-icon"></i>
                                <p>Payments</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../outstanding/outstanding.php" class="nav-link" data-page="outstanding">
                                <i class="bi bi-hourglass-split nav-icon text-warning"></i>
                                <p>Outstanding</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../overdue/overdue.php" class="nav-link" data-page="overdue">
                                <i class="bi bi-exclamation-triangle nav-icon text-warning"></i>
                                <p>Overdue</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Reports -->
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
                            <a href="../revenue/revenue.php" class="nav-link" data-page="revenue">
                                <i class="bi bi-graph-up-arrow nav-icon"></i>
                                <p>Revenue</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../best-seller/best-seller.php" class="nav-link" data-page="best-seller">
                                <i class="bi bi-trophy nav-icon"></i>
                                <p>Best Seller</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Administration -->
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
                            <a href="../company/company.php" class="nav-link" data-page="company">
                                <i class="bi bi-building-gear nav-icon"></i>
                                <p>Company Setting</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="../user-management/user.php" class="nav-link" data-page="user">
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

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const page = location.pathname
            .split("/")
            .pop()
            .replace(".php", "");

        const pages = {
            dashboard: "dashboard",

            item: "master",
            customer: "master",
            pic: "master",

            invoice: "sales",
            payment: "sales",
            outstanding: "sales",
            overdue: "sales",

            revenue: "reports",
            "best-seller": "reports",

            company: "admin",
            user: "admin"
        };

        // aktifkan link
        document.querySelectorAll(".nav-link[data-page]").forEach(link => {

            if (link.dataset.page === page) {

                link.classList.add("active");

                const menu = pages[page];

                if (menu) {

                    const group = document.querySelector(`[data-menu="${menu}"]`);

                    if (group) {

                        group.classList.add("menu-open");

                        group.querySelector(":scope > .nav-link")
                            .classList.add("active");
                    }
                }
            }

        });

    });
</script>