<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'AuthController::login');
$routes->post('login-process', 'AuthController::loginProcess');
$routes->get('register', 'AuthController::register');
$routes->post('register-process', 'AuthController::registerProcess');
$routes->get('logout', 'AuthController::logout');
$routes->get('forgot-password', 'AuthController::forgotPassword');
$routes->post('send-reset-link', 'AuthController::sendResetLink');
