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
            /* Sedikit lebih gelap dari main page untuk struktur */
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
            color: #dee2e6 !important;
            /* Kontras tinggi untuk teks menu default */
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            padding: 0.55rem 0.75rem !important;
            border-radius: 6px !important;
            transition: all 0.1s ease-in-out;
            position: relative;
        }

        /* Efek Hover: Lebih terang dan bersih */
        .app-sidebar .nav-link:hover {
            color: #ffffff !important;
            background-color: #2b3035 !important;
            /* Warna hover standar Bootstrap dark */
        }

        /* Status Aktif: Menonjol dengan background kontras */
        .app-sidebar .nav-link.active {
            color: #ffffff !important;
            background-color: #0d6efd !important;
            /* Warna Blue Primary Bootstrap murni untuk indikasi tegas */
            font-weight: 600 !important;
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
    </style>
</head>

<aside class="app-sidebar shadow-sm">
    <div class="sidebar-brand">
        <span class="brand-text">ADMINLTE 4</span>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                aria-label="Main navigation"
                data-accordion="true"
                id="navigation">

                <li class="nav-item" data-group="barang">
                    <a href="../item/item.php" class="nav-link">
                        <i class="nav-icon bi bi-box-seam"></i>
                        <p>Items</p>
                    </a>
                </li>

                <li class="nav-item" data-group="customer">
                    <a href="../customer/customer.php" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Customers</p>
                    </a>
                </li>

                <li class="nav-item" data-group="invoice">
                    <a href="../invoice/invoice.php" class="nav-link">
                        <i class="nav-icon bi bi-receipt-cutoff"></i>
                        <p>Invoices</p>
                    </a>
                </li>

                <li class="nav-item" data-group="laporan">
                    <a href="../sales/sales.php" class="nav-link">
                        <i class="nav-icon bi bi-graph-up-arrow"></i>
                        <p>Sales</p>
                    </a>
                </li>

                <li class="nav-item" data-group="pembayaran">
                    <a href="../payment/payment.php" class="nav-link">
                        <i class="nav-icon bi bi-credit-card"></i>
                        <p>Payments</p>
                    </a>
                </li>

                <li class="nav-item" data-page="tunggakan">
                    <a href="../arrears/arrears.php" class="nav-link">
                        <i class="nav-icon bi bi-exclamation-circle-fill" style="color: #f59e0b !important;"></i>
                        <p>Arrears</p>
                    </a>
                </li>

                <li class="nav-item" data-group="perusahaan">
                    <a href="../company-setting/company.php" class="nav-link">
                        <i class="nav-icon bi bi-building-gear"></i>
                        <p>Company Setting</p>
                    </a>
                </li>

                <li class="nav-item" data-group="Manajemen">
                    <a href="../user-management/user.php" class="nav-link">
                        <i class="nav-icon bi bi-shield-lock"></i>
                        <p>User Management</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- <script>
    (function() {
        const page = location.pathname.split('/').pop().replace(/\.[^.]+$/, '') || 'dashboard';

        const barangPages = ['index', 'edit', 'create'];
        const customerPages = ['customer', 'edit-customer', 'create-customer'];
        const invoicePages = ['table-invoice', 'edit-invoice', 'create-invoice', 'invoice'];
        const laporanPages = ['laporan'];
        const pembayaranPages = ['pembayaran'];
        const perusahaanPages = ['perusahaan'];
        const manajemenPages = ['user-manajemen'];

        // Handler untuk halaman tunggal / spesifik luar grup
        if (page === 'tunggakan') {
            const tunggakanLi = document.querySelector('[data-page="tunggakan"]');
            if (tunggakanLi) tunggakanLi.querySelector('.nav-link').classList.add('active');
        } else if (page === 'dashboard') {
            const dashLi = document.querySelector('[data-page="dashboard"]');
            if (dashLi) dashLi.querySelector('.nav-link').classList.add('active');
        }

        function openGroup(pages, groupName) {
            if (pages.includes(page)) {
                const group = document.querySelector(`[data-group="${groupName}"]`);
                if (group) {
                    group.querySelector('.nav-link').classList.add('active');
                }
            }
        }

        openGroup(barangPages, 'barang');
        openGroup(customerPages, 'customer');
        openGroup(invoicePages, 'invoice');
        openGroup(laporanPages, 'laporan');
        openGroup(pembayaranPages, 'pembayaran');
        openGroup(perusahaanPages, 'perusahaan');
        openGroup(manajemenPages, 'Manajemen');
    })();
</script> -->