<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
   <h2>Chat with Student</h2>

   <div class="chat-box border p-3 mb-3" style="height: 400px; overflow-y: scroll;">
      <?php foreach ($messages as $message): ?>
         <div class="chat-message <?= $message['sender_id'] == session()->get('user_id') ? 'text-end' : '' ?>">
            <p class="fw-bold"><?= $message['sender_id'] == session()->get('user_id') ? 'You' : 'Student' ?></p>
            <div class="alert <?= $message['sender_id'] == session()->get('user_id') ? 'alert-primary' : 'alert-secondary' ?>">
               <?= $message['content'] ?>
               <br><small class="text-muted"><?= $message['created_at'] ?></small>
            </div>
         </div>
      <?php endforeach; ?>
   </div>

   <form action="<?= base_url('lecturer/sendMessage') ?>" method="post">
      <input type="hidden" name="receiver_id" value="<?= $studentId ?>">
      <textarea class="form-control mb-2" name="content" placeholder="Type a message..." required></textarea>
      <button type="submit" class="btn btn-primary">Send</button>
   </form>
</div>
<?= $this->endSection() ?>