<?php
/**
 * Kandidat
 */
class Riwayat extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("front/b_user_model", "bum");
        $this->load("front/b_user_jobhistory_model", "bujhm");
        $this->load("front/b_user_keluarga_model", "bukgm");
        $this->load("front/c_apply_model", "cam");
        $this->load("front/c_apply_progress_model", "capm");
        $this->current_menu = 'kandidat_riwayat_formal';
    }
    public function index()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        redir(base_url('dashboard'));
    }

    public function formal()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_riwayat_formal';

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        $cam = $this->cam->getByUserId($data['sess']->user->id);
        if(!isset($cam->id)){
          redir(base_url('joblist'));
          return;
        }

        require_once(SEMEROOT.'app/controller/kandidat/profil.php');
        $p = new Profil();
        if(!$p->_checkBeforeIsiData($cam)){
          $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
          redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
          return;
        }

        $this->_setULog($data['sess']->user->id, 12, 'riwayat pendidikan formal');

        $this->setTitle('Riwayat Pendidikan Formal: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/riwayat/formal_modal',$data);
        $this->putThemeContent('kandidat/riwayat/formal',$data);
        $this->putJsContent('kandidat/riwayat/formal_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }

    public function informal()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_riwayat_informal';

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        $cam = $this->cam->getByUserId($data['sess']->user->id);
        if(!isset($cam->id)){
          redir(base_url('joblist'));
          return;
        }

        require_once(SEMEROOT.'app/controller/kandidat/profil.php');
        $p = new Profil();
        if(!$p->_checkBeforeIsiData($cam)){
          $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
          redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
          return;
        }

        $this->_setULog($data['sess']->user->id, 12, 'riwayat pendidikan non formal');

        $this->setTitle('Riwayat Pendidikan Non Formal: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/riwayat/informal_modal',$data);
        $this->putThemeContent('kandidat/riwayat/informal',$data);
        $this->putJsContent('kandidat/riwayat/informal_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }
    public function pekerjaan()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_riwayat_pekerjaan';

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        $data['bujhm'] = $this->bujhm->getByUserId($data['bum']->id);

        $cam = $this->cam->getByUserId($data['sess']->user->id);
        if(!isset($cam->id)){
          redir(base_url('joblist'));
          return;
        }

        if(isset($data['bum']->is_jobhistory_exist) && $data['bum']->is_jobhistory_exist == 0){
          $capm = $this->capm->check($data['bum']->id,$cam->id,'Riwayat Pekerjaan','data');
          if(isset($capm->id)){
            if($capm->from_val == 0 || $capm->to_val == 0 || $capm->is_done == 0){
              $this->capm->update($capm->id,array('from_val'=>1,'to_val'=>1,'is_done'=>1));
            }
          }else{
            $di_capm = array();
            $di_capm['b_user_id'] = $data['bum']->id;
            $di_capm['c_apply_id'] = $cam->id;
            $di_capm['utype'] = 'data';
            $di_capm['cdate'] = 'NOW()';
            $di_capm['ldate'] = 'NOW()';
            $di_capm['stepkey'] = 'Riwayat Pekerjaan';
            $di_capm['from_val'] = 1;
            $di_capm['to_val'] = 1;
            $di_capm['is_done'] = 1;
            $this->capm->set($di_capm);
          }
        }

        require_once(SEMEROOT.'app/controller/kandidat/profil.php');
        $p = new Profil();
        if(!$p->_checkBeforeIsiData($cam)){
          $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
          redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
          return;
        }

        $this->_setULog($data['sess']->user->id, 12, 'riwayat pekerjaan');

        $this->setTitle('Data Riwayat Pekerjaan '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/riwayat/pekerjaan_modal',$data);
        $this->putThemeContent('kandidat/riwayat/pekerjaan',$data);
        $this->putJsContent('kandidat/riwayat/pekerjaan_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }

    public function keluarga($reset='')
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_riwayat_keluarga';

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        if($reset == 'reset'){
          $data['bum']->status_kawin = '';
          $data['bum']->saudara_ke = '';
          $data['bum']->saudara_dari = '';
          $data['bum']->jml_anak = '';
          $data['sess']->user->status_kawin = '';
          $data['sess']->user->saudara_ke = '';
          $data['sess']->user->saudara_dari = '';
          $data['sess']->user->jml_anak = '';
          $this->setKey($data['sess']);
          redir(base_url('kandidat/riwayat/keluarga'));
          return;
        }

        $data['orangtua'] = array();
        $data['orangtua']['ayah'] = new stdClass();
        $data['orangtua']['ibu'] = new stdClass();

        $data['keluarga'] = array();
        $data['keluarga']['istri'] = new stdClass();
        $data['keluarga']['suami'] = new stdClass();
        $data['anak'] = array();
        for($i=0;$i<$data['bum']->jml_anak;$i++){
          $data['anak'][$i] = new stdClass();
        }

        $data['saudara'] = array();
        for($i=0;$i<$data['bum']->saudara_dari;$i++){
          $data['saudara'][$i] = new stdClass();
        }

        $bukgm = $this->bukgm->getByUserId($data['bum']->id);
        $saudara_counter = 0;
        $anak_counter = 0;
        foreach($bukgm as $b){
          if($b->utype == 'istri'){
            $data['keluarga']['istri'] = $b;
          }elseif($b->utype == 'suami'){
            $data['keluarga']['suami'] = $b;
          }elseif($b->utype == 'anak'){
            $data['keluarga']['anak'][$anak_counter] = $b;
            $anak_counter++;
          }elseif($b->utype == 'ayah'){
            $data['orangtua']['ayah'] = $b;
          }elseif($b->utype == 'ibu'){
            $data['orangtua']['ibu'] = $b;
          }elseif($b->utype == 'saudara'){
            $data['saudara'][$saudara_counter] = $b;
            $saudara_counter++;
          }
        }
        if(!empty($data['bum']->jk)){
          $data['pasangan'] = $data['keluarga']['istri'];
        }else{
          $data['pasangan'] = $data['keluarga']['suami'];
        }
        $bdate = new DateTime($data['sess']->user->bdate);
        $cdate = new DateTime('now');
        $data['sess']->user->usia = $bdate->diff($cdate)->y;

        $cam = $this->cam->getByUserId($data['sess']->user->id);
        if(!isset($cam->id)){
          redir(base_url('joblist'));
          return;
        }

        require_once(SEMEROOT.'app/controller/kandidat/profil.php');
        $p = new Profil();
        if(!$p->_checkBeforeIsiData($cam)){
          $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
          redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
          return;
        }

        $this->_setULog($data['sess']->user->id, 12, 'keluarga');

        $this->setTitle('Data Keluarga '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/riwayat/keluarga_modal',$data);
        $this->putThemeContent('kandidat/riwayat/keluarga',$data);
        $this->putJsContent('kandidat/riwayat/keluarga_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }
    public function organisasi()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_riwayat_organisasi';

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        $data['bujhm'] = $this->bujhm->getByUserId($data['bum']->id);

        $cam = $this->cam->getByUserId($data['sess']->user->id);
        if(!isset($cam->id)){
          redir(base_url('joblist'));
          return;
        }

        require_once(SEMEROOT.'app/controller/kandidat/profil.php');
        $p = new Profil();
        if(!$p->_checkBeforeIsiData($cam)){
          $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
          redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
          return;
        }

        $this->_setULog($data['sess']->user->id, 12, 'riwayat organisasi');

        $this->setTitle('Data Riwayat Organisasi '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/riwayat/organisasi_modal',$data);
        $this->putThemeContent('kandidat/riwayat/organisasi',$data);
        $this->putJsContent('kandidat/riwayat/organisasi_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }
}
