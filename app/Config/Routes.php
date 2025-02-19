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

// ADMIN ROUTES (Hanya Bisa Diakses oleh ADMIN)
$routes->group('admin', ['filter' => 'access:ADMIN'], function ($routes) {
   $routes->get('/', 'Admin::index');
   $routes->get('users', 'Admin::users');
   $routes->get('create_user', 'Admin::createUser');
   $routes->post('save_user', 'Admin::saveUser');

   $routes->post('users/toggle-status', 'UserController::toggleStatus'); // API
   $routes->post('users/delete', 'UserController::deleteUser'); // API
   $routes->post('log/exportToExcel', 'LogController::exportToExcel'); // API

   $routes->get('majors', 'Admin::majors');
   $routes->get('settings', 'Admin::settings');

   $routes->get('chats', 'Admin::chat');
   $routes->get('chat/(:any)', 'Admin::chat/$1'); // API
   $routes->post('chat/(:any)/send', 'Admin::sendMessage/$1'); // API

   $routes->get('announcements', 'Admin::announcements');
   $routes->post('announcements/create', 'Admin::createAnnouncement');

   $routes->get('registrations', 'Admin::registrations');
   $routes->post('approve-registration/(:num)', 'Admin::approveRegistration/$1');
   $routes->get('requirements', 'Admin::requirements');
   $routes->post('requirements/create', 'Admin::createRequirement');
   $routes->post('requirements/approve/(:num)', 'Admin::approveRequirement/$1');
   $routes->get('monitoring', 'Admin::monitoring');
   $routes->get('activity_logs', 'Admin::activityLogs');
});


// STUDENT ROUTES (Hanya Bisa Diakses oleh STUDENT)
$routes->group('student', ['filter' => 'access:STUDENT'], function ($routes) {
   $routes->get('/', 'Student::index');
   $routes->get('registration', 'Student::registration'); // Menampilkan halaman pendaftaran mahasiswa
   $routes->get('requirements/(:any)', 'Student::requirements/$1');
   $routes->post('requirements/submit', 'Student::submitRequirement');
   $routes->get('thesis', 'Student::thesis');
   $routes->post('thesis/proposal', 'Student::submitProposal');
   $routes->get('chats', 'Student::chat');
   $routes->get('chat/(:any)', 'Student::chat/$1');
   $routes->post('chat/(:any)/send', 'Student::sendMessage/$1'); // API
});

// LECTURER ROUTES (Hanya Bisa Diakses oleh LECTURER)
$routes->group('lecturer', ['filter' => 'access:LECTURER'], function ($routes) {
   $routes->get('/', 'Lecturer::index');
   $routes->get('chats', 'Lecturer::chat');
   $routes->get('supervision', 'Lecturer::supervision');
   $routes->get('thesis-evaluation', 'Lecturer::thesisEvaluation');
   $routes->post('thesis-evaluation/submit', 'Lecturer::submitEvaluation');
   $routes->get('messages', 'Lecturer::messages');
   $routes->post('messages/send', 'Lecturer::sendMessage');
});
