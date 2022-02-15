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
						<button id="aedit" type="button" class="btn btn-info text-left btn-submit" style="text-align: left;"><i class="fa fa-pencil icon-submit"></i> Edit</button>
						<button id="bhapus" type="button" class="btn btn-danger text-left btn-submit" style="text-align: left;"><i class="fa fa-trash-o icon-submit"></i> Hapus</button>
					</div>
				</div>
				<div class="row" style="margin-top: 1em; ">
					<div class="col-md-12" style="border-top: 1px #afafaf dashed;">&nbsp;</div>
					<div class="col-xs-12 btn-group-vertical" style="">
						<button type="button" class="btn btn-default btn-block text-left btn-submit" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
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
				<h2 class="modal-title">Tambah Urutan Tes Skill</h2>
			</div>
			<!-- END Modal Header -->

			<!-- Modal Body -->
			<div class="modal-body">
				<form id="modal_tambah_form" action="<?= base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
					<div class="form-group">
						<div class="col-md-12">
							<label class="control-label" for="ia_banksoal_id">Pilih Bank Soal*</label>
							<select id="ia_banksoal_id" type="text" name="a_banksoal_id" class="form-control" required>
								<option value=""> -- pilih -- </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label class="control-label" for="iurutan">Urutan</label>
							<input id="iurutan" type="number" name="urutan" class="form-control" min="1" max="99" />
						</div>
						<div class="col-md-6">
							<label class="control-label" for="ipassing_grade">Passing Grade</label>
							<input id="ipassing_grade" type="number" name="passing_grade" class="form-control" min="0" max="100" />
						</div>
					</div>
					<div class="form-group form-actions">
						<div class="col-xs-12 text-right">
							<button type="button" class="btn btn-default btn-submit" data-dismiss="modal"><i class="fa fa-times icon-submit"></i> Tutup </button>
							<button id="btambah_submit" type="submit" value="simpan" class="btn btn-primary btn-submit"> Simpan <i class="fa fa-save icon-submit"></i></button>
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
				<h2 class="modal-title">Edit Profil</h2>
			</div>
			<!-- END Modal Header -->

			<!-- Modal Body -->
			<div class="modal-body">
				<form id="modal_edit_form" action="<?= base_url_admin(); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered">
					<input type="hidden" id="ieid" name="id" value="" />
					<input type="hidden" id="ieb_lowongan_id" name="b_lowongan_id" value="" />
					<div class="form-group">
						<div class="col-md-12">
							<label class="control-label" for="ia_banksoal_id">Pilih Bank Soal*</label>
							<select id="iea_banksoal_id" type="text" name="a_banksoal_id" class="form-control" required>
								<option value=""> -- pilih -- </option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<label class="control-label" for="iurutan">Urutan *</label>
							<input id="ieurutan" type="number" name="urutan" class="form-control" min="1" max="99" required />
						</div>
						<div class="col-md-6">
							<label class="control-label" for="ipassing_grade">Passing Grade</label>
							<input id="iepassing_grade" type="number" name="passing_grade" class="form-control" min="0" max="100" />
						</div>
					</div>
					<div class="form-group form-actions">
						<div class="col-xs-12 text-right">
							<button type="button" class="btn btn-default btn-submit" data-dismiss="modal"><i class="fa fa-times icon-submit"></i> Tutup </button>
							<button type="submit" value="simpan" class="btn btn-primary btn-submit"> Simpan <i class="fa fa-save icon-submit"></i></button>
						</div>
					</div>
				</form>
			</div>
			<!-- END Modal Body -->
		</div>
	</div>
</div>
