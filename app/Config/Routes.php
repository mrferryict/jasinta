<?php

namespace Config;

use CodeIgniter\Routing\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

// Default Home Route
$routes->get('/', 'Home::index');

// AUTHENTICATION ROUTES
$routes->group('auth', function ($routes) {
   $routes->get('login', 'Auth::login');
   $routes->post('login', 'Auth::login');
   $routes->get('register', 'Auth::register');
   $routes->post('register', 'Auth::register');
   $routes->get('forgot-password', 'Auth::forgotPassword');
   $routes->post('forgot-password', 'Auth::forgotPassword');
   $routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
   $routes->post('update-password', 'Auth::updatePassword');
   $routes->get('verify-email/(:any)', 'Auth::verifyEmail/$1');
   $routes->get('logout', 'Auth::logout');
});

// ADMINISTRATOR ROUTES (Hanya Bisa Diakses oleh Administrator)
$routes->group('admin', ['filter' => 'auth:ADMIN'], function ($routes) {
   $routes->get('/', 'Admin::index');
   $routes->get('students', 'Admin::students');
   $routes->get('lecturers', 'Admin::lecturers');
   $routes->get('announcements', 'Admin::announcements');
   $routes->post('announcements/create', 'Admin::createAnnouncement');
   $routes->get('messages', 'Admin::messages');
   $routes->post('messages/send', 'Admin::sendMessage');
   $routes->get('student-registrations', 'Admin::studentRegistrations');
   $routes->post('approve-registration/(:num)', 'Admin::approveRegistration/$1');
   $routes->get('requirements', 'Admin::requirements');
   $routes->post('requirements/create', 'Admin::createRequirement');
   $routes->post('requirements/approve/(:num)', 'Admin::approveRequirement/$1');
   $routes->get('monitoring', 'Admin::monitoring');
   $routes->get('activity_logs', 'Admin::activityLogs');
});

// STUDENT ROUTES (Hanya Bisa Diakses oleh Mahasiswa)
$routes->group('student', ['filter' => 'auth:STUDENT'], function ($routes) {
   $routes->get('/', 'Student::index');
   $routes->get('registration', 'Student::registration'); // Menampilkan halaman pendaftaran mahasiswa
   $routes->get('requirements/(:any)', 'Student::requirements/$1');
   $routes->post('requirements/submit', 'Student::submitRequirement');
   $routes->get('thesis', 'Student::thesis');
   $routes->post('thesis/proposal', 'Student::submitProposal');
   $routes->get('messages', 'Student::messages');
   $routes->post('messages/send', 'Student::sendMessage');
});

// LECTURER ROUTES (Hanya Bisa Diakses oleh Dosen)
$routes->group('lecturer', ['filter' => 'auth:LECTURER'], function ($routes) {
   $routes->get('/', 'Lecturer::index');
   $routes->get('supervision', 'Lecturer::supervision');
   $routes->get('thesis-evaluation', 'Lecturer::thesisEvaluation');
   $routes->post('thesis-evaluation/submit', 'Lecturer::submitEvaluation');
   $routes->get('messages', 'Lecturer::messages');
   $routes->post('messages/send', 'Lecturer::sendMessage');
});
