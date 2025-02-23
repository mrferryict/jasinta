<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="app-main">
   <div class="app-content-header">
      <div class="container-fluid">
         <div class="col-sm-12 d-flex justify-content-between align-items-center">
            <h3 class="mb-0 fw-bold"><?= esc($pageTitle) ?></h3>
            <div>
               <?= $this->renderSection('headerMenu') ?> </div>
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
                        <?= $this->renderSection('tableContent') ?>
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
<?= $this->renderSection('extraCSS') ?>
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
                     description: 'User mengklik Export to Excel: Settings',
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

<?= $this->renderSection('extraScript') ?>
<?= $this->endSection() ?>