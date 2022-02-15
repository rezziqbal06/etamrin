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
		<li>Tambah</li>
	</ul>
	<!-- END Static Layout Header -->

	<!-- Content -->
	<div class="block full">

		<div class="block-title">
			<h2><strong>Tambah Lowongan Pekerjaan</strong></h2>
		</div>


		<form id="ftambah" action="<?= base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">

			<div class="form-group">
				<div class="col-md-12">
					<label class="control-label" for="inama">Judul Lowongan *</label>
					<input type="text" id="inama" class="form-control" name="nama">
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<label class="control-label" for="ia_jabatan_id">Posisi/Jabatan *</label>
					<select id="ia_jabatan_id" name="a_jabatan_id" class="form-control select2">
						<option value="">-- pilih posisi --</option>
						<?php if (isset($posisi[0]->id)) {
							foreach ($posisi as $p) { ?>
								<option value="<?= $p->id ?>"><?= $p->nama ?></option>
						<?php }
						} ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label" for="ia_company_id">Penempatan *</label>
					<select id="ia_company_id" name="a_company_id" class="form-control select2" required>
						<option value="">-- pilih penempatan --</option>
						<?php if (isset($alamat[0]->id)) {
							foreach ($alamat as $p) { ?>
								<option value="<?= $p->id ?>"><?= $p->nama ?></option>
						<?php }
						} ?>
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label" for="isdate">Mulai tanggal *</label>
					<input id="isdate" type="text" name="sdate" class="form-control datepicker" placeholder="" autocomplete="off" required />
				</div>
				<div class="col-md-3">
					<label class="control-label" for="iedate">Berakhir pada *</label>
					<input id="iedate" type="text" name="edate" class="form-control datepicker" placeholder="" autocomplete="off" required />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12">
					<label for="ideskripsi">Deskripsi*</label>
					<textarea id="ideskripsi" type="text" name="deskripsi" class="ckeditor" minlength="2" placeholder="Deskripsi" required></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label class="control-label" for="ia_jabatan_id_uinterview1">Jabatan User Interviewer 1 *</label>
					<select id="ia_jabatan_id_uinterview1" name="a_jabatan_id_uinterview1" class="form-control select2">
						<option value="">-- pilih posisi --</option>
						<?php if (isset($posisi[0]->id)) {
							foreach ($posisi as $p) { ?>
								<option value="<?= $p->id ?>"><?= $p->nama ?></option>
						<?php }
						} ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label" for="ia_jabatan_id_uinterview2">Jabatan User Interviewer 2 *</label>
					<select id="ia_jabatan_id_uinterview2" name="a_jabatan_id_uinterview2" class="form-control select2">
						<option value="">-- pilih posisi --</option>
						<?php if (isset($posisi[0]->id)) {
							foreach ($posisi as $p) { ?>
								<option value="<?= $p->id ?>"><?= $p->nama ?></option>
						<?php }
						} ?>
					</select>
				</div>
			</div>

			<div id="pjabatan" class="form-group" style="display: none;">
				<div class="col-md-6">
					<label class="control-label" for="imin_pendidikan">Minimal Pendidikan *</label>
					<select id="imin_pendidikan" name="min_pendidikan" class="form-control" required>
						<?php foreach($this->config->semevar->pendidikans as $k=>$v){ if($v<3) continue; ?>
	          <option value="<?=$k?>"><?=$k?></option>
	          <?php } ?>
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label" for="imin_exp">Minimal Pengalaman *</label>
					<input id="imin_exp" type="number" name="min_exp" class="form-control" placeholder="minimal pengalaman" required />
				</div>
				<div class="col-md-6">
					<label class="control-label" for="imax_usia">Maksimal Usia *</label>
					<input id="imax_usia" type="number" name="max_usia" class="form-control" placeholder="" required />
				</div>
				<div class="col-md-3">
					<label class="control-label" for="imin_iq">Minimal IQ *</label>
					<input id="imin_iq" type="number" name="min_iq" class="form-control" placeholder="Min. Score IQ" required />
				</div>
				<div class="col-md-3">
					<label class="control-label" for="imin_cs">Minimal CS *</label>
					<input id="imin_cs" type="number" name="min_cs" class="form-control" placeholder="Min. Score CS" min="0" max="99" required />
				</div>
			</div>

			<div id="pdetail" class="form-group">
				<div class="col-md-3">
					<label class="control-label">Gaji Minimum</label>
					<input id="ivsgaji" type="hidden" class="form-control " name="sgaji" placeholder="Gaji awal">
					<input id="isgaji" type="text" class="form-control rupiah-uang" name="" placeholder="Gaji awal" data-selector="ivsgaji">
				</div>
				<div class="col-md-3">
					<label class="control-label">Gaji Maksimum</label>
					<input id="ivegaji" type="hidden" class="form-control " name="egaji" placeholder="Gaji akhir">
					<input id="iegaji" type="text" class="form-control rupiah-uang" name="" placeholder="Gaji akhir" data-selector="ivegaji">
				</div>
				<div class="col-md-3">
					<label class="control-label">Jadwal Kerja</label>
					<select id="ittype" class="form-control" placeholder="" name="ttype">
						<option value="penuh" selected>Full time</option>
						<option value="paruh">Part time</option>
					</select>
				</div>
				<div class="col-md-3">
					<label class="control-label">Fresh Graduated Welcomed?</label>
					<select id="iis_freshg" class="form-control" placeholder="" name="is_freshg">
						<option value="1">Iya</option>
						<option value="0" selected>Tidak</option>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="col-md-12">
						<label for="igambar">Gambar (20KB - 3MB)</label>
						<input accept=".jpg,.png,.jpeg,.webp" type="file" id="igambar">
						<br />
						<label for="ibg_warna">Background Color</label>
						<input type="text" id="ibg_warna" class="form-control demo" data-control="hue" name="bg_warna" value="#ff6161">
					</div>
					<div class="col-md-3">
						<label for="iis_favorite" class="control-label">Is Favourite?</label>
						<select id="iis_favorite" class="form-control" placeholder="" name="is_favorite">
							<option value="0" selected>Tidak</option>
							<option value="1">Iya</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="iis_active" class="control-label">Aktif</label>
						<select id="iis_active" class="form-control" placeholder="" name="is_active">
							<option value="1">Iya</option>
							<option value="0">Tidak</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<img id="agambar" src="" alt="" class="img-responsive img-rounded">
				</div>
				<div class="col-md-12">
					<br>
				</div>
			</div>

			<div class="form-group form-actions">
				<div class="col-xs-12 text-right">
					<button type="button" class="btn btn-sm btn-default btn-submit" data-dismiss="modal">Close</button>
					<button id="bftambah" type="submit" class="btn btn-sm btn-primary btn-submit"> Simpan <i class="fa fa-save icon-submit"></i></button>
				</div>
			</div>
		</form>
	</div>
</div>
