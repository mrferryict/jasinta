<?= $this->extend('admin/template') ?>

<?= $this->section('headerMenu') ?>
<?= $this->endSection() ?>

<?= $this->section('tableContent') ?>
<thead>
   <tr>
      <th>Nama</th>
   </tr>
</thead>
<tbody>
   <?php if (!empty($majors) && is_array($majors)) : ?>
      <?php foreach ($majors as $i) : ?>
         <tr id="row-<?= esc($i['id']) ?>">
            <td><?= esc($i['name']) ?></td>
         </tr>
      <?php endforeach; ?>
   <?php else : ?>
      <tr>
         <td colspan="6"><?= lang('App.noData') ?></td>
      </tr>
   <?php endif; ?>
</tbody>
<?= $this->endSection() ?>

<?= $this->section('extraCSS') ?>
<?= $this->endSection() ?>

<?= $this->section('extraScript') ?>
<?= $this->endSection() ?>