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
         <?= view('admin/info_boxes', ['infoBoxes' => $infoBoxes]) ?>
      </div>
      <!-- end::Info Box -->
      <!-- begin::Main Table -->
      <div class="container-fluid">
         <div class="row">
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title fw-bold"><?= $tableTitle ?></h3>
                  </div>
                  <?= view('admin/progress_table') ?>
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

<?= $this->section('script') ?>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
   $(document).ready(function() {
      $('#example1').DataTable({
         "paging": true, // Enable pagination
         "lengthChange": true, // Enable length change
         "searching": true, // Enable search
         "ordering": true, // Enable sorting
         "info": true, // Enable info
         "autoWidth": false, // Disable auto width
         "responsive": true, // Enable responsive
      });
   });
</script>
<?= $this->endSection() ?>