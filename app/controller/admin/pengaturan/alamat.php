<?php
class Alamat extends JI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->setTheme('admin');
		$this->current_parent = 'pengaturan';
		$this->current_page = 'pengaturan_alamat';
		$this->load('admin/a_company_model', 'acm');
		$this->load('admin/b_alamat_interview_model', 'baim');
		$this->lib("seme_curl", 'curl');
	}
	public function index()
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'));
			die();
		}
		$this->setTitle('Pengaturan: Alamat Interview' . $this->config->semevar->admin_site_suffix);

		$this->putThemeContent("pengaturan/alamat/home_modal", $data);
		$this->putThemeContent("pengaturan/alamat/home", $data);
		$this->putJsContent("pengaturan/alamat/home_bottom", $data);
		$this->loadLayout('col-2-left', $data);
		$this->render();
	}

	public function baru()
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'));
			die();
		}
		$this->setTitle('Pengaturan: Alamat Interview Baru ' . $this->config->semevar->admin_site_suffix);

		$kabkota = $this->curl->get("https://alamat.thecloudalert.com/api/kabkota/get/");
		$wilayah = json_decode($kabkota);
		$data['wilayah'] = $wilayah->result;

		$this->putThemeContent("pengaturan/alamat/baru_modal", $data);
		$this->putThemeContent("pengaturan/alamat/baru", $data);
		$this->putJsContent("pengaturan/alamat/baru_bottom", $data);
		$this->loadLayout('col-2-left', $data);
		$this->render();
	}
	public function edit($id)
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'));
			die();
		}
		$data['baim'] = $this->baim->getById($id);

		if (!isset($data['baim']->id)) {
			redir(base_url_admin('pengaturan/alamat/'));
			die();
		}
		$this->setTitle('Pengaturan: Alamat Interview Edit #' . $data['baim']->id . ' ' . $this->config->semevar->admin_site_suffix);

		$this->putThemeContent("pengaturan/alamat/edit_modal", $data);
		$this->putThemeContent("pengaturan/alamat/edit", $data);
		$this->putJsContent("pengaturan/alamat/edit_bottom", $data);
		$this->loadLayout('col-2-left', $data);
		$this->render();
	}
	public function detail($id)
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'));
			die();
		}
		$data['detail'] = $this->baim->getById($id);
		if (!isset($data['detail']->id)) {
			redir(base_url_admin('pengaturan/alamat/'));
			die();
		}
		$this->setTitle('Pengaturan: Alamat Interview Detail ' . ucwords(strtolower($data['detail']->nama)) . ' ' . $this->config->semevar->admin_site_suffix);

		$this->putThemeContent("pengaturan/alamat/detail_modal", $data);
		$this->putThemeContent("pengaturan/alamat/detail", $data);
		$this->putJsContent("pengaturan/alamat/detail_bottom", $data);
		$this->loadLayout('col-2-left', $data);
		$this->render();
	}
}
