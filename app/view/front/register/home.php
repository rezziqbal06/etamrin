<style>
.img-pas-foto-tips {
  font-size: 0.6em;
    position: relative;
  z-index: 2;
  top: -10px;
}
#ifoto {
  position: relative;
  z-index: 3;
  top: -55px;
  width: 100%;
}
</style>

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
          <h1>Pendaftaran</h1>
          <p class="">
            <?=$this->config->semevar->register_form_instruction?>
          </p>

          <?php $this->getThemeElement('register/_form',$__forward);?>

        </div>
      </div>
    </div>
  </div>
</main>
