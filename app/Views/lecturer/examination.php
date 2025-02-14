<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
   <h2>Assigned Examinations</h2>

   <table class="table table-bordered">
      <thead class="table-dark">
         <tr>
            <th>Name</th>
            <th>NIM</th>
            <th>Thesis Title</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($students as $student): ?>
            <tr>
               <td><?= $student['name'] ?></td>
               <td><?= $student['number'] ?></td>
               <td><?= $student['title'] ?></td>
               <td>
                  <a href="<?= base_url("lecturer/chat/{$student['id']}") ?>" class="btn btn-primary btn-sm">Chat</a>
                  <a href="<?= base_url("lecturer/progress/{$student['id']}") ?>" class="btn btn-info btn-sm">View Progress</a>
               </td>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
<?= $this->endSection() ?>