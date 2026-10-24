<?php
// sections_officers.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';

// Check if tables exist and create them if needed
function ensureTablesExist($conn) {
    // Check sections table
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'sections'");
    if (mysqli_num_rows($result) == 0) {
        mysqli_query($conn, "CREATE TABLE sections (
            id INT PRIMARY KEY AUTO_INCREMENT,
            school_id INT NOT NULL,
            section_name VARCHAR(100) NOT NULL,
            section_type ENUM('Primary', 'Secondary', 'AL', 'Other') DEFAULT 'Other',
            main_section VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (school_id) REFERENCES school_info(id) ON DELETE CASCADE
        )");
    }
    
    // Check officers table
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'officers'");
    if (mysqli_num_rows($result) == 0) {
        mysqli_query($conn, "CREATE TABLE officers (
            id INT PRIMARY KEY AUTO_INCREMENT,
            school_id INT NOT NULL,
            section_id INT NULL,
            officer_name VARCHAR(100) NOT NULL,
            officer_id_number VARCHAR(50) UNIQUE NOT NULL,
            phone VARCHAR(20) NOT NULL,
            is_incharge BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (school_id) REFERENCES school_info(id) ON DELETE CASCADE,
            FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL
        )");
    }
}

// Ensure tables exist
ensureTablesExist($conn);

// Handle actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$section_id = isset($_GET['section_id']) ? (int)$_GET['section_id'] : 0;
$officer_id = isset($_GET['officer_id']) ? (int)$_GET['officer_id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Save Section (Add/Edit)
    if (isset($_POST['save_section'])) {
        $section_name = mysqli_real_escape_string($conn, trim($_POST['section_name']));
        $section_type = mysqli_real_escape_string($conn, $_POST['section_type']);
        $main_section = mysqli_real_escape_string($conn, trim($_POST['main_section']));
        
        if (empty($section_name)) {
            $error = "Section name is required";
        } else {
            if (!empty($_POST['section_id'])) {
                // Update existing section
                $sql = "UPDATE sections SET 
                        section_name='$section_name', 
                        section_type='$section_type', 
                        main_section='$main_section' 
                        WHERE id=" . (int)$_POST['section_id'] . " AND school_id=$school_id";
                
                if (mysqli_query($conn, $sql)) {
                    $message = "Section updated successfully!";
                } else {
                    $error = "Error updating section: " . mysqli_error($conn);
                }
            } else {
                // Insert new section
                $sql = "INSERT INTO sections (school_id, section_name, section_type, main_section) 
                        VALUES ($school_id, '$section_name', '$section_type', '$main_section')";
                
                if (mysqli_query($conn, $sql)) {
                    $message = "Section added successfully!";
                } else {
                    $error = "Error adding section: " . mysqli_error($conn);
                }
            }
        }
    }
    
    // Save Officer (Add/Edit)
    elseif (isset($_POST['save_officer'])) {
        $officer_name = mysqli_real_escape_string($conn, trim($_POST['officer_name']));
        $officer_id_number = mysqli_real_escape_string($conn, trim($_POST['officer_id_number']));
        $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
        $section_id = !empty($_POST['section_id']) ? (int)$_POST['section_id'] : 'NULL';
        $is_incharge = isset($_POST['is_incharge']) ? 1 : 0;
        
        // If setting as incharge, remove other incharge from this section
        if ($is_incharge && $section_id != 'NULL') {
            mysqli_query($conn, "UPDATE officers SET is_incharge=0 WHERE section_id=$section_id AND school_id=$school_id");
        }
        
        if (empty($officer_name) || empty($officer_id_number) || empty($phone)) {
            $error = "All fields are required";
        } else {
            if (!empty($_POST['officer_id'])) {
                // Update existing officer
                $sql = "UPDATE officers SET 
                        officer_name='$officer_name', 
                        officer_id_number='$officer_id_number', 
                        phone='$phone', 
                        section_id=" . ($section_id != 'NULL' ? $section_id : "NULL") . ", 
                        is_incharge=$is_incharge 
                        WHERE id=" . (int)$_POST['officer_id'] . " AND school_id=$school_id";
                
                if (mysqli_query($conn, $sql)) {
                    $message = "Officer updated successfully!";
                } else {
                    $error = "Error updating officer: " . mysqli_error($conn);
                }
            } else {
                // Insert new officer
                $sql = "INSERT INTO officers (school_id, section_id, officer_name, officer_id_number, phone, is_incharge) 
                        VALUES ($school_id, " . ($section_id != 'NULL' ? $section_id : "NULL") . ", '$officer_name', '$officer_id_number', '$phone', $is_incharge)";
                
                if (mysqli_query($conn, $sql)) {
                    $message = "Officer added successfully!";
                } else {
                    $error = "Error adding officer: " . mysqli_error($conn);
                }
            }
        }
    }
    
    // Delete Section
    elseif (isset($_POST['delete_section'])) {
        $del_section_id = (int)$_POST['delete_section'];
        
        // Check if section has officers
        $check = mysqli_query($conn, "SELECT COUNT(*) as total FROM officers WHERE section_id=$del_section_id");
        $check_row = mysqli_fetch_assoc($check);
        
        if ($check_row['total'] > 0) {
            $error = "Cannot delete section with assigned officers. Please reassign officers first.";
        } else {
            if (mysqli_query($conn, "DELETE FROM sections WHERE id=$del_section_id AND school_id=$school_id")) {
                $message = "Section deleted successfully!";
            } else {
                $error = "Error deleting section";
            }
        }
    }
    
    // Delete Officer
    elseif (isset($_POST['delete_officer'])) {
        $del_officer_id = (int)$_POST['delete_officer'];
        if (mysqli_query($conn, "DELETE FROM officers WHERE id=$del_officer_id AND school_id=$school_id")) {
            $message = "Officer deleted successfully!";
        } else {
            $error = "Error deleting officer";
        }
    }
}

// Get counts for dashboard
$total_sections = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM sections WHERE school_id=$school_id"))['count'];
$total_officers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM officers WHERE school_id=$school_id"))['count'];
$inchage_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM officers WHERE school_id=$school_id AND is_incharge=1"))['count'];
$unassigned_officers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM officers WHERE school_id=$school_id AND section_id IS NULL"))['count'];
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-grid"></i> Sections & Officers Management</h2>
                <p class="text-muted">Manage school sections and assign officers as in-charge</p>
            </div>
            <div>
                <button class="btn btn-primary" onclick="location.href='?action=add_section#addSection'">
                    <i class="bi bi-plus-circle"></i> Add Section
                </button>
                <button class="btn btn-success" onclick="location.href='?action=add_officer#addOfficer'">
                    <i class="bi bi-person-plus"></i> Add Officer
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Sections</h5>
                        <h2><?php echo $total_sections; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Officers</h5>
                        <h2><?php echo $total_officers; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">In-charge Officers</h5>
                        <h2><?php echo $inchage_count; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Unassigned Officers</h5>
                        <h2><?php echo $unassigned_officers; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Add Section Form (hidden by default, shown when action=add_section) -->
        <?php if ($action == 'add_section'): 
            $section = null;
        ?>
            <div class="content-card mb-4" id="addSection">
                <h4 class="mb-4">
                    <i class="bi bi-plus-circle"></i> Add New Section
                </h4>
                
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room/Section Name *</label>
                            <input type="text" class="form-control" name="section_name" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Type</label>
                            <select class="form-select" name="section_type">
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="AL">AL</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Main Section (if any)</label>
                            <input type="text" class="form-control" name="main_section" placeholder="e.g., Science Section">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" name="save_section" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Section
                        </button>
                        <a href="sections_officers.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Add Officer Form (hidden by default, shown when action=add_officer) -->
        <?php if ($action == 'add_officer'): 
            $officer = null;
        ?>
            <div class="content-card mb-4" id="addOfficer">
                <h4 class="mb-4">
                    <i class="bi bi-person-plus"></i> Add New Officer
                </h4>
                
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="officer_name" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID Number *</label>
                            <input type="text" class="form-control" name="officer_id_number" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assign to Section</label>
                            <select class="form-select" name="section_id">
                                <option value="">-- Not Assigned --</option>
                                <?php
                                $sections = mysqli_query($conn, "SELECT id, section_name FROM sections WHERE school_id = $school_id ORDER BY section_name");
                                if ($sections) {
                                    while ($sec = mysqli_fetch_assoc($sections)):
                                    ?>
                                    <option value="<?php echo $sec['id']; ?>">
                                        <?php echo htmlspecialchars($sec['section_name']); ?>
                                    </option>
                                <?php 
                                    endwhile;
                                } ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_incharge" id="is_incharge" value="1">
                                <label class="form-check-label" for="is_incharge">
                                    <strong>Make this officer the Section In-charge</strong>
                                </label>
                                <small class="text-muted d-block">(Only one in-charge per section)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" name="save_officer" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Officer
                        </button>
                        <a href="sections_officers.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Edit Section Form (hidden by default, shown when action=edit_section) -->
        <?php if ($action == 'edit_section' && $section_id): 
            $result = mysqli_query($conn, "SELECT * FROM sections WHERE id = $section_id AND school_id = $school_id");
            $section = mysqli_fetch_assoc($result);
            if ($section):
        ?>
            <div class="content-card mb-4" id="editSection">
                <h4 class="mb-4">
                    <i class="bi bi-pencil"></i> Edit Section: <?php echo htmlspecialchars($section['section_name']); ?>
                </h4>
                
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="section_id" value="<?php echo $section['id']; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Room/Section Name *</label>
                            <input type="text" class="form-control" name="section_name" 
                                   value="<?php echo htmlspecialchars($section['section_name']); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Type</label>
                            <select class="form-select" name="section_type">
                                <option value="Primary" <?php echo $section['section_type'] == 'Primary' ? 'selected' : ''; ?>>Primary</option>
                                <option value="Secondary" <?php echo $section['section_type'] == 'Secondary' ? 'selected' : ''; ?>>Secondary</option>
                                <option value="AL" <?php echo $section['section_type'] == 'AL' ? 'selected' : ''; ?>>AL</option>
                                <option value="Other" <?php echo $section['section_type'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Main Section (if any)</label>
                            <input type="text" class="form-control" name="main_section" 
                                   value="<?php echo htmlspecialchars($section['main_section'] ?? ''); ?>" 
                                   placeholder="e.g., Science Section">
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" name="save_section" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Section
                        </button>
                        <a href="sections_officers.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; endif; ?>

        <!-- Edit Officer Form (hidden by default, shown when action=edit_officer) -->
        <?php if ($action == 'edit_officer' && $officer_id): 
            $result = mysqli_query($conn, "SELECT * FROM officers WHERE id = $officer_id AND school_id = $school_id");
            $officer = mysqli_fetch_assoc($result);
            if ($officer):
        ?>
            <div class="content-card mb-4" id="editOfficer">
                <h4 class="mb-4">
                    <i class="bi bi-pencil"></i> Edit Officer: <?php echo htmlspecialchars($officer['officer_name']); ?>
                </h4>
                
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="officer_id" value="<?php echo $officer['id']; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="officer_name" 
                                   value="<?php echo htmlspecialchars($officer['officer_name']); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID Number *</label>
                            <input type="text" class="form-control" name="officer_id_number" 
                                   value="<?php echo htmlspecialchars($officer['officer_id_number']); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" name="phone" 
                                   value="<?php echo htmlspecialchars($officer['phone']); ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assign to Section</label>
                            <select class="form-select" name="section_id">
                                <option value="">-- Not Assigned --</option>
                                <?php
                                $sections = mysqli_query($conn, "SELECT id, section_name FROM sections WHERE school_id = $school_id ORDER BY section_name");
                                while ($sec = mysqli_fetch_assoc($sections)):
                                    $selected = ($officer['section_id'] == $sec['id']) ? 'selected' : '';
                                ?>
                                <option value="<?php echo $sec['id']; ?>" <?php echo $selected; ?>>
                                    <?php echo htmlspecialchars($sec['section_name']); ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_incharge" id="is_incharge" value="1"
                                    <?php echo $officer['is_incharge'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_incharge">
                                    <strong>Make this officer the Section In-charge</strong>
                                </label>
                                <small class="text-muted d-block">(Only one in-charge per section)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" name="save_officer" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Officer
                        </button>
                        <a href="sections_officers.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; endif; ?>

        <!-- ========== BOTTOM SECTIONS - ALWAYS VISIBLE ========== -->

        <!-- Section 1: Complete Sections List with Edit/Delete -->
        <div class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-building"></i> Complete Sections List</h5>
                <span class="badge bg-primary">Total: <?php echo $total_sections; ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Section Name</th>
                            <th>Type</th>
                            <th>Main Section</th>
                            <th>Current In-charge</th>
                            <th width="250">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sections = mysqli_query($conn, "SELECT s.*, 
                            (SELECT o.id FROM officers o WHERE o.section_id = s.id AND o.is_incharge = 1 LIMIT 1) as incharge_id,
                            (SELECT o.officer_name FROM officers o WHERE o.section_id = s.id AND o.is_incharge = 1 LIMIT 1) as incharge_name
                            FROM sections s WHERE s.school_id = $school_id ORDER BY s.section_name");
                        
                        if ($sections && mysqli_num_rows($sections) > 0):
                            while ($section = mysqli_fetch_assoc($sections)):
                        ?>
                        <tr>
                            <td>#<?php echo $section['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($section['section_name']); ?></strong></td>
                            <td><span class="badge bg-info"><?php echo $section['section_type']; ?></span></td>
                            <td><?php echo htmlspecialchars($section['main_section'] ?: '-'); ?></td>
                            <td>
                                <?php if ($section['incharge_name']): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($section['incharge_name']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Not Assigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- EDIT BUTTON - Blue -->
                                <button class="btn btn-sm btn-primary me-1" title="Edit Section" 
                                        onclick="location.href='?action=edit_section&section_id=<?php echo $section['id']; ?>#editSection'">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                
                                <!-- DELETE BUTTON - Red -->
                                <button class="btn btn-sm btn-danger" title="Delete Section" 
                                        onclick="confirmDelete('section', <?php echo $section['id']; ?>, '<?php echo addslashes($section['section_name']); ?>')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="6" class="text-center text-muted">No sections added yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Complete Officers List with Edit/Delete -->
        <div class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-people"></i> Complete Officers List</h5>
                <span class="badge bg-success">Total: <?php echo $total_officers; ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Phone</th>
                            <th>Assigned Section</th>
                            <th>Role</th>
                            <th width="300">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $officers = mysqli_query($conn, "SELECT o.*, s.section_name 
                            FROM officers o 
                            LEFT JOIN sections s ON o.section_id = s.id 
                            WHERE o.school_id = $school_id 
                            ORDER BY o.officer_name");
                        
                        if ($officers && mysqli_num_rows($officers) > 0):
                            while ($officer = mysqli_fetch_assoc($officers)):
                        ?>
                        <tr>
                            <td>#<?php echo $officer['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($officer['officer_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($officer['officer_id_number']); ?></td>
                            <td><?php echo htmlspecialchars($officer['phone']); ?></td>
                            <td>
                                <?php if ($officer['section_name']): ?>
                                    <span class="badge bg-info">
                                        <?php echo htmlspecialchars($officer['section_name']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($officer['is_incharge']): ?>
                                    <span class="badge bg-warning">In-charge</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Member</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- EDIT BUTTON - Blue -->
                                <button class="btn btn-sm btn-primary me-1" title="Edit Officer" 
                                        onclick="location.href='?action=edit_officer&officer_id=<?php echo $officer['id']; ?>#editOfficer'">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                
                                <!-- DELETE BUTTON - Red -->
                                <button class="btn btn-sm btn-danger" title="Delete Officer" 
                                        onclick="confirmDelete('officer', <?php echo $officer['id']; ?>, '<?php echo addslashes($officer['officer_name']); ?>')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                                
                                <!-- ASSIGN BUTTON - Yellow (Optional) -->
                                <button class="btn btn-sm btn-warning mt-1" title="Quick Assign" 
                                        onclick="quickAssign(<?php echo $officer['id']; ?>)">
                                    <i class="bi bi-arrow-left-right"></i> Assign
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="7" class="text-center text-muted">No officers added yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 3: Current Assignments List (Shows which officer is assigned to which section) -->
        <div class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-diagram-3"></i> Current Section Assignments</h5>
                <span class="badge bg-info">Active Assignments</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Section</th>
                            <th>Section Type</th>
                            <th>Assigned Officer</th>
                            <th>Officer ID</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $assignments = mysqli_query($conn, "SELECT 
                            s.id as section_id,
                            s.section_name,
                            s.section_type,
                            o.id as officer_id,
                            o.officer_name,
                            o.officer_id_number,
                            o.phone,
                            o.is_incharge
                            FROM sections s
                            LEFT JOIN officers o ON s.id = o.section_id
                            WHERE s.school_id = $school_id
                            ORDER BY s.section_name, o.is_incharge DESC");
                        
                        $has_assignments = false;
                        if ($assignments && mysqli_num_rows($assignments) > 0):
                            while ($assign = mysqli_fetch_assoc($assignments)):
                                if ($assign['officer_name']): // Only show if officer is assigned
                                    $has_assignments = true;
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($assign['section_name']); ?></strong></td>
                            <td><span class="badge bg-info"><?php echo $assign['section_type']; ?></span></td>
                            <td><?php echo htmlspecialchars($assign['officer_name']); ?></td>
                            <td><?php echo htmlspecialchars($assign['officer_id_number']); ?></td>
                            <td><?php echo htmlspecialchars($assign['phone']); ?></td>
                            <td>
                                <?php if ($assign['is_incharge']): ?>
                                    <span class="badge bg-warning">In-charge</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Member</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Edit Officer Button -->
                                <button class="btn btn-sm btn-primary me-1" title="Edit Officer" 
                                        onclick="location.href='?action=edit_officer&officer_id=<?php echo $assign['officer_id']; ?>#editOfficer'">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                
                                <!-- Remove Assignment Button -->
                                <button class="btn btn-sm btn-danger" title="Remove Assignment" 
                                        onclick="removeAssignment(<?php echo $assign['officer_id']; ?>)">
                                    <i class="bi bi-person-x"></i> Remove
                                </button>
                            </td>
                        </tr>
                        <?php 
                                endif;
                            endwhile; 
                        endif;
                        
                        if (!$has_assignments):
                        ?>
                        <tr><td colspan="7" class="text-center text-muted">No officers assigned to any sections yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 4: Unassigned Officers List -->
        <div class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-person-dash"></i> Unassigned Officers</h5>
                <span class="badge bg-warning">Total: <?php echo $unassigned_officers; ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th width="250">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $unassigned = mysqli_query($conn, "SELECT * FROM officers 
                            WHERE school_id = $school_id AND section_id IS NULL 
                            ORDER BY officer_name");
                        
                        if ($unassigned && mysqli_num_rows($unassigned) > 0):
                            while ($ofc = mysqli_fetch_assoc($unassigned)):
                        ?>
                        <tr>
                            <td>#<?php echo $ofc['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($ofc['officer_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($ofc['officer_id_number']); ?></td>
                            <td><?php echo htmlspecialchars($ofc['phone']); ?></td>
                            <td><span class="badge bg-secondary">Unassigned</span></td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-primary me-1" title="Edit Officer" 
                                        onclick="location.href='?action=edit_officer&officer_id=<?php echo $ofc['id']; ?>#editOfficer'">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                
                                <!-- Assign Button -->
                                <button class="btn btn-sm btn-warning" title="Assign to Section" 
                                        onclick="quickAssign(<?php echo $ofc['id']; ?>)">
                                    <i class="bi bi-person-plus"></i> Assign
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="6" class="text-center text-muted">No unassigned officers.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hidden Quick Assign Form -->
        <form id="quickAssignForm" method="POST" action="?action=assign_officer" style="display: none;">
            <input type="hidden" name="assign_officer" id="quick_assign_officer_id">
        </form>

        <!-- Hidden Remove Assignment Form -->
        <form id="removeAssignmentForm" method="POST" style="display: none;">
            <input type="hidden" name="save_officer" value="1">
            <input type="hidden" name="officer_id" id="remove_officer_id">
            <input type="hidden" name="section_id" value="">
            <input type="hidden" name="is_incharge" value="0">
            <input type="hidden" name="officer_name" id="remove_officer_name">
            <input type="hidden" name="officer_id_number" id="remove_officer_id_number">
            <input type="hidden" name="phone" id="remove_officer_phone">
        </form>

        <!-- Delete Forms -->
        <form id="deleteSectionForm" method="POST" style="display: none;">
            <input type="hidden" name="delete_section" id="delete_section_id">
        </form>
        <form id="deleteOfficerForm" method="POST" style="display: none;">
            <input type="hidden" name="delete_officer" id="delete_officer_id">
        </form>
    </div>
</div>

<style>
/* Make buttons more visible */
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    font-weight: 500;
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    font-weight: 500;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    font-weight: 500;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
}

.table td {
    vertical-align: middle;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-dark {
    background-color: #343a40;
    color: white;
}

.badge {
    font-size: 0.875rem;
    padding: 0.35rem 0.65rem;
}

.content-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

/* Hover effects */
.btn-primary:hover, .btn-danger:hover, .btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Section headers */
.content-card h5 {
    color: #333;
    font-weight: 600;
    border-left: 4px solid #0d6efd;
    padding-left: 10px;
}
</style>

<script>
function confirmDelete(type, id, name) {
    if (confirm(`Are you sure you want to delete ${type} "${name}"? This action cannot be undone.`)) {
        if (type === 'section') {
            document.getElementById('delete_section_id').value = id;
            document.getElementById('deleteSectionForm').submit();
        } else {
            document.getElementById('delete_officer_id').value = id;
            document.getElementById('deleteOfficerForm').submit();
        }
    }
}

function quickAssign(officerId) {
    window.location.href = '?action=assign_officer&officer_id=' + officerId;
}

function removeAssignment(officerId) {
    if (confirm('Are you sure you want to remove this officer from their section?')) {
        // Get officer details first
        fetch(`get_officer_details.php?officer_id=${officerId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('remove_officer_id').value = data.id;
                document.getElementById('remove_officer_name').value = data.officer_name;
                document.getElementById('remove_officer_id_number').value = data.officer_id_number;
                document.getElementById('remove_officer_phone').value = data.phone;
                document.getElementById('removeAssignmentForm').submit();
            })
            .catch(() => {
                // Fallback: just redirect to edit page
                window.location.href = '?action=edit_officer&officer_id=' + officerId + '#editOfficer';
            });
    }
}

// Show message if trying to make multiple in-charges
document.addEventListener('DOMContentLoaded', function() {
    const inchargeCheckbox = document.getElementById('is_incharge');
    if (inchargeCheckbox) {
        inchargeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                const sectionSelect = document.querySelector('select[name="section_id"]');
                if (sectionSelect && !sectionSelect.value) {
                    alert('Please select a section first before making an officer in-charge');
                    this.checked = false;
                }
            }
        });
    }
});

// Check for URL parameters for messages
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('msg')) {
    const msg = urlParams.get('msg');
    if (msg.startsWith('updated_')) {
        const count = msg.replace('updated_', '');
        alert(`${count} officer(s) updated successfully!`);
    }
}

// Auto scroll to form if needed
if (window.location.hash) {
    setTimeout(() => {
        document.querySelector(window.location.hash)?.scrollIntoView({ behavior: 'smooth' });
    }, 100);
}
</script>

<?php require_once "footer.php"; ?>