<?php
/**
 * Kandidat
 */
class Skill extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("front/b_user_model", "bum");
        $this->load("front/b_user_jobhistory_model", "bujhm");
        $this->load("front/c_apply_model", "cam");
        $this->current_menu = 'kandidat_riwayat_formal';
    }
    public function index()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        redir(base_url('kandidat/dashboard'));
        return;

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        $this->setTitle('Riwayat Pendidikan: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/skill/home_modal',$data);
        $this->putThemeContent('kandidat/skill/home',$data);
        $this->putJsContent('kandidat/skill/home_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }

    public function bahasa()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_skill_bahasa';

        $cam = $this->cam->getByUserId($data['sess']->user->id);
        if(!isset($cam->id)){
          redir(base_url('joblist'));
          return;
        }

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        require_once(SEMEROOT.'app/controller/kandidat/profil.php');
        $p = new Profil();
        if(!$p->_checkBeforeIsiData($cam)){
          $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
          redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
          return;
        }

        $this->setTitle('Penguasaan Bahasa Asing: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/skill/bahasa/home_modal',$data);
        $this->putThemeContent('kandidat/skill/bahasa/home',$data);
        $this->putJsContent('kandidat/skill/bahasa/home_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }

    public function komputer()
    {
        $data = $this->__init();
        if(!$this->user_login){
            redir(base_url('login'));
            return;
        }
        $this->current_menu = 'kandidat_skill_komputer';

        $cam = $this->cam->getByUserId($data['sess']->user->id);
        if(!isset($cam->id)){
          redir(base_url('joblist'));
          return;
        }

        $data['bum'] = $this->bum->getById($data['sess']->user->id);
        $this->requiredVerifiedEmail($data['bum']);

        require_once(SEMEROOT.'app/controller/kandidat/profil.php');
        $p = new Profil();
        if(!$p->_checkBeforeIsiData($cam)){
          $this->__flash('Silakan ikut test awal terlebih dahulu sebelum dapat mengisi data');
          redir(base_url('kandidat/dashboard/?ikut_tes_awal_dulu'));
          return;
        }

        $this->setTitle('Penguasaan Kemampuan Komputer: '.$data['sess']->user->fnama.' '.$this->config->semevar->site_suffix);

        $this->putThemeContent('kandidat/skill/komputer/home_modal',$data);
        $this->putThemeContent('kandidat/skill/komputer/home',$data);
        $this->putJsContent('kandidat/skill/komputer/home_bottom',$data);
        $this->loadLayout('col-2-left',$data);
        $this->render();
    }
}
