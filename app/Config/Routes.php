<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('register', 'Auth::register'); // Menampilkan form registrasi mahasiswa
$routes->post('register', 'Auth::register'); // Memproses registrasi mahasiswa
$routes->get('forgot-password', 'Auth::forgotPassword'); // Menampilkan form lupa password
$routes->post('forgot-password', 'Auth::forgotPassword'); // Memproses lupa password
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1'); // Menampilkan halaman reset password
$routes->post('updatePassword', 'Auth::updatePassword'); // Memproses perubahan password
$routes->get('verify-email/(:any)', 'Auth::verifyEmail/$1'); // Memproses verifikasi email
$routes->get('dashboard', 'Admin::dashboard', ['filter' => 'auth']); // Dashboard hanya untuk pengguna yang sudah login
$routes->get('logout', 'Auth::logout', ['filter' => 'auth']);


$routes->group('admin', ['filter' => 'auth'], function ($routes) {
   $routes->get('/', 'Admin::index');
   $routes->get('mahasiswa', 'Admin::mahasiswa');
   $routes->get('dosen', 'Admin::dosen');
   $routes->get('pengumuman', 'Admin::pengumuman');
   $routes->get('pesan', 'Admin::pesan');
   $routes->get('pendaftaran-bimbingan', 'Admin::pendaftaranBimbingan');
   $routes->get('persyaratan-pra-bimbingan', 'Admin::persyaratanPraBimbingan');
   $routes->get('pengajuan-proposal', 'Admin::pengajuanProposal');
   $routes->get('sk-bimbingan', 'Admin::skBimbingan');
   $routes->get('persyaratan-pra-sidang', 'Admin::persyaratanPraSidang');
   $routes->get('sidang', 'Admin::sidang');
   $routes->get('revisi-akhir', 'Admin::revisiAkhir');
   $routes->get('persyaratan-pasca-sidang', 'Admin::persyaratanPascaSidang');
   $routes->get('surat-keterangan-lulus', 'Admin::suratKeteranganLulus');
   $routes->get('aktivitas-pengguna', 'Admin::aktivitasPengguna');
});
