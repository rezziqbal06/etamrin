<style>
	.select2 {
		width: 100% !important;
	}
</style>
<div id="page-content">
	<!-- Static Layout Header -->
	<div class="content-header">
		<div class="row" style="padding: 0.5em 2em;">
			<div class="col-md-6"></div>
			<div class="col-md-6">
				<div class="btn-group pull-right">
					<a href="<?=base_url_admin("pengaturan/lowongan/baru/")?>" class="btn btn-info btn-submit" ><i class="fa fa-plus icon-submit"></i> Baru</a>
				</div>
			</div>
		</div>
	</div>
	<ul class="breadcrumb breadcrumb-top">
		<li>Admin</li>
		<li>Pengaturan</li>
		<li>Lowongan</li>
	</ul>
	<!-- END Static Layout Header -->

	<!-- Content -->
	<div class="block full">

		<div class="block-title">
			<h2><strong>Lowongan Pekerjaan</strong></h2>
		</div>

		<div class="row row-filter">
			<div class="col-md-1">&nbsp;</div>
			<div class="col-md-3">
				<label for="fl_sdate">Tgl Berakhir Dari</label>
				<input type="text" id="fl_min_edate" class="form-control input-datepicker" autocomplete="off" data-date-format="yyyy-mm-dd" />
			</div>
			<div class="col-md-3">
				<label for="fl_edate">Tgl Berakhir Sampai</label>
				<input type="text" id="fl_max_edate" class="form-control input-datepicker" autocomplete="off" data-date-format="yyyy-mm-dd" />
			</div>
			<div class="col-md-2">
				<label>Favorit</label>
				<select id="fl_utype" class="form-control">
					<option value="">-- Semua --</option>
					<option value="1">Iya</option>
					<option value="0">Tidak</option>
				</select>
			</div>
			<div class="col-md-2">
				<label>Status</label>
				<select id="fl_is_active" class="form-control">
					<option value="">-- Semua --</option>
					<option value="1">Aktif</option>
					<option value="0">Tidak Aktif</option>
				</select>
			</div>
			<div class="col-md-1">
				<label>&nbsp;</label>
				<button id="fl_do" class="btn btn-default btn-block btn-submit"><i class="fa fa-filter icon-submit"></i></button>
			</div>
		</div>

		<div class="table-responsive">
			<table id="drTable" class="table table-vcenter table-condensed table-bordered">
				<thead>
					<tr>
						<th class="text-center">ID</th>
						<th>Posisi</th>
						<th>Tanggal Mulai</th>
						<th>Berakhir Pada</th>
						<th>Pengalaman</th>
						<th>Pendidikan</th>
						<th>Usia</th>
						<th>Status</th>
						<th>Min IQ</th>
						<th>Min CS</th>
						<th>Urutan Tes Skill</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	</div>
	<!-- END Content -->
</div>
