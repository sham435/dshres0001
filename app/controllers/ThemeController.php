<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class ThemeController extends Controller
{
    public function switch(string $mode): void
    {
        if (in_array($mode, ['light', 'dark'], true)) {
            $_SESSION['theme'] = $mode;
        }
        // If AJAX request, return JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->json(['success' => true, 'theme' => $_SESSION['theme']]);
        }
        $this->back();
    }
}