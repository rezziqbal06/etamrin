<?php
/**
 * Controller class for throw 404 response code
 *
 * @package SemeFramework
 * @since SemeFramework 1.0
 *
 * @codeCoverageIgnore
 */
 class NoAccess extends JI_Controller{
 	public function __constructx(){
     parent::__construct();
 		$this->setTheme('front/');
 	}
 	public function index(){
 		$data = $this->__init();
 		header("HTTP/1.0 403 Forbidden");
 		$this->setTitle("Error 403: No Access");
 		$this->setDescription($this->config->semevar->site_description);
 		$this->loadLayout('noaccess',$data);
 		$this->render();
 	}
 }
