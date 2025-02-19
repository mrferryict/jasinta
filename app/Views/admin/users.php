<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="app-main">
   <div class="app-content-header">
      <div class="container-fluid">
         <div class="col-sm-12 d-flex justify-content-between align-items-center">
            <h3 class="mb-0 fw-bold"><?= esc($pageTitle) ?></h3>
            <div>
               <a href="<?php echo base_url('admin/create_user'); ?>" class="btn btn-primary">
                  <i class="bi bi-plus"></i>
                  <?= lang('App.newUser') ?>
               </a>
            </div>
         </div>
      </div>
   </div>

   <div class="app-content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <table id="mainTable" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>Nama</th>
                              <th>Email</th>
                              <th>NIM/NIDN</th>
                              <th>Divisi</th>
                              <th>Status</th>
                              <th>Aksi</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php if (!empty($users) && is_array($users)) : ?>
                              <?php foreach ($users as $user) : ?>
                                 <tr id="row-<?= esc($user['id']) ?>">
                                    <td><?= esc($user['name']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td><?= esc($user['number']) ?></td>
                                    <td><?= esc($user['division']) ?></td>
                                    <td>
                                       <span class="badge <?= $user['status'] ? 'bg-success' : 'bg-danger' ?>">
                                          <?= $user['status'] ? 'Aktif' : 'Nonaktif' ?>
                                       </span>
                                    </td>
                                    <td>
                                       <!-- Toggle Status -->
                                       <button class="btn btn-sm <?= $user['status'] ? 'btn-danger' : 'btn-success' ?> toggle-status"
                                          data-id="<?= esc($user['id']) ?>" data-status="<?= $user['status'] ?>">
                                          <?= $user['status'] ? 'Nonaktifkan' : 'Aktifkan' ?>
                                       </button>

                                       <!-- Delete -->
                                       <button class="btn btn-sm btn-outline-danger delete-user"
                                          data-id="<?= esc($user['id']) ?>">
                                          <i class="bi bi-trash"></i> Hapus
                                       </button>
                                    </td>
                                 </tr>
                              <?php endforeach; ?>
                           <?php else : ?>
                              <tr>
                                 <td colspan="6"><?= lang('App.noUsers') ?></td>
                              </tr>
                           <?php endif; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</main>

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
   $(document).ready(function() {
      $('#mainTable').DataTable({
         dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
         buttons: [{
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Export Excel',
            className: 'btn btn-success'
         }],
         language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
         }
      });

      // Toggle Status User
      $('.toggle-status').on('click', function() {
         let userId = $(this).data('id');
         let newStatus = $(this).data('status') ? 0 : 1;
         let button = $(this);

         $.post('<?= base_url('admin/users/toggle-status') ?>', {
            id: userId,
            status: newStatus
         }, function(response) {
            if (response.success) {
               let badge = $('#row-' + userId).find('td:nth-child(5) span');
               let newText = newStatus ? 'Aktif' : 'Nonaktif';
               let newClass = newStatus ? 'bg-success' : 'bg-danger';

               badge.text(newText).removeClass('bg-success bg-danger').addClass(newClass);
               button.text(newStatus ? 'Nonaktifkan' : 'Aktifkan')
                  .toggleClass('btn-danger btn-success')
                  .data('status', newStatus);
            } else {
               alert('Gagal mengubah status pengguna.');
            }
         }, 'json');
      });

      // Delete User
      $('.delete-user').on('click', function() {
         let userId = $(this).data('id');
         if (!confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) return;

         $.post('<?= base_url('admin/users/delete') ?>', {
            id: userId
         }, function(response) {
            if (response.success) {
               $('#row-' + userId).fadeOut(500, function() {
                  $(this).remove();
               });
            } else {
               alert('Gagal menghapus pengguna.');
            }
         }, 'json');
      });
   });
</script>
<?= $this->endSection() ?>