<?php
/*
Lowongan
 */
class Home extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load('front/b_lowongan_model','blm');
        $this->current_menu = 'user_lowongan';
    }
    public function index()
    {
        $data = $this->__init();
        $data['clm'] = $this->blm->get();
        $this->setTitle('Lowongan '.$this->config->semevar->site_suffix);

        $this->loadCss($this->cdn_url('skin/front/css/timeline.min'));
        $this->putThemeContent('lowongan/home',$data);
        $this->putThemeContent('lowongan/home_modal',$data);
        $this->putJsReady('lowongan/home_bottom',$data);
        if(isset($data['sess']->user->id)){
          $this->loadLayout('col-2-left',$data);
        }else{
          $this->loadLayout('col-1',$data);
        }

        $this->render();
    }
}
