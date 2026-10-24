<?php
declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class LanguageController extends Controller
{
    public function switch(string $locale): void
    {
        $allowed = config('languages', ['en', 'es']);
        if (in_array($locale, $allowed, true)) {
            $_SESSION['language'] = $locale;
        }
        $this->back();
    }
}