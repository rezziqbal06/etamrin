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
					<div class="col-xs-12 btn-group-vertical ">
						<!-- <a id="adetail" href="#" class="btn btn-info text-left" style="text-align: left;"> Detail <i class="fa fa-info-circle"></i></a> -->
						<button id="aedit" type="button" class="btn btn-info text-left" style="text-align: left;"><i class="fa fa-pencil"></i> Edit </button>
						<button id="bhapus" type="button" class="btn btn-danger text-left btn-submit" style="text-align: left;"><i class="fa fa-trash-o icon-submit"></i> Hapus </button>
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
<div id="modal_tambah" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header text-center">
				<h2 class="modal-title">Tambah</h2>
			</div>
			<!-- END Modal Header -->

			<!-- Modal Body -->
			<div class="modal-body">
				<form id="ftambah" action="<?= base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
					<fieldset>
						<div class="form-group row">
							<div class="col-md-4">
								<label class="" for="inama">Kelas *</label>
								<input id="inama" type="text" name="nama" class="form-control" minlength="1" maxlength="200" placeholder="Nama Kelas" required />
							</div>
							<div class="col-md-8">
								<label class="" for="iwali_kelas">Wali Kelas *</label>
								<input id="iwali_kelas" type="text" name="wali_kelas" class="form-control" minlength="1" maxlength="200" placeholder="Wali Kelas" required />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="" for="iwali_kelas">Deskripsi</label>
								<textarea id="ideskripsi" type="text" name="deskripsi" class="form-control" minlength="1" maxlength="200" minlength="3" placeholder="Deskripsi"></textarea>
							</div>
						</div>
					</fieldset>

					<div class="form-group form-actions">
						<div class="col-xs-12 text-right">
							<button type="button" class="btn btn-sm btn-default btn-submit" data-dismiss="modal">Close</button>
							<button id="bftambah" type="submit" class="btn btn-sm btn-primary btn-submit"> Simpan <i class="fa fa-save icon-submit"></i></button>
						</div>
					</div>
				</form>
			</div>
			<!-- END Modal Body -->
		</div>
	</div>
</div>

<!-- modal edit -->
<div id="modal_edit" class="modal fade" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header text-center">
				<h2 class="modal-title">Edit</h2>
			</div>
			<!-- END Modal Header -->

			<!-- Modal Body -->
			<div class="modal-body">
				<form id="fedit" action="<?= base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
					<fieldset>
						<div class="form-group row">
							<input id="ieid" type="hidden" name="id" class="form-control" minlength="1" maxlength="200" placeholder="Nama Kelas" />
							<div class="col-md-4">
								<label class="" for="ienama">Kelas *</label>
								<input id="ienama" type="text" name="nama" class="form-control" minlength="1" maxlength="200" placeholder="Nama Kelas" required />
							</div>
							<div class="col-md-8">
								<label class="" for="iewali_kelas">Wali Kelas *</label>
								<input id="iewali_kelas" type="text" name="wali_kelas" class="form-control" minlength="1" maxlength="200" placeholder="Wali Kelas" required />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<label class="" for="iewali_kelas">Deskripsi</label>
								<textarea id="iedeskripsi" type="text" name="deskripsi" class="form-control" minlength="1" maxlength="200" minlength="3" placeholder="Deskripsi"></textarea>
							</div>
						</div>
					</fieldset>
					<div class="form-group form-actions">
						<div class="col-xs-12 text-right">
							<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Tutup</button>
							<button type="submit" class="btn btn-sm btn-primary btn-submit"> Simpan Perubahan <i class="fa fa-save icon-submit"></i></button>
						</div>
					</div>
				</form>
			</div>
			<!-- END Modal Body -->
		</div>
	</div>
</div>