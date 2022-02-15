<?php
class kelas extends JI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->setTheme('admin');
		$this->current_parent = 'pengaturan';
		$this->current_page = 'kelas';
	}

	public function index()
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'), 0);
			die();
		}


		$this->setTitle('Pengaturan: kelas ' . $this->config->semevar->admin_site_suffix);
		$this->putJsFooter($this->cdn_url('skin/admin/') . 'js/pages/index');

		$this->putThemeContent("pengaturan/kelas/home_modal", $data);
		$this->putThemeContent("pengaturan/kelas/home", $data);
		$this->putJsContent("pengaturan/kelas/home_bottom", $data);
		$this->loadLayout('col-2-left', $data);
		$this->render();
	}
	public function simpan()
	{
		echo 'tes';
	}
}
