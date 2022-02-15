<?php
class Home extends JI_Controller {
	public function __construct(){
    parent::__construct();
		$this->load("api_admin/c_apply_model",'clm');
		$this->load("api_admin/b_lowongan_model", 'blm');
		$this->load("api_admin/b_lowongan_view_model",'blvm');
		$this->load("api_admin/c_apply_model",'cam');
		$this->load("api_admin/c_interview_model",'cim');
	}
	public function index(){
		$d = $this->__init();

		$data = array();
		$data['mostviewed'] = array();
		$data['mostreferred'] = array();
		$data['mostrecent'] = array();
		if(!$this->admin_login){
			$this->status = '404';
			header("HTTP/1.0 404 Not Found");
			$this->__json_out($data);
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$data['mostviewed'] = $this->blvm->mostViewed();
		$data['mostreferred'] = $this->blvm->mostReferred();
		$data['mostrecent'] = $this->clm->mostRecent();
		foreach($data['mostreferred'] as &$mr){
			if(empty($mr->referrer)) $mr->referrer='Direct / Langsung';
		}
		$this->__json_out($data);
	}

	public function grafik(){
		$d = $this->__init();

		$data = array();
		if(!$this->admin_login){
			$this->status = '404';
			header("HTTP/1.0 404 Not Found");
			$this->__json_out($data);
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$begin = new DateTime('-30 days');
		$end = new DateTime('today');
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		foreach ($period as $dt) {
			$data[$dt->format("d M")] = new stdClass();
			$data[$dt->format("d M")]->viewcount = 0;
		}

		foreach($this->blvm->grafik() as $gr){
			$k = date('d M', strtotime($gr->cdate));
			if(isset($data[$k]->viewcount)){
				$data[$k]->viewcount = (int) $gr->viewcount;
			}
		}
		$this->__json_out($data);
	}

	public function statistik_by_lowongan(){
		$b_lowongan_id = (int) $this->input->post('b_lowongan_id','0');
		if(empty($b_lowongan_id)) $b_lowongan_id = '';

		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)!=10){
			$start_date = date('Y-m-d',strtotime('-30 days'));
		}else{
			$start_date = date('Y-m-d',strtotime($start_date));
		}
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)!=10){
			$end_date = date('Y-m-d',strtotime('now'));
		}else{
			$end_date = date('Y-m-d',strtotime($end_date));
		}

		$data = $this->blm->statistik_by_lowongan($b_lowongan_id,$start_date,$end_date);
		foreach($data as &$dt){
			$dt->cdate=$this->__dateIndonesia($dt->cdate, 'tanggal');
			$dt->ldate=$this->__dateIndonesia($dt->ldate, 'tanggal');
			$dt->edate=$this->__dateIndonesia($dt->edate, 'tanggal');
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_by_lowongan_dilihat(){
		$data = array();
		$b_lowongan_ids = $this->input->post('b_lowongan_ids','0');
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)==10) $start_date = date('Y-m-d',strtotime($start_date));
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)==10) $end_date = date('Y-m-d',strtotime($end_date));
		if(is_array($b_lowongan_ids) && count($b_lowongan_ids)) $data = $this->blvm->statistik_by_lowongan($b_lowongan_ids, $start_date, $end_date);

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_by_lowongan_apply(){
		$data = array();
		$b_lowongan_ids = $this->input->post('b_lowongan_ids','0');
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)==10) $start_date = date('Y-m-d',strtotime($start_date));
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)==10) $end_date = date('Y-m-d',strtotime($end_date));
		if(is_array($b_lowongan_ids) && count($b_lowongan_ids)) $data = $this->cam->statistik_by_lowongan_apply($b_lowongan_ids, $start_date, $end_date);

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_by_tes_awal(){
		$data = array();
		$b_lowongan_ids = $this->input->post('b_lowongan_ids','0');
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)==10) $start_date = date('Y-m-d',strtotime($start_date));
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)==10) $end_date = date('Y-m-d',strtotime($end_date));
		if(is_array($b_lowongan_ids) && count($b_lowongan_ids)) $data = $this->cam->statistik_by_tes_awal($b_lowongan_ids, $start_date, $end_date);

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_by_interview_hr(){
		$data = array();
		$b_lowongan_ids = $this->input->post('b_lowongan_ids','0');
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)==10) $start_date = date('Y-m-d',strtotime($start_date));
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)==10) $end_date = date('Y-m-d',strtotime($end_date));
		if(is_array($b_lowongan_ids) && count($b_lowongan_ids)) $data = $this->cam->statistik_by_interview_hr($b_lowongan_ids, $start_date, $end_date);

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_by_interview_user(){
		$data = array();
		$b_lowongan_ids = $this->input->post('b_lowongan_ids','0');
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)==10) $start_date = date('Y-m-d',strtotime($start_date));
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)==10) $end_date = date('Y-m-d',strtotime($end_date));
		if(is_array($b_lowongan_ids) && count($b_lowongan_ids)) $data = $this->cam->statistik_by_interview_user($b_lowongan_ids, $start_date, $end_date);

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_by_lolos(){
		$data = array();
		$b_lowongan_ids = $this->input->post('b_lowongan_ids','0');
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)==10) $start_date = date('Y-m-d',strtotime($start_date));
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)==10) $end_date = date('Y-m-d',strtotime($end_date));

		if(is_array($b_lowongan_ids) && count($b_lowongan_ids)) $data = $this->cam->statistik_by_lolos($b_lowongan_ids, $start_date, $end_date);

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_by_tidak_lolos(){
		$data = array();
		$b_lowongan_ids = $this->input->post('b_lowongan_ids','0');
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)==10) $start_date = date('Y-m-d',strtotime($start_date));
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)==10) $end_date = date('Y-m-d',strtotime($end_date));

		if(is_array($b_lowongan_ids) && count($b_lowongan_ids)) $data = $this->cam->statistik_by_tidak_lolos($b_lowongan_ids, $start_date, $end_date);

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_apply_baru(){
		$a_jabatan_id = (int) $this->input->post('a_jabatan_id','0');
		if(empty($a_jabatan_id)) $a_jabatan_id = '';
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)!=10){
			$start_date = date('Y-m-d',strtotime('-30 days'));
		}else{
			$start_date = date('Y-m-d',strtotime($start_date));
		}
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)!=10){
			$end_date = date('Y-m-d',strtotime('now'));
		}else{
			$end_date = date('Y-m-d',strtotime($end_date));
		}
		$data = array();
		$data['total'] = 0;
		$data['grafik'] = array();
		$begin = new DateTime($start_date);
		$end = new DateTime($end_date);
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		foreach ($period as $dt) {
			$data['grafik'][$dt->format("d M")] = 0;
		}
		foreach($this->cam->statistik_apply_baru($start_date,$end_date) as $dt){
			$key = date("d M", strtotime($dt->cdate));
			if(isset($data['grafik'][$key])){
				$data['grafik'][$key] = (int) $dt->total;
			}
			$data['total'] += $dt->total;
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_seleksi_awal(){
		$a_jabatan_id = (int) $this->input->post('a_jabatan_id','0');
		if(empty($a_jabatan_id)) $a_jabatan_id = '';
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)!=10){
			$start_date = date('Y-m-d',strtotime('-30 days'));
		}else{
			$start_date = date('Y-m-d',strtotime($start_date));
		}
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)!=10){
			$end_date = date('Y-m-d',strtotime('now'));
		}else{
			$end_date = date('Y-m-d',strtotime($end_date));
		}
		$data = array();
		$data['total'] = 0;
		$data['grafik'] = array();
		$begin = new DateTime($start_date);
		$end = new DateTime($end_date);
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		foreach ($period as $dt) {
			$data['grafik'][$dt->format("d M")] = 0;
		}
		foreach($this->cam->statistik_seleksi_awal($start_date,$end_date) as $dt){
			$key = date("d M", strtotime($dt->cdate));
			if(isset($data['grafik'][$key])){
				$data['grafik'][$key] = (int) $dt->total;
			}
			$data['total'] += $dt->total;
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}

	public function statistik_interview(){
		$a_jabatan_id = (int) $this->input->post('a_jabatan_id','0');
		if(empty($a_jabatan_id)) $a_jabatan_id = '';
		$start_date = $this->input->post('start_date','');
		if(strlen($start_date)!=10){
			$start_date = date('Y-m-d',strtotime('-30 days'));
		}else{
			$start_date = date('Y-m-d',strtotime($start_date));
		}
		$end_date = $this->input->post('end_date','');
		if(strlen($end_date)!=10){
			$end_date = date('Y-m-d',strtotime('now'));
		}else{
			$end_date = date('Y-m-d',strtotime($end_date));
		}
		$data = array();
		$data['total'] = 0;
		$data['grafik'] = array();
		$begin = new DateTime($start_date);
		$end = new DateTime($end_date);
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);
		foreach ($period as $dt) {
			$data['grafik'][$dt->format("d M")] = 0;
		}
		foreach($this->cim->statistik_interview_hr($start_date,$end_date) as $dt){
			$key = date("d M", strtotime($dt->cdate));
			if(isset($data['grafik'][$key])){
				$data['grafik'][$key] = (int) $dt->total;
			}
			$data['total'] += $dt->total;
		}

		$this->status = 200;
		$this->message = 'Berhasil';
		$this->__json_out($data);
	}
}
