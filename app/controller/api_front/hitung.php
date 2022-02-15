<?php
class Hitung extends JI_Controller
{
  public $max_file_size = 2000000;

  public function __construct()
  {
    parent::__construct();
		$this->load('admin/a_discangka_model','adam');
    $this->load('api_front/a_banksoal_model', 'absm');
    $this->load('api_front/b_user_model', 'bum');
    $this->load('api_front/c_apply_capturetes_model', 'bucm');
    $this->load('api_front/c_apply_sessiontes_model', 'bussm');
    $this->load('api_front/c_apply_tes_model', 'catm');
    $this->load('api_front/c_apply_model', 'clm');
  }

  /**
	* Procedures for processing kepribadian soal
	* @param  array  $data  array from data before
	* @return array  $data  array to data after
	*/
	public function _prosesTesKepribadian($data){
		$data['kurang'] = array(
			'Z'=>0,
			'K'=>0,
			'S'=>0,
			'B'=>0,
			'N'=>0,
		);
		$data['sangat'] = $data['kurang'];
		$data['change'] = $data['kurang'];
    $data['kodefikasi'] = array();
    if(!isset($data['catm'])){
      return $data;
    }
    if(!is_array($data['catm']) || !isset($data['catm']['kepribadian'])){
      return $data;
    }

    // jika jawaban tidak ada 28 item
    if(count($data['catm']['kepribadian']) != 28){
      return $data;
    }

		foreach($data['catm']['kepribadian'] as $kep){
			if(isset($data['sangat'][strtoupper($kep->bobot_m)])) $data['sangat'][strtoupper($kep->bobot_m)]++;
			if(isset($data['kurang'][strtoupper($kep->bobot_l)])) $data['kurang'][strtoupper($kep->bobot_l)]++;
		}
		foreach($data['sangat'] as $k=>$v){
			if(isset($data['change'][$k]) && isset($data['sangat'][$k]) && isset($data['kurang'][$k]) ){
        $data['change'][$k] = $data['sangat'][$k] - $data['kurang'][$k];
      }

		}
		$s = array();
		$k = array();
		$c = array();

		//hitung data grafik sangat
		$i=0;
		foreach($data['sangat'] as $chg){
			$s[] = array($i, $chg);
			$i++;
		}
		$d=0;
		if(isset($s[$d][1])){
			if($s[$d][1]>10 && $s[$d][1]<=27){
				$s[$d][1] = 7;
			}else if($s[$d][1]>7 && $s[$d][1]<=10){
				$s[$d][1] = 6;
			}else if($s[$d][1]>5 && $s[$d][1]<=7){
				$s[$d][1] = 5;
			}else if($s[$d][1]>3 && $s[$d][1]<=5){
				$s[$d][1] = 4;
			}else if($s[$d][1]>1 && $s[$d][1]<=3){
				$s[$d][1] = 3;
			}else if($s[$d][1]>0 && $s[$d][1]<=1){
				$s[$d][1] = 2;
			}else{
				$s[$d][1] = 1;
			}
		}

		$d=1;
		if(isset($s[$d][1])){
			if($s[$d][1]>10 && $s[$d][1]<=28){
				$s[$d][1] = 7;
			}else if($s[$d][1]>8 && $s[$d][1]<=10){
				$s[$d][1] = 6;
			}else if($s[$d][1]>7 && $s[$d][1]<=8){
				$s[$d][1] = 5;
			}else if($s[$d][1]>5 && $s[$d][1]<=7){
				$s[$d][1] = 4;
			}else if($s[$d][1]>3 && $s[$d][1]<=5){
				$s[$d][1] = 3;
			}else if($s[$d][1]>2 && $s[$d][1]<=3){
				$s[$d][1] = 2;
			}else{
				$s[$d][1] = 1;
			}
		}

		$d=2;
		if(isset($s[$d][1])){
			if($s[$d][1]>12 && $s[$d][1]<=26){
				$s[$d][1] = 7;
			}else if($s[$d][1]>10 && $s[$d][1]<=12){
				$s[$d][1] = 6;
			}else if($s[$d][1]>8 && $s[$d][1]<=10){
				$s[$d][1] = 5;
			}else if($s[$d][1]>6 && $s[$d][1]<=8){
				$s[$d][1] = 4;
			}else if($s[$d][1]>4 && $s[$d][1]<=6){
				$s[$d][1] = 3;
			}else if($s[$d][1]>3 && $s[$d][1]<=4){
				$s[$d][1] = 2;
			}else{
				$s[$d][1] = 1;
			}
		}

		$d=3;
		if(isset($s[$d][1])){
			if($s[$d][1]>10 && $s[$d][1]<=24){
				$s[$d][1] = 7;
			}else if($s[$d][1]>7 && $s[$d][1]<=10){
				$s[$d][1] = 6;
			}else if($s[$d][1]>6 && $s[$d][1]<=7){
				$s[$d][1] = 5;
			}else if($s[$d][1]>4 && $s[$d][1]<=6){
				$s[$d][1] = 4;
			}else if($s[$d][1]>3 && $s[$d][1]<=4){
				$s[$d][1] = 3;
			}else if($s[$d][1]>2 && $s[$d][1]<=3){
				$s[$d][1] = 2;
			}else{
				$s[$d][1] = 1;
			}
		}

		$i=0;
		foreach($data['kurang'] as $chg){
			$k[] = array($i, $chg);
			$i++;
		}

		//hitung data grafik kurang
		$d=0;
		if(isset($k[$d][1])){
			if($k[$d][1]>15 && $k[$d][1]<=27){
				$k[$d][1] = 1;
			}else if($k[$d][1]>13 && $k[$d][1]<=15){
				$k[$d][1] = 2;
			}else if($k[$d][1]>10 && $k[$d][1]<=13){
				$k[$d][1] = 3;
			}else if($k[$d][1]>9 && $k[$d][1]<=10){
				$k[$d][1] = 4;
			}else if($k[$d][1]>7 && $k[$d][1]<=9){
				$k[$d][1] = 5;
			}else if($k[$d][1]>4 && $k[$d][1]<=7){
				$k[$d][1] = 6;
			}else{
				$k[$d][1] = 7;
			}
		}

		$d=1;
		if(isset($k[$d][1])){
			if($k[$d][1]>8 && $k[$d][1]<=26){
				$k[$d][1] = 1;
			}else if($k[$d][1]>7 && $k[$d][1]<=8){
				$k[$d][1] = 2;
			}else if($k[$d][1]>5 && $k[$d][1]<=7){
				$k[$d][1] = 3;
			}else if($k[$d][1]>4 && $k[$d][1]<=5){
				$k[$d][1] = 4;
			}else if($k[$d][1]>3 && $k[$d][1]<=4){
				$k[$d][1] = 5;
			}else if($k[$d][1]>2 && $k[$d][1]<=3){
				$k[$d][1] = 6;
			}else{
				$k[$d][1] = 7;
			}
		}

		$d=2;
		if(isset($k[$d][1])){
			if($k[$d][1]>8 && $k[$d][1]<=27){
				$k[$d][1] = 1;
			}else if($k[$d][1]>7 && $k[$d][1]<=8){
				$k[$d][1] = 2;
			}else if($k[$d][1]>4 && $k[$d][1]<=7){
				$k[$d][1] = 3;
			}else if($k[$d][1]>3 && $k[$d][1]<=4){
				$k[$d][1] = 4;
			}else if($k[$d][1]>2 && $k[$d][1]<=3){
				$k[$d][1] = 5;
			}else if($k[$d][1]>1 && $k[$d][1]<=2){
				$k[$d][1] = 6;
			}else{
				$k[$d][1] = 7;
			}
		}

		$d=3;
		if(isset($k[$d][1])){
			if($k[$d][1]>11 && $k[$d][1]<=27){
				$k[$d][1] = 1;
			}else if($k[$d][1]>9 && $k[$d][1]<=11){
				$k[$d][1] = 2;
			}else if($k[$d][1]>8 && $k[$d][1]<=9){
				$k[$d][1] = 3;
			}else if($k[$d][1]>6 && $k[$d][1]<=8){
				$k[$d][1] = 4;
			}else if($k[$d][1]>5 && $k[$d][1]<=6){
				$k[$d][1] = 5;
			}else if($k[$d][1]>3 && $k[$d][1]<=5){
				$k[$d][1] = 6;
			}else{
				$k[$d][1] = 7;
			}
		}

		$i=0;
		foreach($data['change'] as $chg){
			$c[] = array($i, $chg);
			$i++;
		}

		//hitung data grafik kurang
		$d=0;
		if(isset($c[$d][1])){
			if($c[$d][1]>5 && $c[$d][1]<=27){
				$c[$d][1] = 7;
			}else if($c[$d][1]>-1 && $c[$d][1]<=5){
				$c[$d][1] = 6;
			}else if($c[$d][1]>-5 && $c[$d][1]<=-1){
				$c[$d][1] = 5;
			}else if($c[$d][1]>-8 && $c[$d][1]<=-5){
				$c[$d][1] = 4;
			}else if($c[$d][1]>-12 && $c[$d][1]<=-8){
				$c[$d][1] = 3;
			}else if($c[$d][1]>-15 && $c[$d][1]<=-12){
				$c[$d][1] = 2;
			}else{
				$c[$d][1] = 1;
			}
		}

		$d=1;
		if(isset($c[$d][1])){
			if($c[$d][1]>7 && $c[$d][1]<=28){
				$c[$d][1] = 7;
			}else if($c[$d][1]>5 && $c[$d][1]<=7){
				$c[$d][1] = 6;
			}else if($c[$d][1]>2 && $c[$d][1]<=5){
				$c[$d][1] = 5;
			}else if($c[$d][1]>0 && $c[$d][1]<=2){
				$c[$d][1] = 4;
			}else if($c[$d][1]>-3 && $c[$d][1]<=0){
				$c[$d][1] = 3;
			}else if($c[$d][1]>-6 && $c[$d][1]<=-3){
				$c[$d][1] = 2;
			}else{
				$c[$d][1] = 1;
			}
		}

		$d=2;
		if(isset($c[$d][1])){
			if($c[$d][1]>11 && $c[$d][1]<=26){
				$c[$d][1] = 7;
			}else if($c[$d][1]>8 && $c[$d][1]<=11){
				$c[$d][1] = 6;
			}else if($c[$d][1]>5 && $c[$d][1]<=8){
				$c[$d][1] = 5;
			}else if($c[$d][1]>2 && $c[$d][1]<=5){
				$c[$d][1] = 4;
			}else if($c[$d][1]>-1 && $c[$d][1]<=2){
				$c[$d][1] = 3;
			}else if($c[$d][1]>-5 && $c[$d][1]<=-1){
				$c[$d][1] = 2;
			}else{
				$c[$d][1] = 1;
			}
		}

		$d=3;
		if(isset($c[$d][1])){
			if($c[$d][1]>5 && $c[$d][1]<=24){
				$c[$d][1] = 7;
			}else if($c[$d][1]>2 && $c[$d][1]<=5){
				$c[$d][1] = 6;
			}else if($c[$d][1]>-1 && $c[$d][1]<=2){
				$c[$d][1] = 5;
			}else if($c[$d][1]>-3 && $c[$d][1]<=-1){
				$c[$d][1] = 4;
			}else if($c[$d][1]>-6 && $c[$d][1]<=-3){
				$c[$d][1] = 3;
			}else if($c[$d][1]>-9 && $c[$d][1]<=-6){
				$c[$d][1] = 2;
			}else{
				$c[$d][1] = 1;
			}
		}

		//pop all last array values
		array_pop($s);
		array_pop($k);
		array_pop($c);

		$data['grafik'] = array(
			's'=> $s,
			'k'=> $k,
			'c'=> $c
		);

		//disc angka to result conversion
		$adrms = array();

		$adrms[0] = '';
		foreach($s as $adr) $adrms[0] .= $adr[1];

		$adrms[1] = '';
		foreach($k as $adr) $adrms[1] .= $adr[1];

		$adrms[2] = '';
		foreach($c as $adr) $adrms[2] .= $adr[1];

		$data['disc_result'] = array();
		foreach($adrms as $adrm){
			$data['disc_result'][$adrm] = new stdClass();
		}
		$adam = $this->adam->getByIds($adrms);
		foreach($adam as $ada){
			if(isset($data['disc_result'][$ada->kodefikasi])){
				$data['disc_result'][$ada->kodefikasi] = $ada;
			}
		}
		$data['kodefikasi'] = $adrms;
		unset($adam,$ada,$adrms,$adr);
		// $this->debug($data['disc_result']);
		// die();
		// echo json_encode($s);die();
		// $this->debug($data['grafik']);
		// $this->debug($data['sangat']);
		// $this->debug($data['kurang']);
		// $this->debug($data['change']);
		// $this->debug($data['catm']['kepribadian']);
		// die();

		return $data;
	}

  public function index()
  {
  }

  public function jawaban($a_banksoal_id='',$b_user_id=''){
    $d = $this->__init();

    $b_user_id = (int) $b_user_id;
    if($b_user_id<=0) $b_user_id = 0;
    $data['bum'] = $this->bum->getById($b_user_id);
    if(!isset($data['bum']->id)){
      $this->status = 1030;
      $this->message = 'Invalid ID';
      $this->__json_out($data);
      return;
    }

    $data['catm'] = array();
    $a_banksoal_id = (int) $a_banksoal_id;
    if($a_banksoal_id<=0) $a_banksoal_id = 0;
    $catm = $this->catm->getByBankSoalIdUserId($a_banksoal_id,$b_user_id);
    if(count($catm) == 0 || !is_array($catm)){
      $this->status = 1031;
      $this->message = 'Belum pernah tes';
      $this->__json_out($data);
      return;
    }
    if(!isset($catm[0]->utype)){
      $this->status = 1032;
      $this->message = 'Belum pernah tes';
      $this->__json_out($data);
      return;
    }
    $utype = strtolower($catm[0]->utype);

    $data['catm'][$utype] = array();
    foreach($catm as $cat){
      $data['catm'][$utype][] = $cat;
    }
    switch($utype){
      case 'kepribadian':
        $data = $this->_prosesTesKepribadian($data);
        $this->__json_out($data);
        break;
      case 'cs':
        echo 'underconst';
        break;
      default:

    }

  }
}
