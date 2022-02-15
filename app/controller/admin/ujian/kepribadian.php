<?php
class BankSoal extends JI_Controller{

	public function __construct(){
    parent::__construct();
		$this->setTheme('admin');
		$this->lib("seme_purifier");
		$this->current_parent = 'ujian_banksoal';
		$this->current_page = 'ujian_banksoal';
		$this->load("admin/a_banksoal_model","absm");
	}
	public function index($id=""){
		$data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}
		$id = (int) $id;
		if($id<=0){
			redir(base_url_admin('ujian/banksoal/?err=2'));
			die();
		}
		$absm = $this->absm->getById($id);
		if(!isset($absm->id)){
			redir(base_url_admin('ujian/banksoal/?err=1'));
			die();
		}
		if($absm->utype == 'teks') $absm->pilihan_jml = 0;
		$this->setTitle('E-Learning: Bank Soal: Detail #'.$absm->id.' '.$this->config->semevar->admin_site_suffix);

		$absm->nama = htmlentities($absm->nama);
		$data['absm'] = $absm;
		unset($absm);

		//load ckeditor js
		$this->putJsFooter($this->cdn_url('skin/admin/js/helpers/ckeditor/ckeditor'));

		$this->putThemeContent("ujian/banksoal/detail_modal",$data);
		$this->putThemeContent("ujian/banksoal/detail",$data);
		$this->putJsContent("ujian/banksoal/detail_bottom",$data);
		$this->loadLayout('col-2-left',$data);
		$this->render();
	}
}
