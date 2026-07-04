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
                <div class="mb-5">
                    <h3 class="fw-bold h4 m-0 text-white">Arrears & Outstanding Bills</h3>
                    <p class="text-muted small m-0">Monitor overdue accounts, credits, and unpaid customer invoices</p>
                </div>

                <div class="content-wrapper">
                    <div class="app-content">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <p class="card-title mb-0">Company Information</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <strong>Company Name</strong>
                                                <p class="mb-0">Xonada</p>
                                            </div>
                                            <div class="col-6">
                                                <strong>Business entity</strong>
                                                <p class="mb-0">E-commerce</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <strong>Country</strong>
                                                <p class="mb-0">Indonesia</p>
                                            </div>
                                            <div class="col-6">
                                                <strong>Province</strong>
                                                <p class="mb-0">East Java</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <strong>City/Regency</strong>
                                                <p class="mb-0">Surabaya</p>
                                            </div>
                                            <div class="col-6">
                                                <strong>Subdistrict</strong>
                                                <p class="mb-0">Sawahan</p>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <strong>Business Address</strong>
                                                <p class="mb-0">Jl. Diponegoro, Surabaya</p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <a href="edit.php?id=1" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Change
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak & Lainnya -->
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <p class="card-title">Company Contact Information</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <strong>Company Email</strong>
                                                <p class="mb-0">Azura@gmail.com</p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>Company Phone Number</strong>
                                                <p class="mb-0">081234567890</p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <a href="edit.php?id=1" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Change
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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