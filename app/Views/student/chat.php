<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!--begin::Content-->
<main class="app-main">
   <div class="app-content-header">
      <!-- begin::Page Title -->
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-6">
               <h3 class="mb-0 fw-bold"><?= $pageTitle ?></h3>
            </div>
         </div>
      </div>
      <!-- end::Page Title -->
   </div>
   <div class="app-content">
      <!-- begin::Info Box -->
      <div class="container-fluid">
         <?= view('mahasiswa/info_boxes', ['infoBoxes' => $infoBoxes]) ?>
      </div>
      <!-- end::Info Box -->
      <!-- begin::Main Table -->
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header">TANYA ADMINISTRATOR</div>
               <div class="card-body">
                  <div class="chat-box">
                     <?php foreach ($messages as $message): ?>
                        <div class="<?= $message['sender_id'] == session()->get('user_id') ? 'chat-message student' : 'chat-message receiver' ?>">
                           <strong><?= $message['sender_id'] == session()->get('user_id') ? 'You' : $receiver['name'] ?>:</strong>
                           <p><?= $message['content'] ?></p>
                           <small><?= date('d-m-Y H:i', strtotime($message['created_at'])) ?></small>

                           <!-- Menampilkan status read_at -->
                           <?php if ($message['sender_id'] == session()->get('user_id')): ?>
                              <span class="text-muted">
                                 <?= $message['read_at'] ? '<i class="bi bi-check2-all text-primary"></i> Read' : '<i class="bi bi-check2"></i> Sent' ?>
                              </span>
                           <?php endif; ?>
                        </div>
                     <?php endforeach; ?>
                  </div>

                  <form action="<?= base_url('student/sendMessage') ?>" method="post">
                     <input type="hidden" name="receiver_id" value="<?= $receiver['id'] ?>">
                     <textarea name="content" class="form-control" placeholder="Type a message..."></textarea>
                     <button type="submit" class="btn btn-primary mt-2">Send</button>
                  </form>
               </div>
            </div>
         </div>
         <!-- end::Main Table -->
      </div>
</main>
<!--end::Content-->
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ?>