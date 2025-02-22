<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
   <h2 class="mb-4">Lecturer Dashboard</h2>

   <div class="row">
      <!-- Supervised Students -->
      <div class="col-md-6">
         <div class="card">
            <div class="card-header bg-primary text-white">
               Supervised Students
            </div>
            <div class="card-body">
               <ul class="list-group">
                  <?php foreach ($supervisedStudents as $student): ?>
                     <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $student['student_name'] ?> (<?= $student['nim'] ?>)
                        <?= lang('App.ass') ?> <?= lang('App.' . $student['role']) ?>
                        <a href="<?= base_url("lecturer/progress/{$student['student_id']}") ?>" class="btn btn-sm btn-info">View Progress</a>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </div>
         </div>
      </div>

      <!-- Assigned Examinations -->
      <div class="col-md-6">
         <div class="card">
            <div class="card-header bg-success text-white">
               Assigned Examinations
            </div>
            <div class="card-body">
               <ul class="list-group">
                  <?php foreach ($examinedStudents as $student): ?>
                     <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $student['student_name'] ?> (<?= $student['nim'] ?>)
                        <?= lang('App.ass') ?> <?= lang('App.' . $student['role']) ?>
                        <a href="<?= base_url("lecturer/examination") ?>" class="btn btn-sm btn-warning">View</a>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>