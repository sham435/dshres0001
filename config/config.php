<?php
declare(strict_types=1);

/**
 * Application Configuration
 */
return [
    'app_name'    => 'Nachchaduwa Muslim Maha Vidyalaya',
    'app_url'     => 'http://localhost',
    'app_env'     => 'development',
    'app_debug'   => true,
    'app_timezone' => 'Asia/Colombo',

    'db' => [
        'host'     => getenv('DB_HOST') ?: '127.0.0.1',
        'port'     => getenv('DB_PORT') ?: '5432',
        'dbname'   => getenv('DB_NAME') ?: 'school_db',
        'user'     => getenv('DB_USER') ?: 'postgres',
        'password' => getenv('DB_PASS') ?: '',
        'charset'  => 'utf8',
    ],

    'session' => [
        'name'     => 'school_session',
        'lifetime' => 7200,
        'path'     => '/',
        'domain'   => '',
        'secure'   => false,  // Set true in production (HTTPS)
        'httponly' => true,
        'samesite' => 'Lax',
    ],

    'upload' => [
        'max_size'    => 5 * 1024 * 1024, // 5MB
        'allowed_ext' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
        'path'        => __DIR__ . '/../storage/uploads/',
    ],

    'pagination' => [
        'per_page' => 20,
    ],

    'languages' => ['en', 'es', 'fr', 'si'],
    'default_language' => 'en',
    'default_theme' => 'light',
];