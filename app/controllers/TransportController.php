<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class TransportController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $routes = Database::fetchAll("SELECT * FROM bus_routes WHERE is_active = TRUE ORDER BY route_name");
        $buses = Database::fetchAll("SELECT b.*, r.route_name FROM buses b LEFT JOIN bus_routes r ON b.route_id = r.id ORDER BY b.bus_number");
        $assignments = Database::fetchAll(
            "SELECT st.*, s.first_name || ' ' || s.last_name as student_name, r.route_name, b.bus_number
             FROM student_transport st
             JOIN students s ON st.student_id = s.id
             JOIN bus_routes r ON st.route_id = r.id
             LEFT JOIN buses b ON st.bus_id = b.id
             WHERE st.is_active = TRUE
             ORDER BY r.route_name, s.first_name"
        );
        $this->view('transport/index', [
            'title'       => 'Transport',
            'routes'      => $routes,
            'buses'       => $buses,
            'assignments' => $assignments,
        ]);
    }
}