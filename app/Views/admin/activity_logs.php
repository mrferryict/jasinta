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
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title">User Activity Logs</h3>
                  </div>
                  <div class="card-body">
                     <table id="logsTable" class="table table-bordered">
                        <thead>
                           <tr>
                              <th>Waktu Akses</th>
                              <th>Email Pengguna</th>
                              <th>Jenis Kegiatan</th>
                              <th>Deskripsi</th>
                              <th>IP Address</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($logs as $log): ?>
                              <tr>
                                 <td><?= date('d-m-Y H:i:s', strtotime($log['created_at'])) ?></td>
                                 <td><?= esc($log['email']) ?></td>
                                 <td><?= esc($log['action']) ?></td>
                                 <td><?= esc($log['description']) ?></td>
                                 <td><?= esc($log['ip']) ?></td>
                              </tr>
                           <?php endforeach; ?>
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
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
   $(document).ready(function() {
      $('#logsTable').DataTable();
   });
</script>
<?= $this->endSection() ?>