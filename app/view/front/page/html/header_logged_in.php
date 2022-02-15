<style>
.dropdown-menu.user-dropdown {
  left: auto;
  right: 20px;
}
.navbar .navbar-brand .logo-main {
  width: 100px;
  height: auto;
}
</style>
<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-transparent">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url('kandidat/dashboard') ?>">
      <img class="logo-main" src="<?= $this->cdn_url($this->config->semevar->site_logo) ?>" alt="<?= $this->config->semevar->site_name ?>">
    </a>
    <button id="" class="navbar-toggler btn-menu-left-show" type="button" aria-label="Toggle menu left">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item d-none">
          <a href="<?=base_url('notifikasi')?>" class="nav-link">
            <i class="fa fa-bell-o ml-2"></i>
            <span class="badge bg-secondary" style="">2</span> <span class="d-inline d-sm-none"> Notifikasi</span>
          </a>
        </li>
        <li class="nav-item dropdown d-none">
          <a class="nav-link dropdown-toggle " href="#" id="navbarUserMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?=($sess->user->foto)?>" class="user-foto-circle-small" />
          </a>
          <ul id="navbarUserDropdown" class="dropdown-menu user-dropdown" aria-labelledby="navbarUserDropdown">
            <li><a class="dropdown-item" href="<?=base_url('kandidat/dashboard/')?>">Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?=base_url('joblist/')?>">Career</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?=base_url('logout')?>">Logout</a></li>
          </ul>
        </li>
        <li class="d-md-none">
          <a class="dropdown-item" href="<?=base_url('logout')?>">Logout</a>

            <br class="d-block" />
            <br class="d-block" />
            <br class="d-block" />
        </li>
      </ul>
    </div>
  </div>
  </div>
</nav>

<header class="">
  <div class="container">
    <div class="row g-0 align-items-center">
      <div class="col-3 col-lg-3 d-none d-lg-block">
        <div class="img-logo">
          <a href="<?= base_url() ?>" class="a-user-foto a-user-foto-ganti">
            <img src="<?= $this->cdn_url($sess->user->foto) ?>" onerror="this.onerror=null;this.src='<?= $this->cdn_url() ?>media/user/default.png'" />
          </a>
        </div>
        <div class="user-info">
          <div class="user-info-name">
            <?= $sess->user->fnama ?>
          </div>
          <div class="user-info-extra">
            <?= ucwords($sess->user->email) ?>
          </div>
          <div class="d-grid gap-2 d-md-block user-info-action">
            <a href="<?=base_url('kandidat/dashboard/')?>" class="btn btn-warning btn-sm">
              Dashboard
            </a>
            <a href="<?=base_url('joblist')?>" class="btn btn-outline-warning btn-sm">
              Joblist
            </a>
            <a href="<?=base_url('logout')?>" class="btn btn-outline-warning btn-sm">
              Logout
            </a>
          </div>
        </div>
      </div>
      <div class="col col-lg-7">
        &nbsp;
      </div>
    </div>
  </div>
</header>

<form id="user_foto_form" method="post" action="">
  <input id="user_foto" name="foto" type="file" accept=".png,.jpg,.jpeg" style="display:none;" />
</form>
<img id="user_foto_ori" src="" style="display:none" />
<img id="user_foto_rsz" src="" style="display:none" />
<img id="user_foto_preview" src="" style="display:none" />
