<?= $this->extend('admin/main') ?>
<?= $this->section('content') ?>
<!--begin::App Main-->
<main class="app-main">
   <!--begin::App Content Header-->
   <div class="app-content-header">
      <!--begin::Container-->
      <div class="container-fluid">
         <!--begin::Row-->
         <div class="row">
            <div class="col-sm-6">
               <h3 class="mb-0">Dashboard v2</h3>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard v2</li>
               </ol>
            </div>
         </div>
         <!--end::Row-->
      </div>
      <!--end::Container-->
   </div>
   <div class="app-content">
      <!--begin::Container-->
      <div class="container-fluid">
         <!-- Info boxes -->
         <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
               <div class="info-box">
                  <span class="info-box-icon text-bg-primary shadow-sm">
                     <i class="bi bi-gear-fill"></i>
                  </span>
                  <div class="info-box-content">
                     <span class="info-box-text">CPU Traffic</span>
                     <span class="info-box-number">
                        10
                        <small>%</small>
                     </span>
                  </div>
                  <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
               <div class="info-box">
                  <span class="info-box-icon text-bg-danger shadow-sm">
                     <i class="bi bi-hand-thumbs-up-fill"></i>
                  </span>
                  <div class="info-box-content">
                     <span class="info-box-text">Likes</span>
                     <span class="info-box-number">41,410</span>
                  </div>
                  <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <!-- fix for small devices only -->
            <!-- <div class="clearfix hidden-md-up"></div> -->
            <div class="col-12 col-sm-6 col-md-3">
               <div class="info-box">
                  <span class="info-box-icon text-bg-success shadow-sm">
                     <i class="bi bi-cart-fill"></i>
                  </span>
                  <div class="info-box-content">
                     <span class="info-box-text">Sales</span>
                     <span class="info-box-number">760</span>
                  </div>
                  <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
               <div class="info-box">
                  <span class="info-box-icon text-bg-warning shadow-sm">
                     <i class="bi bi-people-fill"></i>
                  </span>
                  <div class="info-box-content">
                     <span class="info-box-text">New Members</span>
                     <span class="info-box-number">2,000</span>
                  </div>
                  <!-- /.info-box-content -->
               </div>
               <!-- /.info-box -->
            </div>
            <!-- /.col -->
         </div>
         <!-- /.row -->
         <!--begin::Row-->
         <div class="row">

            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title">DataTable with Search and Sort</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <table id="example1" class="table table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>ID</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Role</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td>1</td>
                              <td>John Doe</td>
                              <td>john@example.com</td>
                              <td>Admin</td>
                           </tr>
                           <tr>
                              <td>2</td>
                              <td>Jane Smith</td>
                              <td>jane@example.com</td>
                              <td>User</td>
                           </tr>
                           <tr>
                              <td>3</td>
                              <td>Michael Johnson</td>
                              <td>michael@example.com</td>
                              <td>Editor</td>
                           </tr>
                           <tr>
                              <td>4</td>
                              <td>Sarah Brown</td>
                              <td>sarah@example.com</td>
                              <td>User</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <!-- /.card-body -->
               </div>
               <!-- /.card -->
            </div>
            <!-- /.col -->

         </div>
         <!--end::Row-->
         <!--begin::Row-->

         <!--end::Row-->
      </div>
      <!--end::Container-->
   </div>
   <!--end::App Content-->
</main>
<!--end::App Main-->
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
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