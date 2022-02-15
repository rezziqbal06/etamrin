<?php
class TestSkill extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->setTheme('admin');
		$this->lib("seme_purifier");
		$this->load("admin/a_banksoal_model","absm");
		$this->load("admin/b_soal_model","bsm");
		$this->load("admin/b_soal_pilihan_model","bspm");
		$this->current_parent = 'ujian_testskill';
		$this->current_page = 'ujian_testskill';
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

		$this->setTitle('Bank Soal: Test Skill '.$this->config->semevar->admin_site_suffix);

		$this->putThemeContent("ujian/testskill/home_modal",$data);
		$this->putThemeContent("ujian/testskill/home",$data);
		$this->putJsContent("ujian/testskill/home_bottom",$data);
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
		$this->putJsFooter($this->cdn_url('skin/admin/js/helpers/ckeditor/ckeditor'));

		$this->setTitle('Ujian: Bank Soal: Baru '.$this->config->semevar->admin_site_suffix);

		$this->putThemeContent("ujian/testskill/baru_modal",$data);
		$this->putThemeContent("ujian/testskill/baru",$data);

		$this->putJsContent("ujian/testskill/baru_bottom",$data);
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
			redir(base_url_admin('ujian/testskill/'));
			die();
		}
		$pengguna = $data['sess']->admin;
		$absm = $this->absm->getById($id);
		if(!isset($absm->id)){
			redir(base_url_admin('ujian/testskill/'));
			die();
		}
		if(in_array($absm->utype,array('iq','kepribadian','cs'))){
			redir(base_url_admin('ujian/banksoal/'));
			die();
		}
		$this->putJsFooter($this->cdn_url('skin/admin/js/helpers/ckeditor/ckeditor'));

		$data['absm'] = $absm;

		$this->setTitle('Ujian: Bank Soal: Edit #'.$absm->id.' '.$this->config->semevar->admin_site_suffix);
		$this->putThemeContent("ujian/testskill/edit_modal",$data);
		$this->putThemeContent("ujian/testskill/edit",$data);
		$this->putJsContent("ujian/testskill/edit_bottom",$data);
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
			redir(base_url_admin('ujian/testskill/'));
			die();
		}
		$absm = $this->absm->getById($id);
		if(!isset($absm->id)){
			redir(base_url_admin('ujian/testskill/'));
			die();
		}
		$mode = strtolower($absm->utype);
		$data['mode'] = $mode;
		if($absm->utype == 'teks') $absm->pilihan_jml = 0;
		$this->setTitle('Ujian: Bank Soal: Detail #'.$absm->id.' '.$this->config->semevar->admin_site_suffix);

		$absm->nama = htmlentities($absm->nama);
		$data['absm'] = $absm;

		$sids = array();
		$data['soal'] = array();
		$soal = $this->bsm->getByBankSoalId($absm->id);
		foreach($soal as $s){
			$sids[] = (int) $s->id;
			$s->pilihans = array();
			$data['soal'][$s->id] = $s;
		}
		unset($s,$soal,$absm);

		if(count($sids)){
			$pilihans = $this->bspm->getBySoalIds($sids);
			foreach($pilihans as $p){
				if(isset($data['soal'][$p->b_soal_id]->pilihans)){
					$data['soal'][$p->b_soal_id]->pilihans[] = $p;
				}
			}
			unset($p,$sids);
		}


		//load ckeditor js
		$this->putJsFooter($this->cdn_url('skin/admin/js/helpers/ckeditor/ckeditor'));

		$this->putThemeContent("ujian/testskill/detail/".$mode."_modal",$data);
		$this->putThemeContent("ujian/testskill/detail/".$mode."",$data);
		$this->putJsContent("ujian/testskill/detail/_bottom",$data);
		$this->putJsContent("ujian/testskill/detail/".$mode."_bottom",$data);
		$this->loadLayout('col-2-left-online',$data);
		$this->render();
	}
}
