<?php
/**
 * Kandidat
 */
class Profil extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("front/b_user_model", "bum");
        $this->load("front/b_user_pendidikan_model", "bupdm");
        $this->load("front/c_apply_model", "cam");
        $this->load('front/c_apply_progress_model','capm');
        $this->current_menu = 'kandidat_profile';
        $this->currentStepRekrutmen = 2;
    }
    public function _checkBeforeIsiData($cam){
      $this->load('front/c_apply_sessiontes_model','castm');
      $castm = $this->castm->sudahTesCS($cam->id);
      if(isset($castm->id)){
        return true;
      }else{
        return false;
      }
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
          redir(base_url('joblist'));
          return;
        }
        // $this->validasiStatusApply($data['sess']->user,1,3,1);

        $this->_setULog($data['sess']->user->id, 12, 'pribadi');

        $this->putJsFooter('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js');
        $this->loadCss('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min','before');

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        $data['bum'] = $data['sess']->user;
        unset(
          $data['bum']->password,
          $data['bum']->api_web_token,
          $data['bum']->api_mobile_token,
          $data['bum']->api_reg_token
        );
        $data['bum']->alamat_generator = implode(', ',array(
          $data['bum']->desakel,
          $data['bum']->kecamatan,
          $data['bum']->kabkota,
          $data['bum']->provinsi,
          $data['bum']->negara
        ));
        $data['bum']->domisili_alamat_generator = implode(', ',array(
          $data['bum']->domisili_desakel,
          $data['bum']->domisili_kecamatan,
          $data['bum']->domisili_kabkota,
          $data['bum']->domisili_provinsi,
          $data['bum']->domisili_negara
        ));

        $data['bum']->punya_sim = '';
        if(!empty($data['bum']->is_sim_c)) {
          $data['bum']->punya_sim = 'C';
        }
        if(!empty($data['bum']->is_sim_b)) {
          $data['bum']->punya_sim = 'B';
        }
        if(!empty($data['bum']->is_sim_a)) {
          $data['bum']->punya_sim = 'A';
        }
        $data['highedu'] = array();
        $data['highedu']['S3'] = 0;
        $data['highedu']['S2'] = 0;
        $data['highedu']['S1'] = 0;
        $data['highedu']['D4'] = 0;
        $data['highedu']['D3'] = 0;
        $data['highedu']['D2'] = 0;
        $data['highedu']['D1'] = 0;
        $data['highedu']['SMK'] = 0;
        $data['highedu']['SMA'] = 0;
        $data['highedu']['SMP'] = 0;
        $data['bupdm'] = $this->bupdm->getByUserId($data['bum']->id);
        if(count($data['bupdm'])){
          foreach($data['bupdm'] as $bup){
            switch(strtolower($bup->jenjang)){
              case 's3':
                $data['highedu']['S3']++;
                break;
              case 's2':
                $data['highedu']['S2']++;
                break;
              case 's1':
                $data['highedu']['S1']++;
                break;
              case 'd4':
                $data['highedu']['D4']++;
                break;
              case 'd3':
                $data['highedu']['D3']++;
                break;
              case 'sma':
                $data['highedu']['SMA']++;
                break;
              case 'smk':
                $data['highedu']['SMK']++;
                break;
              default:
                $data['highedu']['SMP']++;
            }
          }

          foreach($data['highedu'] as $khe=>$vhe){
            if($khe == 'S3'){
              if($vhe > 0){
                $data['tertinggi'] = 'S3';
                break;
              }
            }
            if($khe == 'S2'){
              if($vhe > 0){
                $data['tertinggi'] = 'S2';
                break;
              }
            }
            if($khe == 'S1'){
              if($vhe > 0){
                $data['tertinggi'] = 'S1';
                break;
              }
            }
            if($khe == 'D4'){
              if($vhe > 0){
                $data['tertinggi'] = 'D4';
                break;
              }
            }
            if($khe == 'D3'){
              if($vhe > 0){
                $data['tertinggi'] = 'D3';
                break;
              }
            }
            if($khe == 'SMK'){
              if($vhe > 0){
                $data['tertinggi'] = 'SMK';
                break;
              }
            }
            if($khe == 'SMA'){
              if($vhe > 0){
                $data['tertinggi'] = 'SMA';
                break;
              }
            }
          }
        }

        if(!$this->_checkBeforeIsiData($cam)){
          $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
          redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
          return;
        }

        $this->setTitle('Profil: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/profil/home_modal',$data);
        $this->putThemeContent('kandidat/profil/home',$data);
        $this->putJsContent('kandidat/profil/home_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }

    public function edit()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }

        $data['bum'] = $data['sess']->user;
        unset(
          $data['bum']->password,
          $data['bum']->api_web_token,
          $data['bum']->api_mobile_token,
          $data['bum']->api_reg_token
        );

        $this->setTitle('Edit Profil: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/profil/password_modal',$data);
        $this->putThemeContent('kandidat/profil/password',$data);
        $this->putJsContent('kandidat/profil/password_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }

    public function ganti_password()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        // $this->debug($data['sess']->user);
        // die();
        $data['bum'] = $data['sess']->user;
        $this->setTitle('Ganti Password: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/profil/password_modal',$data);
        $this->putThemeContent('kandidat/profil/password',$data);
        $this->putJsContent('kandidat/profil/password_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }
}
