<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php $validation = session()->get('validation') ?>

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
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body">
                     <?php if (isset($validation)): ?>
                        <div class="alert alert-danger">
                           <?= $validation->listErrors() ?>
                        </div>
                     <?php endif; ?>

                     <form action="<?= base_url('/admin/save_user') ?>" method="post">
                        <div class="mb-3">
                           <label for="division" class="form-label">Divisi</label>
                           <select class="form-select" id="division" name="division">
                              <option value="STUDENT" <?= old('division') == 'STUDENT' ? 'selected' : '' ?>>Mahasiswa</option>
                              <option value="LECTURER" <?= old('division') == 'LECTURER' ? 'selected' : '' ?>>Dosen</option>
                              <option value="ADMIN" <?= old('division') == 'ADMIN' ? 'selected' : '' ?>>Admin</option>
                           </select>
                        </div>
                        <div class="mb-3">
                           <label for="name" class="form-label">Nama Lengkap</label>
                           <input type="text" class="form-control" id="name" name="name" autofocus oninput="this.value = this.value.toUpperCase()" value="<?= old('name') ?>">
                        </div>
                        <div class="mb-3">
                           <label for="email" class="form-label">Email</label>
                           <input type="email" class="form-control" id="email" name="email" oninput="this.value = this.value.toLowerCase()" value="<?= old('email') ?>">
                        </div>
                        <div class="mb-3">
                           <label for="password" class="form-label">Password</label>
                           <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="mb-3">
                           <label for="number" class="form-label" id="number_type">NIM/NIDN/NIP</label>
                           <input type="text" class="form-control" id="number" name="number" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= old('number') ?>">
                        </div>
                        <div class="mb-3" id="major_group">
                           <label for="major_id" class="form-label">Jurusan</label>
                           <select class="form-select" id="major_id" name="major_id">
                              <?php foreach ($majors as $major): ?>
                                 <option value="<?= $major['id'] ?>" <?= old('major_id') == $major['id'] ? 'selected' : '' ?>><?= esc($major['name']) ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                        <div class="mb-3" id="semester_group">

                           <label for="semester_id" class="form-label">Semester</label>
                           <select class="form-select" id="semester_id" name="semester_id" disabled>
                              <?php $i = 0;
                              foreach ($semesters as $semester): $i++; ?>
                                 <option value="<?= $semester['id'] ?>" <?= ($i == 1) ? 'selected' : '' ?>><?= esc($semester['name']) ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</main>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
   $(document).ready(function() {
      // Fungsi untuk menampilkan/menyembunyikan field Jurusan dan Semester
      function toggleMajorSemester() {
         var division = $('#division').val();
         if (division === 'STUDENT') {
            $('#major_group').show();
            $('#semester_group').show();
            $('#number_type').text('NIM');
         } else {
            $('#major_group').hide();
            $('#semester_group').hide();
            $('#number_type').text('NIDN/NIP');
         }
      }

      // Panggil fungsi saat halaman dimuat
      toggleMajorSemester();

      // Panggil fungsi saat pilihan Divisi berubah
      $('#division').change(function() {
         toggleMajorSemester();
      });
   });
</script>
<?= $this->endSection() ?>