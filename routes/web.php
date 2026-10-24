<?php
declare(strict_types=1);

use Core\Router;

/** @var Router $router */

// Public pages (homepage)
$router->get('/', 'HomeController@about', 'home');
$router->get('/en', 'HomeController@english', 'english');
$router->get('/en/', 'HomeController@english', 'english.slash');
$router->get('/contact', 'HomeController@contact', 'contact');
$router->post('/contact', 'HomeController@submitContact', 'contact.submit');

// Authentication (backend admin access)
$router->get('/login', 'AuthController@login', 'login');
$router->post('/login', 'AuthController@authenticate', 'login.post');
$router->get('/logout', 'AuthController@logout', 'logout');
$router->get('/forgot-password', 'AuthController@forgotPassword', 'forgot.password');
$router->post('/forgot-password', 'AuthController@sendResetLink', 'forgot.password.post');
$router->get('/reset-password/{token}', 'AuthController@resetPassword', 'reset.password');
$router->post('/reset-password', 'AuthController@updatePassword', 'reset.password.post');

// Dashboard
$router->get('/dashboard', 'DashboardController@index', 'dashboard');

// Students
$router->get('/students', 'StudentController@index', 'students.index');
$router->get('/students/create', 'StudentController@create', 'students.create');
$router->post('/students', 'StudentController@store', 'students.store');
$router->get('/students/{id}', 'StudentController@show', 'students.show');
$router->get('/students/{id}/edit', 'StudentController@edit', 'students.edit');
$router->post('/students/{id}', 'StudentController@update', 'students.update');
$router->post('/students/{id}/delete', 'StudentController@destroy', 'students.destroy');

// Teachers
$router->get('/teachers', 'TeacherController@index', 'teachers.index');
$router->get('/teachers/create', 'TeacherController@create', 'teachers.create');
$router->post('/teachers', 'TeacherController@store', 'teachers.store');
$router->get('/teachers/{id}', 'TeacherController@show', 'teachers.show');
$router->get('/teachers/{id}/edit', 'TeacherController@edit', 'teachers.edit');
$router->post('/teachers/{id}', 'TeacherController@update', 'teachers.update');
$router->post('/teachers/{id}/delete', 'TeacherController@destroy', 'teachers.destroy');

// Attendance
$router->get('/attendance', 'AttendanceController@index', 'attendance.index');
$router->post('/attendance', 'AttendanceController@store', 'attendance.store');
$router->get('/attendance/report', 'AttendanceController@report', 'attendance.report');

// Exams
$router->get('/exams', 'ExamController@index', 'exams.index');
$router->get('/exams/create', 'ExamController@create', 'exams.create');
$router->post('/exams', 'ExamController@store', 'exams.store');
$router->get('/exams/{id}/results', 'ExamController@results', 'exams.results');
$router->post('/exams/{id}/results', 'ExamController@submitResults', 'exams.submitResults');

// Timetable
$router->get('/timetable', 'TimetableController@index', 'timetable.index');

// Notices
$router->get('/notices', 'NoticeController@index', 'notices.index');
$router->get('/notices/create', 'NoticeController@create', 'notices.create');
$router->post('/notices', 'NoticeController@store', 'notices.store');
$router->get('/notices/{id}', 'NoticeController@show', 'notices.show');

// Transport
$router->get('/transport', 'TransportController@index', 'transport.index');

// Leave Management
$router->get('/leaves', 'LeaveController@index', 'leaves.index');
$router->get('/leaves/create', 'LeaveController@create', 'leaves.create');
$router->post('/leaves', 'LeaveController@store', 'leaves.store');
$router->post('/leaves/{id}/approve', 'LeaveController@approve', 'leaves.approve');
$router->post('/leaves/{id}/reject', 'LeaveController@reject', 'leaves.reject');

// Settings
$router->get('/settings', 'SettingsController@index', 'settings.index');
$router->post('/settings', 'SettingsController@update', 'settings.update');

// Language switch
$router->get('/lang/{locale}', 'LanguageController@switch', 'lang.switch');

// Theme switch
$router->get('/theme/{mode}', 'ThemeController@switch', 'theme.switch');