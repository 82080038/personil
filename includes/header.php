<?php
/**
 * Header Template SMO-BAGOPS
 * Include di setiap halaman setelah login
 */

if (!isset($page_title)) $page_title = APP_NAME;
if (!isset($active_menu)) $active_menu = '';

$current_user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(generateCSRFToken()); ?>">
    <title><?php echo htmlspecialchars($page_title); ?> - <?php echo APP_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo ASSETS_URL; ?>css/custom.css" rel="stylesheet">
    
    <style>
        :root {
            --polri-blue: #003366;
            --polri-gold: #C9A227;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--polri-blue) 0%, #004080 100%);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #333;
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--polri-blue);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 8px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid var(--polri-gold);
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--polri-blue);
            border-color: var(--polri-blue);
        }
        
        .btn-primary:hover {
            background-color: #004080;
            border-color: #004080;
        }
        
        .text-polri {
            color: var(--polri-blue);
        }
        
        .badge-pangkat-perwira {
            background-color: #dc3545;
        }
        
        .badge-pangkat-bintara {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-pangkat-tamtama {
            background-color: #17a2b8;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">
                <i class="bi bi-shield-shaded me-2"></i>
                <?php echo APP_NAME; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <?php echo $current_user ? $current_user['pangkat'] . ' ' . $current_user['nama'] : 'Guest'; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_menu === 'dashboard' ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>index.php">
                                <i class="bi bi-speedometer2"></i>Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_menu === 'personel' ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>modules/personel/list.php">
                                <i class="bi bi-people"></i>Data Personel
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_menu === 'sprin' ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>modules/sprin/list.php">
                                <i class="bi bi-file-text"></i>Surat Perintah
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_menu === 'sprin_baru' ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>modules/sprin/form.php">
                                <i class="bi bi-plus-circle"></i>Buat Sprin Baru
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_menu === 'laporan' ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>modules/laporan/anev.php">
                                <i class="bi bi-graph-up"></i>Analisa & Evaluasi
                            </a>
                        </li>
                        
                        <li class="nav-item mt-4">
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-1 text-muted">
                                <span>Master Data</span>
                            </h6>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_menu === 'master_pangkat' ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>modules/master/pangkat.php">
                                <i class="bi bi-stars"></i>Master Pangkat
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active_menu === 'master_satfung' ? 'active' : ''; ?>" 
                               href="<?php echo BASE_URL; ?>modules/master/satfung.php">
                                <i class="bi bi-building"></i>Master Satfung
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
