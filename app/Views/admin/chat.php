<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="app-main">
   <div class="app-content">
      <div class="container-fluid">
         <div class="row">
            <?php if (!empty($contacts)) : ?>
               <!-- ✅ KOLOM KIRI: DAFTAR CHAT -->
               <div class="col-md-4 chat-list-container">
                  <div class="chat-list-header">
                     <h5>Chats</h5>
                  </div>
                  <div class="chat-list">
                     <?php foreach ($contacts as $contact): ?>
                        <div class="chat-item <?= ($contact['id'] == $activeChatId) ? 'active' : '' ?>"
                           onclick="window.location.href='<?= base_url('admin/chat/' . $contact['id']) ?>'">
                           <div class="chat-avatar">
                              <img src="<?= base_url('uploads/avatars/default.png') ?>" alt="Avatar">
                           </div>
                           <div class="chat-info">
                              <div class="chat-name d-flex justify-content-between">
                                 <span class="text-start"><?= esc($contact['name']) ?></span>
                                 <span class="text-end fw-light text-white-50 small">
                                    <?= (!empty($contact['last_message_time']) && $contact['last_message_time'] !== null)
                                       ? \CodeIgniter\I18n\Time::parse($contact['last_message_time'])->humanize()
                                       : 'No time available' ?>
                                 </span>
                              </div>
                              <div class="chat-last-message"><?= esc($contact['last_message'] ?? 'No messages') ?></div>
                           </div>
                           <div class="chat-meta">
                              <span class="chat-time"><?= \CodeIgniter\I18n\Time::parse($contact['last_message_time'])->humanize() ?></span>
                              <?php if ($contact['unread_chats'] > 0): ?>
                                 <span class="chat-unread"><?= $contact['unread_chats'] ?></span>
                              <?php endif; ?>
                           </div>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div> <!-- ✅ Tutup col-md-4 chat-list-container -->

               <!-- ✅ KOLOM KANAN: AREA CHAT -->
               <div class="col-md-8 chat-box-container">
                  <div class="chat-header">
                     <div class="chat-avatar">
                        <img src="<?= base_url('uploads/avatars/' . ($receiver['avatar'] ?? 'default.png')) ?>" alt="Avatar">
                     </div>
                     <div class="chat-info">
                        <div class="chat-name"><?= esc($receiver['name']) ?></div>
                     </div>
                  </div>

                  <div class="chat-box" id="chat-box">
                     <?php foreach ($messages as $msg): ?>
                        <div class="chat-bubble <?= ($msg['sender_id'] == session()->get('user_id')) ? 'chat-right' : 'chat-left' ?>">
                           <p><?= esc($msg['message']) ?></p>
                           <?php if ($msg['file']): ?>
                              <a href="<?= base_url('uploads/chat/' . $msg['file']) ?>" target="_blank">
                                 <i class="bi bi-paperclip"> <?= lang('App.attachment') ?></i>
                              </a>
                           <?php endif; ?>

                           <span class="chat-time">
                              <?= \CodeIgniter\I18n\Time::parse($msg['created_at'])->humanize() ?>

                              <?php if ($msg['sender_id'] == session()->get('user_id')): ?>
                                 <?php if ($msg['read_at']): ?>
                                    <i class="bi bi-check2-all text-primary"></i>
                                 <?php else: ?>
                                    <i class="bi bi-check2"></i>
                                 <?php endif; ?>
                              <?php endif; ?>
                           </span>
                        </div>
                     <?php endforeach; ?>
                  </div>

                  <!-- ✅ FORM KIRIM PESAN -->
                  <form action="<?= base_url('admin/chat/' . $receiver['id'] . '/send') ?>" method="post" enctype="multipart/form-data" id="chatForm">
                     <?= csrf_field() ?>
                     <div class="chat-input">
                        <textarea name="message" class="form-control" placeholder="Type a message..." required></textarea>
                        <div class="custom-file">
                           <input type="file" class="custom-file-input" id="customFile" name="file" accept=".png, .jpg, .jpeg, .pdf, .docx, .xlsx">
                           <label class="custom-file-label" for="customFile"><?= lang('App.chooseFile') ?></label>
                        </div>
                        <button type="submit" class="btn btn-primary"><?= lang('App.send') ?></button>
                     </div>
                  </form>
               </div> <!-- ✅ Tutup col-md-8 chat-box-container -->

            <?php else : ?>
               <h1 class="text-center">Belum ada chat aktif</h1>
               <a class="text-center" href="<?= base_url() ?>">Kembali ke Beranda</a>
            <?php endif; ?>
         </div> <!-- ✅ Tutup div.row -->
      </div> <!-- ✅ Tutup div.container-fluid -->
   </div> <!-- ✅ Tutup div.app-content -->
</main> <!-- ✅ Tutup main -->

<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('custom/css/chats.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?php if (!empty($contacts)) : ?>
   <script>
      $(document).ready(function() {
         $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);

         setInterval(function() {
            $("#chat-box").load(location.href + " #chat-box > *");
            $(".chat-list").load(location.href + " .chat-list > *");
            $("#chat-button").load(location.href + " #chat-button > *");
         }, 3000);

         $('#chatForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
               url: '<?= base_url('admin/chat/' . $receiver['id'] . '/send') ?>',
               type: 'POST',
               data: formData,
               processData: false,
               contentType: false,
               success: function(response) {
                  $("#chat-box").load(location.href + " #chat-box > *");
                  $('textarea[name="message"]').val('');
                  $('input[name="file"]').val('');
                  $("#chat-box").scrollTop($("#chat-box")[0].scrollHeight);
               },
               error: function() {
                  alert('Gagal mengirim pesan.');
               }
            });
         });
      });
   </script>
<?php endif ?>
<?= $this->endSection() ?>