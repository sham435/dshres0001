<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('public/home', [
            'title' => config('app_name'),
        ], 'public');
    }

    public function about(): void
    {
        // Serve the enhanced standalone about page (Sinhala)
        $file = BASE_PATH . '/public/about.html';
        if (file_exists($file)) {
            echo file_get_contents($file);
            exit;
        }
        $this->view('public/about', [
            'title' => 'About Us',
        ], 'public');
    }

    public function english(): void
    {
        // Serve the English version of the homepage
        $file = BASE_PATH . '/public/en.html';
        if (file_exists($file)) {
            echo file_get_contents($file);
            exit;
        }
        $this->view('public/home', [
            'title' => config('app_name'),
        ], 'public');
    }

    public function contact(): void
    {
        $this->view('public/contact', [
            'title' => 'Contact Us',
        ], 'public');
    }

    public function submitContact(): void
    {
        // Public contact form - CSRF not required for anonymous submissions
        $name = $this->sanitize($_POST['name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $message = $this->sanitize($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            $this->flash('error', 'All fields are required.');
            $this->redirect('/contact');
        }

        // In production, send email or store in DB
        $this->flash('success', 'Thank you for contacting us. We will get back to you soon.');
        $this->redirect('/contact');
    }
}