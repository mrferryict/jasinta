<!doctype html>
<html lang="en">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title><?= $settings['appName'] ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <meta name="title" content="<?= esc($settings['appName']) ?>" />
   <meta name="author" content="<?= langUppercase('App.footerCopyright') ?>" />
   <meta name="description" content="<?= esc($settings['appName']) ?>" />

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
                  <div id="chat-button">
                     <a class="nav-link" href="<?= base_url(trim(strtolower(session()->get('division'))) . '/chats') ?>">
                        <i class="bi bi-chat-text"></i>
                        <?php
                        $totalUnreadMessages = 0;
                        foreach ($sendersList as $i) {
                           $totalUnreadMessages += $i['unread_messages'];
                        } ?>
                        <?php if ($totalUnreadMessages > 0) : ?>
                           <span class="navbar-badge badge text-bg-danger"><?= $totalUnreadMessages ?></span>
                        <?php endif; ?>
                     </a>
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