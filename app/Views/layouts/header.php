<!doctype html>
<html lang="en">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title><?= $settings['app_name'] ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <meta name="title" content="<?= esc($settings['app_name']) ?>" />
   <meta name="author" content="<?= langUppercase('App.footerCopyright') ?>" />
   <meta name="description" content="<?= esc($settings['app_name']) ?>" />

   <!-- Fonts -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
   <!-- Third Party Plugin (OverlayScrollbars) -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
   <!-- Bootstrap Icons -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
   <!-- Required Plugin (AdminLTE) -->
   <link rel="stylesheet" href="<?= f_template() ?>dist/css/adminlte.css" />
   <!-- ApexCharts -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" crossorigin="anonymous" />

   <?= $this->renderSection('css') ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
   <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body sticky-top">
         <div class="container-fluid">
            <ul class="navbar-nav">
               <li class="nav-item">
                  <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                     <i class="bi bi-list"></i>
                  </a>
               </li>
               <li class="nav-item">
                  <span id="real-time-clock" class="nav-link fw-semibold"></span>
               </li>

            </ul>
            <ul class="navbar-nav ms-auto">
               <!-- Messages -->
               <li class="nav-item dropdown">
                  <a class="nav-link" data-bs-toggle="dropdown" href="#">
                     <i class="bi bi-chat-text"></i>
                     <span class="navbar-badge badge text-bg-danger">3</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                     <a href="#" class="dropdown-item">
                        <!--begin::Message-->
                        <div class="d-flex">
                           <div class="flex-shrink-0">
                              <img src="../../dist/assets/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3">
                           </div>
                           <div class="flex-grow-1">
                              <h3 class="dropdown-item-title">
                                 Brad Diesel
                                 <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                              </h3>
                              <p class="fs-7">Call me whenever you can...</p>
                              <p class="fs-7 text-secondary">
                                 <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                              </p>
                           </div>
                        </div>
                        <!--end::Message-->
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="#" class="dropdown-item">
                        <!--begin::Message-->
                        <div class="d-flex">
                           <div class="flex-shrink-0">
                              <img src="../../dist/assets/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3">
                           </div>
                           <div class="flex-grow-1">
                              <h3 class="dropdown-item-title">
                                 John Pierce
                                 <span class="float-end fs-7 text-secondary">
                                    <i class="bi bi-star-fill"></i>
                                 </span>
                              </h3>
                              <p class="fs-7">I got your message bro</p>
                              <p class="fs-7 text-secondary">
                                 <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                              </p>
                           </div>
                        </div>
                        <!--end::Message-->
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="#" class="dropdown-item">
                        <!--begin::Message-->
                        <div class="d-flex">
                           <div class="flex-shrink-0">
                              <img src="../../dist/assets/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3">
                           </div>
                           <div class="flex-grow-1">
                              <h3 class="dropdown-item-title">
                                 Nora Silvester
                                 <span class="float-end fs-7 text-warning">
                                    <i class="bi bi-star-fill"></i>
                                 </span>
                              </h3>
                              <p class="fs-7">The subject goes here</p>
                              <p class="fs-7 text-secondary">
                                 <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                              </p>
                           </div>
                        </div>
                        <!--end::Message-->
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                  </div>
               </li>
               <!-- Announcements -->
               <li class="nav-item dropdown">
                  <a class="nav-link" data-bs-toggle="dropdown" href="#">
                     <i class="bi bi-bell-fill"></i>
                     <span class="navbar-badge badge text-bg-warning">15</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                     <span class="dropdown-item dropdown-header">15 Notifications</span>
                     <div class="dropdown-divider"></div>
                     <a href="#" class="dropdown-item">
                        <i class="bi bi-envelope me-2"></i> 4 new messages
                        <span class="float-end text-secondary fs-7">3 mins</span>
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="#" class="dropdown-item">
                        <i class="bi bi-people-fill me-2"></i> 8 friend requests
                        <span class="float-end text-secondary fs-7">12 hours</span>
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="#" class="dropdown-item">
                        <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                        <span class="float-end text-secondary fs-7">2 days</span>
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                  </div>
               </li>
               <li class="nav-item dropdown user-menu">
                  <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                     <i class="bi bi-person-circle"></i>
                     <span class="fw-bold ms-2 me-2"><?= session()->get('name') ?></span>
                     <small>[<?= session()->get('division') ?>]</small>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                     <li class="user-body">

                        <!-- <a href="#" class="dropdown-item">
                           <i class="bi bi-person-badge me-2"></i> <?= langUppercase('App.profile') ?>
                        </a> -->
                        <!-- <div class="dropdown-divider"></div> -->
                        <a href="<?= base_url('auth/logout') ?>" class="dropdown-item">
                           <i class="bi bi-box-arrow-right me-2"></i> <?= langUppercase('App.signOut') ?>
                        </a>
                     </li>
                  </ul>
               </li>
            </ul>
         </div>
      </nav>