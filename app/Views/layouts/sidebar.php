<?php

$s = new \App\Models\StageModel();
$stageRoutes = $s->getAllStages();
?>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
   <!--begin::Sidebar Brand-->
   <div class="sidebar-brand">
      <?php $division = session()->get('division'); ?>
      <a href="<?= base_url(match ($division) {
                  'STUDENT' => 'student',
                  'LECTURER' => 'lecturer',
                  default => 'admin'
               }) ?>" class="brand-link">
         <img src="<?= f_images('logo_jasinta_light.png') ?>" alt="Logo JASINTA" class="brand-image" />
         <span class="brand-text fw-light"><?= langUppercase('App.name') ?></span>
      </a>
   </div>
   <!--end::Sidebar Brand-->
   <!--begin::Sidebar Wrapper-->
   <div class="sidebar-wrapper">
      <nav class="mt-2">
         <!--begin::Sidebar Menu-->
         <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">


            <!-- **Menu untuk ADMIN** -->
            <?php if ($division == 'ADMIN'): ?>
               <li class="nav-item">
                  <a href="<?= base_url('admin/activity_logs') ?>" class="nav-link <?= ($activeMenu == 'activityLogs') ? 'active' : '' ?>">
                     <i class="nav-icon bi bi-clock-history"></i>
                     <p><?= langUppercase('App.activityLogs') ?></p>
                  </a>
               </li>
               <li class="nav-item <?= (in_array($activeMenu, ['users', 'majors', 'settings'])) ? 'menu-open' : '' ?>">
                  <a href="#" class="nav-link">
                     <i class="nav-icon bi bi-speedometer"></i>
                     <p>
                        <?= langUppercase('App.masterData') ?>
                        <i class="nav-arrow bi bi-chevron-right"></i>
                     </p>
                  </a>
                  <ul class="nav nav-treeview">
                     <li class="nav-item small">
                        <a href="<?= base_url('admin/users') ?>" class="nav-link <?= ($activeMenu == 'users') ? 'active' : '' ?>">
                           <i class="nav-icon bi bi-person-lines-fill"></i>
                           <p><?= langUppercase('App.users') ?></p>
                        </a>
                     </li>
                     <li class="nav-item small">
                        <a href="<?= base_url('admin/majors') ?>" class="nav-link <?= ($activeMenu == 'majors') ? 'active' : '' ?>">
                           <i class="nav-icon bi bi-journal-bookmark"></i>
                           <p><?= langUppercase('App.majors') ?></p>
                        </a>
                     </li>
                     <li class="nav-item small">
                        <a href="<?= base_url('admin/settings') ?>" class="nav-link <?= ($activeMenu == 'settings') ? 'active' : '' ?>">
                           <i class="nav-icon bi bi-gear"></i>
                           <p><?= langUppercase('App.settings') ?></p>
                        </a>
                     </li>
                  </ul>
               </li>
               <li class="nav-item">
                  <a href="#" class="nav-link">
                     <i class="nav-icon bi bi-database"></i>
                     <p>
                        <?= langUppercase('App.monitoring') ?>
                        <i class="nav-arrow bi bi-chevron-right"></i>
                     </p>
                  </a>
                  <ul class="nav nav-treeview">
                     <?php foreach ($stageRoutes as $i): ?>
                        <?php if ($i['name'] == 'BAB 1') : ?>
                           <li class="nav-item small"><a href="<?= base_url('admin/supervision') ?>" class="nav-link"><i class="nav-icon bi bi-clipboard-check"></i>
                                 <p><?= langUppercase('App.supervision') ?></p>
                              </a></li>
                        <?php elseif ($i['name'] == 'BAB 2' || $i['name'] == 'BAB 3' || $i['name'] == "BAB 4" || $i['name'] == "BAB 5") : ?>
                        <?php else : ?>
                           <li class="nav-item small"><a href="<?= base_url('admin/' . $i['route']) ?>" class="nav-link"><i class="nav-icon bi bi-clipboard-check"></i>
                                 <p><?= $i['name'] ?></p>
                              </a></li>
                        <?php endif; ?>
                     <?php endforeach; ?>
                  </ul>
               </li>
            <?php endif; ?>


            <!-- **Menu untuk STUDENT** -->
            <?php if ($division == 'STUDENT'): $i = 1; ?>
               <?php $passed = true;
               $i = 1; ?>
               <?php foreach ($stageRoutes as $sr): ?>
                  <li class="nav-item">
                     <a href="<?= $passed ? base_url('student/' . strtolower(str_replace(' ', '_', $sr['name']))) : '#' ?>"
                        class="nav-link <?= $passed ? '' : 'disabled' ?>">
                        <i class="nav-icon bi <?= $passed ? 'bi-arrow-right-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?>"></i>
                        <p class="<?= $passed ? '' : 'text-secondary' ?>"><?= $i++ . '. ' . $sr['name'] ?></p>
                     </a>
                  </li>
                  <?php if ($sr['name'] == $student['stage_name']) $passed = false; ?>
               <?php endforeach; ?>
            <?php endif; ?>


            <!-- **Menu untuk LECTURER** -->
            <?php if ($division == 'LECTURER'): ?>
               <li class="nav-header"><?= langUppercase('App.appointments') ?></li>
               <li class="nav-item"><a href="<?= base_url('lecturer/supervision') ?>" class="nav-link"><i class="nav-icon bi bi-person-bounding-box"></i>
                     <p><?= langUppercase('App.supervisions') ?></p>
                  </a></li>
               <li class="nav-item"><a href="<?= base_url('lecturer/examinations') ?>" class="nav-link"><i class="nav-icon bi bi-mortarboard"></i>
                     <p><?= langUppercase('App.defenses') ?></p>
                  </a></li>

               <?php if ($isKapordi): ?>
                  <li class="nav-header"><?= langUppercase('App.monitoring') ?></li>
                  <?php foreach ($stages as $stage): ?>
                     <li class="nav-item"><a href="<?= base_url('lecturer/monitoring/' . strtolower(str_replace(' ', '_', $stage))) ?>" class="nav-link"><i class="nav-icon bi bi-clipboard-check"></i>
                           <p><?= $stage ?></p>
                        </a></li>
                  <?php endforeach; ?>
               <?php endif; ?>
            <?php endif; ?>

         </ul>
         <!--end::Sidebar Menu-->
      </nav>
   </div>
   <!--end::Sidebar Wrapper-->
</aside>