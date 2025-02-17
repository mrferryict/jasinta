<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="app-main">
   <div class="app-content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-6">
               <h3 class="mb-0 fw-bold"><?= $pageTitle ?></h3>
            </div>
         </div>
      </div>
   </div>

   <div class="app-content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-8 mx-auto">
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title">Informasi Pendaftaran</h3>
                  </div>
                  <div class="card-body">
                     <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="<?= $student['name'] ?>" readonly>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="<?= $student['email'] ?>" readonly>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" value="<?= $student['number'] ?>" readonly>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Program Studi</label>
                        <input type="text" class="form-control" value="<?= $student['major_name'] ?>" readonly>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <input type="text" class="form-control" value="<?= $student['semester_name'] ?>" readonly>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Status Pendaftaran</label>
                        <input type="text" class="form-control <?= $student['student_status'] === 'ACTIVE' ? 'text-success' : 'text-danger' ?>"
                           value="<?= $student['student_status'] ?>" readonly>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <!-- Jika mahasiswa telah aktif, tampilkan pengumuman -->
         <?php if ($student['student_status'] == "ACTIVE") : ?>
            <div class="row mt-4">
               <div class="col-lg-8 mx-auto">
                  <div class="card">
                     <div class="card-header">
                        <h3 class="card-title">Pengumuman</h3>
                     </div>
                     <div class="card-body">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <th>Tanggal & Waktu</th>
                                 <th>Judul</th>
                                 <th>Pesan</th>
                                 <th>Aksi</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach ($announcements as $announcement) : ?>
                                 <tr>
                                    <td><?= date('d-m-Y H:i', strtotime($announcement['created_at'])) ?></td>
                                    <td><?= $announcement['title'] ?></td>
                                    <td><?= substr($announcement['content'], 0, 50) . '...' ?></td>
                                    <td>
                                       <a href="<?= base_url("student/chat/1") ?>" class="btn btn-link">Tanya/Jawab</a>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         <?php else : ?>
            <div class="text-center mt-4">
               <h5 class="text-danger"><?= lang('App.waitingAdminApproval') ?></h5>
            </div>
         <?php endif; ?>
      </div>
   </div>
</main>

<?= $this->endSection() ?>