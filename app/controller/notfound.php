<?php
/**
 * Controller class for throw 404 response code
 *
 * @package SemeFramework
 * @since SemeFramework 1.0
 *
 * @codeCoverageIgnore
 */
 class Notfound extends JI_Controller{
 	public function __constructx(){
     parent::__construct();
 		$this->setTheme('front/');
 	}
 	public function index(){
 		$data = $this->__init();
 		header("HTTP/1.0 404 Not Found");
 		$this->setTitle("Not Found ".$this->config->semevar->site_suffix);
 		$this->setDescription($this->config->semevar->site_description);
 		$this->loadLayout('notfound',$data);
 		$this->render();
 	}
 }
