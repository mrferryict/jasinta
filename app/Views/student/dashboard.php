<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!--begin::Content-->
<?php if ($student['student_status'] == "ACTIVE") : ?>
   <main class="app-main">
      <div class="app-content-header">
         <!-- begin::Page Title -->
         <div class="container-fluid">
            <div class="row">
               <div class="col-sm-6">
                  <h3 class="mb-0 fw-bold"><?= $pageTitle ?></h3>
               </div>
            </div>
         </div>
         <!-- end::Page Title -->
      </div>
      <div class="app-content">
         <!-- begin::Info Box -->
         <div class="container-fluid">
            <?= view('student/info_boxes', ['infoBoxes' => $infoBoxes]) ?>
         </div>
         <!-- end::Info Box -->
         <!-- begin::Main Table -->
         <div class="container-fluid">
            <div class="row">
               <div class="col-12">
                  <div class="card">
                     <div class="card-header">
                        <h3 class="card-title"><?= lang('App.announcements') ?></h3>
                     </div>
                     <div class="card-body">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <th>Tanggal dan Waktu</th>
                                 <th>Judul</th>
                                 <th>Pesan</th>
                                 <th>Aksi</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach ($announcements as $announcement): ?>
                                 <tr>
                                    <td><?= date('d-m-Y H:i', strtotime($announcement['created_at'])) ?></td>
                                    <td><?= $announcement['title'] ?></td>
                                    <td><?= $announcement['content'] ?></td>
                                    <td><a href="<?= base_url('student/chats') ?>" class="btn btn-link"><?= lang('App.askAdmin') ?></a></td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <!-- end::Main Table -->
            </div>
         </div>
   </main>
<?php else : ?>
   <main class="app-main">
      <div class="app-content-header">
         <!-- begin::Page Title -->
         <div class="container-fluid">
            <div class="row">
               <div class="col-sm-6">
                  <h3 class="mb-0 fw-bold"><?= lang('App.waitingAdminApproval') ?></h3>
               </div>
            </div>
         </div>
         <!-- end::Page Title -->
      </div>
   </main>
<?php endif; ?>
<!--end::Content-->

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>