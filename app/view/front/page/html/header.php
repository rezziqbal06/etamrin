<?php if (!$this->user_login || isset($is_disabled_header_logged_in)) { ?>
  <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand d-flex w-50 me-auto" href="<?= base_url() ?>">
        <img class="logo-main" src="<?= $this->cdn_url($this->config->semevar->site_logo_small) ?>" alt="<?= $this->config->semevar->site_name ?>">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsingNavbar" aria-controls="collapsingNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="navbar-collapse collapse w-100" id="collapsingNavbar">
        <ul class="navbar-nav w-100 justify-content-center gap-md-5">
          <li class="nav-item"><a href="<?= $this->current_page == 'homepage' ? '#' : base_url() ?>" title="Halaman Utama <?= $this->config->semevar->site_name ?>" class="nav-link <?php if ($this->current_page == 'homepage') echo 'active'; ?> move2home">Home</a></li>
          <li class="nav-item"><a href="<?= $this->current_page == 'homepage' ? '#' : base_url() ?>" class="nav-link move2about">About</a></li>
          <li class="nav-item"><a href="<?= $this->current_page == 'homepage' ? '#' : base_url() ?>" class="nav-link move2workat">WorkAt</a></li>
          <li class="nav-item"><a href="<?= $this->current_page == 'homepage' ? '#career' : base_url('joblist') ?>" class="nav-link <?php if ($this->current_page == 'career') echo 'active'; ?> move2career">Career</a></li>
        </ul>
        <ul class="nav navbar-nav ms-auto w-100 justify-content-end gap-1">
          <li class="nav-item"><a href="#" class="nav-link bsearch">Search <img src="<?= $this->cdn_url("media/homepage/search.svg") ?>" width="20px" alt=""></a></li>
          <?php if ($this->user_login) { ?>
            <li class="nav-item d-none d-md-block"><a href="<?= base_url('kandidat/dashboard') ?>" class="nav-link" style="border-left: 2px solid black; padding-left:0.5rem">Dashboard <img src="<?= $this->cdn_url("media/homepage/user.svg") ?>" width="20px" alt=""></a></li>
            <li class="nav-item d-block d-md-none"><a href="<?= base_url('kandidat/dashboard') ?>" class="nav-link">Dashboard <img src="<?= $this->cdn_url("media/homepage/user.svg") ?>" width="20px" alt=""></a></li>
          <?php } else { ?>
            <li class="nav-item d-none d-md-block"><a href="<?= base_url('login') ?>" class="nav-link" style="border-left: 2px solid black; padding-left:0.5rem">Login <img src="<?= $this->cdn_url("media/homepage/user.svg") ?>" width="20px" alt=""></a></li>
            <li class="nav-item d-block d-md-none"><a href="<?= base_url('login') ?>" class="nav-link">Login <img src="<?= $this->cdn_url("media/homepage/user.svg") ?>" width="20px" alt=""></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    </div>
  </nav>
<?php
  $this->getThemeElement('page/html/modal_search', $__forward);
} else {
  $this->getThemeElement('page/html/header_logged_in', $__forward);
}
?>
