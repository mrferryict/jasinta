<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!--begin::Content-->
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
                     <h3 class="card-title">PENGUMUMAN</h3>
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
                                 <td><?= substr($announcement['message'], 0, 50) . '...' ?></td>
                                 <td><a href="<?= base_url("mahasiswa/chat/1") ?>" class="btn btn-link">Tanya/Jawab</a></td>
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
<!--end::Content-->
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>