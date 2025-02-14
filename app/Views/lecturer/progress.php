<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
   <h2>Student Progress</h2>

   <table class="table table-bordered">
      <thead class="table-dark">
         <tr>
            <th>Stage</th>
            <th>Status</th>
            <th>Updated At</th>
         </tr>
      </thead>
      <tbody>
         <?php foreach ($studentProgress as $progress): ?>
            <tr>
               <td><?= $progress['stage_name'] ?></td>
               <td><?= $progress['status'] ?></td>
               <td><?= $progress['updated_at'] ?></td>
            </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
<?= $this->endSection() ?>