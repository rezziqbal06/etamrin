<?php
class Soal extends JI_Controller {

	public function __construct(){
    parent::__construct();
		$this->setTheme('admin');
		$this->lib("seme_purifier");
		$this->load('admin/a_banksoal_model','absm');
		$this->load('admin/a_jabatan_model','ajm');
		$this->load('admin/b_lowongan_model','blm');
		$this->load('admin/b_user_model','bum');
    $this->load('admin/b_soal_model','bsm');
		$this->load('admin/b_soal_pilihan_model','bspm');
		$this->load('admin/c_apply_model','cam');
		$this->load('admin/c_apply_tes_model','catm');
	}
  public function index(){

	}
  public function cs($cam_id){
		$data['catm'] = $this->catm->rekapCS($cam_id);
		$data['cam'] = $this->cam->getById($cam_id);
		$data['bum'] = $this->bum->getById($data['cam']->b_user_id);
		$data['blm'] = $this->blm->getById($data['cam']->b_lowongan_id);
		$data['ajm'] = $this->ajm->getById($data['blm']->a_jabatan_id);

		$this->setTitle('Rekap Soal CS untuk '.$data['bum']->fnama.' #'.$data['bum']->kode.'');
		// $this->debug($data);
		$this->putThemeContent("rekap/soal/cs/home_modal",$data);
		$this->putThemeContent("rekap/soal/cs/home",$data);
		$this->putJsReady("rekap/soal/cs/home_bottom",$data);
		$this->loadLayout('soal',$data);
		$this->render();
	}
  public function iq($cam_id){
		$data['catm'] = $this->catm->rekapIQ($cam_id);

		$data['cam'] = $this->cam->getById($cam_id);
		$data['bum'] = $this->bum->getById($data['cam']->b_user_id);
		$data['blm'] = $this->blm->getById($data['cam']->b_lowongan_id);
		$data['ajm'] = $this->ajm->getById($data['blm']->a_jabatan_id);

		$this->setTitle('Rekap Soal IQ untuk '.$data['bum']->fnama.' #'.$data['bum']->kode.'');

		$sids = array();
		$data['soal'] = array();
		if(isset($data['catm'][0]->a_banksoal_id)){
			$bsm = $this->bsm->getByBankSoalId($data['catm'][0]->a_banksoal_id);
	    foreach($bsm as $s){
	      $s->id = (int) $s->id;
	      $sids[] = $s->id;
	      $data['soal'][$s->id] = $s;
				$data['soal'][$s->id]->is_benar = 0;
	      $data['soal'][$s->id]->pilihans = array();
	    }
	    unset($bsm,$s);
		}


    if(count($sids)){
      $bspm = $this->bspm->getBySoalIds($sids);
      foreach($bspm as $p){
        $sid = (int) $p->b_soal_id;
        if(isset($data['soal'][$sid]->pilihans)){
          $data['soal'][$sid]->pilihans[$p->id] = $p;
        }
      }
      unset($bspm, $p, $sids);
    }

		$data['hasil'] = new stdClass();
		$data['hasil']->b = 0;
		$data['hasil']->s = 0;
		$data['hasil']->iq = 0;
		foreach($data['catm'] as $catm){
			$sid = (int) $catm->b_soal_id;
			$bspid = (int) $catm->b_soal_pilihan_id;
			if(isset($data['soal'][$sid]->pilihans[$bspid])){
				if(!empty($data['soal'][$sid]->pilihans[$bspid]->bobot)){
					$data['soal'][$sid]->is_benar = 1;
					$data['hasil']->b++;
				}else{
					$data['hasil']->s++;
				}
			}
		}

		$rtiq = $this->_resultTestIQ($data['hasil']->b);
		if(isset($rtiq->iq)){
			$data['hasil']->iq = (int) $rtiq->iq;
		}

		$this->putThemeContent("rekap/soal/iq/home_modal",$data);
		$this->putThemeContent("rekap/soal/iq/home",$data);
		$this->putJsReady("rekap/soal/iq/home_bottom",$data);
		$this->loadLayout('soal',$data);
		$this->render();
	}
  public function kepribadian($cam_id){
		$data['catm'] = $this->catm->rekapKepribadian($cam_id);
		$data['cam'] = $this->cam->getById($cam_id);
		$data['bum'] = $this->bum->getById($data['cam']->b_user_id);
		$data['blm'] = $this->blm->getById($data['cam']->b_lowongan_id);
		$data['ajm'] = $this->ajm->getById($data['blm']->a_jabatan_id);


		$this->setTitle('Rekap Soal Kepribadian untuk '.$data['bum']->fnama.' #'.$data['bum']->kode.'');
		$data['soal'] = array();
		if(isset($data['catm'][0])){
			$bsm = $this->bsm->getByBankSoalId($data['catm'][0]->a_banksoal_id);
	    $sids = array();
	    foreach($bsm as $s){
	      $s->id = (int) $s->id;
	      $sids[] = $s->id;
	      $data['soal'][$s->id] = $s;
	      $data['soal'][$s->id]->pilihans = array();
	    }
	    unset($bsm,$s);


	    if(count($sids)){
	      $bspm = $this->bspm->getBySoalIds($sids);
	      foreach($bspm as $p){
	        $sid = (int) $p->b_soal_id;
	        if(isset($data['soal'][$sid]->pilihans)){
	          $data['soal'][$sid]->pilihans[] = $p;
	        }
	      }
	      unset($bspm, $p, $sids);
	    }
		}

		$this->putThemeContent("rekap/soal/kepribadian/home_modal",$data);
		$this->putThemeContent("rekap/soal/kepribadian/home",$data);
		$this->putJsReady("rekap/soal/kepribadian/home_bottom",$data);
		$this->loadLayout('soal',$data);
		$this->render();
	}
}
