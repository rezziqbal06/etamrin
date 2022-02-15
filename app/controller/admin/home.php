<?php
class Home extends JI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->setTheme('admin');
		$this->lib('tgl');
		$this->current_parent = 'dashboard';
		$this->current_page = 'dashboard';
	}

	public function index()
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'), 0);
			die();
		}

		$this->setTitle('Dashboard ' . $this->config->semevar->admin_site_suffix);
		$this->putJsFooter($this->cdn_url('skin/admin/') . 'js/pages/index');

		$this->putThemeContent("home/home", $data);
		$this->putJsContent("home/home_bottom", $data);
		$this->loadLayout('col-2-left', $data);
		$this->render();
	}
}
