<main class="daftar">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2 col-12">
        <div class="main-logo">
          <a href="<?=base_url()?>">
            <img src="<?=$this->cdn_url($this->config->semevar->site_logo)?>" class="img-fluid main-logo animate__animated animate__slideInDown  animate__slow" />
          </a>
        </div>
        <div class="bungkus animate__animated animate__fadeIn animate__delay-1s">

          <h1>Berhasil Daftar</h1>
          <p>
            Terima kasih Anda telah mengisi form data diri, Silahkan <b>CEK EMAIL</b> anda di inbox / spam.
            Jika dalam 1x24 jam Anda belum menerima email balasan harap segera hubungi <a href="mailto:<?=$this->config->semevar->email_reply?>?subject=ERROR - <?=strtoupper($sess->user->fnama)?>"><?=$this->config->semevar->email_reply?></a> dengan subject <b>ERROR - <?=strtoupper($sess->user->fnama)?></b>.
          </p>
          <a href="<?=base_url()?>joblist" class="register">Lanjut Pilih Lowongan</a>
        </div>
      </div>
    </div>
  </div>
</div>
