<?php
/**
 * Kandidat
 */
class Keterangan extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("front/b_user_model", "bum");
        $this->load("front/b_user_jobhistory_model", "bujhm");
        $this->load("front/c_apply_model", "cam");
        $this->current_menu = 'kandidat_keterangan_referensi';
    }

    public function index()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->load("front/a_pertanyaan_model",'apm');
        $this->load("front/b_user_jawaban_model",'bujm');
        $data['pertanyaan'] = $this->apm->getActive();
        $this->current_menu = 'kandidat_keterangan';

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $data['jawabans'] = $this->bujm->getByUserId($data['sess']->user->id);
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

        $this->_setULog($data['sess']->user->id, 12, 'keterangan lainnya');

        $this->setTitle('Keterangan Lainnya '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/keterangan/home_modal',$data);
        $this->putThemeContent('kandidat/keterangan/home',$data);
        $this->putJsContent('kandidat/keterangan/home_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }

    public function kenalan()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_keterangan_kenalan';

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

        $this->_setULog($data['sess']->user->id, 12, 'kenalan di perusahaan');

        $this->setTitle('Kenalan di Perusahaan '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/keterangan/kenalan_modal',$data);
        $this->putThemeContent('kandidat/keterangan/kenalan',$data);
        $this->putJsContent('kandidat/keterangan/kenalan_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }

    public function referensi()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_keterangan_referensi';

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

        $this->_setULog($data['sess']->user->id, 12, 'referensi');

        $this->setTitle('Rekomendasi &amp; Referensi: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/keterangan/referensi_modal',$data);
        $this->putThemeContent('kandidat/keterangan/referensi',$data);
        $this->putJsContent('kandidat/keterangan/referensi_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }
}
