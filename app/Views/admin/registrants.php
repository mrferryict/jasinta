<?= $this->extend('admin/template') ?>

<?= $this->section('headerMenu') ?>
<?= $this->endSection() ?>

<?= $this->section('tableContent') ?>
<thead>
   <tr>
      <th><?= lang('App.name') ?></th>
      <th><?= lang('App.email') ?></th>
      <th><?= lang('App.nim') ?></th>
      <th><?= lang('App.status') ?></th>
      <th><?= lang('App.action') ?></th>
   </tr>
</thead>
<tbody>
   <?php if (!empty($registrants) && is_array($registrants)) : ?>
      <?php foreach ($registrants as $registrant) : ?>
         <tr id="row-<?= esc($registrant['id']) ?>">
            <td><?= esc($registrant['name']) ?></td>
            <td><?= esc($registrant['email']) ?></td>
            <td><?= esc($registrant['nim']) ?></td>
            <td>
               <span class="badge <?= ($registrant['academic_status'] == 'NEW') ? 'bg-primary' : 'bg-warning' ?>">
                  <?= ($registrant['academic_status'] ==  'NEW') ? 'BARU' : 'MENGULANG' ?>
               </span>
            </td>
            <td>
               <!-- Approve -->
               <button class="btn btn-sm btn-success approve-registrant"
                  data-id="<?= esc($registrant['id']) ?>">
                  <i class="bi bi-check-circle"></i> <?= lang('App.approve') ?>
               </button>
               <!-- Reject -->
               <button class="btn btn-sm btn-danger reject-registrant"
                  data-id="<?= esc($registrant['id']) ?>">
                  <i class="bi bi-x-circle"></i> <?= lang('App.reject') ?>
               </button>
            </td>
         </tr>
      <?php endforeach; ?>
   <?php else : ?>
      <tr>
         <td colspan="6"><?= lang('App.noRegistrant') ?></td>
      </tr>
   <?php endif; ?>
</tbody>
<?= $this->endSection() ?>

<?= $this->section('extraCSS') ?>
<?= $this->endSection() ?>

<?= $this->section('extraScript') ?>
<script>
   $(document).ready(function() {
      // Approve registrant
      $('.approve-registrant').on('click', function() {
         let registrantId = $(this).data('id');
         if (!confirm('Apakah Anda yakin ingin menyetujui pendaftar ini?')) return;

         $.post('<?= base_url('admin/registrant/approve') ?>', {
            id: registrantId
         }, function(response) {
            if (response.success) {
               $('#row-' + registrantId).fadeOut(500, function() {
                  $(this).remove();
               });
            } else {
               alert('Gagal memberikan persetujuan.');
            }
         }, 'json');
      });
      // Reject registrant
      $('.reject-registrant').on('click', function() {
         let registrantId = $(this).data('id');
         if (!confirm('Apakah Anda yakin ingin menolak pendaftar ini?')) return;

         $.post('<?= base_url('admin/registrant/reject') ?>', {
            id: registrantId
         }, function(response) {
            if (response.success) {
               $('#row-' + registrantId).fadeOut(500, function() {
                  $(this).remove();
               });
            } else {
               alert('Gagal memberikan penolakan.');
            }
         }, 'json');
      });
   });
</script>
<?= $this->endSection() ?>