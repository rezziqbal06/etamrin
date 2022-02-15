<!DOCTYPE html>
<html class="no-js" lang="id">
<?php $this->getThemeElement('page/html/head',$__forward); ?>
<?php if($this->config->environment == 'production'){ ?>
  <?php $this->getThemeElement('page/html/gtag', $__forward) ?>
<?php } ?>
<body class="tes">
  <style>
  footer.footer-area {
    position: fixed;
    height: 2.5em;
    bottom: 0;
    width: 100%;
  }
  </style>
  <header>
    <div class="container">
      <div class="row g-0 align-items-center">
        <div class="col-12">
          <br>
        </div>
      </div>
    </div>
  </header>
  <div class="container container-dashboard">
    <div class="row">
      <div class="col-12">
        <div id="main-page">
          <?php $this->getThemeContent(); ?>
        </div>
      </div>
    </div>
  </div>

  <?php //$this->getThemeElement('page/html/footer',$__forward); ?>
  <?php //$this->getThemeElement('page/html/aside',$__forward); ?>

  <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
  <?php $this->getJsFooter(); ?>
  <script>
  $(document).ready(function(e){
      <?php $this->getJsReady(); ?>
  });
  <?php $this->getJsContent(); ?>
  </script>

</body>
</html>
