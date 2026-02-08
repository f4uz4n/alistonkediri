<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aliston Tour & Travel - Premium Dashboard</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --primary-color: #ef3338;
            --primary-soft: rgba(239, 51, 56, 0.1);
            --primary-dark: #d32f2f;
            --secondary-color: #00a651;
            --secondary-soft: rgba(0, 166, 81, 0.1);
            --bg-body: #f4f7fa;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 85px;
            --topbar-height: 70px;
            --card-shadow: 0 10px 30px 0 rgba(0,0,0,0.05);
            --transition-speed: 0.3s;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-body);
            color: #1a1a1a;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Sidebar Glassmorphism */
                #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #sidebar::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Sidebar Logo */
                        .sidebar-brand {
            padding: 1rem 0.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            transition: all var(--transition-speed);
        }

                .sidebar-brand img {
            max-height: 80px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.08));
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .collapsed .sidebar-brand img {
            height: 35px;
        }

        /* Navigation Menu */
        .nav-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s ease;
            white-space: nowrap;
            font-weight: 500;
        }

        .nav-link i {
            font-size: 1.4rem;
            margin-right: 1rem;
            min-width: 24px;
            text-align: center;
        }

        .nav-link:hover {
            background: var(--primary-soft);
            color: var(--primary-color);
        }

        .nav-link.active {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 8px 16px rgba(239, 51, 56, 0.2);
        }

        .collapsed .nav-link span {
            display: none;
        }

        /* Topbar */
        #topbar {
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            z-index: 900;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            justify-content: space-between;
        }

        #topbar.expanded {
            left: var(--sidebar-collapsed-width);
        }

        /* Main Content Area */
        main {
            margin-top: var(--topbar-height);
            margin-left: var(--sidebar-width);
            padding: 2.5rem;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            min-height: calc(100vh - var(--topbar-height));
        }

        main.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Cards & Components */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-premium {
            background: var(--primary-color);
            color: #fff;
            border-radius: 12px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(239, 51, 56, 0.2);
        }

        .btn-premium:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 51, 56, 0.3);
            color: #fff;
        }

        /* User Profile Dropdown */
        .user-profile {
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .user-profile:hover {
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        @media (max-width: 991.98px) {
            #sidebar {
                left: -100%;
            }
            #sidebar.active {
                left: 0;
            }
            #topbar {
                left: 0;
            }
            main {
                margin-left: 0;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php if(session()->get('isLoggedIn')): ?>
    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="sidebar-brand">
            <img src="<?= get_company_logo() ?>" alt="Aliston Logo">
        </div>

        <ul class="nav-menu">
            <?php if(session()->get('role') == 'owner'): ?>
                <li class="nav-item">
                    <a href="<?= base_url('owner') ?>" class="nav-link <?= current_url() == base_url('owner') ? 'active' : '' ?>">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item border-top my-2 pt-2">
                    <small class="text-secondary fw-bold text-uppercase px-3" style="font-size: 0.65rem;">Layanan</small>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('package') ?>" class="nav-link <?= strpos(current_url(), 'package') !== false ? 'active' : '' ?>">
                        <i class="bi bi-briefcase-fill"></i>
                        <span>Paket Perjalanan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/materials') ?>" class="nav-link <?= strpos(current_url(), 'materials') !== false ? 'active' : '' ?>">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                        <span>Materi Promosi</span>
                    </a>
                </li>
                <li class="nav-item border-top my-2 pt-2">
                    <small class="text-secondary fw-bold text-uppercase px-3" style="font-size: 0.65rem;">Manajemen</small>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/agency') ?>" class="nav-link <?= strpos(current_url(), 'agency') !== false ? 'active' : '' ?>">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Daftar Agensi</span>
                    </a>
                </li>
                <li class="nav-item border-top my-2 pt-2">
                    <small class="text-secondary fw-bold text-uppercase px-3" style="font-size: 0.65rem;">Daftar Jamaah</small>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/participant') ?>" class="nav-link <?= (strpos(current_url(), 'owner/participant') !== false && strpos(current_url(), 'documents') === false && strpos(current_url(), 'equipment') === false && strpos(current_url(), 'kelola') === false && strpos(current_url(), 'cancellations') === false && strpos(current_url(), 'cancel-form') === false && strpos(current_url(), 'boarding-list') === false && strpos(current_url(), 'register') === false) ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Semua Jamaah</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/participant/documents') ?>" class="nav-link <?= strpos(current_url(), 'documents') !== false ? 'active' : '' ?>">
                        <i class="bi bi-file-earmark-check-fill"></i>
                        <span>Kelengkapan Berkas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/payment-verification') ?>" class="nav-link <?= strpos(current_url(), 'payment-verification') !== false ? 'active' : '' ?>">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Verifikasi Pembayaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/participant/cancellations') ?>" class="nav-link <?= strpos(current_url(), 'cancellations') !== false || strpos(current_url(), 'cancel-form') !== false ? 'active' : '' ?>">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>Pembatalan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/participant/boarding-list') ?>" class="nav-link <?= strpos(current_url(), 'boarding-list') !== false ? 'active' : '' ?>">
                        <i class="bi bi-airplane-engines-fill"></i>
                        <span>Boarding</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/tabungan') ?>" class="nav-link <?= strpos(current_url(), 'owner/tabungan') !== false ? 'active' : '' ?>">
                        <i class="bi bi-cash-coin"></i>
                        <span>Tabungan Perjalanan</span>
                    </a>
                </li>
                <li class="nav-item border-top my-2 pt-2">
                    <small class="text-secondary fw-bold text-uppercase px-3" style="font-size: 0.65rem;">Master</small>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/cities') ?>" class="nav-link <?= strpos(current_url(), 'owner/cities') !== false ? 'active' : '' ?>">
                        <i class="bi bi-geo-alt"></i>
                        <span>Kota</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/hotels') ?>" class="nav-link <?= strpos(current_url(), 'owner/hotels') !== false ? 'active' : '' ?>">
                        <i class="bi bi-building"></i>
                        <span>Hotel & Kamar</span>
                    </a>
                </li>
                <li class="nav-item border-top my-2 pt-2">
                    <small class="text-secondary fw-bold text-uppercase px-3" style="font-size: 0.65rem;">Perlengkapan</small>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/equipment') ?>" class="nav-link <?= (strpos(current_url(), 'equipment') !== false && strpos(current_url(), 'participants') === false && strpos(current_url(), 'checklist') === false) ? 'active' : '' ?>">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Master Atribut</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/equipment/participants') ?>" class="nav-link <?= strpos(current_url(), 'equipment/participants') !== false || strpos(current_url(), 'equipment/checklist') !== false ? 'active' : '' ?>">
                        <i class="bi bi-clipboard-check-fill"></i>
                        <span>Pengambilan Atribut</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/reports') ?>" class="nav-link <?= strpos(current_url(), 'reports') !== false ? 'active' : '' ?>">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Laporan Bisnis</span>
                    </a>
                </li>
                <li class="nav-item border-top my-2 pt-2">
                    <small class="text-secondary fw-bold text-uppercase px-3" style="font-size: 0.65rem;">Keuangan</small>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/commissions') ?>" class="nav-link <?= strpos(current_url(), 'commissions') !== false ? 'active' : '' ?>">
                        <i class="bi bi-wallet2"></i>
                        <span>Komisi Agensi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/testimoni') ?>" class="nav-link <?= strpos(current_url(), 'owner/testimoni') !== false ? 'active' : '' ?>">
                        <i class="bi bi-chat-quote"></i>
                        <span>Testimoni Jamaah</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/banners') ?>" class="nav-link <?= strpos(current_url(), 'owner/banners') !== false ? 'active' : '' ?>">
                        <i class="bi bi-images"></i>
                        <span>Banner Beranda</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('owner/settings') ?>" class="nav-link <?= strpos(current_url(), 'settings') !== false ? 'active' : '' ?>">
                        <i class="bi bi-gear-fill"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                </li>
                                    <?php else: ?>
                <li class="nav-item">
                    <a href="<?= base_url('agency') ?>" class="nav-link <?= current_url() == base_url('agency') ? 'active' : '' ?>">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('agency/packages') ?>" class="nav-link <?= strpos(current_url(), 'packages') !== false ? 'active' : '' ?>">
                        <i class="bi bi-airplane-fill"></i>
                        <span>Paket Travel</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('agency/materials') ?>" class="nav-link <?= strpos(current_url(), 'agency/materials') !== false ? 'active' : '' ?>">
                        <i class="bi bi-cloud-arrow-down-fill"></i>
                        <span>Materi Promosi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('agency/participants') ?>" class="nav-link <?= strpos(current_url(), 'participants') !== false ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Daftar Jamaah</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('agency/payments') ?>" class="nav-link <?= strpos(current_url(), 'payments') !== false ? 'active' : '' ?>">
                        <i class="bi bi-wallet2"></i>
                        <span>Laporan Pembayaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('agency/income') ?>" class="nav-link <?= strpos(current_url(), 'income') !== false ? 'active' : '' ?>">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Laporan Penghasilan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('agency/testimoni') ?>" class="nav-link <?= strpos(current_url(), 'agency/testimoni') !== false ? 'active' : '' ?>">
                        <i class="bi bi-chat-quote"></i>
                        <span>Testimoni Jamaah</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('agency/profile') ?>" class="nav-link <?= strpos(current_url(), 'agency/profile') !== false ? 'active' : '' ?>">
                        <i class="bi bi-person-gear"></i>
                        <span>Ubah Profil</span>
                    </a>
                </li>
                <?php endif; ?>
        </ul>

        <div class="mt-auto">
            <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                <i class="bi bi-box-arrow-left"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Topbar -->
    <header id="topbar">
        <button id="toggle-sidebar" class="btn btn-light rounded-circle p-2">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="dropdown">
            <div class="user-profile d-flex align-items-center" data-bs-toggle="dropdown">
                <div class="bg-primary-soft text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="d-none d-md-block me-2">
                    <span class="small fw-bold d-block"><?= session()->get('username') ?></span>
                    <span class="text-muted" style="font-size: 0.75rem;"><?= ucfirst(session()->get('role')) ?></span>
                </div>
                <i class="bi bi-chevron-down small"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4 mt-2">
                <li><a class="dropdown-item rounded-3 py-2" href="#"><i class="bi bi-shield-lock me-2"></i>Keamanan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger rounded-3 py-2" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
            </ul>
        </div>
    </header>

    <!-- Content Area -->
    <main id="main-content">
        <?php if(session()->getFlashdata('msg')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('msg') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const topbar = document.getElementById('topbar');
        const main = document.getElementById('main-content');
        const toggle = document.getElementById('toggle-sidebar');

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            topbar.classList.toggle('expanded');
            main.classList.toggle('expanded');
        });
    </script>
    <?php else: ?>
        <!-- Public Area (Login Page fallback) -->
        <div class="main-content container py-5">
            <?= $this->renderSection('content') ?>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

</body>
</html>




