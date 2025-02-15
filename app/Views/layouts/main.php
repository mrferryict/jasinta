<?= $this->include('layouts/header') ?>
<?= $this->include('layouts/sidebar') ?>

<div class="content-wrapper">
   <?= $this->renderSection('content') ?>
</div>

<?= $this->include('layouts/footer') ?>