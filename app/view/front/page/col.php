<!DOCTYPE html>
<html class="no-js" lang="id">
<?php $this->getThemeElement('page/html/head',$__forward); ?>
<?php if($this->config->environment == 'production'){ ?>
  <?php $this->getThemeElement('page/html/gtag', $__forward) ?>
<?php } ?>
<body>
  <?php $this->getThemeElement('page/html/header',$__forward); ?>
  <?php $this->getThemeContent(); ?>
  <?php $this->getThemeElement('page/html/footer',$__forward); ?>

  <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
  <?php $this->getJsFooter(); ?>

  <!-- Load and execute javascript code used only in this page -->
  <script>
  console.log('bfore ready');
  var modal_pilihan = {};
  if(document.getElementById('modal_pilihan') !== null){
    var modal_pilihan = new bootstrap.Modal(document.getElementById('modal_pilihan'));
  }
  <?php $this->getJsContent(); ?>
  $(document).ready(function(e){
    $('.header-btn-pilihan').on('click',function(){
      modal_pilihan.show();
    });

    <?php $this->getJsReady(); ?>
    console.log('after ready');
  });
  </script>
</body>
</html>
