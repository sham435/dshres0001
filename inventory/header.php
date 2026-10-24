<?php
// header.php - Start with PHP tag, NO whitespace before it

// Create upload directories if they don't exist
$upload_dirs = ['uploads/logos/', 'uploads/officers/', 'uploads/items/'];
foreach ($upload_dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "include.php";

// Check if user is logged in
if (!isset($_SESSION["user_email"])) {
    header("location: login.php");
    exit();
}

// Get school info
$school_sql = "SELECT * FROM school_info LIMIT 1";
$school_result = mysqli_query($conn, $school_sql);
$school = mysqli_fetch_assoc($school_result);

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $school['school_name'] ?? 'Inventory System'; ?> - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden;
        }

        /* Top Navigation Bar */
        .top-navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            height: 60px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: white !important;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .navbar-brand img {
            max-height: 40px;
            margin-right: 10px;
            border-radius: 50%;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            width: 250px;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            z-index: 1020;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .sidebar.collapsed {
            margin-left: -250px;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu .menu-header {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 1rem 1rem 0.5rem 1.5rem;
            font-weight: 700;
            letter-spacing: 0.05rem;
        }

        .sidebar-menu .menu-item {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu .menu-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }

        .sidebar-menu .menu-item.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            border-left-color: white;
            font-weight: 600;
        }

        .sidebar-menu .menu-item i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            transition: all 0.3s ease;
            min-height: calc(100vh - 60px);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Toggle Button */
        .sidebar-toggle {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            transition: background 0.2s;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* User Info in Navbar */
        .user-info {
            display: flex;
            align-items: center;
            color: white;
        }

        .user-info .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .user-info .user-details {
            display: none;
        }

        @media (min-width: 768px) {
            .user-info .user-details {
                display: block;
                line-height: 1.2;
            }
            .user-details .user-name {
                font-size: 0.9rem;
                font-weight: 600;
            }
            .user-details .user-role {
                font-size: 0.7rem;
                opacity: 0.8;
            }
        }

        /* Mobile Responsive */
        @media (max-width: 767px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.mobile-show {
                margin-left: 0;
                width: 250px;
                z-index: 1040;
            }
            
            .main-content {
                margin-left: 0;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 60px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1030;
            }

            .overlay.show {
                display: block;
            }
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .page-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .page-header h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: #4e4e4e;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #224abe;
            border-color: #224abe;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .preview-logo {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
            border-radius: 0.5rem;
            border: 1px solid #d1d3e2;
            padding: 5px;
        }

        /* Alert Styles */
        .alert {
            border-radius: 0.5rem;
            border-left: 4px solid;
        }

        .alert-success {
            border-left-color: var(--success-color);
        }

        .alert-danger {
            border-left-color: var(--danger-color);
        }

        /* Stat card improvements */
        .stat-card .card-body {
            padding: 1.25rem;
        }

        .stat-card .text-xs {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05rem;
        }

        .stat-card .h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card .fa-2x {
            font-size: 2rem;
            color: #dddfeb;
        }

        .stat-card .card-footer {
            padding: 0.75rem 1.25rem;
            background-color: #f8f9fc;
            border-top: 1px solid #e3e6f0;
        }

        /* Content card improvements */
        .content-card h5 {
            color: #4e73df;
            font-weight: 600;
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
        }

        /* Table improvements */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border-top: none;
            border-bottom: 2px solid #e3e6f0;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            color: #4e4e4e;
            letter-spacing: 0.05rem;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }

        /* Badge improvements */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            font-size: 0.7rem;
        }

        /* Alert improvements */
        .alert-info {
            background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
        }

        .alert-info a {
            color: white;
            text-decoration: underline;
        }

        .alert-info a:hover {
            color: #f8f9fc;
        }

        /* Button group improvements */
        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="d-flex justify-content-between align-items-center h-100">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle d-md-none" id="mobileSidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <button class="sidebar-toggle d-none d-md-block" id="sidebarToggle">
                    <i class="bi bi-arrow-left-circle"></i>
                </button>
                <a class="navbar-brand ms-2" href="main_page.php">
                    <?php if(!empty($school['logo_path']) && file_exists($school['logo_path'])): ?>
                        <img src="<?php echo $school['logo_path']; ?>" alt="Logo">
                    <?php endif; ?>
                    <span class="d-none d-sm-inline"><?php echo $school['school_name'] ?? 'School'; ?></span>
                </a>
            </div>
            
            <div class="user-info">
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
              
<!-- Add this in the top navbar, near the user info -->
<div class="d-flex align-items-center me-3">
    <a href="qr_generator.php" class="btn btn-sm btn-light me-2" title="Generate QR Codes">
        <i class="bi bi-qr-code"></i> QR
    </a>
</div>
                <div class="user-details">
                    <div class="user-name"><?php echo $_SESSION['user_email'] ?? 'User'; ?></div>
                    <div class="user-role"><?php echo ucfirst($_SESSION['user_type'] ?? 'User'); ?></div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay for Mobile -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <div class="menu-header">MAIN MENU</div>
            
            <a href="school_details.php" class="menu-item <?php echo ($current_page == 'school_details.php') ? 'active' : ''; ?>">
                <i class="bi bi-building"></i> Change School Details
            </a>
            
            <a href="sections_officers.php" class="menu-item <?php echo ($current_page == 'sections_officers.php') ? 'active' : ''; ?>">
                <i class="bi bi-people"></i> Add Section & Officers
            </a>
            
            <div class="menu-header">INVENTORY MANAGEMENT</div>
            
            <a href="insert_data.php" class="menu-item <?php echo ($current_page == 'insert_data.php') ? 'active' : ''; ?>">
                <i class="bi bi-plus-circle"></i> Insert Data
            </a>
            
            <a href="bulk_upload.php" class="menu-item <?php echo ($current_page == 'bulk_upload.php') ? 'active' : ''; ?>">
                <i class="bi bi-cloud-upload"></i> Bulk Upload
            </a>
            
            <a href="edit_data.php" class="menu-item <?php echo ($current_page == 'edit_data.php') ? 'active' : ''; ?>">
                <i class="bi bi-pencil-square"></i> Edit Data
            </a>
            
            <div class="menu-header">REPORTS & ANALYSIS</div>
            
            <a href="report.php" class="menu-item <?php echo ($current_page == 'report.php') ? 'active' : ''; ?>">
                <i class="bi bi-file-earmark-text"></i> Report
            </a>
            
            <a href="inventory_map.php" class="menu-item <?php echo ($current_page == 'inventory_map.php') ? 'active' : ''; ?>">
                <i class="bi bi-map"></i> Inventory Map
            </a>
            
            <div class="menu-header">OPERATIONS</div>
            
            <a href="check_inventory.php" class="menu-item <?php echo ($current_page == 'check_inventory.php') ? 'active' : ''; ?>">
                <i class="bi bi-check-circle"></i> Check Inventory
            </a>
            
            <a href="removing_inventory.php" class="menu-item <?php echo ($current_page == 'removing_inventory.php') ? 'active' : ''; ?>">
                <i class="bi bi-trash"></i> Removing Inventory
            </a>
            
            <div class="menu-header">ACCOUNT</div>
            
            <a href="auth/logout.php" class="menu-item">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>

    <!-- JavaScript for Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            // Desktop toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    
                    // Toggle icon
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.classList.remove('bi-arrow-left-circle');
                        icon.classList.add('bi-arrow-right-circle');
                    } else {
                        icon.classList.remove('bi-arrow-right-circle');
                        icon.classList.add('bi-arrow-left-circle');
                    }
                });
            }

            // Mobile toggle
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.add('mobile-show');
                    overlay.classList.add('show');
                });
            }

            // Overlay click
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-show');
                    overlay.classList.remove('show');
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 767) {
                    sidebar.classList.remove('mobile-show');
                    if (overlay) overlay.classList.remove('show');
                }
            });
        });
    </script>