<?php
class Home extends JI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->setTheme('admin');
		$this->lib("seme_purifier");
		$this->current_parent = 'pelamar_baru';
		$this->current_page = 'pelamar_baru';
		$this->load('b_user_log_model', 'bulm');
		$this->load('admin/a_company_model', 'acm');
		$this->load('admin/a_pengguna_model', 'apm');
		$this->load('admin/a_jabatan_model', 'ajm');
		$this->load('admin/a_itemoffer_model', 'aiom');
		$this->load('admin/a_discangka_model', 'adam');
		$this->load('admin/b_user_model', 'bum');
		$this->load('admin/b_lowongan_model', 'blm');
		$this->load('admin/b_user_file_model', 'bufm');
		$this->load('admin/c_apply_model', 'cam');
		$this->load('admin/c_interview_model', 'cim');
		$this->load('admin/c_interview_hasil_model', 'cihm');
		$this->load('admin/c_interview_nilai_model', 'cinm');
		$this->load('admin/d_offer_model', 'dom');
	}

	/**
	 * Procedures for building hasil tes data
	 * @param  array  $data  array from data before
	 * @return array  $data  array to data after
	 */
	private function _hasilTes($data)
	{
		$data['hasil_tes'] = array();
		if (isset($data['bum']->hasil_tes)) {
			$jht = json_decode($data['bum']->hasil_tes);
			if (!is_array($jht)) $jht = array();
			foreach ($jht as $ht) {
				$data['hasil_tes'][$ht->utype] = $ht;
			}
		}
		$data = $this->_tesIQCalculation($data);
		$data = $this->_tesCSCalculation($data);

		return $data;
	}

	/**
	 * Procedures for building result from IQ test result
	 * @param  array  $data  array from data before
	 * @return array  $data  array to data after
	 */
	private function _tesIQCalculation($data)
	{
		if (isset($data['hasil_tes']['iq']->poin) && !isset($data['hasil_tes']['iq']->score_iq)) {
			$hasil_iq = $this->_resultTestIQ($data['hasil_tes']['iq']->poin);
			if (isset($hasil_iq->nama)) {
				$data['hasil_tes']['iq']->score_iq = $hasil_iq->iq;
				$data['hasil_tes']['iq']->ket = $hasil_iq->nama;
			}
		}

		return $data;
	}

	/**
	 * Procedures for building result from CS test result
	 * @param  array  $data  array from data before
	 * @return array  $data  array to data after
	 */
	private function _tesCSCalculation($data)
	{
		if (isset($data['hasil_tes']['cs']->poin)) {
			$hasil_cs = $this->_resultTestCS((int) $data['hasil_tes']['cs']->poin);
			if (isset($hasil_cs->cs)) {
				$data['hasil_tes']['cs']->score_cs = $hasil_cs->cs;
			}
		}
		return $data;
	}

	/**
	 * Procedures for building family data
	 * @param  array  $data  array from data before
	 * @return array  $data  array to data after
	 */
	private function _genDataKeluarga($data)
	{
		//data keluarga
		$this->load('admin/b_user_keluarga_model', 'bukgm');
		$bukgm = $this->bukgm->getByUserId($data['bum']->id);
		$data['ayah'] = new stdClass();
		$data['ibu'] = new stdClass();
		$data['istri'] = new stdClass();
		$data['suami'] = new stdClass();
		$data['anak'] = array();
		$data['saudara'] = array();
		$data['pasangan'] = new stdClass();

		foreach ($bukgm as $bukg) {
			if ($bukg->utype == 'ayah') {
				$data['ayah'] = $bukg;
			} elseif ($bukg->utype == 'ibu') {
				$data['ibu'] = $bukg;
			} elseif ($bukg->utype == 'istri') {
				$data['istri'] = $bukg;
			} elseif ($bukg->utype == 'suami') {
				$data['suami'] = $bukg;
			} elseif ($bukg->utype == 'anak') {
				$data['anak'][] = $bukg;
			} else {
				$data['saudara'][] = $bukg;
			}
		}
		unset($bukgm, $bukg);
		if ($data['bum']->status_kawin == 'Menikah' && !empty($data['bum']->jk)) {
			$data['pasangan'] = $data['istri'];
		}
		if ($data['bum']->status_kawin == 'Menikah' && empty($data['bum']->jk)) {
			$data['pasangan'] = $data['suami'];
		}
		return $data;
	}


	public function index()
	{
		$data = $this->__init();
		if (!$this->admin_login) {
			redir(base_url_admin('login'));
			die();
		}

		$this->setTitle('Pelamar ' . $this->config->semevar->admin_site_suffix);

		$this->putThemeContent("pelamar/home/home_modal", $data);
		$this->putThemeContent("pelamar/home/home", $data);
		$this->putJsContent("pelamar/home/home_bottom", $data);
		$this->loadLayout('col-2-left-online', $data);
		$this->render();
	}
	public function detail($id)
	{
		$data = $this->__init();
		$data['cam'] = $this->cam->getById($id);
		if (!isset($data['cam']->id)) {
			redir(base_url_admin('notfound'));
			die();
		}

		$data['bum'] = $this->bum->getById($data['cam']->b_user_id);
		if (!isset($data['bum']->id)) {
			redir(base_url_admin('notfound'));
			die();
		}
		$data['bum']->apply_statno = (int) $data['bum']->apply_statno;
		if ($data['bum']->apply_statno == 8 && !empty($data['cam']->is_process) && empty($data['cam']->is_failed)) {
			$edate = date('Y-m-d 23:59:59', strtotime('+180 days'));
			$this->cam->update($data['cam']->id, array('edate' => $edate, 'is_process' => 0, 'is_failed' => 1));
			redir(base_url_admin('pelamar/home/detail/' . $data['cam']->id));
			return;
		}

		if (empty($data['cam']->is_process) && !empty($data['cam']->is_failed) && $data['bum']->apply_statno != 8) {
			if (is_null($data['cam']->edate)) {
				$edate = date('Y-m-d 23:59:59', strtotime('+180 days'));
				$this->cam->update($data['cam']->id, array('edate' => $edate));
			}
			$this->bum->update($data['bum']->id, array('apply_statno' => 8));
			redir(base_url_admin('pelamar/home/detail/' . $data['cam']->id));
			return;
		}

		$data['blm'] = $this->blm->getById($data['cam']->b_lowongan_id);

		//progress rekrutmen
		$data['upload_progress'] = new stdClass();
		$data['upload_progress']->from_val = 0;
		$data['upload_progress']->to_val = 5;
		$data['current_progress'] = 0;
		$data['total_progress'] = 6;
		$this->load('admin/c_apply_progress_model', 'capm');
		$data['capm'] = $this->capm->getByUserId($data['bum']->id, $data['cam']->id, 'data');
		foreach ($data['capm'] as $capm) {
			$data['current_progress'] += (int) $capm->from_val;
			$data['total_progress'] += (int) $capm->to_val;
			if ($capm->stepkey == 'Upload Data') {
				$data['upload_progress'] = $capm;
			}
		}
		if ($data['total_progress'] <= 0) $data['total_progress'] = 1;


		$data = $this->_hasilTes($data);

		$data['bufm'] = array();
		$data['bufm']['cv'] = new stdClass();
		$data['bufm']['ktp'] = new stdClass();
		$data['bufm']['portofolio'] = new stdClass();
		$data['bufm']['ijazah'] = new stdClass();
		$data['bufm']['transkrip'] = new stdClass();
		$data['bufm']['vaksin'] = new stdClass();
		foreach ($this->bufm->getByUserId($data['bum']->id) as $bufm) {
			if (isset($data['bufm'][$bufm->utype])) $data['bufm'][$bufm->utype] = $bufm;
		}

		//data riwayat pekerjaan
		$this->load('admin/b_user_pendidikan_model', 'bupdm');
		$data['pendidikan'] = $this->bupdm->getByUserId($data['bum']->id);

		//data riwayat pekerjaan
		$this->load('admin/b_user_jobhistory_model', 'bujhm');
		$data['bujhm'] = $this->bujhm->getByUserId($data['bum']->id);

		$data = $this->_genDataKeluarga($data);

		//data organization history result
		$this->load('admin/b_user_orghistory_model', 'buohm');
		$data['buohm'] = $this->buohm->getByUserId($data['bum']->id);

		//data relasi result
		$this->load('admin/b_user_relasi_model', 'burm');
		$data['burm'] = $this->burm->getByUserId($data['bum']->id);

		//data jawaban tes
		$this->load('admin/c_apply_sessiontes_model', 'castm');
		$data['is_tes_done'] = 0;
		$data['tes_sesi'] = array();
		$castm = $this->castm->getCurrent($data['bum']->id, $data['cam']->id);
		foreach ($castm as $k => $bss) {
			$data['tes_sesi'][$bss->utype] = $bss;
			if ($bss->is_done) {
				$data['tes_sesi'][$bss->utype]->from_val = 1;
				$data['is_tes_done']++;
			}
		}
		if (isset($data['tes_sesi']['cs']->from_val) && $data['tes_sesi']['cs']->from_val >= $data['tes_sesi']['cs']->to_val) {
			$data['current_progress']++;
		}
		if (
			isset($data['tes_sesi']['iq']->from_val) &&
			$data['tes_sesi']['iq']->from_val >= $data['tes_sesi']['iq']->to_val &&
			isset($data['tes_sesi']['kepribadian']->from_val) &&
			$data['tes_sesi']['kepribadian']->from_val >= $data['tes_sesi']['kepribadian']->to_val
		) {
			$data['current_progress']++;
		}
		unset($bss);
		$data['catm'] = array('iq', 'kepribadian', 'cs');
		$data['catm']['iq'] = array();
		$data['catm']['kepribadian'] = array();
		$data['catm']['cs'] = array();
		$this->load('admin/c_apply_tes_model', 'catm');
		$catm = $this->catm->getByUserId($data['bum']->id, $data['cam']->id);
		foreach ($catm as $but) {
			if (isset($data['catm'][$but->utype])) $data['catm'][$but->utype][] = $but;
		}
		unset($catm, $but);

		require_once(SEMEROOT . 'app/controller/api_front/hitung.php');
		$hitung = new Hitung();
		$data = $hitung->_prosesTesKepribadian($data);

		//data capture tes
		$this->load('admin/c_apply_capturetes_model', 'cactm');
		$data['cactm'] = $this->cactm->getByUserId($data['bum']->id);

		//skill
		$this->load('admin/b_user_skill_model', 'busm');
		$data['busm'] = $this->busm->getByUserId($data['bum']->id);

		//keterangan lainnya
		$this->load('admin/b_user_jawaban_model', 'bujam');
		$data['bujam'] = $this->bujam->getByUserId($data['bum']->id);

		//get ajabatan
		$data['ajm'] = $this->ajm->get();

		$data['is_need_changes'] = array();
		$data['is_interview_done'] = 0;
		$interview_hasil = json_decode($data['cam']->interview_hasil);
		if (isset($interview_hasil->nilai)) {
			$data['cam']->interview_hasil = $interview_hasil;
		}
		$data['interview'] = new stdClass();
		$data['interview']->hr = new stdClass();
		$data['interview']->hr->id = '';
		$data['interview']->hr->a_pengguna_id1 = null;
		$data['interview']->hr->a_pengguna_id2 = null;
		$data['interview']->hr->apm1 = new stdClass();
		$data['interview']->hr->apm1->cdate = '';
		$data['interview']->hr->apm1->cihmid = '';
		$data['interview']->hr->apm1->kelebihan = '';
		$data['interview']->hr->apm1->pengembangan = '';
		$data['interview']->hr->apm1->kesimpulan = '';
		$data['interview']->hr->apm1->nilai = array();
		$data['interview']->hr->apm1->nilai_akhir = 0;
		$data['interview']->hr->apm2 = new stdClass();
		$data['interview']->hr->apm2->cdate = '';
		$data['interview']->hr->apm2->cihmid = '';
		$data['interview']->hr->apm2->kelebihan = '';
		$data['interview']->hr->apm2->pengembangan = '';
		$data['interview']->hr->apm2->kesimpulan = '';
		$data['interview']->hr->apm2->nilai = array();
		$data['interview']->hr->apm2->nilai_akhir = 0;
		$data['interview']->hr->status_teks = '';
		$data['interview']->user = new stdClass();
		$data['interview']->user->id = '';
		$data['interview']->user->a_pengguna_id1 = null;
		$data['interview']->user->a_pengguna_id2 = null;
		$data['interview']->user->apm1 = new stdClass();
		$data['interview']->user->apm1->cdate = '';
		$data['interview']->user->apm1->cihmid = '';
		$data['interview']->user->apm1->kelebihan = '';
		$data['interview']->user->apm1->pengembangan = '';
		$data['interview']->user->apm1->kesimpulan = '';
		$data['interview']->user->apm1->nilai = array();
		$data['interview']->user->apm1->nilai_akhir = 0;
		$data['interview']->user->apm2 = new stdClass();
		$data['interview']->user->apm2->cihmid = '';
		$data['interview']->user->apm2->cdate = '';
		$data['interview']->user->apm2->kelebihan = '';
		$data['interview']->user->apm2->pengembangan = '';
		$data['interview']->user->apm2->kesimpulan = '';
		$data['interview']->user->apm2->nilai = array();
		$data['interview']->user->apm2->nilai_akhir = 0;
		$data['interview']->user->status_teks = '';

		$data['cim'] = $this->cim->getByApplyId($data['cam']->id);
		$cim_count = count($data['cim']);
		$data['current_progress'] += $cim_count;
		$data['total_progress'] += 4;
		if (is_array($data['cim']) && $cim_count) {
			foreach ($data['cim'] as $ci) {
				$ciutype = strtolower($ci->utype);
				$role = 'HR';
				if ($ciutype == 'user') $role = 'User 1';
				$apm1 = $this->apm->getById($ci->a_pengguna_id1);
				$cihm = $this->cihm->get($ci->id, $ci->a_pengguna_id1, $role);

				$data['interview']->{$ciutype}->id = $ci->id;
				$data['interview']->{$ciutype}->status_no = $ci->status_no;
				$data['interview']->{$ciutype}->status_teks = $ci->status_teks;
				$data['interview']->{$ciutype}->a_pengguna_id1 = $ci->a_pengguna_id1;
				$data['interview']->{$ciutype}->a_pengguna_id2 = $ci->a_pengguna_id2;
				$data['interview']->{$ciutype}->apm1->ajm = $this->ajm->getById($apm1->a_jabatan_id);
				$data['interview']->{$ciutype}->apm1->id = $ci->a_pengguna_id1;
				$data['interview']->{$ciutype}->apm1->nama = $apm1->nama;
				$data['interview']->{$ciutype}->apm1->email = $apm1->email;
				$data['interview']->{$ciutype}->apm1->cdate = isset($cihm->cdate) ? $cihm->cdate : '';
				$data['interview']->{$ciutype}->apm1->cihmid = isset($cihm->id) ? $cihm->id : '';
				$data['interview']->{$ciutype}->apm1->kesimpulan = isset($cihm->kesimpulan) ? $cihm->kesimpulan : '';
				$data['interview']->{$ciutype}->apm1->kelebihan = isset($cihm->kelebihan) ? $cihm->kelebihan : '';
				$data['interview']->{$ciutype}->apm1->pengembangan = isset($cihm->pengembangan) ? $cihm->pengembangan : '';
				$data['interview']->{$ciutype}->apm1->nilai = $this->cinm->get($ci->id, $ci->a_pengguna_id1, $role);
				$data['interview']->{$ciutype}->apm1->nilai_akhir = isset($cihm->nilai_akhir) ? $cihm->nilai_akhir : 0;
				if (isset($cihm->b_lowongan_id_asal) && isset($cihm->b_lowongan_id_ganti) && !is_null($cihm->b_lowongan_id_ganti) && !is_null($cihm->b_lowongan_id_asal)) {
					$data['interview']->{$ciutype}->apm1->ganti_jabatan = $this->blm->getById($cihm->b_lowongan_id_ganti);
					$data['is_need_changes'][] = $data['interview']->{$ciutype}->apm1->ganti_jabatan;
				}
				if ($ci->status_no == '9' || $ci->status_no == '8') {
					$data['is_interview_done']++;
				}

				// $this->debug($data['interview']->{$ciutype}->apm1->nilai);
				// die();

				if ($ciutype == 'user' && !is_null($ci->a_pengguna_id2)) {
					$role = 'User 2';
					$apm2 = $this->apm->getById($ci->a_pengguna_id2);
					$cihm = $this->cihm->get($ci->id, $ci->a_pengguna_id2, $role);
					$data['interview']->{$ciutype}->apm2->ajm = $this->ajm->getById($apm2->a_jabatan_id);
					$data['interview']->{$ciutype}->apm2->id = $ci->a_pengguna_id2;
					$data['interview']->{$ciutype}->apm2->nama = $apm2->nama;
					$data['interview']->{$ciutype}->apm2->email = $apm2->email;
					$data['interview']->{$ciutype}->apm2->cdate = isset($cihm->cdate) ? $cihm->cdate : '';
					$data['interview']->{$ciutype}->apm2->cihmid = isset($cihm->id) ? $cihm->id : '';
					$data['interview']->{$ciutype}->apm2->kesimpulan = isset($cihm->kesimpulan) ? $cihm->kesimpulan : '';
					$data['interview']->{$ciutype}->apm2->kelebihan = isset($cihm->kelebihan) ? $cihm->kelebihan : '';
					$data['interview']->{$ciutype}->apm2->pengembangan = isset($cihm->pengembangan) ? $cihm->pengembangan : '';
					$data['interview']->{$ciutype}->apm2->nilai = $this->cinm->get($ci->id, $ci->a_pengguna_id2, $role);
					$data['interview']->{$ciutype}->apm2->nilai_akhir = isset($cihm->nilai_akhir) ? $cihm->nilai_akhir : 0;
					if (isset($cihm->b_lowongan_id_asal) && isset($cihm->b_lowongan_id_ganti) && !is_null($cihm->b_lowongan_id_ganti) && !is_null($cihm->b_lowongan_id_asal)) {
						$data['interview']->{$ciutype}->apm2->ganti_jabatan = $this->blm->getById($cihm->b_lowongan_id_ganti);
						$data['is_need_changes'][] = $data['interview']->{$ciutype}->apm2->ganti_jabatan;
					}
				}
			}
			if (!isset($interview_hasil->nilai) || !isset($interview_hasil->status) || !isset($interview_hasil->hr->nilai) || !isset($interview_hasil->user1->nilai)) {

				$interview_hasil = new stdClass();
				if (isset($data['interview']->hr->apm1->nilai[0]->id) && isset($data['interview']->user->apm1->nilai[0]->id) && isset($data['interview']->user->apm2->nilai[0]->id)) {
					$interview_hasil->nilai = 0;
					$interview_hasil->status = '';
					$interview_hasil->passing_grade = 0;
					$interview_hasil->hr = new stdClass();
					$interview_hasil->hr->nilai = 0;
					$interview_hasil->hr->bobot_persen = 20;

					$ncount = 1;
					foreach ($data['interview']->hr->apm1->nilai as $n) {
						$interview_hasil->hr->nilai += $n->nilai;
						$interview_hasil->passing_grade += $n->passing_grade;
						$ncount++;
					}
					$interview_hasil->passing_grade = round($interview_hasil->passing_grade / $ncount, 1);
					$data['interview']->hr->apm1->nilai_akhir = round(($interview_hasil->hr->nilai / $ncount) * ($interview_hasil->hr->bobot_persen / 100), 1);
					$interview_hasil->nilai += $data['interview']->hr->apm1->nilai_akhir;
					$this->cihm->update($data['interview']->hr->apm1->cihmid, array('nilai_akhir' => $data['interview']->hr->apm1->nilai_akhir));

					$interview_hasil->user1 = new stdClass();
					$interview_hasil->user1->nilai = 0;
					$interview_hasil->user1->bobot_persen = 50;

					$ncount = 1;
					foreach ($data['interview']->user->apm1->nilai as $n) {
						$interview_hasil->user1->nilai += $n->nilai;
						$ncount++;
					}
					$data['interview']->user->apm1->nilai_akhir = round(($interview_hasil->user1->nilai / $ncount) * ($interview_hasil->user1->bobot_persen / 100), 1);
					$interview_hasil->nilai += $data['interview']->user->apm1->nilai_akhir;
					$this->cihm->update($data['interview']->user->apm1->cihmid, array('nilai_akhir' => $data['interview']->user->apm1->nilai_akhir));

					$interview_hasil->user2 = new stdClass();
					$interview_hasil->user2->nilai = 0;
					$interview_hasil->user2->bobot_persen = 30;

					$ncount = 1;
					foreach ($data['interview']->user->apm2->nilai as $n) {
						$interview_hasil->user2->nilai += $n->nilai;
						$ncount++;
					}
					$data['interview']->user->apm2->nilai_akhir = round(($interview_hasil->user2->nilai / $ncount) * ($interview_hasil->user2->bobot_persen / 100), 1);
					$interview_hasil->nilai += $data['interview']->user->apm2->nilai_akhir;
					$this->cihm->update($data['interview']->user->apm2->cihmid, array('nilai_akhir' => $data['interview']->user->apm2->nilai_akhir));

					if ($interview_hasil->nilai > $interview_hasil->passing_grade) {
						$interview_hasil->status = 'Lolos';
					} else {
						$interview_hasil->status = 'Tidak Lolos';
					}
					$interview_hasil->nilai = '' . $interview_hasil->nilai;
					$interview_hasil->passing_grade = '' . $interview_hasil->passing_grade;
					$camdu = array();
					$camdu['interview_hasil'] = json_encode($interview_hasil);
					$this->cam->update($data['cam']->id, $camdu);
				} elseif (isset($data['interview']->hr->apm1->nilai[0]->id) && isset($data['interview']->user->apm1->nilai[0]->id) && !isset($data['interview']->user->apm2->nilai[0]->id)) {
					$interview_hasil->nilai = 0;
					$interview_hasil->status = '';
					$interview_hasil->passing_grade = 0;
					$interview_hasil->hr = new stdClass();
					$interview_hasil->hr->nilai = 0;
					$interview_hasil->hr->bobot_persen = 30;

					$ncount = 1;
					foreach ($data['interview']->hr->apm1->nilai as $n) {
						$interview_hasil->passing_grade += $n->passing_grade;
						$interview_hasil->hr->nilai += $n->nilai;
						$ncount++;
					}
					$interview_hasil->passing_grade = round($interview_hasil->passing_grade / $ncount, 1);
					$data['interview']->hr->apm1->nilai_akhir = round(($interview_hasil->hr->nilai / $ncount) * ($interview_hasil->hr->bobot_persen / 100), 1);
					$interview_hasil->nilai += $data['interview']->hr->apm1->nilai_akhir;
					$this->cihm->update($data['interview']->hr->apm1->cihmid, array('nilai_akhir' => $data['interview']->hr->apm1->nilai_akhir));

					$interview_hasil->user1 = new stdClass();
					$interview_hasil->user1->nilai = 0;
					$interview_hasil->user1->bobot_persen = 70;

					$ncount = 1;
					foreach ($data['interview']->user->apm1->nilai as $n) {
						$interview_hasil->user1->nilai += $n->nilai;
						$ncount++;
					}
					$data['interview']->user->apm1->nilai_akhir = round(($interview_hasil->user1->nilai / $ncount) * ($interview_hasil->user1->bobot_persen / 100), 1);
					$interview_hasil->nilai += $data['interview']->user->apm1->nilai_akhir;
					$this->cihm->update($data['interview']->user->apm1->cihmid, array('nilai_akhir' => $data['interview']->user->apm1->nilai_akhir));

					if ($interview_hasil->nilai > $interview_hasil->passing_grade) {
						$interview_hasil->status = 'Lolos';
					} else {
						$interview_hasil->status = 'Tidak Lolos';
					}
					$interview_hasil->nilai = '' . $interview_hasil->nilai;
					$interview_hasil->passing_grade = '' . $interview_hasil->passing_grade;
					$data['cam']->interview_hasil = $interview_hasil;

					$camdu = array();
					$camdu['interview_hasil'] = json_encode($interview_hasil);
					$this->cam->update($data['cam']->id, $camdu);
				}
			}
		}

		//hitung progress interview
		if (isset($interview_hasil->hr->nilai)) {
			$data['current_progress'] += 1;
			// if($data['bum']->apply_statno <= 5) $this->bum->update($data['bum']->id, array('apply_statno'=>6));
		}
		if (isset($interview_hasil->user1->nilai)) {
			$data['current_progress'] += 1;
			// if($data['bum']->apply_statno == 6) $this->bum->update($data['bum']->id, array('apply_statno'=>7));
		}

		if ($data['bum']->apply_statno > 1 && $data['bum']->apply_statno <= 3) {
			if (
				isset($data['tes_sesi']['cs']->is_done) &&
				$data['tes_sesi']['cs']->is_done &&
				isset($data['tes_sesi']['iq']->is_done) &&
				$data['tes_sesi']['iq']->is_done &&
				isset($data['tes_sesi']['kepribadian']->is_done) &&
				$data['tes_sesi']['kepribadian']->is_done &&
				(
					(isset($data['hasil_tes']['iq']->lolos1) &&
						$data['hasil_tes']['iq']->lolos1 == true) ||
					(isset($data['hasil_tes']['iq']->lolos2) &&
						$data['hasil_tes']['iq']->lolos2 == true)
				) && (
					(isset($data['hasil_tes']['cs']->lolos1) &&
						$data['hasil_tes']['cs']->lolos1 == true) ||
					(isset($data['hasil_tes']['cs']->lolos2) &&
						$data['hasil_tes']['cs']->lolos2 == true)
				)
			) {
				$this->bum->update($data['bum']->id, array('apply_statno' => 4));
				redir(base_url_admin('pelamar/home/detail/' . $data['cam']->id));
			}
		}

		$aio = $this->aiom->getAll();
		foreach ($aio as $item) {
			$item->id = str_replace(' ', '_', $item->nama);
			$item->id = str_replace('/', '_', $item->id);
			$item->id = strtolower($item->id);
		}

		$data['aio'] = $aio;
		$dom = $this->dom->getByApplyId($data['cam']->id);
		if (isset($dom->id)) {
			$data_bum = json_decode($dom->bum_hasil);
			foreach ($data_bum as $k => $v) {
				if ($k == "Tgl. Masuk") $dom->tgl_masuk = $v;
			}

			$offering_nego = json_decode($dom->offering_nego);
			if (isset($offering_nego->gaji_pokok)) $dom->nego = $offering_nego->gaji_pokok;

			$data['dom'] = $dom;
		}
		//finalize total progress
		if ($data['current_progress'] > $data['total_progress']) $data['current_progress'] = $data['total_progress'];

		// $this->debug($data);
		// die();
		$this->putJsFooter($this->cdn_url('assets/slick/slick.min'));
		$this->loadCss($this->cdn_url('assets/slick/slick'));
		$this->loadCss($this->cdn_url('assets/slick/slick-theme'));


		$this->setTitle('Detail Pelamar #' . $data['cam']->id . '' . $this->config->semevar->admin_site_suffix);

		$this->putThemeContent("pelamar/home/detail_modal", $data);
		$this->putThemeContent("pelamar/home/detail", $data);
		$this->putJsReady("pelamar/home/detail_bottom", $data);
		$this->loadLayout('col-2-left-online', $data);
		$this->render();
	}
}
