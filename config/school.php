<?php
return [
    'name'    => env('SCHOOL_NAME', 'Lanka Edu School'),
    'phone'   => env('SCHOOL_PHONE', '+94 25 222 0000'),
    'email'   => env('SCHOOL_EMAIL', 'info@school.test'),
    'address' => env('SCHOOL_ADDRESS', 'Anuradhapura, Sri Lanka'),
    'social'  => ['facebook' => '#', 'twitter' => '#', 'instagram' => '#', 'youtube' => '#'],
    // demo login buttons (only rendered when APP_DEBUG=true)
    'demo_accounts' => [
        'Super Admin' => ['cj@cj.com', 'cj'],
        'Admin'       => ['admin@admin.com', 'cj'],
        'Teacher'     => ['teacher@teacher.com', 'cj'],
    ],
];
