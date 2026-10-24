<?php
// inventory_map.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;

// Get all sections from database
$sections_query = "SELECT s.*, 
                   COUNT(i.id) as item_count,
                   (SELECT o.officer_name FROM officers o WHERE o.section_id = s.id AND o.is_incharge = 1 LIMIT 1) as incharge_name
                   FROM sections s 
                   LEFT JOIN inventory_items i ON s.id = i.section_id
                   WHERE s.school_id = $school_id
                   GROUP BY s.id
                   ORDER BY s.section_name";
$sections = mysqli_query($conn, $sections_query);

// Get items for selected section (via AJAX or initial load)
$selected_section = isset($_GET['section_id']) ? (int)$_GET['section_id'] : 0;
$items = [];

if ($selected_section > 0) {
    $items_query = "SELECT i.*, o.officer_name as incharge_name 
                    FROM inventory_items i 
                    LEFT JOIN officers o ON i.incharge_officer_id = o.id 
                    WHERE i.section_id = $selected_section AND i.school_id = $school_id
                    ORDER BY i.item_name";
    $items_result = mysqli_query($conn, $items_query);
    
    if ($items_result) {
        while ($row = mysqli_fetch_assoc($items_result)) {
            $items[] = $row;
        }
    }
}

// Get section details if selected
$selected_section_details = null;
if ($selected_section > 0) {
    $sec_query = "SELECT s.*, o.officer_name as incharge_name 
                  FROM sections s 
                  LEFT JOIN officers o ON o.section_id = s.id AND o.is_incharge = 1
                  WHERE s.id = $selected_section AND s.school_id = $school_id";
    $sec_result = mysqli_query($conn, $sec_query);
    if ($sec_result && mysqli_num_rows($sec_result) > 0) {
        $selected_section_details = mysqli_fetch_assoc($sec_result);
    }
}

// Function to generate color based on section name
function getSectionColor($section_name) {
    $colors = [
        '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
        '#D4A5A5', '#9B59B6', '#3498DB', '#E67E22', '#2ECC71',
        '#E74C3C', '#1ABC9C', '#F1C40F', '#8E44AD', '#2980B9',
        '#D35400', '#27AE60', '#C0392B', '#16A085', '#F39C12'
    ];
    
    // Simple hash to get consistent color for same section
    $index = abs(crc32($section_name)) % count($colors);
    return $colors[$index];
}

// Function to generate SVG layout
function generateSectionLayout($sections) {
    $svg_width = 1000;
    $svg_height = 600;
    $padding = 20;
    $cols = 4; // Number of columns in grid
    $box_width = ($svg_width - ($padding * ($cols + 1))) / $cols;
    $box_height = 120;
    
    $svg = '<svg width="100%" height="600" viewBox="0 0 ' . $svg_width . ' ' . $svg_height . '" preserveAspectRatio="xMidYMid meet">';
    
    // Add background
    $svg .= '<rect width="' . $svg_width . '" height="' . $svg_height . '" fill="#f8f9fc" rx="10" ry="10"/>';
    
    // Add title
    $svg .= '<text x="20" y="30" font-family="Arial" font-size="16" font-weight="bold" fill="#4e73df">SCHOOL LAYOUT MAP</text>';
    $svg .= '<text x="20" y="50" font-family="Arial" font-size="12" fill="#666">Click on any section to view inventory items</text>';
    
    $index = 0;
    mysqli_data_seek($sections, 0);
    
    while ($section = mysqli_fetch_assoc($sections)) {
        $row = floor($index / $cols);
        $col = $index % $cols;
        
        $x = $padding + ($col * ($box_width + $padding));
        $y = 80 + ($row * ($box_height + $padding));
        
        $color = getSectionColor($section['section_name']);
        $hover_color = adjustBrightness($color, 20);
        
        // Section box with hover effect
        $svg .= '<a href="?section_id=' . $section['id'] . '#items-section">';
        $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . $box_width . '" height="' . $box_height . '" 
                 fill="' . $color . '" stroke="#fff" stroke-width="2" rx="8" ry="8"
                 style="transition: all 0.3s; cursor: pointer;"
                 onmouseover="this.setAttribute(\'fill\', \'' . $hover_color . '\'); this.setAttribute(\'stroke-width\', \'3\');"
                 onmouseout="this.setAttribute(\'fill\', \'' . $color . '\'); this.setAttribute(\'stroke-width\', \'2\');"/>';
        
        // Section name
        $words = str_split($section['section_name'], 15);
        $text_y = $y + 30;
        foreach ($words as $word) {
            $svg .= '<text x="' . ($x + 10) . '" y="' . $text_y . '" font-family="Arial" font-size="12" font-weight="bold" fill="white">' . htmlspecialchars($word) . '</text>';
            $text_y += 18;
        }
        
        // Section type
        $svg .= '<text x="' . ($x + 10) . '" y="' . ($y + 70) . '" font-family="Arial" font-size="10" fill="white" opacity="0.9">' . $section['section_type'] . '</text>';
        
        // Item count badge
        $count_x = $x + $box_width - 40;
        $count_y = $y + 30;
        $svg .= '<circle cx="' . $count_x . '" cy="' . $count_y . '" r="15" fill="#fff" opacity="0.3"/>';
        $svg .= '<text x="' . $count_x . '" y="' . ($count_y + 5) . '" font-family="Arial" font-size="12" font-weight="bold" fill="white" text-anchor="middle">' . $section['item_count'] . '</text>';
        
        // Incharge indicator
        if ($section['incharge_name']) {
            $svg .= '<text x="' . ($x + 10) . '" y="' . ($y + 90) . '" font-family="Arial" font-size="9" fill="white" opacity="0.8">👤 ' . htmlspecialchars(substr($section['incharge_name'], 0, 15)) . '</text>';
        }
        
        $svg .= '</a>';
        
        $index++;
    }
    
    // Add legend
    $legend_y = 80 + ((ceil($index / $cols)) * ($box_height + $padding)) + 30;
    $svg .= '<rect x="20" y="' . $legend_y . '" width="200" height="60" fill="#fff" stroke="#ddd" rx="5" ry="5"/>';
    $svg .= '<text x="30" y="' . ($legend_y + 20) . '" font-family="Arial" font-size="11" font-weight="bold" fill="#333">Legend:</text>';
    $svg .= '<circle cx="35" cy="' . ($legend_y + 35) . '" r="6" fill="#4e73df"/>';
    $svg .= '<text x="50" y="' . ($legend_y + 40) . '" font-family="Arial" font-size="10" fill="#666">Click any section to view items</text>';
    
    $svg .= '</svg>';
    
    return $svg;
}

// Helper function to adjust color brightness
function adjustBrightness($hex, $percent) {
    // Remove # if present
    $hex = ltrim($hex, '#');
    
    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Adjust brightness
    $r = max(0, min(255, $r + $percent));
    $g = max(0, min(255, $g + $percent));
    $b = max(0, min(255, $b + $percent));
    
    return '#' . sprintf("%02x%02x%02x", $r, $g, $b);
}
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-map"></i> Inventory Map</h2>
                <p class="text-muted">Visual representation of school sections and their inventory</p>
            </div>
            <div>
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset View
                </button>
            </div>
        </div>

        <!-- Main Map Card -->
        <div class="content-card">
            <div class="row">
                <div class="col-12">
                    <div class="map-container text-center">
                        <?php 
                        mysqli_data_seek($sections, 0);
                        echo generateSectionLayout($sections); 
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Details and Items -->
        <?php if ($selected_section > 0 && $selected_section_details): ?>
        <div class="content-card mt-4" id="items-section">
            <div class="row mb-3">
                <div class="col-md-8">
                    <h4>
                        <i class="bi bi-building" style="color: <?php echo getSectionColor($selected_section_details['section_name']); ?>"></i>
                        <?php echo htmlspecialchars($selected_section_details['section_name']); ?>
                        <small class="text-muted">(<?php echo $selected_section_details['section_type']; ?>)</small>
                    </h4>
                    <?php if ($selected_section_details['main_section']): ?>
                        <p class="text-muted">Main Section: <?php echo htmlspecialchars($selected_section_details['main_section']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="insert_data.php?section_id=<?php echo $selected_section; ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add Item to this Section
                    </a>
                    <a href="report.php?section=<?php echo $selected_section; ?>" class="btn btn-info">
                        <i class="bi bi-file-text"></i> Generate Report
                    </a>
                </div>
            </div>

            <!-- Section Info Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted">In-charge Officer</h6>
                            <h5 class="mb-0">
                                <?php if ($selected_section_details['incharge_name']): ?>
                                    <?php echo htmlspecialchars($selected_section_details['incharge_name']); ?>
                                <?php else: ?>
                                    <span class="text-muted">Not Assigned</span>
                                <?php endif; ?>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Total Items</h6>
                            <h5 class="mb-0"><?php echo count($items); ?></h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Working Items</h6>
                            <h5 class="mb-0">
                                <?php 
                                $working = array_filter($items, function($item) {
                                    return $item['condition_status'] == 'working';
                                });
                                echo count($working);
                                ?>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Available Items</h6>
                            <h5 class="mb-0">
                                <?php 
                                $available = array_filter($items, function($item) {
                                    return !$item['is_transferred'] && empty($item['borrowed_officer']);
                                });
                                echo count($available);
                                ?>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <h5 class="mb-3"><i class="bi bi-box"></i> Inventory Items in this Section</h5>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Brand</th>
                            <th>Inventory #</th>
                            <th>Serial #</th>
                            <th>Book #</th>
                            <th>Condition</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($items) > 0): ?>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?php if ($item['image_path'] && file_exists($item['image_path'])): ?>
                                        <img src="<?php echo $item['image_path']; ?>" alt="Item" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <div style="width: 40px; height: 40px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($item['brand_name'] ?: '-'); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                                <td><?php echo htmlspecialchars($item['serial_number'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['book_number']); ?></td>
                                <td>
                                    <?php
                                    $condition_class = [
                                        'working' => 'success',
                                        'good' => 'info',
                                        'not_working' => 'warning',
                                        'not_usable' => 'danger'
                                    ];
                                    $condition_text = [
                                        'working' => 'Working',
                                        'good' => 'Good',
                                        'not_working' => 'Not Working',
                                        'not_usable' => 'Not Usable'
                                    ];
                                    ?>
                                    <span class="badge bg-<?php echo $condition_class[$item['condition_status']] ?? 'secondary'; ?>">
                                        <?php echo $condition_text[$item['condition_status']] ?? $item['condition_status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($item['is_transferred']): ?>
                                        <span class="badge bg-warning">Transferred</span>
                                    <?php elseif ($item['borrowed_officer']): ?>
                                        <span class="badge bg-info">Borrowed</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-box" style="font-size: 2rem;"></i><br>
                                    No items found in this section.<br>
                                    <a href="insert_data.php?section_id=<?php echo $selected_section; ?>" class="btn btn-sm btn-primary mt-2">
                                        Add First Item
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Legend Card -->
        <div class="content-card mt-4">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="bi bi-info-circle"></i> How to Use the Inventory Map</h6>
                    <ul class="small">
                        <li>Each colored square represents a section or room</li>
                        <li>Click on any square to view all inventory items in that section</li>
                        <li>The number in the circle shows total items in that section</li>
                        <li>Section in-charge officer is displayed at the bottom of each square</li>
                        <li>Colors are automatically generated based on section names</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6><i class="bi bi-palette"></i> Color Legend</h6>
                    <div class="d-flex flex-wrap">
                        <?php 
                        mysqli_data_seek($sections, 0);
                        $display_count = 0;
                        while ($sec = mysqli_fetch_assoc($sections)): 
                            if ($display_count >= 8) break; // Show only first 8 in legend
                        ?>
                        <div class="me-3 mb-2">
                            <span style="display: inline-block; width: 15px; height: 15px; background: <?php echo getSectionColor($sec['section_name']); ?>; border-radius: 3px;"></span>
                            <small class="ms-1"><?php echo htmlspecialchars(substr($sec['section_name'], 0, 10)); ?></small>
                        </div>
                        <?php 
                            $display_count++;
                        endwhile; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.map-container {
    background: #f8f9fc;
    border-radius: 10px;
    padding: 10px;
    overflow-x: auto;
}

.map-container svg {
    min-width: 800px;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.6rem;
}

.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}

/* Hover effects for SVG elements */
.map-container a:hover rect {
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .map-container {
        padding: 5px;
    }
    
    .map-container svg {
        min-width: 600px;
    }
}
</style>

<script>
// Add smooth scroll when clicking on section
document.querySelectorAll('a[href^="?section_id="]').forEach(link => {
    link.addEventListener('click', function(e) {
        // Allow the link to work normally, but we'll add smooth scroll
        setTimeout(() => {
            document.getElementById('items-section')?.scrollIntoView({ behavior: 'smooth' });
        }, 100);
    });
});

// Add tooltips for sections
function showSectionTooltip(sectionName, itemCount) {
    // Can be enhanced with actual tooltips
    console.log(`Section: ${sectionName}, Items: ${itemCount}`);
}

// Responsive SVG view
window.addEventListener('resize', function() {
    const svg = document.querySelector('svg');
    if (svg) {
        // Adjust SVG view if needed
    }
});
</script>

<?php require_once "footer.php"; ?>