<?= $this->extend('admin/template') ?>

<?= $this->section('headerMenu') ?>
<a href="<?php echo base_url('admin/create_user'); ?>" class="btn btn-primary">
   <i class="bi bi-plus"></i>
   <?= lang('App.newUser') ?>
</a>
<?= $this->endSection() ?>

<?= $this->section('tableContent') ?>
<thead>
   <tr>
      <th>Nama</th>
      <th>Email</th>
      <th>NIM/NIDN</th>
      <th>Divisi</th>
      <th>Status</th>
      <th>Aksi</th>
   </tr>
</thead>
<tbody>
   <?php if (!empty($users) && is_array($users)) : ?>
      <?php foreach ($users as $user) : ?>
         <tr id="row-<?= esc($user['id']) ?>">
            <td><?= strtoupper(esc($user['name'])) ?></td>
            <td><?= esc($user['email']) ?></td>
            <td><?= esc($user['number']) ?></td>
            <td><?= esc($user['division']) ?></td>
            <td>
               <span class="badge <?= $user['status'] ? 'bg-success' : 'bg-danger' ?>">
                  <?= $user['status'] ? 'Aktif' : 'Nonaktif' ?>
               </span>
            </td>
            <td>
               <!-- Toggle Status -->
               <button class="btn btn-sm <?= $user['status'] ? 'btn-danger' : 'btn-success' ?> toggle-status"
                  data-id="<?= esc($user['id']) ?>" data-status="<?= $user['status'] ?>">
                  <?= $user['status'] ? 'Nonaktifkan' : 'Aktifkan' ?>
               </button>

               <!-- Delete -->
               <button class="btn btn-sm btn-outline-danger delete-user"
                  data-id="<?= esc($user['id']) ?>">
                  <i class="bi bi-trash"></i> Hapus
               </button>
            </td>
         </tr>
      <?php endforeach; ?>
   <?php else : ?>
      <tr>
         <td colspan="6"><?= lang('App.noUsers') ?></td>
      </tr>
   <?php endif; ?>
</tbody>
<?= $this->endSection() ?>

<?= $this->section('extraCSS') ?>
<?= $this->endSection() ?>

<?= $this->section('extraScript') ?>
<script>
   // Toggle Status User
   $('.toggle-status').on('click', function() {
      let userId = $(this).data('id');
      let newStatus = $(this).data('status') ? 0 : 1;
      let button = $(this);

      $.post('<?= base_url('admin/user/toggle-status') ?>', {
         id: userId,
         status: newStatus
      }, function(response) {
         if (response.success) {
            let badge = $('#row-' + userId).find('td:nth-child(5) span');
            let newText = newStatus ? 'Aktif' : 'Nonaktif';
            let newClass = newStatus ? 'bg-success' : 'bg-danger';

            badge.text(newText).removeClass('bg-success bg-danger').addClass(newClass);
            button.text(newStatus ? 'Nonaktifkan' : 'Aktifkan')
               .toggleClass('btn-danger btn-success')
               .data('status', newStatus);
         } else {
            alert('Gagal mengubah status pengguna.');
         }
      }, 'json');
   });

   // Delete User
   $('.delete-user').on('click', function() {
      let userId = $(this).data('id');
      if (!confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) return;

      $.post('<?= base_url('admin/users/delete') ?>', {
         id: userId
      }, function(response) {
         if (response.success) {
            $('#row-' + userId).fadeOut(500, function() {
               $(this).remove();
            });
         } else {
            alert('Gagal menghapus pengguna.');
         }
      }, 'json');
   });
</script>
<?= $this->endSection() ?>