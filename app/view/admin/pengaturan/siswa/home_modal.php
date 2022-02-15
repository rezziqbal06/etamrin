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
						<a id="aedit" href="#" class="btn btn-info text-left" style="text-align: left;"><i class="fa fa-pencil"></i> Edit </a>
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
								<label class="" for="inama">Nama *</label>
								<input id="inama" type="text" name="fnama" class="form-control" minlength="1" maxlength="200" placeholder="Nama Jabatan" required />
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
						<div class="form-group">
							<div class="col-md-4">
								<label class="" for="ienama">Nama *</label>
								<input id="ieid" type="hidden" name="id" class="form-control" minlength="1" maxlength="200" placeholder="id" required />
								<input id="ienama" type="text" name="nama" class="form-control" minlength="1" maxlength="200" placeholder="Nama Jabatan" required />
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

<div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="noteModalLabel">Catatan</h5>
			</div>
			<div class="modal-body">
				<ul class="list-group list-group-numbered">
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<strong class="fw-bold">Sesuaikan format</strong>
							<p>Pastikan urutan kolom pada tabel sesuai dengan contoh format yang diberikan. Jika terjadi perubahan maka data input tidak sesuai yang diinginkan</p>
						</div>
					</li>
					<li class="list-group-item d-flex justify-content-between align-items-start">
						<div class="ms-2 me-auto">
							<strong class="fw-bold">Auto Update</strong>
							<p>Jika ada data import yang memiliki penamaan Jabatan yang sama dengan data di database atau di file import itu sendiri, maka sistem akan otomatis update data tersebut (tidak menambahkan menjadi data yang baru).</p>
						</div>
					</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info btn-block text-left" data-dismiss="modal">Saya mengerti</button>
			</div>
		</div>
	</div>
</div>