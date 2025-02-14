<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title><?= $settings['app_name'] ?></title>
   <!--begin::Primary Meta Tags-->
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <meta name="title" content="<?= $settings['app_name'] ?>" />
   <meta name="author" content="<?= lang('App.footerCopyright') ?>" />
   <meta name="description" content="<?= $settings['app_name'] ?>" />
   <!--end::Primary Meta Tags-->
   <!--begin::Fonts-->
   <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous" />
   <!--end::Fonts-->
   <!--begin::Third Party Plugin(OverlayScrollbars)-->
   <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous" />
   <!--end::Third Party Plugin(OverlayScrollbars)-->
   <!--begin::Third Party Plugin(Bootstrap Icons)-->
   <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous" />
   <!--end::Third Party Plugin(Bootstrap Icons)-->
   <!--begin::Required Plugin(AdminLTE)-->
   <link rel="stylesheet" href="<?= f_template() ?>dist/css/adminlte.css" />
   <!--end::Required Plugin(AdminLTE)-->
   <!-- apexcharts -->
   <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous" />
   <?= $this->renderSection('css') ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
   <!--begin::App Wrapper-->
   <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body sticky-top">
         <!--begin::Container-->
         <div class="container-fluid">
            <!--begin::Start Navbar Links-->
            <ul class="navbar-nav">
               <li class="nav-item">
                  <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                     <i class="bi bi-list"></i>
                  </a>
               </li>
               <li class="nav-item">
                  <span id="real-time-clock" class="nav-link fw-semibold"></span>
               </li>
               <!--begin::Messages Dropdown Menu-->
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
                              <img
                                 src="<?= f_template() ?>dist/assets/img/user1-128x128.jpg"
                                 alt="User Avatar"
                                 class="img-size-50 rounded-circle me-3" />
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
                              <img
                                 src="<?= f_template() ?>dist/assets/img/user8-128x128.jpg"
                                 alt="User Avatar"
                                 class="img-size-50 rounded-circle me-3" />
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
                              <img
                                 src="<?= f_template() ?>dist/assets/img/user3-128x128.jpg"
                                 alt="User Avatar"
                                 class="img-size-50 rounded-circle me-3" />
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
               <!--end::Messages Dropdown Menu-->
               <!--begin::Notifications Dropdown Menu-->
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
               <!--end::Notifications Dropdown Menu-->
               <!--begin::Fullscreen Toggle-->
               <li class="nav-item">
                  <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                     <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                     <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                  </a>
               </li>
               <!--end::Fullscreen Toggle-->
            </ul>
            <!--end::Start Navbar Links-->
            <!--begin::End Navbar Links-->
            <ul class="navbar-nav ms-auto">

               <!--begin::User Menu Dropdown-->
               <li class="nav-item dropdown user-menu">
                  <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                     <span class="d-none d-md-inline text-uppercase"><i class="bi bi-person-circle"></i>
                        <span class="fw-bold ms-2 me-2"><?= session()->get('name') ?></span> <small>[<?= session()->get('division') ?>]</small>
                     </span>

                  </a>
                  <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                     <!--begin::Menu Body-->
                     <li class="user-body">
                        <!--begin::Row-->
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                           <i class="bi bi-envelope me-2"></i> <?= lang('App.profile') ?>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= base_url('auth/logout') ?>" class="dropdown-item">
                           <i class="bi bi-people-fill me-2"></i> <?= lang('App.signOut') ?>
                        </a>
                        <!--end::Row-->
                     </li>
                     <!--end::Menu Body-->
                  </ul>
               </li>
               <!--end::User Menu Dropdown-->
            </ul>
            <!--end::End Navbar Links-->
         </div>
         <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <?php
      $stages = [
         'PENDAFTARAN',
         'SYARAT PROPOSAL',
         'PROPOSAL',
         'BAB 1',
         'BAB 2',
         'BAB 3',
         'BAB 4',
         'BAB 5',
         'SYARAT SIDANG',
         'SIDANG',
         'REVISI',
         'SKL'
      ];

      // Ambil tahapan terakhir mahasiswa langsung dari database
      $studentId = session()->get('user_id'); // Ambil ID mahasiswa yang login
      $progressModel = new \App\Models\ProgressModel();

      $currentStage = $progressModel
         ->select('stages.name')
         ->join('stages', 'stages.id = progress.stage_id')
         ->join('thesis', 'thesis.id = progress.thesis_id')
         ->where('thesis.student_id', $studentId)
         ->orderBy('progress.created_at', 'DESC') // Ambil stage terbaru
         ->limit(1)
         ->get()
         ->getRowArray()['name'] ?? 'PENDAFTARAN';

      // Tentukan apakah tahapan sudah terbuka
      $stageIndex = array_search($currentStage, $stages);
      ?>

      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
         <div class="sidebar-brand">
            <a href="<?= base_url('student/dashboard') ?>" class="brand-link">
               <img src="<?= f_images('logo_jasinta_light.png') ?>" alt="Logo JASINTA" class="brand-image" />
               <span class="brand-text fw-light"><?= lang('App.name') ?></span>
            </a>
         </div>

         <div class="sidebar-wrapper">
            <nav class="mt-2">
               <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

                  <!-- Dashboard -->
                  <li class="nav-item">
                     <a href="<?= base_url('student/dashboard') ?>" class="nav-link active">
                        <i class="nav-icon bi bi-house-door"></i>
                        <p>Dashboard</p>
                     </a>
                  </li>

                  <!-- Dynamic Stage Menu -->
                  <?php foreach ($stages as $index => $stage): ?>
                     <li class="nav-item">
                        <a href="<?= $index <= $stageIndex ? base_url('student/' . strtolower(str_replace(' ', '_', $stage))) : '#' ?>"
                           class="nav-link <?= $index > $stageIndex ? 'disabled text-danger' : '' ?>">
                           <i class="nav-icon bi <?= $index > $stageIndex ? 'bi-x-circle' : 'bi-check-circle' ?>"></i>
                           <p><?= $stage ?></p>
                        </a>
                     </li>
                  <?php endforeach; ?>

               </ul>
            </nav>
         </div>
      </aside>

      <!--end::Sidebar-->

      <?= $this->renderSection('content') ?>

      <!--begin::Footer-->
      <footer class="app-footer">
         <!--begin::To the end-->
         <div class="float-end d-none d-sm-inline"><?= lang('App.aboutMe') ?></div>
         <!--end::To the end-->
         <!--begin::Copyright-->
         <strong>
            <?= lang('App.footerCopyright') ?>
         </strong>
         <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
   </div>
   <!--end::App Wrapper-->
   <!--begin::Script-->
   <!--begin::Third Party Plugin(OverlayScrollbars)-->
   <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"></script>
   <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
   <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"></script>
   <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
   <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"></script>
   <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
   <script src="<?= f_template() ?>dist/js/adminlte.js"></script>
   <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
   <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
         scrollbarTheme: 'os-theme-light',
         scrollbarAutoHide: 'leave',
         scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function() {
         const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
         if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
               scrollbars: {
                  theme: Default.scrollbarTheme,
                  autoHide: Default.scrollbarAutoHide,
                  clickScroll: Default.scrollbarClickScroll,
               },
            });
         }
      });
   </script>
   <!--end::OverlayScrollbars Configure-->
   <!-- OPTIONAL SCRIPTS -->
   <!-- apexcharts -->
   <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"></script>
   <script>
      // Fungsi untuk menampilkan waktu dalam format DD-MMM-YYYY HH:MM:SS
      function updateClock() {
         const now = new Date(); // Ambil waktu saat ini
         const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
         const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

         // Ambil nama hari berdasarkan indeks getDay()
         const dayName = days[now.getDay()];

         // Format tanggal
         const day = String(now.getDate()).padStart(2, '0'); // DD
         const month = months[now.getMonth()]; // MMM
         const year = now.getFullYear(); // YYYY

         // Format waktu
         const hours = String(now.getHours()).padStart(2, '0'); // HH
         const minutes = String(now.getMinutes()).padStart(2, '0'); // MM
         const seconds = String(now.getSeconds()).padStart(2, '0'); // SS

         // Gabungkan dalam format yang diinginkan
         const formattedTime = `${dayName}, ${day}-${month}-${year} | ${hours}:${minutes}:${seconds}`;

         // Tampilkan waktu di elemen dengan ID real-time-clock
         document.getElementById('real-time-clock').textContent = formattedTime;
      }

      // Perbarui waktu setiap detik
      setInterval(updateClock, 1000);

      // Jalankan fungsi sekali saat halaman dimuat
      updateClock();
   </script>

   <?= $this->renderSection('script') ?>
   <!--end::Script-->
</body>
<!--end::Body-->

</html>