<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<?php $this->getThemeElement("page/html/head", $__forward); ?>

<body>
    <!-- Error Container -->
    <div id="error-container">
        <div class="row">
            <div class="col-md-12">
                <h3><i class="fa fa-chevron-circle-left text-muted"></i> <a href="<?= base_url_admin(); ?>">Kembali</a></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <h1 class="animation-pulse"><i class="fa fa-exclamation-circle text-info"></i> 404</h1>
                <h2 class="h3">Afwan, halaman yang anda tuju tidak ditemukan.<br>Kembali ke <a href="<?= base_url_admin(); ?>">halaman utama</a></h2>
            </div>
        </div>
    </div>
    <!-- END Error Container -->
</body>

</html>