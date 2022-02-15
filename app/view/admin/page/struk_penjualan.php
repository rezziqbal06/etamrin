<?php
  if(!isset($struk_judul)) $struk_judul = 'JUDUL STRUK';
  if(!isset($struk_footer)) $struk_footer = '';
  if(strlen($struk_footer)<=4) $struk_footer = 'Terima kasih atas kunjungannya, barang yang sudah dibeli tidak bisa ditukar kembali';
  if(!isset($is_resep)) $is_resep = 0;
  if(!isset($pajak_status)) $pajak_status = 0;
  if(!isset($pajak_persentase)) $pajak_persentase = 0.0;
  if(!isset($pajak_nominal)) $pajak_nominal = 0;
  if(!isset($struk_alamat)) $struk_alamat = 'ALAMAT';
  if(!isset($struk_kode)) $struk_kode = '';
  if(!isset($resep_kode)) $resep_kode = $struk_kode;
  if(!isset($struk_tanggal)) $struk_tanggal = '';
  if(!isset($struk_tanggal_catat)) $struk_tanggal_catat = '';
  if(!isset($struk_kasir_nama)) $struk_kasir_nama = 'Admin';
  if(!isset($struk_pelanggan_nama)) $struk_pelanggan_nama = 'Customer';
?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->
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
    <div id="non_printable" style="margin-left:23%">
      <a href="#" id="btn-back" class="btn btn-default" onclick="if(confirm('Apakah anda yakin?')) window.close();"><i class="icon-arrow-left"></i> Manajemen Penjualan</a>
      <a href="<?=base_url_admin('penjualan/cabang/print_struk_tindakan/'.$order->id)?>" id="btn-back" class="btn btn-success"><i class="icon-file"></i> Struk Tindakan</a>
      <!--<a href="<?=base_url_admin('penjualan/cabang/print_struk_inventory/'.$order->id)?>" id="btn-back" class="btn btn-success"><i class="icon-file"></i> Struk Inventory</a>-->
      <a href="<?=base_url_admin('penjualan/cabang/print_struk_penjualan/'.$order->id)?>" id="btn-back" class="btn btn-success"><i class="icon-file"></i> Struk Penjualan</a>
      <button onclick="window.print()" class="btn btn-info"><i class="icon-print"></i> Cetak</button>
    </div>
    <!-- Page Content -->
    <div id="printable">
      <div id="page-content" class="full" style="padding:0px;border:none;">
        <?php ?>
          <div style="position:relative;margin-left:0px auto;width:350px;font-size:14px;">
            <p class="text-center" style="margin-left:15px;">=====================================================</p>
            <p class="text-center" style="font-weight:bold;font-size:15px;width:350px;margin-top:-15px;margin-bottom:-15px;">
              <?=$struk_judul?>
            </p>
            <p class="text-center" style="margin-left:15px;margin-top:15px;font-size:11px;width:300px;">
              <?=$struk_alamat?>
            </p>
            <p class="text-center" style="margin-left:15px;">=====================================================</p>
            <div style="margin-left:20px;display:inline-block;margin-top:-15px;margin-bottom:-5px;">
              <div>
                <div style="width:100px;display:inline-block;">Kode</div>
                <div style="width:20px;display:inline-block;">:</div>
                <div style="display:inline-block;"><?=$struk_kode?></div>
              </div>
              <?php if(!empty($is_resep)){ ?>
              <div>
                <div style="width:100px;display:inline-block;">No Resep</div>
                <div style="width:20px;display:inline-block;">:</div>
                <div style="display:inline-block;"><?=$resep_kode?></div>
              </div>
              <?php } ?>
              <div>
                <div style="width:100px;display:inline-block;">Tanggal</div>
                <div style="width:20px;display:inline-block;">:</div>
                <div style="display:inline-block;"><?=$struk_tanggal?></div>
              </div>
              <div>
                <div style="width:100px;display:inline-block;">Waktu</div>
                <div style="width:20px;display:inline-block;">:</div>
                <div style="display:inline-block;"><?=$struk_waktu?></div>
              </div>
              <div>
                <div style="width:100px;display:inline-block;">Kasir</div>
                <div style="width:20px;display:inline-block;">:</div>
                <div style="display:inline-block;"><?=$struk_kasir_nama?></div>
              </div>
              <div>
                <div style="width:100px;display:inline-block;">Pelanggan</div>
                <div style="width:20px;display:inline-block;">:</div>
                <div style="display:inline-block;"><?=$struk_pelanggan_nama?></div>
              </div>
            </div>
            <p class="text-center" style="margin-left:15px;">=====================================================</p>
            <?php if(isset($order->detail)){  ?>
            <div style="margin-left:20px;margin-right:8px;display:inline-block;margin-top:-20px;">
              <div class="text-center" style="width:320px;display:inline-block;font-weight:bold;">Transaksi</div>
              <ol style="list-style:none;margin-left:0px;font-size:14px;">
                <?php $urutan=0; foreach($order->detail as $od){ $urutan++; ?>
                <li style="padding-bottom:3px;line-height:1.2;">
                  <div style="width:200px;display:inline-block;">
                    <span style="width: 20px;"><?=str_pad($urutan,2,"0",STR_PAD_LEFT)?>.</span> <?=$od->c_produk_nama?>
                    <br>
                    <div style="font-size: smaller;">
                      <?php if($od->harga_asal != $od->harga_jadi){ ?>
                      <span style="color: #3c3c3c; text-decoration: line-through">Rp <?=number_format($od->harga_asal,0,',','.')?></span>
                      <span style="">Rp <?=number_format($od->harga_jadi,0,',','.')?></span>
                      <?php }else{ ?>
                      <span style="">Rp <?=number_format($od->harga_jadi,0,',','.')?></span>
                      <?php } ?>
                      <?php if($od->qty>1){ ?>
                       x <?=$od->qty?><?php if($od->c_produk_utype=='barang') echo  $od->c_produk_satuan; ?>

                      <br /><?php if($od->diskon_item>0){
                        echo 'X disc: Rp'.number_format($od->diskon_item,0,',','.');
                      } ?>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="text-right" style="width:90px;display:inline-block;vertical-align:top;font-size: smaller;">
                    <?php if($od->diskon_row>0){ ?>
                    <span style="text-decoration: line-through;">Rp <?=number_format($od->sub_total+$od->diskon_row,0,',','.')?></span>
                    <br />
                    <?php } ?>
                    Rp <?=number_format($od->sub_total,0,',','.')?>
                  </div>
                </li>
                <?php } ?>
              </ol>
            </div>
            <?php } ?>
            <p class="text-center" style="margin-left:15px;margin-top:-13px;">=====================================================</p>
            <div style="margin-left:8px;margin-top:-20px;margin-bottom:-3px;">
              <div class="text-right" style="width:180px;display:inline-block;">Total</div>
              <div class="text-right" style="width:120px;display:inline-block;text-decoration: italic;">Rp <?=number_format($struk_subtotal,0,',','.')?></div>
              <?php if($order->pajak_nominal>0){ ?>
              <div class="text-right" style="width:180px;display:inline-block;">Pajak</div>
              <div class="text-right" style="width:120px;display:inline-block;text-decoration: italic;"><?=number_format($order->pajak_persentase,2,',','.')?>%</div>
              <?php } ?>
              <div class="text-right" style="width:180px;display:inline-block;">Diskon</div>
              <div class="text-right" style="width:120px;display:inline-block;text-decoration: italic;">Rp <?=number_format($struk_diskon,0,',','.')?></div>

              <div class="text-right" style="width:180px;display:inline-block;">Voucher</div>
              <div class="text-right" style="width:120px;display:inline-block;">Rp <?=number_format($struk_voucher,0,',','.')?></div>

              <div class="text-right" style="width:180px;display:inline-block;">Total Harga</div>
              <div class="text-right" style="width:120px;display:inline-block;font-weight:bold;">Rp <?=number_format($struk_total,0,',','.')?></div>
              <?php if(false){ ?>
              <div class="text-right" style="width:180px;display:inline-block;">Uang Terima</div>
              <div class="text-right" style="width:120px;display:inline-block;">Rp <?=number_format($struk_terima,0,',','.')?></div>

              <div class="text-right" style="width:180px;display:inline-block;">Uang Kembali</div>
              <div class="text-right" style="width:120px;display:inline-block;">Rp <?=number_format($struk_kembali,0,',','.')?></div>
              <?php } ?>
            </div>
            <p class="text-center" style="margin-left:15px;">=====================================================</p>
            <p class="text-left" style="margin-left:15px;margin-top:-15px;font-size:11px;width:300px;"><?=trim($struk_footer)?></p>
            <?php if(isset($order->npwp)){ if(strlen($order->npwp)>1){ ?>
            <p class="text-center" style="margin-left:15px;">=====================================================</p>
            <p class="text-left" style="margin-left:15px;margin-top:-15px;font-size:11px;width:300px;">NPWP <?=$order->npwp?></p>
            <?php }} ?>
        </div>
      </div>
    </div>
    <!-- END Page Content -->
  </div>
  <!-- END Page Container -->

  <!-- Scroll to top link, check main.js - scrollToTop() -->
  <a href="#" id="to-top"><i class="icon-chevron-up"></i></a>
  <!-- Excanvas for Flot (Charts plugin) support on IE8 -->
  <!--[if lte IE 8]><script src="js/helpers/excanvas.min.js"></script><![endif]-->

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
