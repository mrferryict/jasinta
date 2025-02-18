<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="app-main">
   <div class="container">
      <h3 class="mb-4"><?= $pageTitle ?></h3>

      <!-- Form Tambah Syarat -->
      <div class="card mb-4">
         <div class="card-header">Add New Requirement</div>
         <div class="card-body">
            <form action="<?= base_url('admin/requirements/add') ?>" method="post">
               <div class="mb-3">
                  <label class="form-label">Select Stage</label>
                  <select name="stage_id" class="form-control" required>
                     <?php foreach ($stages as $stage) : ?>
                        <option value="<?= $stage['id'] ?>"><?= esc($stage['name']) ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <div class="mb-3">
                  <label class="form-label">Requirement Description</label>
                  <input type="text" name="description" class="form-control" required>
               </div>
               <button type="submit" class="btn btn-primary">Add Requirement</button>
            </form>
         </div>
      </div>

      <!-- Tabel Syarat -->
      <div class="card">
         <div class="card-header">Existing Requirements</div>
         <div class="card-body">
            <table class="table table-striped">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Stage</th>
                     <th>Description</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($requirements as $index => $req) : ?>
                     <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($req['stage_id']) ?></td>
                        <td><?= esc($req['description']) ?></td>
                        <td>
                           <a href="<?= base_url('admin/requirements/delete/' . $req['id']) ?>"
                              class="btn btn-danger btn-sm"
                              onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</main>

<?= $this->endSection() ?>