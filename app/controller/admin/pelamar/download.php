<?php
//loading library
$vendorDirPath = (SEMEROOT . 'kero/lib/phpoffice/vendor/');
$vendorDirPath = realpath($vendorDirPath);
require_once $vendorDirPath . '/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Download extends JI_Controller {
	public $forbidden_key;

	public function __construct(){
    parent::__construct();
		$this->setTheme('admin');
		$this->lib("seme_purifier");
		$this->load('api_admin/a_jabatan_model','ajm');
		$this->load('api_admin/b_lowongan_model','blm');
		$this->load('api_admin/b_lowongan_view_model','blvm');
		$this->load('api_admin/b_user_model','bum');
		$this->load('api_admin/b_user_file_model','bufm');
		$this->load('api_admin/b_user_keluarga_model','bukm');
		$this->load('api_admin/b_user_pendidikan_model','bupm');
		$this->load('api_admin/b_user_jobhistory_model','bujhm');
		$this->load('api_admin/b_user_orghistory_model','buohm');
		$this->load('api_admin/b_user_relasi_model','burm');
		$this->load('api_admin/b_user_skill_model','busm');
		$this->load('api_admin/b_user_jawaban_model','bujm');
		$this->load('api_admin/c_apply_model','cam');
		$this->load('api_admin/c_apply_sessiontes_model','castm');
		$this->load("api_admin/c_interview_model",'cim');
		$this->forbidden_key = array('id','apply_statno','is_process','is_failed','is_active','b_user_is_active');
	}
	public function _textCenter(){
		return array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	}
	public function _textBorderBold(){
		return array(
			'font'  => array(
				'bold'  => true
			),
			'borders' => array(
				'outline' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
				)
			),
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
		);
	}
	public function _cellBordered(){
		return array(
			'borders' => array(
				'outline' => array(
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
				)
			)
		);
	}
  public function index(){
		$data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}

    $mindate = $this->input->request('mindate','');
    if(strlen($mindate)!=10) $mindate = '';

    $maxdate = $this->input->request('maxdate','');
    if(strlen($maxdate)!=10) $maxdate = '';

    //create object xls
		$ssheet = new Spreadsheet();

    //create sheet-1 and define columns widht
		$ssheet->setActiveSheetIndex(0);
    $sheet = $ssheet->getActiveSheet();
    $sheet->setTitle('Pelamar');

		$pelamar_ids = $this->input->request('pelamar_ids','');
		if(strlen($pelamar_ids)>1){
			$pelamar_ids = explode(',',$pelamar_ids);
		}else{
			$pelamar_ids = array();
		}

		$keyword = $this->input->request('keyword','');
		if(strlen($keyword)<=2) $keyword = '';

		$filters = $this->input->request('filters','');
		if(strlen($filters)<=2) $filters = '{}';
		$filters = json_decode($filters);
		if(!is_object($filters)) $filters = new stdClass();

		$b_lowongan_id = (int) $this->input->request('b_lowongan_id','0');
		if($b_lowongan_id<=0) $b_lowongan_id = '';

		$pelamar = $this->bum->reportPelamarAll($pelamar_ids,$keyword,$filters,$b_lowongan_id);
		if(!is_array($pelamar) || count($pelamar) == 0){
			echo "empty data";
			die();
		}
		$countPelamarObj=0;
		foreach($pelamar[0] as $k=>$v){
			$countPelamarObj++;
		}

		$pelamar_ids = array();
		foreach($pelamar as $p){
			$pelamar_ids[] = $p->id;
		}
		unset($p);

		$castm = array();
		foreach($this->castm->getByPelamarIds($pelamar_ids) as $c){
			if(!isset($castm[$c->b_user_id])){
				$castm[$c->b_user_id] = new stdClass();
				$castm[$c->b_user_id]->cs = 'Belum Tes';
				$castm[$c->b_user_id]->iq = 'Belum Tes';
				$castm[$c->b_user_id]->kepribadian = 'Belum Tes';
			}
			$hasil_tes = 'Belum Tes';
			if($c->is_done && isset($c->hasil_tes)){
				if($c->utype != 'kepribadian'){
					$c->hasil_tes = json_decode($c->hasil_tes);
					if(is_array($c->hasil_tes) && count($c->hasil_tes)){
						foreach($c->hasil_tes as $ht){
							if($ht->utype == $c->utype){
								$hasil_tes = 'Tidak Lolos';
								if(!empty($ht->lolos2) || !empty($ht->lolos2)){
									$hasil_tes = 'Lolos';
								}
							}
						}
					}
				}else{
					$hasil_tes = 'Sudah Tes';
				}
			}
			$castm[$c->b_user_id]->{$c->utype} = $hasil_tes;
		}

		$cim = array();
		foreach($this->cim->getByPelamarIds($pelamar_ids) as $c){
			if(!isset($cim[$c->b_user_id])){
				$cim[$c->b_user_id] = new stdClass();
				$cim[$c->b_user_id]->hr = 'Belum Interview';
				$cim[$c->b_user_id]->user = 'Belum Interview';
			}
			$hasil_interview = 'Belum Interview';
			switch($c->status_teks){
				case INTERVIEW_SELESAI:
					$hasil_interview = 'Selesai interview';
					break;
				case INTERVIEW_DIJADWALKAN:
					$hasil_interview = 'Interview telah dijadwalkan';
					break;
				case INTERVIEW_DIBATALKAN:
					$hasil_interview = 'Interview dibatalkan';
					break;
				case INTERVIEW_DIRESCHEDULE:
					$hasil_interview = 'Interview telah reschedule oleh User';
					break;
				case INTERVIEW_DIRESCHEDULE_PELAMAR:
					$hasil_interview = 'Pelamar minta reschedule interview';
					break;
				case INTERVIEW_BERMINAT:
					$hasil_interview = 'Interview telah dijadwalkan';
					break;
				case INTERVIEW_TIDAK_BERMINAT:
					$hasil_interview = 'Tidak berminat interview';
					break;
				case INTERVIEW_GANTI_JADWAL:
					$hasil_interview = 'Interview telah di reschedule oleh User';
					break;
				default:
					$hasil_interview = 'Belum Interview';
			}
			$cim[$c->b_user_id]->{$c->utype} = $hasil_interview;
		}

		$colAlpha = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		if($countPelamarObj > count($colAlpha)){
			echo "Outbound data";
			die();
		}

		$rowIdx = 1;

		$colIdx = 0;
		$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, 'No');
		$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_textBorderBold())->getAlignment()->applyFromArray($this->_textCenter());
		$colIdx++;
		foreach($pelamar[0] as $k=>$v){
			if(in_array($k,$this->forbidden_key)) continue;
			$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, strtoupper($k));
			$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_textBorderBold())->getAlignment()->applyFromArray($this->_textCenter());
			$colIdx++;
		}
		unset($k,$v);
		$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, 'Hasil Tes CS');
		$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_textBorderBold())->getAlignment()->applyFromArray($this->_textCenter());
		$colIdx++;
		$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, 'Hasil Tes IQ');
		$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_textBorderBold())->getAlignment()->applyFromArray($this->_textCenter());
		$colIdx++;
		$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, 'Hasil Tes Kepribadian');
		$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_textBorderBold())->getAlignment()->applyFromArray($this->_textCenter());
		$colIdx++;
		$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, 'Interview HR');
		$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_textBorderBold())->getAlignment()->applyFromArray($this->_textCenter());
		$colIdx++;
		$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, 'Interview User');
		$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_textBorderBold())->getAlignment()->applyFromArray($this->_textCenter());
		$colIdx++;
		$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, 'Status');
		$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_textBorderBold())->getAlignment()->applyFromArray($this->_textCenter());
		$colIdx++;


		$nomor = 1;
		$rowIdx++;
		foreach($pelamar as $pel){
			$colIdx = 0;
			$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, $nomor);
			$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_cellBordered());
			$colIdx++;

			foreach($pel as $k=>$v){
				if(in_array($k,$this->forbidden_key)) continue;
				$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, $v);
				$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_cellBordered());
				$colIdx++;
			}

			//tes CS
			$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, (isset($castm[$pel->id]->cs) ? $castm[$pel->id]->cs : 'Belum Tes'));
			$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_cellBordered());
			$colIdx++;

			//tes IQ
			$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, (isset($castm[$pel->id]->iq) ? $castm[$pel->id]->iq : 'Belum Tes'));
			$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_cellBordered());
			$colIdx++;

			//tes Kepribadian
			$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, (isset($castm[$pel->id]->kepribadian) ? $castm[$pel->id]->kepribadian : 'Belum Tes'));
			$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_cellBordered());
			$colIdx++;

			//Interview HR
			$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, (isset($cim[$pel->id]->hr) ? $cim[$pel->id]->hr : 'Belum Interview'));
			$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_cellBordered());
			$colIdx++;

			//Interview User
			$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, (isset($cim[$pel->id]->user) ? $cim[$pel->id]->user : 'Belum Interview'));
			$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_cellBordered());
			$colIdx++;

			// status rekrutment
			if(empty($pel->is_failed) && empty($pel->is_process)){
				$status_rekrutment = 'Lolos';
			}elseif(!empty($pel->is_failed) && empty($pel->is_process)){
				$status_rekrutment = 'Tidak lolos';
			}elseif(empty($pel->is_failed) && !empty($pel->is_process)){
				$status_rekrutment = 'Masih dalam proses';
			}
			$sheet->setCellValue($colAlpha[$colIdx].$rowIdx, $status_rekrutment);
			$sheet->getStyle($colAlpha[$colIdx].$rowIdx)->applyFromArray($this->_cellBordered());
			$colIdx++;

			$nomor++;
			$rowIdx++;
		}
		unset($pelamar,$pel,$p,$k,$v);

		// auto fit columns
		foreach ($sheet->getColumnIterator() as $column) {
    	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

    //save file
		$save_dir = $this->__checkDir(date("Y/m"));
		$save_file = 'daftar-pelamar';
		if ($mindate != $maxdate) {
			$save_file = $save_file . str_replace('-', '', $mindate) . '-' . str_replace('-', '', $maxdate);
		} else {
			$save_file = $save_file . str_replace('-', '', $mindate);
		}
		$save_file = str_replace(' ', '', str_replace('/', '', $save_file));

		$swriter = new Xlsx($ssheet);
		if (file_exists($save_dir . '/' . $save_file . '.xlsx')) unlink($save_dir . '/' . $save_file . '.xlsx');
		$swriter->save($save_dir . '/' . $save_file . '.xlsx');

		$download_path = str_replace(SEMEROOT,'/',$save_dir.'/'.$save_file.'.xlsx');
		$this->__forceDownload($save_dir . '/' . $save_file . '.xlsx');
  }
	public function detail($c_apply_id){
		$data = $this->__init();
		if(!$this->admin_login){
			redir(base_url_admin('login'));
			die();
		}
		$c_apply_id = (int) $c_apply_id;
		if($c_apply_id<=0){
			redir(base_url_admin("?invalid_c_apply_Id"));
			die();
		}
		$cam = $this->cam->getById($c_apply_id);
		if(!isset($cam->id)){
			redir(base_url_admin("?c_apply_id_not_found"));
			die();
		}
		$bum = $this->bum->getById($cam->b_user_id);
		if(!isset($bum->id)){
			redir(base_url_admin("?b_user_id_not_found"));
			die();
		}
		$blm = $this->blm->getById($cam->b_lowongan_id);
		if(!isset($blm->id)){
			redir(base_url_admin("?b_lowongan_id_not_found"));
			die();
		}
		$ajm = $this->ajm->getById($blm->a_jabatan_id);
		if(!isset($ajm->id)){
			redir(base_url_admin("?a_jabatan_id_not_found"));
			die();
		}

    //create object xls
		$ssheet = new Spreadsheet();
		$ssheet->getDefaultStyle()->getFont()->setName('Arial');
		$ssheet->getDefaultStyle()->getFont()->setSize(12);
		$ssheet->getActiveSheet()->getHeaderFooter()->setOddFooter('&C&HGenerated by SBP Recruitment System '.date('Y-m-d H:i:s'));

    //create sheet-1 and define columns widht
		$ssheet->setActiveSheetIndex(0);
    $sheet = $ssheet->getActiveSheet();
    $sheet->setTitle('Ringkasan');

		if(file_exists(SEMEROOT.$bum->foto)){
			$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
			$drawing->setName(''.$bum->fnama);
			$drawing->setDescription('Foto profil '.$bum->fnama);
			$drawing->setPath(SEMEROOT.$bum->foto);
			$drawing->setWidth(360);
			$drawing->setWorksheet($ssheet->getActiveSheet());
		}

    $sheet = $ssheet->getActiveSheet();
		$sheet->getColumnDimension('A')->setAutoSize(false);
		$sheet->getColumnDimension('B')->setAutoSize(false);
		$sheet->getColumnDimension('C')->setAutoSize(false);
		$sheet->getColumnDimension('A')->setWidth(40);
		$sheet->getColumnDimension('B')->setWidth(5);
		$sheet->getColumnDimension('C')->setWidth(70);
		$rowIdx = 1;
		$sheet->getStyle("C$rowIdx")->getFont()->setSize(24);
		$sheet->getStyle("C$rowIdx")->getFont()->setBold(true);
		$sheet->setCellValue("C$rowIdx", $bum->fnama);
		$sheet->getStyle("C$rowIdx", $bum->tentang)->getAlignment()->setWrapText(true);
		$sheet->getRowDimension($rowIdx)->setRowHeight(-1);

		$rowIdx++;
		$sheet->setCellValue("C$rowIdx", $bum->tentang);
		$sheet->getRowDimension($rowIdx)->setRowHeight(-1);
		$sheet->getStyle("C$rowIdx", $bum->tentang)->getAlignment()->setWrapText(true);

		$rowIdx++;
		$rowIdx++;
		$rowIdx++;
		if(strlen($bum->social_linkedin)>1){
			$sheet->setCellValue("C$rowIdx", "linkedin: ".$bum->social_linkedin);
		}
		if(strlen($bum->social_ig)>1){
			$sheet->setCellValue("C$rowIdx", "instagram: ".$bum->social_ig);
		}
		if(strlen($bum->social_fb)>1){
			$sheet->setCellValue("C$rowIdx", "facebook: ".$bum->social_fb);
		}

		// sheet data pribadi
		$sheet = $ssheet->createSheet();
    $sheet->setTitle('Status Apply');
		$rowIdx = 1;
		$sheet->setCellValue("A$rowIdx", 'Kode Kandidat');
		$sheet->setCellValue("B$rowIdx", "'".$bum->kode);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Posisi yg di Apply');
		$sheet->setCellValue("B$rowIdx", $ajm->nama);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Tgl apply');
		$sheet->setCellValue("B$rowIdx", $cam->cdate);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Pendidikan Terakhir');
		$sheet->setCellValue("B$rowIdx", "".$bum->pendidikan_terakhir);

		if(intval($bum->kerja_exp_y)>0){
			$bum->kerja_exp_y = $bum->kerja_exp_y.' Tahun';
		}else{
			$bum->kerja_exp_y = 'Freshgraduate / Belum berpengalaman';
		}
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Pengalaman Kerja');
		$sheet->setCellValue("B$rowIdx", "".$bum->kerja_exp_y.'');

		$f = new DateTime($bum->bdate);
		$t = new DateTime('today');
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Usia');
		$sheet->setCellValue("B$rowIdx", $f->diff($t)->y.' tahun '.$f->diff($t)->m.' bulan');

		$hasil_tes = new stdClass();
		$hasil_tes->cs = 'Belum Tes';
		$hasil_tes->iq = 'Belum Tes';
		$hasil_tes->kepribadian = 'Belum Tes';
		$jht = json_decode($bum->hasil_tes);
		if(is_array($jht) && count($jht)){
			foreach($jht as $ht){
				if($ht->utype == 'cs' && isset($ht->lolos1) && isset($ht->lolos2)){
					if($ht->lolos1 == true || $ht->lolos2 == true){
						$hasil_tes->cs = 'Lolos';
					}else{
						$hasil_tes->cs = 'Tidak Lolos';
					}
				}elseif($ht->utype == 'iq' && isset($ht->lolos1) && isset($ht->lolos2)){
					if($ht->lolos1 == true || $ht->lolos2 == true){
						$hasil_tes->cs = 'Lolos';
					}else{
						$hasil_tes->cs = 'Tidak Lolos';
					}
				}
			}
		}
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Hasil Tes CS');
		$sheet->setCellValue("B$rowIdx", $hasil_tes->cs);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Hasil Tes IQ');
		$sheet->setCellValue("B$rowIdx", $hasil_tes->iq);
		unset($hasil_tes);

		$data = new stdClass();
		$data->ktp = '-';
		$data->cv = '-';
		$data->portofolio = '-';
		$data->ijazah = '-';
		$data->transkrip = '-';
		$data->vaksin = '-';
		$data->sertifikat_komputer = '-';
		$data->sertifikat_bahasa = '-';
		foreach($this->bufm->getByUserId($bum->id) as $dt){
			$data->{$dt->utype} = base_url($dt->src);
		}
		unset($dt);

		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'File KTP');
		$sheet->setCellValue("B$rowIdx", $data->ktp);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'File CV');
		$sheet->setCellValue("B$rowIdx", $data->cv);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'File Portofolio');
		$sheet->setCellValue("B$rowIdx", $data->portofolio);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'File Ijazah');
		$sheet->setCellValue("B$rowIdx", $data->ijazah);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'File Transkrip');
		$sheet->setCellValue("B$rowIdx", $data->transkrip);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'File Vaksin');
		$sheet->setCellValue("B$rowIdx", $data->vaksin);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'File Sertifikat Komputer');
		$sheet->setCellValue("B$rowIdx", $data->sertifikat_komputer);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'File Sertifikat Kemampuan Bahasa Asing');
		$sheet->setCellValue("B$rowIdx", $data->sertifikat_bahasa);


		// auto fit columns
		foreach ($sheet->getColumnIterator() as $column) {
    	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}


		// sheet data pribadi
		$sheet = $ssheet->createSheet();
    $sheet->setTitle('Data Pribadi');
		$rowIdx = 1;

		$rowIdx = 1;
		$sheet->setCellValue("A$rowIdx", 'Nama');
		$sheet->setCellValue("B$rowIdx", $bum->fnama);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Nama Panggilan');
		$sheet->setCellValue("B$rowIdx", $bum->cnama);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Email');
		$sheet->setCellValue("B$rowIdx", $bum->email);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'No HP / WhatsApp');
		$sheet->setCellValue("B$rowIdx", "'".$bum->telp);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Tempat, Tgl Lahir');
		$sheet->setCellValue("B$rowIdx", $bum->tlahir.', '.$bum->bdate);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Agama');
		$sheet->setCellValue("B$rowIdx", $bum->agama);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Hobi');
		$sheet->setCellValue("B$rowIdx", $bum->hobi);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Tinggi Badan');
		$sheet->setCellValue("B$rowIdx", $bum->tinggi_badan.' cm');
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Berat Badan');
		$sheet->setCellValue("B$rowIdx", $bum->berat_badan.' kg');
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Gol. Darah');
		$sheet->setCellValue("B$rowIdx", $bum->gol_darah);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Jenis Kelamin');
		$sheet->setCellValue("B$rowIdx", !empty($bum->jk) ? 'L' : 'P');
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Anak Ke-');
		$sheet->setCellValue("B$rowIdx", $bum->saudara_dari.' dari '.$bum->saudara_ke.' bersaudara/i');

		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Status Pernikahan');
		$sheet->setCellValue("B$rowIdx", $bum->status_kawin);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Jml Anak');
		$sheet->setCellValue("B$rowIdx", $bum->jml_anak.' orang');

		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Pakai Kendaraan');
		$sheet->setCellValue("B$rowIdx", $bum->pakai_kendaraan);

		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Jenis Tempat Tinggal');
		$sheet->setCellValue("B$rowIdx", $bum->jenis_alamat);

		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Alamat');
		$sheet->setCellValue("B$rowIdx", $bum->alamat.' '.$bum->alamat2);
		$rowIdx++;
		$sheet->setCellValue("B$rowIdx", 'Desa/Kel:'.$bum->desakel.', Kec. '.$bum->kecamatan);
		$rowIdx++;
		$sheet->setCellValue("B$rowIdx", $bum->kabkota.', '.$bum->provinsi.' '.$bum->kodepos);

		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'No. KTP');
		$sheet->setCellValue("B$rowIdx", "`".$bum->noktp);
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'Alamat KTP');
		$sheet->setCellValue("B$rowIdx", $bum->domisili_alamat.' '.$bum->domisili_alamat2);
		$rowIdx++;
		$sheet->setCellValue("B$rowIdx", 'Desa/Kel:'.$bum->domisili_desakel.', Kec. '.$bum->domisili_kecamatan);
		$rowIdx++;
		$sheet->setCellValue("B$rowIdx", $bum->domisili_kabkota.', '.$bum->domisili_provinsi.' '.$bum->domisili_kodepos);

		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'No. NPWP');
		$sheet->setCellValue("B$rowIdx", $bum->npwp);

		$jenis_sim = '';
		if(!empty($bum->is_sim_a)){
			$jenis_sim = '(SIM A)';
		}elseif(!empty($bum->is_sim_b)){
			$jenis_sim = '(SIM B)';
		}elseif(!empty($bum->is_sim_c)){
			$jenis_sim = '(SIM C)';
		}
		$rowIdx++;
		$sheet->setCellValue("A$rowIdx", 'No. SIM');
		$sheet->setCellValue("B$rowIdx", $bum->nosim.' '.$jenis_sim.'' );

		// auto fit columns
		foreach ($sheet->getColumnIterator() as $column) {
    	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		// sheet riwayat pekerjaan
		$sheet = $ssheet->createSheet();
		$sheet->setTitle("Riwayat Pekerjaan");
		$rowIdx = 1;

		$sheet
			->setCellValue("A$rowIdx", 'No.')
			->setCellValue("B$rowIdx", 'Nama Perusahaan')
			->setCellValue("C$rowIdx", 'Bidang')
			->setCellValue("D$rowIdx", 'Departemen')
			->setCellValue("E$rowIdx", 'Lokasi')
			->setCellValue("F$rowIdx", 'Jabatan Masuk')
			->setCellValue("G$rowIdx", 'Jabatan Keluar')
			->setCellValue("H$rowIdx", 'Mulai dari')
			->setCellValue("I$rowIdx", 'Sampai dengan')
			->setCellValue("J$rowIdx", 'Alasan Berhenti')
			->setCellValue("K$rowIdx", 'Gaji + Tunjangan')
			->setCellValue("L$rowIdx", 'Jobdes');

		$rowIdx++;
		$data = $this->bujhm->getByUserId($bum->id);
		if(count($data)){
			$nomor = 1;
			foreach($data as $dt){
				$sheet
					->setCellValue("A$rowIdx", $nomor)
					->setCellValue("B$rowIdx", $dt->perusahaan_nama.' ('.$dt->perusahaan_jenis.')')
					->setCellValue("C$rowIdx", $dt->perusahaan_bidang)
					->setCellValue("D$rowIdx", $dt->perusahaan_departemen)
					->setCellValue("E$rowIdx", $dt->penempatan)
					->setCellValue("F$rowIdx", $dt->jabatan)
					->setCellValue("G$rowIdx", $dt->jabatan_akhir)
					->setCellValue("H$rowIdx", $dt->date_start)
					->setCellValue("I$rowIdx", $dt->date_finish)
					->setCellValue("J$rowIdx", $dt->alasan_berhenti)
					->setCellValue("K$rowIdx", $dt->salary)
					->setCellValue("L$rowIdx", $dt->jobdes);
				$nomor++;
				$rowIdx++;
			}
			unset($data,$dt);
		}else{
			$sheet->setCellValue('A' . $rowIdx, 'Belum ada data')->mergeCells('A' . $rowIdx . ':L' . $rowIdx . '');
		}

		// auto fit columns
		foreach ($sheet->getColumnIterator() as $column) {
    	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		// sheet riwayat keluarga
		$sheet = $ssheet->createSheet();
		$sheet->setTitle("Keluarga");
		$rowIdx = 1;

		$sheet
			->setCellValue("A$rowIdx", 'No.')
			->setCellValue("B$rowIdx", 'Nama')
			->setCellValue("C$rowIdx", 'Hubungan')
			->setCellValue("D$rowIdx", 'Jenis Kelamin')
			->setCellValue("E$rowIdx", 'Usia')
			->setCellValue("F$rowIdx", 'Pendidikan Terakhir')
			->setCellValue("G$rowIdx", 'Pekerjaan');

		$rowIdx++;
		$data = $this->bukm->getByUserId($bum->id);
		if(count($data)){
			$nomor = 1;
			foreach($data as $dt){
				$jk = !empty($dt->jk) ? 'L' : 'P';
				if($dt->utype == 'ibu' || $dt->utype == 'istri'){
					$jk = 'P';
				}elseif($dt->utype == 'ayah' || $dt->utype == 'suami'){
					$jk = 'L';
				}
				$sheet
					->setCellValue("A$rowIdx", $nomor)
					->setCellValue("B$rowIdx", $dt->nama)
					->setCellValue("C$rowIdx", $dt->utype)
					->setCellValue("D$rowIdx", $jk)
					->setCellValue("E$rowIdx", $dt->usia.' Tahun')
					->setCellValue("F$rowIdx", $dt->pendidikan)
					->setCellValue("G$rowIdx", $dt->pekerjaan);
				$nomor++;
				$rowIdx++;
			}
			unset($data,$dt);
		}else{
			$sheet->setCellValue('A' . $rowIdx, 'Belum ada data')->mergeCells('A' . $rowIdx . ':G' . $rowIdx . '');
		}

		// auto fit columns
		foreach ($sheet->getColumnIterator() as $column) {
    	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		// sheet riwayat pekerjaan
		$sheet = $ssheet->createSheet();
		$sheet->setTitle("Pendidikan");
		$rowIdx = 1;

		$sheet
			->setCellValue("A$rowIdx", 'No.')
			->setCellValue("B$rowIdx", 'Jenis')
			->setCellValue("C$rowIdx", 'Nama Sekolah / Universitas / Instansi')
			->setCellValue("D$rowIdx", 'Jenjang')
			->setCellValue("E$rowIdx", 'Lokasi')
			->setCellValue("F$rowIdx", 'Jurusan')
			->setCellValue("G$rowIdx", 'Nilai / IPK')
			->setCellValue("H$rowIdx", 'Mulai dari Tahun')
			->setCellValue("I$rowIdx", 'Sampai dengan Tahun')
			->setCellValue("J$rowIdx", 'Keterangan')
			->setCellValue("K$rowIdx", 'Sumber Dana');

		$rowIdx++;
		$data = $this->bupm->getByUserId($bum->id);
		if(count($data)){
			$nomor = 1;
			foreach($data as $dt){
				$sheet
					->setCellValue("A$rowIdx", $nomor)
					->setCellValue("B$rowIdx", $dt->utype)
					->setCellValue("C$rowIdx", $dt->nama)
					->setCellValue("D$rowIdx", $dt->jenjang)
					->setCellValue("E$rowIdx", $dt->lokasi)
					->setCellValue("F$rowIdx", $dt->jurusan)
					->setCellValue("G$rowIdx", $dt->nilai)
					->setCellValue("H$rowIdx", $dt->tahun_mulai)
					->setCellValue("I$rowIdx", $dt->tahun_selesai)
					->setCellValue("J$rowIdx", $dt->keterangan)
					->setCellValue("K$rowIdx", $dt->sumber_dana);
				$nomor++;
				$rowIdx++;
			}
			unset($data,$dt);
		}else{
			$sheet->setCellValue('A' . $rowIdx, 'Belum ada data')->mergeCells('A' . $rowIdx . ':K' . $rowIdx . '');
		}

		// auto fit columns
		foreach ($sheet->getColumnIterator() as $column) {
    	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		// sheet data pribadi
		$sheet = $ssheet->createSheet();
    $sheet->setTitle('Lainnya');

		$rowIdx = 1;
		$data = $this->bujm->getByUserId($bum->id);
		if(count($data)){
			foreach($data as $dt){
				$sheet->setCellValue("A$rowIdx", 'T: '.strip_tags($dt->pertanyaan));
				$sheet->getStyle("A$rowIdx")->getFont()->setBold(true);
				$rowIdx++;
				$sheet->setCellValue("A$rowIdx", 'J: '.$dt->jawaban);
				$rowIdx++;
			}
		}else{
			$sheet->setCellValue("A$rowIdx", 'Belum ada data');
		}

		// auto fit columns
		foreach ($sheet->getColumnIterator() as $column) {
    	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		//back to first sheet
		$ssheet->setActiveSheetIndex(0);

    //save file
		$save_dir = $this->__checkDir(date("Y/m"));
		$save_file = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ','-',$bum->fnama)).'-'.$bum->kode);
		$save_file = str_replace(' ', '', str_replace('/', '', $save_file));

		$swriter = new Xlsx($ssheet);
		if (file_exists($save_dir . '/' . $save_file . '.xlsx')) unlink($save_dir . '/' . $save_file . '.xlsx');
		$swriter->save($save_dir . '/' . $save_file . '.xlsx');

		$download_path = str_replace(SEMEROOT,'/',$save_dir.'/'.$save_file.'.xlsx');
		$this->__forceDownload($save_dir . '/' . $save_file . '.xlsx');
	}

	public function report_lowongan_xls(){
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

		$blm = array();
		$blm_ids = array();
		foreach($this->blm->statistik_by_lowongan($b_lowongan_id,$start_date,$end_date) as $dt){
			$dt->cdate=$this->__dateIndonesia($dt->cdate, 'tanggal');
			$dt->ldate=$this->__dateIndonesia($dt->ldate, 'tanggal');
			$dt->edate=$this->__dateIndonesia($dt->edate, 'tanggal');
			$blm[$dt->id] = $dt;
			$blm[$dt->id]->viewed = 0;
			$blm[$dt->id]->applied = 0;
			$blm[$dt->id]->tes_awal = 0;
			$blm[$dt->id]->interview_hr = 0;
			$blm[$dt->id]->interview_user = 0;
			$blm[$dt->id]->lolos = 0;
			$blm[$dt->id]->tidak_lolos = 0;
			$blm_ids[] = $dt->id;
		}
		foreach($this->blvm->statistik_by_lowongan($blm_ids, $start_date, $end_date) as $rdt){
			if(isset($blm[$rdt->b_lowongan_id]) && $rdt->total) $blm[$rdt->b_lowongan_id]->viewed = $rdt->total;
		}
		foreach($this->cam->statistik_by_lowongan_apply($blm_ids, $start_date, $end_date) as $rdt){
			if(isset($blm[$rdt->b_lowongan_id]) && $rdt->total) $blm[$rdt->b_lowongan_id]->applied = $rdt->total;
		}
		foreach($this->cam->statistik_by_tes_awal($blm_ids, $start_date, $end_date) as $rdt){
			if(isset($blm[$rdt->b_lowongan_id]) && $rdt->total) $blm[$rdt->b_lowongan_id]->tes_awal = $rdt->total;
		}
		foreach($this->cam->statistik_by_interview_hr($blm_ids, $start_date, $end_date) as $rdt){
			if(isset($blm[$rdt->b_lowongan_id]) && $rdt->total) $blm[$rdt->b_lowongan_id]->interview_hr = $rdt->total;
		}
		foreach($this->cam->statistik_by_interview_user($blm_ids, $start_date, $end_date) as $rdt){
			if(isset($blm[$rdt->b_lowongan_id]) && $rdt->total) $blm[$rdt->b_lowongan_id]->interview_user = $rdt->total;
		}
		foreach($this->cam->statistik_by_lolos($blm_ids, $start_date, $end_date) as $rdt){
			if(isset($blm[$rdt->b_lowongan_id]) && $rdt->total) $blm[$rdt->b_lowongan_id]->lolos = $rdt->total;
		}
		foreach($this->cam->statistik_by_tidak_lolos($blm_ids, $start_date, $end_date) as $rdt){
			if(isset($blm[$rdt->b_lowongan_id]) && $rdt->total) $blm[$rdt->b_lowongan_id]->tidak_lolos = $rdt->total;
		}

    //create object xls
		$ssheet = new Spreadsheet();
		$ssheet->getDefaultStyle()->getFont()->setName('Arial');
		$ssheet->getDefaultStyle()->getFont()->setSize(12);
		$ssheet->getActiveSheet()->getHeaderFooter()->setOddFooter('&C&HGenerated by SBP Recruitment System '.date('Y-m-d H:i:s'));

    //create sheet-1 and define columns widht
		$ssheet->setActiveSheetIndex(0);
    $sheet = $ssheet->getActiveSheet();
    $sheet->setTitle('statvcny');

		$rowIdx = 1;
		$sheet->setCellValue("A$rowIdx", 'No');
		$sheet->setCellValue("B$rowIdx", 'Lowongan');
		$sheet->setCellValue("C$rowIdx", 'Tgl Dibuat');
		$sheet->setCellValue("D$rowIdx", 'Tgl Berakhir');
		$sheet->setCellValue("E$rowIdx", 'Dilihat');
		$sheet->setCellValue("F$rowIdx", 'Di Apply');
		$sheet->setCellValue("G$rowIdx", 'Sudah Tes Awal');
		$sheet->setCellValue("H$rowIdx", 'Sudah Interview HR');
		$sheet->setCellValue("I$rowIdx", 'Sudah Interview User');
		$sheet->setCellValue("J$rowIdx", 'Lolos');
		$sheet->setCellValue("K$rowIdx", 'Tidak Lolos');
		if(count($blm)){
			$nomor = 0;
			foreach($blm as $dt){
				$nomor++;
				$rowIdx++;

				$sheet->setCellValue("A$rowIdx", $nomor);
				$sheet->setCellValue("B$rowIdx", $dt->nama);
				$sheet->setCellValue("C$rowIdx", $dt->cdate);
				$sheet->setCellValue("D$rowIdx", $dt->edate);
				$sheet->setCellValue("E$rowIdx", $dt->viewed);
				$sheet->setCellValue("F$rowIdx", $dt->applied);
				$sheet->setCellValue("G$rowIdx", $dt->tes_awal);
				$sheet->setCellValue("H$rowIdx", $dt->interview_hr);
				$sheet->setCellValue("I$rowIdx", $dt->interview_user);
				$sheet->setCellValue("J$rowIdx", $dt->lolos);
				$sheet->setCellValue("K$rowIdx", $dt->tidak_lolos);
			}
		}else{
			$rowIdx++;
			$sheet->setCellValue("A$rowIdx", 'Belum ada data')->mergeCells('A' . $rowIdx . ':K' . $rowIdx . '');
		}

		// auto fit columns
		foreach ($sheet->getColumnIterator() as $column) {
    	$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}

		//back to first sheet
		$ssheet->setActiveSheetIndex(0);

    //save file
		$save_dir = $this->__checkDir(date("Y/m"));
		$save_file = 'report-per-lowongan';
		$save_file = str_replace(' ', '', str_replace('/', '', $save_file));

		$swriter = new Xlsx($ssheet);
		if (file_exists($save_dir . '/' . $save_file . '.xlsx')) unlink($save_dir . '/' . $save_file . '.xlsx');
		$swriter->save($save_dir . '/' . $save_file . '.xlsx');

		$download_path = str_replace(SEMEROOT,'/',$save_dir.'/'.$save_file.'.xlsx');
		$this->__forceDownload($save_dir . '/' . $save_file . '.xlsx');
	}
}
