<?php
$roles = session()->get('roles');
$division = session()->get('division');
$userId = session()->get('user_id');

$stages = ['PENDAFTARAN', 'SYARAT PROPOSAL', 'PROPOSAL', 'BAB 1', 'BAB 2', 'BAB 3', 'BAB 4', 'BAB 5', 'SYARAT SIDANG', 'SIDANG', 'REVISI', 'SKLS'];

$progressModel = new \App\Models\ProgressModel();
$currentStage = $progressModel
   ->select('stages.name')
   ->join('stages', 'stages.id = progress.stage_id')
   ->join('thesis', 'thesis.id = progress.thesis_id')
   ->where('thesis.student_id', $userId)
   ->orderBy('progress.created_at', 'DESC')
   ->limit(1)
   ->get()
   ->getRowArray()['name'] ?? 'PENDAFTARAN';

$stageIndex = array_search($currentStage, $stages);
$majorModel = new \App\Models\MajorModel();
$isKapordi = $majorModel->where('coordinator_id', $userId)->countAllResults() > 0;
?>

<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
   <!--begin::Sidebar Brand-->
   <div class="sidebar-brand">
      <a href="<?= base_url($division == 'STUDENT' ? 'student' : ($division == 'LECTURER' ? 'lecturer' : 'admin')) ?>" class="brand-link">
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
                  <a href="<?= base_url('admin/activity_logs') ?>" class="nav-link">
                     <i class="nav-icon bi bi-clock-history"></i>
                     <p><?= langUppercase('App.activityLogs') ?></p>
                  </a>
               </li>
               <li class="nav-item">
                  <a href="#" class="nav-link">
                     <i class="nav-icon bi bi-speedometer"></i>
                     <p>
                        <?= langUppercase('App.masterData') ?>
                        <i class="nav-arrow bi bi-chevron-right"></i>
                     </p>
                  </a>
                  <ul class="nav nav-treeview">
                     <li class="nav-item"><a href="<?= base_url('admin/people') ?>" class="nav-link"><i class="nav-icon bi bi-person-lines-fill"></i>
                           <p><?= langUppercase('App.people') ?></p>
                        </a></li>
                     <li class="nav-item"><a href="<?= base_url('admin/majors') ?>" class="nav-link"><i class="nav-icon bi bi-journal-bookmark"></i>
                           <p><?= langUppercase('App.majors') ?></p>
                        </a></li>
                     <li class="nav-item"><a href="<?= base_url('admin/settings') ?>" class="nav-link"><i class="nav-icon bi bi-gear"></i>
                           <p><?= langUppercase('App.settings') ?></p>
                        </a></li>
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
                     <?php $stages = ['PENDAFTARAN', 'SYARAT PROPOSAL', 'PROPOSAL', 'BIMBINGAN', 'SYARAT SIDANG', 'SIDANG', 'REVISI', 'SKLS'] ?>
                     <?php foreach ($stages as $stage): ?>
                        <li class="nav-item"><a href="<?= base_url('admin/' . strtolower(str_replace(' ', '_', $stage))) ?>" class="nav-link"><i class="nav-icon bi bi-clipboard-check"></i>
                              <p><?= $stage ?></p>
                           </a></li>
                     <?php endforeach; ?>
                  </ul>
               </li>
            <?php endif; ?>


            <!-- **Menu untuk STUDENT** -->

            <?php if ($division == 'STUDENT'): $i = 1; ?>
               <?php foreach ($stages as $index => $stage): ?>
                  <li class="nav-item">
                     <a href="<?= $index <= $stageIndex ? base_url('student/' . strtolower(str_replace(' ', '_', $stage))) : '#' ?>"
                        class="nav-link <?= $index > $stageIndex ? 'disabled text-danger' : '' ?>">
                        <i class="nav-icon bi <?= $index > $stageIndex ? 'bi-x-circle' : 'bi-check-circle' ?>"></i>
                        <p><?= $i++ . '. ' . $stage ?></p>
                     </a>
                  </li>
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