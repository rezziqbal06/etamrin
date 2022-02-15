<!DOCTYPE html>
<html class="no-js" lang="id">
<?php $this->getThemeElement('page/html/head',$__forward); ?>
<body class="preloader-active">

  <main class="login">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 m-auto">
          <div class="main-logo mt-5">
            <a href="<?=base_url()?>" class="logo">
              <img src="<?=base_url($this->config->semevar->app_logo)?>" alt="<?=$this->config->semevar->site_name?>" class=" animate__animated animate__slideInDown  animate__slower" />
            </a>
          </div>
          <div class="bungkus text-center mt-5">
            <div class="page-not-found-txt ">
              <h2 class="animate__animated animate__fadeInRight  animate__delay-1s">Tidak Ditemukan</h2>
              <p class="animate__animated animate__fadeInLeft  animate__delay-2s">
                Halaman yang anda maksud tidak dapat kami temukan.
                Apabila anda mengklik link dari email, mungkin link tersebut sudah kadaluarsa.
                Atau apabila anda mengklik dari salah satu menu kami, mungkin halamannya belum selesai kami buat.
              </p>
              <div class="page-not-found-buttons animate__animated animate__fadeInRight  animate__delay-3s">
                <a href="<?=base_url()?>" class="btn btn-primary"><i class="fa fa-chevron-left"></i> Kembali ke Halaman Utama</a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
</main>

<!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
<?php $this->getJsFooter(); ?>

<!-- Load and execute javascript code used only in this page -->
<script>
$(document).ready(function(e){
  <?php $this->getJsReady(); ?>
});
<?php $this->getJsContent(); ?>
</script>
</body>
</html>
