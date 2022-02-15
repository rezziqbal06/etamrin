<?php
class Sumber extends JI_Controller
{
  public $errmsg = '';

  public function __construct()
  {
    parent::__construct();
    $this->setTheme('admin');
    $this->current_parent = 'pengaturan_sumber';
    $this->current_page = 'pengaturan_sumber';
  }

  public function index()
  {
    $data = $this->__init();
    if (!$this->admin_login) {
      redir(base_url_admin('login'));
      die();
    }

    $this->setTitle('Pengaturan: Sumber Pelamar ' . $this->config->semevar->admin_site_suffix);
    $this->putThemeContent("pengaturan/sumber/home_modal", $data);
    $this->putThemeContent("pengaturan/sumber/home", $data);

    $this->putJsReady("pengaturan/sumber/home_bottom", $data);
    $this->loadLayout('col-2-left', $data);
    $this->render();
  }
}
