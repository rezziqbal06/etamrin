<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <title><?=$this->getTitle()?></title>
    <meta name="description" content="Point of Sales">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="<?=$this->getShortcutIcon()?>">
    <link rel="icon" href="<?=$this->getIcon()?>">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- The roboto font is included from Google Web Fonts -->
    <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic">-->

    <!-- Bootstrap is included in its original form, unaltered -->
    <link rel="stylesheet" href="<?=$this->cdn_url(); ?>skin/struk/css/bootstrap.min.css">
    <!-- Related styles of various icon packs and javascript plugins -->
    <link rel="stylesheet" href="<?=$this->cdn_url(); ?>skin/struk/css/plugins.css">
    <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
    <link rel="stylesheet" href="<?=$this->cdn_url(); ?>skin/struk/css/main.css">
    <!-- Load a specific file here from css/themes/ folder to alter the default theme of all the template -->
    <!-- The themes stylesheet of this template (for using specific theme color in individual elements (must included last) -->
    <link rel="stylesheet" href="<?=$this->cdn_url(); ?>skin/struk/css/themes.css">
    <!-- END Stylesheets -->
		<style>
			 @media print{
				#non_printable { display: none; }
				#printable { display: block; }
			 }
		</style>
    <!-- Modernizr (Browser feature detection library) & Respond.js (Enable responsive CSS code on browsers that don't support it) -->
    <script src="<?=$this->cdn_url(); ?>skin/struk/js/vendor/modernizr.min.js"></script>
  </head>

  <!-- Body -->
  <!-- In the PHP version you can set the following options from the config file -->
  <!-- Add the class .hide-side-content to <body> to hide side content by default -->
  <body>
    <!-- Page Container -->
    <!-- In the PHP version you can set the following options from the config file -->
    <!-- Add the class .full-width for a full width page -->
    <div id="page-container" class="full-width">
      <div id="non_printable" style="margin-left:23%;">
        <a href="<?=base_url_admin('penjualan/cabang/')?>" id="btn-back" class="btn btn-default"><i class="icon-arrow-left"></i> Manajemen Penjualan</a>
        <a href="<?=base_url_admin('penjualan/cabang/print_struk_tindakan/'.$order->id)?>" id="btn-back" class="btn btn-success"><i class="icon-file"></i> Struk Tindakan</a>
        <!--<a href="<?=base_url_admin('penjualan/cabang/print_struk_inventory/'.$order->id)?>" id="btn-back" class="btn btn-success"><i class="icon-file"></i> Struk Inventory</a>-->
        <a href="<?=base_url_admin('penjualan/cabang/print_struk_penjualan/'.$order->id)?>" id="btn-back" class="btn btn-success"><i class="icon-file"></i> Struk Penjualan</a>
				<button onclick="window.print()" class="btn btn-info"><i class="icon-print"></i> Cetak</button>
      </div>
      <!-- Page Content -->
  		<div id="printable">
        <div id="page-content" class="full" style="padding:0px;border:none;margin-bottom: 20px;">
				<?php if(isset($order->detail)){ foreach($order->detail as $od){ if($od->c_produk_utype != 'jasa') continue; ?>
          <div style="position:relative;margin-left:0px auto;width:350px;font-size:14px;">
  					<p class="text-center" style="margin-left:15px;margin-bottom:5px;">=====================================================</p>
  					<p class="text-center" style="font-weight:bold;font-size:20px;width:350px;margin-top:-15px;margin-bottom:-15px;">
  						<?=$od->terapis_nama?>
  					</p>
  					<p class="text-center" style="margin-left:15px;margin-top:8px;font-size:15px;width:300px;">
  						<?php if(strtolower($order->a_company_utype) == 'cabang') ucfirst($order->a_company_utype); ?> <?=$order->a_company_nama?>
  					</p>
  					<p class="text-center" style="margin-left:15px;margin-top:-20px;">=====================================================</p>
  					<div style="margin-left:20px;display:inline-block;margin-top:-15px;margin-bottom:-5px;">
  						<div>
  							<div style="width:100px;display:inline-block;">Tanggal</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$this->tgl->convert($order->date_order,'hari_tanggal_jam')?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Kode Pasien</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$order->b_user_kode?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Nama Pasien</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$order->b_user_nama?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Treatment</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$od->c_produk_nama?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Poin</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$od->c_produk_poin_terapis?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Tindakan</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$od->c_produk_tindakan_oleh?></div>
  						</div>
  					</div>
  					<p class="text-center" style="margin-left:15px;margin-top:5px;font-size:13px;width:300px;">"Kumpulkan point untuk direkap perbulan"</p>
  					<p class="text-center" style="margin-left:15px;margin-top:-15px;margin-bottom:15px;">=====================================================</p>
  				</div>

          <?php if(!empty($od->c_produk_is_asistensi)){ ?>
  				<div style="position:relative;margin-left:0px auto;width:350px;font-size:14px;">
  					<p class="text-center" style="margin-left:15px;margin-bottom:5px;">=====================================================</p>
  					<p class="text-center" style="font-weight:bold;font-size:20px;width:350px;margin-top:-15px;margin-bottom:-15px;">
  						<?=$od->asistensi_nama?>
  					</p>
  					<p class="text-center" style="margin-left:15px;margin-top:8px;font-size:15px;width:300px;">
  						<?php if(strtolower($order->a_company_utype) == 'cabang') ucfirst($order->a_company_utype); ?> <?=$order->a_company_nama?>
  					</p>
  					<p class="text-center" style="margin-left:15px;margin-top:-20px;">=====================================================</p>
  					<div style="margin-left:20px;display:inline-block;margin-top:-15px;margin-bottom:-5px;">
  						<div>
  							<div style="width:100px;display:inline-block;">Tanggal</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$this->tgl->convert($order->date_order,'hari_tanggal_jam')?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Kode Pasien</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$order->b_user_kode?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Nama Pasien</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$order->b_user_nama?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Treatment</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$od->c_produk_nama?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Poin</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;"><?=$od->c_produk_poin_terapis?></div>
  						</div>
  						<div>
  							<div style="width:100px;display:inline-block;">Tindakan</div>
  							<div style="width:20px;display:inline-block;">:</div>
  							<div style="display:inline-block;">Asistensi</div>
  						</div>
  					</div>
  					<p class="text-center" style="margin-left:15px;margin-top:5px;font-size:13px;width:300px;">"Kumpulkan point untuk direkap perbulan"</p>
  					<p class="text-center" style="margin-left:15px;margin-top:-15px;margin-bottom:15px;">=====================================================</p>
  				</div>
          <?php } ?>

        <?php } } ?>
        </div>
  		</div>
      <!-- END Page Content -->
    </div>
    <!-- END Page Container -->

    <!-- Scroll to top link, check main.js - scrollToTop() -->
    <a href="#" id="to-top"><i class="icon-chevron-up"></i></a>
    <!-- Excanvas for Flot (Charts plugin) support on IE8 -->
    <!--[if lte IE 8]><script src="js/helpers/excanvas.min.js"></script><![endif]-->
    <!-- Jquery plugins and custom javascript code -->
    <script src="<?=$this->cdn_url(); ?>skin/struk/js/vendor/jquery.min.js"></script>

    <!-- Bootstrap.js -->
    <script src="<?=$this->cdn_url(); ?>skin/struk/js/vendor/bootstrap.min.js"></script>

    <!-- Jquery plugins and custom javascript code -->
    <script src="<?=$this->cdn_url(); ?>skin/struk/js/plugins.js"></script>
  	<script>
  		function printDiv(divName) {
  			var printContents = document.getElementById(divName).innerHTML;
  			var originalContents = document.body.innerHTML;
  			document.body.innerHTML = printContents;
  			window.print();
  			document.body.innerHTML = originalContents;
  		}
  		//window.print();
  	</script>
      <!-- Javascript code only for this page -->
  </body>
</html>
