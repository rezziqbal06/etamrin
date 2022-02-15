<?php
	class MasterData extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->setTheme('admin');
		$this->lib("seme_purifier");
		$this->current_parent = 'perusahaan';
		$this->current_page = 'perusahaan_masterdata';
		$this->load("admin/a_company_model","acm");
		$this->load("admin/a_negara_model","anm");
	}
	public function index(){
		$data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}
		$pengguna = $data['sess']->admin;
		$cats = array();
		$cat = array();

		$this->setTitle('Perusahaan: Master Data '.$this->config->semevar->admin_site_suffix);

		$this->putThemeContent("perusahaan/masterdata/home_modal",$data);
		$this->putThemeContent("perusahaan/masterdata/home",$data);
		$this->putJsContent("perusahaan/masterdata/home_bottom",$data);
		$this->loadLayout('col-2-left',$data);
		$this->render();
	}
	public function baru(){
		$data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}
		$pengguna = $data['sess']->admin;
		$data['anm'] = $this->anm->get();

		$this->setTitle('Perusahaan: Master Data: Buat Baru '.$this->config->semevar->admin_site_suffix);

		$this->putThemeContent("perusahaan/masterdata/baru_modal",$data);
		$this->putThemeContent("perusahaan/masterdata/baru",$data);


		$this->putJsContent("perusahaan/masterdata/baru_bottom",$data);
		$this->loadLayout('col-2-left',$data);
		$this->render();
	}
	public function edit($id){
		$data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}
		$id = (int) $id;
		if($id<=0){
			redir(base_url_admin('perusahaan/masterdata/'));
			die();
		}
		$pengguna = $data['sess']->admin;
		$acm = $this->acm->getById($id);
		if(!isset($acm->id)){
			redir(base_url_admin('perusahaan/masterdata/'));
			die();
		}

		//get parent company
		$acid = $this->acm->getById($acm->a_company_id);
		if(!isset($acid->id)){
			$acid = new stdClass();
			$acid->id = "NULL";
			$acid->nama = "-";
		}
		$acm->nama = ($acm->nama);

		$data['acm'] = $acm;
		$data['acid'] = $acid;
		$data['anm'] = $this->anm->get();

		$this->setTitle('Perusahaan: Master Data: Edit #'.$acm->id.' '.$this->config->semevar->admin_site_suffix);
		$this->putThemeContent("perusahaan/masterdata/edit_modal",$data);
		$this->putThemeContent("perusahaan/masterdata/edit",$data);
		$this->putJsContent("perusahaan/masterdata/edit_bottom",$data);
		$this->loadLayout('col-2-left',$data);
		$this->render();
	}
	public function detail($id){
		$data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}
		$id = (int) $id;
		if($id<=0){
			redir(base_url_admin('perusahaan/masterdata/'));
			die();
		}
		$acm = $this->acm->getById($id);
		if(!isset($acm->id)){
			redir(base_url_admin('perusahaan/masterdata/'));
			die();
		}
		$this->setTitle('Perusahaan: Master Data: Detail #'.$acm->id.' '.$this->config->semevar->admin_site_suffix);

		$data['acm'] = $acm;
		$data['acm']->parent = $this->acm->getById($acm->a_company_id);

		$this->putThemeContent("perusahaan/masterdata/detail",$data);
		$this->putJsContent("perusahaan/masterdata/detail_bottom",$data);
		$this->loadLayout('col-2-left',$data);
		$this->render();
	}
}
