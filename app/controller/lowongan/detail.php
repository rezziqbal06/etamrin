<?php
/*
Lowongan
 */
class Detail extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load('front/b_lowongan_model','blm');
        $this->load('front/b_lowongan_detail_model','bldm');
        $this->current_menu = 'user_lowongan';
    }
    public function index($id='')
    {
        $data = $this->__init();
        $id = (int) $id;
        if($id<=0){
          require_once(SEMEROOT.'app/controller/notfound.php');
          $n = new Notfound();
          $n->index();
          return;
        }
        $data['blm'] = $this->blm->getById($id);
        if(!isset($data['blm']->id)){
          require_once(SEMEROOT.'app/controller/notfound.php');
          $n = new Notfound();
          $n->index();
          return;
        }
        $data['blm']->detail = $this->bldm->getByLowonganId($id);
        $data['blm']->responsibility = array();
        $data['blm']->requirement = array();
        $data['blm']->experience = array();
        $data['blm']->benefit = array();
        foreach($this->bldm->getByLowonganId($id) as $d){
          if($d->utype == 'requirement'){
            $data['blm']->requirement[] = $d;
          }elseif($d->utype == 'benefit'){
            $data['blm']->benefit[] = $d;
          }elseif($d->utype == 'responsibility'){
            $data['blm']->responsibility[] = $d;
          }elseif($d->utype == 'experience'){
            $data['blm']->experience[] = $d;
          }else{
            $data['blm']->level[] = $d;
          }
        }

        $this->setTitle('Detail Lowongan: '.$data['blm']->nama.' '.$this->config->semevar->site_suffix);

        $this->loadCss($this->cdn_url('skin/front/css/timeline.min'));
        $this->putThemeContent('lowongan/detail/home',$data);
        $this->putThemeContent('lowongan/detail/home_modal',$data);
        $this->putJsReady('lowongan/detail/home_bottom',$data);
        $this->loadLayout('col-1',$data);
        $this->render();
    }
}
