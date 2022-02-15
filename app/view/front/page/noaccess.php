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
              <h2 class="animate__animated animate__fadeInRight  animate__delay-1s">Belum Bisa Akses</h2>
              <p class="animate__animated animate__fadeInLeft  animate__delay-2s">
                Halaman yang anda maksud ada, namun belum bisa anda akses.
              </p>
              <div class="page-not-found-buttons animate__animated animate__fadeInRight  animate__delay-3s">
                <a href="<?=base_url('dashboard')?>" class="btn btn-primary"><i class="fa fa-chevron-left"></i> Kembali ke Dashboard</a>
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
