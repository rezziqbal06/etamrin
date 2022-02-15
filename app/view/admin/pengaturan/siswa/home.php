<style>
	.select2 {
		width: 100% !important;
	}
</style>
<div id="page-content">
	<!-- Static Layout Header -->
	<div class="content-header">
		<div class="row" style="">
			<div class="col-md-6"></div>
			<div class="col-md-6">
				<div class="btn-group pull-right">
					<button id="atambah" type="button" class="btn btn-info btn-submit"><i class="fa fa-plus icon-submit"></i> Baru</button>
				</div>
			</div>
		</div>
	</div>

	<!-- END Static Layout Header -->

	<!-- Content -->
	<div class="card">

		<div class="card-header">
			<h6><strong>Kelas</strong></h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table id="drTable" class="table table-vcenter table-condensed table-bordered">
					<thead>
						<tr>
							<th class="text-center">ID</th>
							<th>Nama</th>
							<th>Kelas</th>
							<th>NIS</th>
							<!-- <th>Angkatan</th> -->
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- END Content -->

	<div class="block mt-3">
		<div class="block-title">
			<h6><strong>Import Kelas</strong></h6>
		</div>

		<div class="row" style="margin-top:16px">
			<div class="col-md-7">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<span id="caption"></span>
								<div class="progress mt-4" style="height:15px">
									<div class="progress-bar progress-bar-striped bg-warning active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; "></div>
								</div>
							</div>
							<div class="col-md-12">
								<form id="fimport" class="mt-5" method="POST">
									<div class="row">
										<div class="col-md-9">
											<div class="form-group">
												<label for="file_import" class="control-label">Pilih File Excel</label>
												<input id="file_import" name="file_import" type="file" accept=".xls,.xlsx" class="form-control" required />
											</div>
										</div>
										<div class="col-md-3">
											<label for="" class="control-label" style="color:transparent">Action</label>
											<button type="submit" role="button" class="btn btn-warning btn-rounded btn-submit">Import <i class="fa fa-upload icon-submit"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="col-md-5 d-none">
				<div class="card">
					<div class="card-body">
						<div class="card-title">
							Silakan unduh file format yang digunakan untuk import Kelas
						</div>
						<div class="row">
							<div class="col-md-12">
								<form method="GET" action="<?= base_url() ?>/media/file/import_Kelas.xlsx">
									<div class="btn-group" role="group">
										<button type="submit" class="btn btn-primary">Unduh Format</button>
										<button id="btnNote" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#noteModal"><i class="fa fa-info-circle"></i> Catatan sebelum import </button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>