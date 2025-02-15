<footer class="app-footer">
   <div class="float-end d-none d-sm-inline"><?= langUppercase('App.aboutMe') ?></div>
   <strong><?= langUppercase('App.footerCopyright') ?></strong>
</footer>

<!-- Script -->


<!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
   src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
   integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
   crossorigin="anonymous"></script>
<script
   src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
   integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8="
   crossorigin="anonymous"></script>
<!--begin::Required Plugin(Bootstrap 5)-->
<!-- jQuery -->
<script
   src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
   integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
   crossorigin="anonymous"></script>
<!-- AdminLTE JS -->
<script src="<?= f_template() ?>dist/js/adminlte.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>

<script>
   function updateClock() {
      const now = new Date();
      const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
      const formattedTime = `${days[now.getDay()]}, ${now.getDate()}-${months[now.getMonth()]}-${now.getFullYear()} | ${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;
      document.getElementById('real-time-clock').textContent = formattedTime;
   }
   setInterval(updateClock, 1000);
   updateClock();
</script>


<?= $this->renderSection('script') ?>
</body>

</html>