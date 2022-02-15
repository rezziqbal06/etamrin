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
			<div class="col-md-6">
				<a href="<?=base_url_admin('pengaturan/lowongan/detail/'.$lowongan->id)?>" class="btn btn-default"><i class="fa fa-chevron-left"></i> Kembali</a>
			</div>
			<div class="col-md-6">
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-info btn-submit b-data-tambah-modal" ><i class="fa fa-plus icon-submit"></i> Tes</button>
				</div>
			</div>
		</div>
	</div>
	<ul class="breadcrumb breadcrumb-top">
		<li>Admin</li>
		<li>Pengaturan</li>
		<li><a href="<?= base_url_admin() ?>pengaturan/lowongan/">Lowongan</a></li>
    <li><a href="<?= base_url_admin() ?>pengaturan/lowongan/detail/<?=$lowongan->id?>">Detail #<?=$lowongan->id?></a></li>
		<li>Urutan Tes</li>
	</ul>
	<!-- END Static Layout Header -->

	<div class="block full">
		<div class="block-title">
			<h2><strong>Urutan Tes</strong></h2>
		</div>

		<div class="table-responsive">
			<table id="drTable" class="table table-vcenter table-condensed table-bordered">
				<thead>
					<tr>
						<th class="text-center">ID</th>
						<th class="text-center">Urutan</th>
						<th>Jenis Bank Soal</th>
						<th>Nama Bank Soal</th>
						<th>Ket. Bank Soal</th>
						<th>Passing Grade</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	</div>

</div>
