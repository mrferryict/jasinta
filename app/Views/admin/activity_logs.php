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
                  <!-- <div class="card-header">
                     <h3 class="card-title">User Activity Logs</h3>
                  </div> -->
                  <div class="card-body">
                     <table id="mainTable" class="table table-bordered">
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
                                 <td><?= esc($log['ip_address']) ?></td>
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
      var table = $('#mainTable').DataTable({
         dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
         buttons: [{
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Export Excel',
            className: 'btn btn-success',
            action: function(e, dt, node, config) {
               var self = this; // Simpan referensi DataTable

               // ✅ Tambahkan indikator loading manual
               let exportButton = $(node);
               exportButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Exporting...');

               // 🔥 Kirim log ke server sebelum export Excel
               $.ajax({
                  url: '<?= base_url('admin/log/exportToExcel') ?>',
                  method: 'POST',
                  data: {
                     action: 'EXPORT_TO_EXCEL',
                     description: 'User mengklik Export to Excel: Logs',
                     user_id: <?= session()->get('user_id') ?>
                  },
                  success: function(response) {
                     // ✅ Jalankan export setelah logging berhasil
                     $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, node, config);
                  },
                  error: function(xhr, status, error) {
                     // ✅ Tetap jalankan export meskipun logging gagal
                     $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, node, config);
                  },
                  complete: function() {
                     // ✅ Kembalikan tombol ke keadaan semula setelah export selesai
                     exportButton.prop('disabled', false).html('<i class="fas fa-file-excel"></i> Export Excel');
                  }
               });
            }
         }],
         language: {
            url: "<?= base_url('json/id.json') ?>"
         }
      });
   });
</script>
<?= $this->endSection() ?>