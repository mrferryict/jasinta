<!-- begin::Info Boxes -->
<div class="row">
   <!-- Box #1 -->
   <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box">
         <span class="info-box-icon text-bg-primary shadow-sm">
            <i class="bi bi-people-fill"></i>
         </span>
         <div class="info-box-content">
            <span class="info-box-text"><?= lang('App.totalActiveStudents') ?></span>
            <span class="info-box-number"><?= $infoBoxes['totalActiveStudents'] ?></span>
         </div>
      </div>
   </div>
   <!-- Box #2 -->
   <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box">
         <span class="info-box-icon text-bg-success shadow-sm">
            <i class="bi bi-hand-thumbs-up-fill"></i>
         </span>
         <div class="info-box-content">
            <span class="info-box-text"><?= lang('App.studentsOnTrack') ?></span>
            <span class="info-box-number"><?= $infoBoxes['studentsOnTrack'] ?></span>
         </div>
      </div>
   </div>
   <!-- Box #3 -->
   <div class="col-12 col-sm-12 col-md-4">
      <div class="info-box">
         <span class="info-box-icon text-bg-warning shadow-sm">
            <i class="bi bi-hourglass-split"></i>
         </span>
         <div class="info-box-content">
            <span class="info-box-text"><?= lang('App.remainingDaysLeft') ?></span>
            <span class="info-box-number"><?= $infoBoxes['remainingDaysLeft'] . ' ' . lang('App.days') ?></span>
         </div>
      </div>
   </div>
</div>
<!-- end::Info Boxes -->