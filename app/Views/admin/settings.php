<?= $this->extend('admin/template') ?>

<?= $this->section('headerMenu') ?>
<?= $this->endSection() ?>

<?= $this->section('tableContent') ?>
<thead>
   <tr>
      <th>Key</th>
      <th>Value</th>
   </tr>
</thead>
<tbody>
   <?php if (!empty($settings) && is_array($settings)) : ?>
      <?php foreach ($settings as $k => $v) : ?>
         <tr>
            <td><?= esc($k) ?></td>
            <td><?= esc($v) ?></td>
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