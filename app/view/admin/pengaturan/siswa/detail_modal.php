<style>
	.text-left {
		text-align: left !important;
	}
</style>

<!-- modal option -->
<div id="modal_option" class="modal fade " tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header text-center">
				<h2 class="modal-title">Pilihan</h2>
			</div>
			<!-- END Modal Header -->

			<!-- Modal Body -->
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 btn-group-vertical " style="text-align: left;">
						<a id="adetail" href="#" class="btn btn-info text-left"><i class="fa fa-info-circle"></i> Detail Perusahaan</a>
						<a id="avendor_pengguna" href="#" class="btn btn-info text-left"><i class="fa fa-users"></i> Kelola Admin Vendor</a>
						<a id="avendor_brand" href="#" class="btn btn-info text-left"><i class="fa fa-briefcase"></i> Kelola Brand</a>
						<a id="aedit" href="#" class="btn btn-info text-left"><i class="fa fa-pencil"></i> Edit</a>
						<button id="bhapus" type="button" class="btn btn-info text-left"><i class="fa fa-trash-o"></i> Hapus</button>
					</div>
				</div>
				<div class="row" style="margin-top: 1em; ">
					<div class="col-md-12" style="border-top: 1px #afafaf dashed;">&nbsp;</div>
					<div class="col-xs-12 btn-group-vertical" style="">
						<button type="button" class="btn btn-default btn-block text-left" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
					</div>
				</div>
				<!-- END Modal Body -->
			</div>
		</div>
	</div>
</div>

<!-- modal tambah -->
<div id="modal_tambah" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header text-center">
				<h2 class="modal-title">Tambah</h2>
			</div>
			<!-- END Modal Header -->

			<!-- Modal Body -->
			<div class="modal-body">
				<form id="ftambah" action="<?=base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
					<fieldset>
						<div class="form-group">
							<div class="col-md-4">
								<label for="ikode">Kode*</label>
								<input id="ikode" type="text" name="kode" class="form-control" minlength="2" maxlength="8" placeholder="Kode cabang" required />
							</div>
							<div class="col-md-4">
								<label class="" for="inama">Inisial*</label>
								<input id="iinisial" type="text" name="inisial" class="form-control" minlength="1" maxlength="2" placeholder="inisial 2 digit huruf angka" required />
							</div>
							<div class="col-md-4">
								<label for="iutype">Jenis*</label>
								<select id="iutype" name="utype" class="form-control" required>
									<option value="pusat">Pusat</option>
									<option value="wilayah">Wilayah</option>
									<option value="cabang">Cabang</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-8">
								<label class="" for="inama">Nama Vendor*</label>
								<input id="inama" type="text" name="nama" class="form-control" minlength="1" placeholder="Nama Vendor" required />
							</div>
							<div class="col-md-4">
								<label for="itelp">NoHp / Telp</label>
								<input id="itelp" type="text" name="telp" class="form-control" minlength="1" placeholder="" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label" for="ialamat">Alamat *</label>
								<textarea id="ialamat" class="form-control" name="alamat" rows="5"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<label for="iis_active">Active</label>
								<select id="iis_active" name="is_active" class="form-control">
									<option value="1">Iya</option>
									<option value="0">Tidak</option>
								</select>
							</div>
						</div>
					</fieldset>
					<div class="form-group form-actions">
						<div class="col-xs-12 text-right">
							<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
						</div>
					</div>
				</form>
			</div>
			<!-- END Modal Body -->
		</div>
	</div>
</div>

<!-- modal edit -->
<div id="modal_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header text-center">
				<h2 class="modal-title">Edit</h2>
			</div>
			<!-- END Modal Header -->

			<!-- Modal Body -->
			<div class="modal-body">
				<form id="fedit" action="<?=base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
					<fieldset>
						<div class="form-group">
							<div class="col-md-4">
								<label for="iekode">Kode*</label>
								<input id="iekode" type="text" name="kode" class="form-control" minlength="2" maxlength="8" placeholder="Kode Cabang" required />
								<input id="ieid" name="id" type="hidden" value="" />
							</div>
							<div class="col-md-4">
								<label for="ieinisial">Inisial *</label>
								<input id="ieinisial" type="text" name="inisial" class="form-control" minlength="2" maxlength="2" placeholder="2 digit huruf angka" required />
							</div>
							<div class="col-md-4">
								<label for="ieutype">Jenis *</label>
								<select id="ieutype" name="utype" class="form-control" required>
									<option value="pusat">Pusat</option>
									<option value="wilayah">Wilayah</option>
									<option value="cabang">Cabang</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-8">
								<label class="" for="ienama">Nama*</label>
								<input id="ienama" type="text" name="nama" class="form-control" minlength="1" placeholder="nama vendor" required />
							</div>
							<div class="col-md-4">
								<label for="ietelp">Telp</label>
								<input id="ietelp" type="text" name="telp" class="form-control" minlength="1" placeholder="" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="control-label" for="iealamat">Alamat *</label>
								<textarea id="iealamat" class="form-control" name="alamat" required rows="5"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6">
								<label for="iekota">Kota</label>
								<input id="iekota" type="text" name="kota" class="form-control" minlength="1" placeholder="" />
							</div>
							<div class="col-md-6">
								<label for="ieis_active">Active</label>
								<select id="ieis_active" name="is_active" class="form-control">
									<option value="1">Iya</option>
									<option value="0">Tidak</option>
								</select>
							</div>
						</div>
					</fieldset>
					<div class="form-group form-actions">
						<div class="col-xs-12 text-right">
							<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
							<button id="bhapus" type="button" class="btn btn-sm btn-warning">Hapus</button>
							<button type="submit" class="btn btn-sm btn-primary">Simpan</button>
						</div>
					</div>
				</form>
			</div>
			<!-- END Modal Body -->
		</div>
	</div>
</div>
