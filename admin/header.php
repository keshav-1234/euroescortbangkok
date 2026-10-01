<?php
// admin/header.php
require_once __DIR__ . '/../includes/db.php';
check_admin_auth();

$currentPage = basename($_SERVER['PHP_SELF']);
$currentGender = $_GET['gender'] ?? '';
$adminUsername = $_SESSION['admin_user'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' : '' ?>Escort Admin Panel</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #0d0f12;
            --bg-sidebar: #13171d;
            --bg-card: #181d24;
            --bg-card-hover: #1e242d;
            --bg-input: #101419;
            --border-color: #262c36;
            --border-light: #323b49;
            --primary: #c5a880;
            --primary-hover: #b09169;
            --primary-light: rgba(197, 168, 128, 0.12);
            --text-main: #f0f3f6;
            --text-muted: #8b949e;
            --danger: #eb5757;
            --danger-bg: rgba(235, 87, 87, 0.12);
            --success: #27ae60;
            --success-bg: rgba(39, 174, 96, 0.12);
            --info: #2f80ed;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: all 0.25s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: var(--transition);
        }

        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-brand .logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), #8f724d);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-size: 18px;
            font-weight: 800;
        }

        .sidebar-brand .brand-text {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--text-main);
        }

        .sidebar-brand .brand-text span {
            color: var(--primary);
        }

        .sidebar-nav {
            padding: 20px 12px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            padding: 12px 14px 6px;
            font-weight: 600;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .nav-link:hover {
            color: var(--text-main);
            background-color: var(--bg-card);
        }

        .nav-link.active {
            color: var(--primary);
            background-color: var(--primary-light);
            font-weight: 600;
        }

        .nav-badge {
            margin-left: auto;
            background-color: var(--bg-card);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        .nav-link.active .nav-badge {
            background-color: var(--primary);
            color: #000;
            border-color: var(--primary);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border-color);
        }

        .user-panel {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            background: var(--bg-card);
        }

        .user-panel .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .user-panel .user-info {
            flex: 1;
            overflow: hidden;
        }

        .user-panel .user-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-panel .user-role {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* Main Content Wrapper */
        .admin-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
        }

        /* Top Navbar */
        .admin-topbar {
            height: 70px;
            background: var(--bg-sidebar);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 20px;
            cursor: pointer;
        }

        .page-heading-title {
            font-size: 20px;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #0d0f12;
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #000;
        }

        .btn-secondary {
            background-color: var(--bg-card);
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background-color: var(--bg-card-hover);
            border-color: var(--border-light);
        }

        .btn-danger {
            background-color: var(--danger-bg);
            color: var(--danger);
            border-color: rgba(235, 87, 87, 0.3);
        }

        .btn-danger:hover {
            background-color: var(--danger);
            color: #fff;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        /* Content Container */
        .admin-content {
            padding: 32px;
            flex: 1;
        }

        /* Flash Messages */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-success {
            background-color: var(--success-bg);
            color: var(--success);
            border: 1px solid rgba(39, 174, 96, 0.3);
        }

        .alert-error {
            background-color: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(235, 87, 87, 0.3);
        }

        /* Cards & Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 22px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: var(--border-light);
            background: var(--bg-card-hover);
        }

        .stat-info .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 6px;
            font-weight: 500;
        }

        .stat-info .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-main);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-icon.gold { background: var(--primary-light); color: var(--primary); }
        .stat-icon.pink { background: rgba(232, 74, 150, 0.15); color: #e84a96; }
        .stat-icon.blue { background: rgba(47, 128, 237, 0.15); color: #2f80ed; }
        .stat-icon.green { background: var(--success-bg); color: var(--success); }

        /* Tables & Content Cards */
        .content-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-bottom: 32px;
        }

        .card-header {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            flex-wrap: wrap;
            gap: 16px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .data-table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }

        .data-table th {
            background: #14181f;
            padding: 14px 20px;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .data-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .model-cell {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .model-thumb {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border-color);
            background-color: var(--bg-input);
        }

        .model-name {
            font-weight: 600;
            color: var(--text-main);
            display: block;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-girl { background: rgba(232, 74, 150, 0.15); color: #e84a96; }
        .badge-boy { background: rgba(47, 128, 237, 0.15); color: #2f80ed; }
        .badge-active { background: var(--success-bg); color: var(--success); }
        .badge-inactive { background: var(--danger-bg); color: var(--danger); }

        /* Switch toggle */
        .status-toggle {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .status-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #2c333f;
            transition: var(--transition);
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: var(--transition);
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--success);
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* Form styles */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }

        .form-control {
            background-color: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-main);
            padding: 12px 14px;
            font-size: 14px;
            font-family: inherit;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.2);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
                width: 100%;
            }
            .mobile-menu-btn {
                display: block;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <div class="logo-icon"><i class="fa-solid fa-crown"></i></div>
            <div class="brand-text">VIP <span>ESCORT</span></div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Main Menu</div>
            <a href="index.php" class="nav-link <?= ($currentPage === 'index.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-label">Escorts Management</div>
            <a href="models.php" class="nav-link <?= ($currentPage === 'models.php' && empty($currentGender)) ? 'active' : '' ?>">
                <i class="fa-solid fa-users"></i>
                <span>All Escorts</span>
            </a>
            <a href="models.php?gender=girl" class="nav-link <?= ($currentPage === 'models.php' && $currentGender === 'girl') ? 'active' : '' ?>">
                <i class="fa-solid fa-person-dress" style="color: #e84a96;"></i>
                <span>Girls Escorts</span>
            </a>
            <a href="models.php?gender=boy" class="nav-link <?= ($currentPage === 'models.php' && $currentGender === 'boy') ? 'active' : '' ?>">
                <i class="fa-solid fa-person" style="color: #2f80ed;"></i>
                <span>Boys Escorts</span>
            </a>
            <a href="model-add.php" class="nav-link <?= ($currentPage === 'model-add.php') ? 'active' : '' ?>">
                <i class="fa-solid fa-plus-circle" style="color: var(--primary);"></i>
                <span>Add New Escort</span>
            </a>

            <div class="nav-label">Quick Links</div>
            <a href="../index.php" target="_blank" class="nav-link">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>View Website</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-panel">
                <div class="avatar"><i class="fa-solid fa-user-shield"></i></div>
                <div class="user-info">
                    <div class="user-name"><?= e($adminUsername) ?></div>
                    <div class="user-role">Administrator</div>
                </div>
                <a href="logout.php" title="Logout" style="color: var(--danger); font-size: 16px;">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" id="sidebarToggle" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="page-heading-title"><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></h1>
            </div>

            <div class="topbar-right">
                <a href="../index.php" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-eye"></i> Live Site
                </a>
                <a href="model-add.php" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus"></i> Add Escort
                </a>
                <a href="logout.php" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-power-off"></i> Logout
                </a>
            </div>
        </header>

        <!-- Content Body -->
        <main class="admin-content">
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?= e($_SESSION['success']) ?></span>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= e($_SESSION['error']) ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
