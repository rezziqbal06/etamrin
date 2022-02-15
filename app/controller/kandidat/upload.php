<?php
/**
* Kandidat
*/
class Upload extends JI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load("front/b_user_model", "bum");
    $this->load('api_front/b_user_file_model','bufm');
    $this->load("front/c_apply_model", "cam");
    $this->current_menu = 'kandidat_upload';
  }
  public function index()
  {
    $data = $this->__init();
    if(!$this->user_login){
      redir(base_url('login'));
      return;
    }

    $cam = $this->cam->getByUserId($data['sess']->user->id);
    if(!isset($cam->id)){
      redir(base_url('kandidat/dashboard'));
      return;
    }

    $data['bum'] = $this->bum->getById($data['sess']->user->id);
    $this->requiredVerifiedEmail($data['bum']);

    require_once(SEMEROOT.'app/controller/api_front/register.php');
    $r = new Register();
    $data['sess']->user->token = $r->__genTokenMobile($data['sess']->user->id);
    $this->setKey($data['sess']);

    // dd($data['sess']->user);die();

    $data['bum'] = $data['sess']->user;
    $data['bufm'] = array();
    foreach($this->bufm->getByUserId($data['bum']->id) as $bufm){
      if(!isset($data['bufm'][$bufm->utype])) $data['bufm'][$bufm->utype] = $bufm;
    }

    //build form items
    $data['form_items'] = array();

    $fi = new stdClass();
    $fi->ptips = 'Format *.pdf';
    $fi->accept = 'application/pdf';
    $fi->kode = 'ktp';
    $fi->text = 'Upload File KTP';
    $data['form_items'][] = $fi;

    $fi = new stdClass();
    $fi->ptips = 'Format *.pdf';
    $fi->accept = 'application/pdf';
    $fi->kode = 'cv';
    $fi->text = 'Upload File CV';
    $data['form_items'][] = $fi;

    $fi = new stdClass();
    $fi->ptips = 'Format *.pdf';
    $fi->accept = 'application/pdf';
    $fi->kode = 'portofolio';
    $fi->text = 'Upload File Portofolio';
    $data['form_items'][] = $fi;

    $fi = new stdClass();
    $fi->ptips = 'Format *.pdf';
    $fi->accept = 'application/pdf';
    $fi->kode = 'ijazah';
    $fi->text = 'Upload File Ijazah';
    $data['form_items'][] = $fi;

    $fi = new stdClass();
    $fi->ptips = 'Format *.pdf';
    $fi->accept = 'application/pdf';
    $fi->kode = 'transkrip';
    $fi->text = 'Upload File Transkrip';
    $data['form_items'][] = $fi;

    $fi = new stdClass();
    $fi->ptips = 'Format *.pdf';
    $fi->accept = 'application/pdf';
    $fi->kode = 'vaksin';
    $fi->text = 'Upload File Sertifikat Vaksin';
    $data['form_items'][] = $fi;

    require_once(SEMEROOT.'app/controller/kandidat/profil.php');
    $p = new Profil();
    if(!$p->_checkBeforeIsiData($cam)){
      $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
      redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
      return;
    }

    $this->setTitle('Upload File: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

    $this->_setULog($data['sess']->user->id, 14);

    $this->putThemeContent('kandidat/upload/home_modal',$data);
    $this->putThemeContent('kandidat/upload/home',$data);
    $this->putJsContent('kandidat/upload/home_bottom',$data);
    $this->loadLayout('col-2-left',$data);
    $this->render();
  }
}
