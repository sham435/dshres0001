<?php
// main_page.php
require_once "header.php";

// Get user info from session
$user_email = $_SESSION["user_email"];
$user_type = $_SESSION["user_type"] ?? 'User';

// Get some statistics (you can modify these queries based on your actual tables)
$total_items = 0;
$low_stock = 0;
$pending_orders = 0;
$total_users = 0;

// Example queries (uncomment when you have the actual tables)
/*
// Total items count
$items_query = "SELECT COUNT(*) as total FROM inventory_items";
$items_result = mysqli_query($conn, $items_query);
$total_items = mysqli_fetch_assoc($items_result)['total'];

// Low stock items (items with quantity less than threshold)
$low_stock_query = "SELECT COUNT(*) as total FROM inventory_items WHERE quantity <= min_threshold";
$low_stock_result = mysqli_query($conn, $low_stock_query);
$low_stock = mysqli_fetch_assoc($low_stock_result)['total'];

// Pending orders
$orders_query = "SELECT COUNT(*) as total FROM orders WHERE status = 'pending'";
$orders_result = mysqli_query($conn, $orders_query);
$pending_orders = mysqli_fetch_assoc($orders_result)['total'];

// Total users
$users_query = "SELECT COUNT(*) as total FROM users";
$users_result = mysqli_query($conn, $users_query);
$total_users = mysqli_fetch_assoc($users_result)['total'];
*/
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2>
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </h2>
                    <p class="text-muted mb-0">
                        Welcome back, <strong><?php echo htmlspecialchars($school['principal_name'] ?? 'Administrator'); ?></strong>! 
                        Here's what's happening with your inventory today.
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="date-display">
                        <i class="bi bi-calendar3"></i> 
                        <?php echo date('l, F j, Y'); ?>
                        <br>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> 
                            <?php echo date('h:i A'); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-primary h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Items
                                </div>
                                <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo number_format($total_items); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-box-seam fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <a href="inventory_list.php" class="small text-primary stretched-link">
                            View Details <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-success h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Low Stock Items
                                </div>
                                <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo number_format($low_stock); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <a href="check_inventory.php" class="small text-success stretched-link">
                            Check Inventory <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-info h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Pending Orders
                                </div>
                                <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo number_format($pending_orders); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-truck fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <a href="orders.php" class="small text-info stretched-link">
                            View Orders <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-warning h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Users
                                </div>
                                <div class="h3 mb-0 font-weight-bold text-gray-800"><?php echo number_format($total_users); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <a href="users.php" class="small text-warning stretched-link">
                            Manage Users <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions and Recent Activity -->
        <div class="row">
            <!-- Quick Actions Cards -->
            <div class="col-lg-4 mb-4">
                <div class="content-card">
                    <h5 class="mb-3">
                        <i class="bi bi-lightning-charge-fill text-warning"></i> 
                        Quick Actions
                    </h5>
                    
                    <div class="list-group">
                        <a href="insert_data.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-plus-circle-fill text-success me-3"></i>
                            <div>
                                <strong>Add New Item</strong>
                                <br>
                                <small class="text-muted">Add single inventory item</small>
                            </div>
                        </a>
                        
                        <a href="bulk_upload.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-cloud-upload-fill text-info me-3"></i>
                            <div>
                                <strong>Bulk Upload</strong>
                                <br>
                                <small class="text-muted">Upload multiple items via Excel</small>
                            </div>
                        </a>
                        
                        <a href="check_inventory.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-search-heart-fill text-primary me-3"></i>
                            <div>
                                <strong>Check Inventory</strong>
                                <br>
                                <small class="text-muted">Search and verify items</small>
                            </div>
                        </a>
                        
                        <a href="report.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-file-earmark-spreadsheet-fill text-danger me-3"></i>
                            <div>
                                <strong>Generate Report</strong>
                                <br>
                                <small class="text-muted">Create inventory reports</small>
                            </div>
                        </a>
                        
                        <a href="inventory_map.php" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-map-fill text-secondary me-3"></i>
                            <div>
                                <strong>Inventory Map</strong>
                                <br>
                                <small class="text-muted">View items by location</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-8 mb-4">
                <div class="content-card">
                    <h5 class="mb-3">
                        <i class="bi bi-clock-history text-info"></i> 
                        Recent Activity
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Time</th>
                                    <th>Action</th>
                                    <th>Item</th>
                                    <th>User</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample data - replace with actual data from database -->
                                <tr>
                                    <td><?php echo date('h:i A'); ?></td>
                                    <td><span class="badge bg-success">Added</span></td>
                                    <td>Sample Item 1</td>
                                    <td>Admin</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td><?php echo date('h:i A', strtotime('-1 hour')); ?></td>
                                    <td><span class="badge bg-warning">Updated</span></td>
                                    <td>Sample Item 2</td>
                                    <td>Staff</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td><?php echo date('h:i A', strtotime('-2 hours')); ?></td>
                                    <td><span class="badge bg-danger">Removed</span></td>
                                    <td>Sample Item 3</td>
                                    <td>Admin</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="bi bi-info-circle"></i> 
                                        No recent activity to display
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-end mt-2">
                        <a href="activity_log.php" class="btn btn-sm btn-outline-primary">
                            View All Activity <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Summary -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="content-card">
                    <h5 class="mb-3">
                        <i class="bi bi-pie-chart-fill text-primary"></i> 
                        Inventory by Category
                    </h5>
                    
                    <div class="chart-container" style="height: 250px;">
                        <!-- Placeholder for chart - you can add a chart library later -->
                        <div class="d-flex justify-content-center align-items-center h-100 bg-light rounded">
                            <span class="text-muted">
                                <i class="bi bi-bar-chart" style="font-size: 3rem;"></i>
                                <br>
                                Chart will be displayed here
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <span>Electronics</span>
                            <span class="badge bg-primary">45 items</span>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 45%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span>Furniture</span>
                            <span class="badge bg-success">32 items</span>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 32%"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span>Stationery</span>
                            <span class="badge bg-info">23 items</span>
                        </div>
                        <div class="progress mb-2" style="height: 5px;">
                            <div class="progress-bar bg-info" style="width: 23%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="content-card">
                    <h5 class="mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i> 
                        Low Stock Alerts
                    </h5>
                    
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-circle"></i> 
                        <strong>5 items</strong> are running low on stock
                    </div>
                    
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Laptop</strong>
                                <br>
                                <small class="text-muted">Current stock: 2</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">Low</span>
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Printer Paper</strong>
                                <br>
                                <small class="text-muted">Current stock: 10 packs</small>
                            </div>
                            <span class="badge bg-warning rounded-pill">Medium</span>
                        </div>
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Whiteboard Markers</strong>
                                <br>
                                <small class="text-muted">Current stock: 5 boxes</small>
                            </div>
                            <span class="badge bg-warning rounded-pill">Medium</span>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="check_inventory.php" class="btn btn-sm btn-outline-danger">
                            View All Low Stock Items
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Information -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info" role="alert">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Contact Inventory Officer:</strong> 
                            <?php echo htmlspecialchars($school['inventory_officer_name'] ?? 'N/A'); ?> 
                            (<?php echo htmlspecialchars($school['inventory_officer_phone'] ?? 'N/A'); ?>)
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small>
                                Last login: <?php echo date('Y-m-d H:i:s'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add custom CSS for the dashboard -->
<style>
/* Dashboard specific styles */
.stat-card {
    border-radius: 0.5rem;
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
}

.border-left-primary {
    border-left: 0.25rem solid var(--primary-color);
}

.border-left-success {
    border-left: 0.25rem solid var(--success-color);
}

.border-left-info {
    border-left: 0.25rem solid var(--info-color);
}

.border-left-warning {
    border-left: 0.25rem solid var(--warning-color);
}

.card-footer {
    background-color: #f8f9fc;
    border-top: 1px solid #e3e6f0;
}

.card-footer a {
    text-decoration: none;
    font-weight: 600;
}

/* Date display styling */
.date-display {
    background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 2rem;
    display: inline-block;
    font-size: 0.9rem;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
}

/* List group styling */
.list-group-item-action:hover {
    background-color: #f8f9fc;
    transform: translateX(5px);
    transition: transform 0.2s;
}

/* Progress bar customization */
.progress {
    background-color: #eaecf4;
    border-radius: 1rem;
}

.progress-bar {
    border-radius: 1rem;
}

/* Chart container */
.chart-container {
    background-color: #f8f9fc;
    border-radius: 0.5rem;
    border: 1px solid #e3e6f0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .date-display {
        margin-top: 1rem;
        width: 100%;
        text-align: center;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
// Add any dashboard-specific JavaScript here
document.addEventListener('DOMContentLoaded', function() {
    // Update time every minute
    function updateTime() {
        const now = new Date();
        const timeElement = document.querySelector('.date-display small');
        if (timeElement) {
            timeElement.innerHTML = '<i class="bi bi-clock"></i> ' + 
                now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        }
    }
    
    setInterval(updateTime, 60000); // Update every minute
});
</script>

<?php require_once "footer.php"; ?>