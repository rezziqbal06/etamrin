<style>
.select2 {
	width: 100% !important;
}
</style>
<!-- for cache image -->
<img id="imgadd" src="" style="display: none;" />
<img id="imgaddori" src="" style="display: none;" />
<img id="imgedit" src="" style="display: none;" />
<img id="imgeditori" src="" style="display: none;" />
<!-- end for cache image -->
<div id="page-content">
	<!-- Static Layout Header -->
	<div class="content-header">
		<div class="row" style="padding: 0.5em 2em;">
			<div class="col-md-6"></div>
			<div class="col-md-6">

			</div>
		</div>
	</div>
	<ul class="breadcrumb breadcrumb-top">
		<li>Admin</li>
		<li>Pengaturan</li>
		<li><a href="<?= base_url_admin() ?>pengaturan/lowongan/">Lowongan</a></li>
		<li>Edit #<?= $lowongan->id ?></li>
	</ul>
	<!-- END Static Layout Header -->

	<!-- Content -->
	<div class="block full">

		<div class="block-title">
			<h2><strong>Edit Data</strong></h2>
		</div>


		<form id="fedit" action="<?= base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">

			<div class="form-group">
				<div class="col-md-12">
					<label class="control-label" for="ienama">Judul Lowongan *</label>
					<input type="text" id="ienama" value="<?= $lowongan->nama ?>" class="form-control" name="nama">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<label class="control-label" for="iea_jabatan_id">Posisi / Jabatan *</label>
					<select id="iea_jabatan_id" name="a_jabatan_id" class="form-control select2">
						<option value="">-- pilih posisi --</option>
						<?php if (isset($posisi[0]->id)) {
							foreach ($posisi as $p) { ?>
								<?php if ($p->id == $lowongan->a_jabatan_id) { ?>
									<option value="<?= $p->id ?>" selected><?= $p->nama ?></option>
								<?php } else { ?>
									<option value="<?= $p->id ?>"><?= $p->nama ?></option>
								<?php }
							}
						} ?>
					</select>
					<input type="hidden" id="ieid" value="<?= $lowongan->id ?>" name="id">
				</div>
				<div class="col-md-3">
					<label class="control-label" for="iea_company_id">Penempatan *</label>
					<select id="iea_company_id" name="a_company_id" class="form-control select2">
						<option value="">-- pilih penempatan --</option>
						<?php if (isset($alamat[0]->id)) {
							foreach ($alamat as $p) { ?>
								<option value="<?= $p->id ?>"><?= $p->nama ?></option>
							<?php }
						} ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label" for="iesdate">Mulai tanggal *</label>
					<input id="iesdate" type="text" name="sdate" value="<?= $lowongan->sdate ?>" class="form-control datepicker" required />
				</div>
				<div class="col-md-3">
					<label class="control-label" for="ieedate">Berakhir pada *</label>
					<input id="ieedate" type="text" name="edate" value="<?= $lowongan->edate ?>" class="form-control datepicker" required />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label for="iedeskripsi">Deskripsi*</label>
					<textarea id="iedeskripsi" type="text" name="deskripsi" class="ckeditor" minlength="2" maxline="8" placeholder="Deskripsi" required><?= $lowongan->deskripsi ?></textarea>
				</div>
			</div>

			<div id="pjabatan" class="form-group" style="display: none;">
				<div class="col-md-6">
					<label class="control-label" for="iemin_pendidikan">Minimal Pendidikan *</label>
					<select id="iemin_pendidikan" name="min_pendidikan" class="form-control" required>
						<?php foreach($this->config->semevar->pendidikans as $k=>$v){ if($v<3) continue; ?>
							<option value="<?=$k?>" <?=($lowongan->min_pendidikan == $k) ? 'selected' : '' ?>><?=$k?></option>
						<?php } ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label" for="iemin_exp">Minimal Pengalaman *</label>
					<input id="iemin_exp" type="number" name="min_exp" class="form-control" value="<?= $lowongan->min_exp ?>" placeholder="minimal pengalaman" required />
				</div>
				<div class="col-md-6">
					<label class="control-label" for="iemax_usia">Maksimal Usia *</label>
					<input id="iemax_usia" type="number" name="max_usia" class="form-control" value="<?= $lowongan->max_usia ?>" placeholder="" required />
				</div>
				<div class="col-md-3">
					<label class="control-label" for="iemin_iq">Min. IQ *</label>
					<input id="iemin_iq" type="number" name="min_iq" class="form-control" value="<?= $lowongan->min_iq ?>" placeholder="Min Score IQ" required />
				</div>
				<div class="col-md-3">
					<label class="control-label" for="iemin_cs">Min. CS *</label>
					<input id="iemin_cs" type="number" name="min_cs" class="form-control" value="<?= $lowongan->min_cs ?>" placeholder="Min Score Cs" min="0" max="99" required />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label" for="iea_jabatan_id_uinterview1">Jabatan User Interviewer 1 *</label>
					<select id="iea_jabatan_id_uinterview1" name="a_jabatan_id_uinterview1" class="form-control select2">
						<option value="">-- pilih posisi --</option>
						<?php if (isset($posisi[0]->id)) {
							foreach ($posisi as $p) { ?>
								<?php if ($p->id == $lowongan->a_jabatan_id_uinterview1) { ?>
									<option value="<?= $p->id ?>" selected><?= $p->nama ?></option>
								<?php } else { ?>
									<option value="<?= $p->id ?>"><?= $p->nama ?></option>
								<?php }
							}
						} ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label" for="iea_jabatan_id_uinterview2">Jabatan User Interviewer 2 *</label>
					<select id="iea_jabatan_id_uinterview2" name="a_jabatan_id_uinterview2" class="form-control select2">
						<option value="">-- pilih posisi --</option>
						<?php if (isset($posisi[0]->id)) {
							foreach ($posisi as $p) { ?>
								<?php if ($p->id == $lowongan->a_jabatan_id_uinterview2) { ?>
									<option value="<?= $p->id ?>" selected><?= ucwords(strtolower($p->nama)) ?></option>
								<?php } else { ?>
									<option value="<?= $p->id ?>"><?= ucwords(strtolower($p->nama)) ?></option>
								<?php }
							}
						} ?>
					</select>
				</div>
			</div>
			<div id="pdetail" class="form-group">
				<div class="col-md-3">
					<label class="control-label">Jadwal Kerja</label>
					<select id="iettype" class="form-control" placeholder="" name="ttype">
						<option value="penuh" selected>Full time</option>
						<option value="paruh">Part time</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label">Gaji Minimum</label>
					<input id="ievsgaji" type="hidden" class="form-control " name="sgaji" placeholder="Gaji awal">
					<input id="iesgaji" type="text" class="form-control rupiah-uang" placeholder="Gaji Mulai" data-selector="ievsgaji">
				</div>
				<div class="col-md-3">
					<label class="control-label">Gaji Maksimum</label>
					<input id="ievegaji" type="hidden" class="form-control " name="egaji" placeholder="Gaji sampai">
					<input id="ieegaji" type="text" class="form-control rupiah-uang" placeholder="Gaji sampai" data-selector="ievegaji">
				</div>
				<div class="col-md-3">
					<label class="control-label ">Fresh Graduated Welcomed?</label>
					<select id="ieis_freshg" class="form-control" placeholder="" name="is_freshg">
						<option value="1">Iya</option>
						<option value="0" selected>Tidak</option>
					</select>
				</div>
			</div>


			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<div class="col-md-12">
							<label for="iegambar">Gambar (20KB - 3MB)</label>
							<input accept=".jpg,.png,.jpeg,.webp" type="file" id="iegambar">
							<br />
							<label for="iebg_warna">Background Color</label>
							<input type="text" id="iebg_warna" class="form-control demo" data-control="hue" name="bg_warna" value="#ff6161">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-3">
							<label for="ieis_favorite" class="control-label">Is Favorite?</label>
							<select id="ieis_favorite" class="form-control" placeholder="" name="is_favorite">
								<option value="0" selected>Tidak</option>
								<option value="1">Iya</option>
							</select>
						</div>
						<div class="col-md-3">
							<label for="ieis_active" class="control-label">Aktif</label>
							<select id="ieis_active" class="form-control" placeholder="" name="is_active">
								<option value="1">Iya</option>
								<option value="0">Tidak</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<img id="egambar" src="<?= $this->cdn_url($lowongan->gambar) ?>" alt="" class="img-responsive img-rounded">
				</div>
			</div>

			<div class="form-group form-actions">
				<div class="col-xs-12 text-right">
					<button id="bfedit" type="submit" class="btn btn-sm btn-primary btn-submit"> Simpan Perubahan <i class="fa fa-save icon-submit"></i></button>
				</div>
			</div>
		</form>
	</div>

	<div class="block full hidden">
		<div class="block-title">
			<h2><strong>Pengaturan Tes Seleksi</strong></h2>
		</div>
		<form id="ftes_seleksi" action="<?= base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">

			<?php foreach($absm_utype as $k=>$absu){?>
				<div class="form-group">
					<div class="col-md-5">
						<label class="control-label" for="ites_a_banksoal_id_<?=strtolower($k)?>">Tes <?=$k?></label>
						<select id="ites_a_banksoal_id_<?=strtolower($k)?>" name="a_banksoal_id[]" class="form-control" placeholder="">
							<option value="">-- Tidak Ada --</option>
							<?php foreach($absu->soals as $a){ ?>
								<option value="<?=$a->id?>"><?=$a->nama?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-2">
						<label class="control-label" for="ites_is_rand_soal_<?=strtolower($k)?>">Random Soal</label>
						<select id="ites_is_rand_soal_<?=strtolower($k)?>" name="is_rand_soal[]" class="form-control" placeholder="">
							<option value="0">Tidak</option>
							<option value="1">Iya</option>
						</select>
					</div>
					<div class="col-md-2">
						<label class="control-label" for="ites_is_rand_jawaban_<?=strtolower($k)?>">Random Pil.jwb.</label>
						<select id="ites_is_rand_jawaban_<?=strtolower($k)?>" name="is_rand_jawaban[]" class="form-control" placeholder="">
							<option value="0">Tidak</option>
							<option value="1">Iya</option>
						</select>
					</div>
					<div class="col-md-2">
						<label class="control-label" for="ites_passing_grade_<?=strtolower($k)?>">Passing Grade *</label>
						<input type="number" id="ites_passing_grade_<?=strtolower($k)?>" name="passing_grade[]" min="0" max="100" class="form-control" value="" required />
					</div>

				</div>
			<?php } ?>


			<div class="form-group form-actions">
				<div class="col-xs-12 text-right">
					<button type="submit" class="btn btn-sm btn-primary btn-submit"> Simpan Perubahan <i class="fa fa-save icon-submit"></i></button>
				</div>
			</div>

		</form>
	</div>

</div>
