<?= $this->extend('admin/template') ?>

<?= $this->section('headerMenu') ?>
<?= $this->endSection() ?>

<?= $this->section('tableContent') ?>
<thead>
   <tr>
      <th>Waktu Akses</th>
      <th>Email Pengguna</th>
      <th>Jenis Kegiatan</th>
      <th>Deskripsi</th>
      <th>IP Address</th>
   </tr>
</thead>
<tbody>
   <?php foreach ($logs as $log): ?>
      <tr>
         <td><?= date('d-m-Y H:i:s', strtotime($log['created_at'])) ?></td>
         <td><?= esc($log['email']) ?></td>
         <td><?= esc($log['action']) ?></td>
         <td><?= esc($log['description']) ?></td>
         <td><?= esc($log['ip_address']) ?></td>
      </tr>
   <?php endforeach; ?>
</tbody>
</table>
<?= $this->endSection() ?>

<?= $this->section('extraCSS') ?>
<?= $this->endSection() ?>

<?= $this->section('extraScript') ?>
<?= $this->endSection() ?>