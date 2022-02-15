<!DOCTYPE html>
<html class="no-js" lang="id">
<?php $this->getThemeElement('page/html/head', $__forward); ?>
<?php if ($this->config->environment == 'production') { ?>
  <?php $this->getThemeElement('page/html/gtag', $__forward) ?>
<?php } ?>

<body>
  <?php $this->getThemeElement('page/html/header', $__forward); ?>
  <?php $this->getThemeContent(); ?>
  <?php $this->getThemeElement('page/html/footer', $__forward); ?>
  <?php $this->getThemeElement('page/html/aside', $__forward); ?>

  <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
  <?php $this->getJsFooter(); ?>

  <!-- Load and execute javascript code used only in this page -->
  <script>
    console.log('bfore ready');
    var modal_pilihan = {};
    if (document.getElementById('modal_pilihan') !== null) {
      var modal_pilihan = new bootstrap.Modal(document.getElementById('modal_pilihan'));
    }
    $(document).ready(function(e) {
      $('.header-btn-pilihan').on('click', function() {
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

      $('.bsearch').on('click', function(e) {
        e.preventDefault();
        $('#modal-search').modal('show');
      })

      $('#fsearch').on('submit', function(e) {
        e.preventDefault();
        var keyword = $('#isearch').val();
        NProgress.start();
        $('#modal-search').modal('hide');
        window.location = '<?= base_url("joblist/?keyword=") ?>' + keyword;
      })

      <?php $this->getJsReady(); ?>
      <?php $this->getJsContent(); ?>
      console.log('after ready');
    });
  </script>
</body>

</html>