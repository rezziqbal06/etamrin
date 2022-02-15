<?php
	class Media extends JI_Controller{
	var $status = 0;
	var $treehtml = '';
	var $module = "cms_media";
	var $page = "cms_media";

	public function __construct(){
    parent::__construct();
		$this->setTheme("admin/");
		$this->current_parent = 'cms';
		$this->current_page = 'cms_media';
		$this->is_login_user = 0;
	}
	public function index(){
		$data = array();
		$data = $this->__init();
		//$this->debug($data['sess']->user->modules);
		//die();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}

		$this->setTitle("CMS: Media ".$this->config->semevar->admin_site_suffix);
		$this->setDescription('Manage your media on '.$this->config->semevar->app_name);

		$this->putThemeContent("cms/media/home_modal",$data);
		$this->putThemeContent("cms/media/home",$data);
		$this->putJsContent("cms/media/home_bottom",$data);
		$this->loadLayout("col-2-left",$data);
		$this->render();
	}
	public function browser(){
		$data = array();
		$data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin());
			die();
		}
		$data['CKEditor'] = $this->input->request('CKEditor');
		$data['CKEditorFuncNum'] = $this->input->request('CKEditorFuncNum');
		if(empty($data['CKEditorFuncNum'])) unset($data['CKEditorFuncNum']);
		$data['langCode'] = $this->input->request('langCode');

		$this->setTitle("CMS: Media: Browser ".$this->config->semevar->admin_site_suffix);

		$this->putThemeContent("cms/media/browser_modal",$data);
		$this->putThemeContent("cms/media/browser",$data);
		$this->putJsContent("cms/media/browser_bottom",$data);
		$this->loadLayout("media",$data);
		$this->render();
	}
}
