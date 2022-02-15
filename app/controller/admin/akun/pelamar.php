<?php
class Pelamar extends JI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->setTheme('admin');
		$this->lib("seme_purifier");
		$this->load("admin/a_company_model", "acm");
		$this->load("admin/a_negara_model", "anm");
		$this->load("admin/b_user_model", "bum");
		$this->current_parent = 'akun';
		$this->current_page = 'akun_pelamar';
	}
	public function index()
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'));
			die();
		}
		$pengguna = $data['sess']->admin;
		$cats = array();
		$cat = array();

		$this->setTitle('Akun: Pelamar '.$this->config->semevar->admin_site_suffix);

		$this->putThemeContent("akun/pelamar/home_modal", $data);
		$this->putThemeContent("akun/pelamar/home", $data);
		$this->putJsContent("akun/pelamar/home_bottom", $data);
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
		$pengguna = $data['sess']->admin;
		$data['anm'] = $this->anm->get();

		$this->setTitle('Akun: Pelamar: Buat Baru '.$this->config->semevar->admin_site_suffix);

		$this->putThemeContent("akun/pelamar/baru_modal", $data);
		$this->putThemeContent("akun/pelamar/baru", $data);


		$this->putJsContent("akun/pelamar/baru_bottom", $data);
		$this->loadLayout('col-2-left', $data);
		$this->render();
	}
	public function edit($id='',$cam_id='')
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'));
			die();
		}
		$id = (int) $id;
		if ($id<=0) {
			redir(base_url_admin('akun/pelamar/'));
			die();
		}
		$cam_id = (int) $cam_id;
		if ($cam_id<=0) {
			$cam_id = '';
		}
		$data['cam_id'] = $cam_id;

		$pengguna = $data['sess']->admin;
		$data['bum'] = $this->bum->getById($id);
		if (!isset($data['bum']->id)) {
			redir(base_url_admin('akun/pelamar/'));
			die();
		}

		$this->setTitle('Akun: Pelamar: Edit #'.$data['bum']->id.' '.$this->config->semevar->admin_site_suffix);
		$this->putThemeContent("akun/pelamar/edit_modal", $data);
		$this->putThemeContent("akun/pelamar/edit", $data);
		$this->putJsContent("akun/pelamar/edit_bottom", $data);
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
		$id = (int) $id;
		if ($id<=0) {
			redir(base_url_admin('akun/pelamar/'));
			die();
		}
		$acm = $this->acm->getById($id);
		if (!isset($acm->id)) {
			redir(base_url_admin('akun/pelamar/'));
			die();
		}
		$this->setTitle('Akun: Pelamar: Detail #'.$acm->id.' '.$this->config->semevar->admin_site_suffix);

		$data['acm'] = $acm;
		$data['acm']->parent = $this->acm->getById($acm->a_company_id);

		$this->putThemeContent("akun/pelamar/detail", $data);
		$this->putJsContent("akun/pelamar/detail_bottom", $data);
		$this->loadLayout('col-2-left', $data);
		$this->render();
	}
}
