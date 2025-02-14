<?php

$maxStages = count($stages);

// **SORTING: Urutkan mahasiswa berdasarkan tahapan tertinggi**
usort($students, function ($a, $b) use ($stages) {
   $indexA = array_search($a['stage'], $stages);
   $indexB = array_search($b['stage'], $stages);
   return $indexB - $indexA; // Urutan descending (tertinggi duluan)
});
?>

<div class="card-body">
   <div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
      <table class="table table-sm table-bordered table-hover text-center">
         <thead class="table-dark">
            <tr>
               <th class="text-nowrap p-1" style="font-size: 10px; min-width: 120px;">Mahasiswa</th>
               <?php foreach ($stages as $stage): ?>
                  <?php
                  $currentDate = strtotime(date('Y-m-d'));
                  $deadlineDate = strtotime($deadlines["deadline_$stage"] ?? '');
                  $isLate = $currentDate && $deadlineDate && $currentDate > $deadlineDate;
                  ?>
                  <th class="text-nowrap p-1" style="font-size: 8px; min-width: 50px; background-color: <?= $isLate ? '#dc3545' : '#28a745' ?>; color: white;">
                     <?= $stage ?><br><small><?= $deadlines["deadline_$stage"] ?></small>
                  </th>
               <?php endforeach; ?>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($students as $student): ?>
               <tr>
                  <td class="fw-bold text-wrap p-2" style="font-size: 10px;"><?= $student['name'] ?> <br> (<?= $student['number'] ?>)</td>
                  <?php
                  $currentStageIndex = array_search($student['stage'], $stages);
                  ?>
                  <?php foreach ($stages as $index => $stage): ?>
                     <?php
                     $stageDate = strtotime($student['stage_date'] ?? date('Y-m-d')); // Default ke hari ini
                     $deadlineDate = strtotime($deadlines["deadline_$stage"] ?? '');
                     $isCompleted = $index <= $currentStageIndex; // Tahapan yang sudah dilalui
                     $isLate = $isCompleted && $deadlineDate && $stageDate > $deadlineDate;
                     $cellColor = $isCompleted ? ($isLate ? 'bg-danger text-white' : 'bg-success text-white') : '';
                     ?>
                     <td class="<?= $cellColor ?> p-2"></td>
                  <?php endforeach; ?>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>