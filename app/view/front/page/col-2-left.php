<!DOCTYPE html>
<html class="no-js" lang="id">
<?php $this->getThemeElement('page/html/head',$__forward); ?>
<?php if($this->config->environment == 'production'){ ?>
  <?php $this->getThemeElement('page/html/gtag', $__forward) ?>
<?php } ?>
<body class="dashboard">
  <?php $this->getThemeElement('page/html/header',$__forward); ?>
  <div class="container container-dashboard">
    <div class="row">
      <div id="menu-left-container" class="d-none d-sm-none d-md-none d-lg-block d-xl-block col-lg-4 col-xl-3">
        <div id="menu-left" class="">
          <?php $this->getThemeElement('page/html/menu_left',$__forward); ?>
        </div>
      </div>
      <div class="col-12 col-md-12 col-lg-8 col-xl-9">
        <div id="main-page">
          <?php $this->getThemeContent(); ?>
        </div>
      </div>
    </div>
  </div>
  

  <?php $this->getThemeElement('page/html/footer',$__forward); ?>
  <?php $this->getThemeElement('page/html/aside',$__forward); ?>

  <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
  <?php $this->getJsFooter(); ?>

  <!-- Load and execute javascript code used only in this page -->
  <script>
  console.log('bfore ready');
  var modal_pilihan = {};
  if(document.getElementById('modal_pilihan') !== null){
    var modal_pilihan = new bootstrap.Modal(document.getElementById('modal_pilihan'));
  }
  //check image allowed
  !function(n){n.fn.checkFileType=function(e){return e=n.extend({allowedExtensions:[],success:function(){},error:function(){}},e),this.each(function(){n(this).on("change",function(){var s=n(this).val().toLowerCase(),t=s.substring(s.lastIndexOf(".")+1);-1==n.inArray(t,e.allowedExtensions)?(e.error(),n(this).focus()):e.success()})})}}(jQuery);

  $(document).ready(function(e){
    $('.header-btn-pilihan').on('click',function(){
      modal_pilihan.show();
    });
    var canvasWrapper = $(".off-canvas-wrapper");
    $(".btn-menu").on('click', function() {
      canvasWrapper.addClass('active');
      $("body").addClass('fix');
      console.log('add class');
    });

    $(".close-action > .btn-close, .off-canvas-overlay").on('click', function() {
      canvasWrapper.removeClass('active');
      $("body").removeClass('fix');
      console.log('remove class');
    });

    $('.main-menu').slicknav({
      appendTo: '.res-mobile-menu',
      closeOnClick: true,
      removeClasses: true,
      closedSymbol: '<i class="icon-arrows-plus"></i>',
      openedSymbol: '<i class="icon-arrows-minus"></i>'
    });

    <?php $this->getJsReady(); ?>
    <?php $this->getJsContent(); ?>

    <?php
    if(isset($sess->user->id)){
      $this->getThemeElement('page/html/js_logged_in',$__forward);
    }
    ?>
  });
  </script>
</body>
</html>
