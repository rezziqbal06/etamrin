<div id="page-content">
	<!-- Static Layout Header -->
	<div class="content-header">
		<div class="row" style="padding: 0.5em 2em;">
			<div class="col-md-6">
				<a onclick="window.history.go(-1); return false;" class="btn btn-default"><i class="fa fa-chevron-left"></i> Kembali</a>
			</div>
			<div class="col-md-6">
				<div class="btn-group pull-right">
					<a href="<?= base_url_admin("pengaturan/lowongan/tes/$lowongan->id") ?>" class="btn btn-default"><i class="fa fa-list"></i> Urutan Tes Skill</a>
					<a href="<?= base_url_admin("pengaturan/lowongan/edit/$lowongan->id") ?>" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
				</div>
			</div>
		</div>
	</div>
	<ul class="breadcrumb breadcrumb-top">
		<li>Admin</li>
		<li>Pengaturan</li>
		<li><a href="<?= base_url_admin() ?>pengaturan/lowongan/">Lowongan</a></li>
		<li>Detail #<?= $lowongan->id ?></li>
	</ul>
	<!-- END Static Layout Header -->

	<!-- Content -->
	<div class="row">
		<div class="col-md-6">
			<div class="block full">

				<div class="block-title">
					<h2><strong>Lowongan: <?= $lowongan->nama ?></strong></h2>
				</div>

				<table class="table table-borderless table-striped">
					<tbody>
						<tr>
							<td><strong>Posisi</strong></td>
							<td><strong><?= strtoupper($lowongan->jabatan_nama); ?></strong></td>
						</tr>
						<tr>
							<td><strong>Penempatan</strong></td>
							<td><?= strtoupper($lowongan->company_nama); ?></td>
						</tr>
						<tr>
							<td><strong>Wilayah</strong></td>
							<td><?= strtoupper($lowongan->lok_area); ?></td>
						</tr>
						<tr>
							<td><strong>Tanggal Mulai</strong></td>
							<td><?= $this->__dateIndonesia($lowongan->sdate); ?></td>
						</tr>
						<tr>
							<td><strong>Berakhir Pada</strong></td>
							<td><?= $this->__dateIndonesia($lowongan->edate); ?></td>
						</tr>
						<tr>
							<td><strong>Expired</strong></td>
							<td><?= $lowongan->expired; ?></td>
						</tr>
						<tr>
							<td><strong>Jabatan User Interviewer 1</strong></td>
							<td><?= isset($jabatan_interviewer1->nama) ? $jabatan_interviewer1->nama : '-'?></td>
						</tr>
						<tr>
							<td><strong>Jabatan User Interviewer 2</strong></td>
							<td><?= isset($jabatan_interviewer2->nama) ? $jabatan_interviewer2->nama : '-' ?></td>
						</tr>
						<tr>
							<td><strong>Status</strong></td>
							<td>
								<?php
								$ia = (int) $lowongan->is_active;
								if ($ia == 1) {
									echo '<label class="label label-success">Aktif</label>';
								} else {
									echo '<label class="label label-default">Tidak Aktif</label>';
								}
								?>

								<?php
								$ia = (int) $lowongan->is_favorite;
								if ($ia == 1) {
									echo ' <label class="label label-info">Favorite</label>';
								}
								?>
							</td>
						</tr>

					</tbody>
				</table>

			</div>



		</div>
		<div class="col-md-6">
			<div class="block full">

				<div class="block-title" style="background-color: <?= $lowongan->bg_warna ?>;">
					<h2><strong>Detail</strong></h2>
				</div>
				<table class="table table-borderless table-striped">
					<tbody>
						<tr>
							<td rowspan="2"><strong>Penawaran Gaji</strong></td>
							<td><?= 'Rp. ' . number_format($lowongan->sgaji)?></td>
						</tr>
						<tr>
							<td><?= 'Rp. ' . number_format($lowongan->egaji); ?></td>
						</tr>
						<tr>
							<td><strong>Min Pendidikan</strong></td>
							<td><strong><?= strtoupper($lowongan->min_pendidikan); ?></strong></td>
						</tr>
						<tr>
							<td><strong>Min Pengalaman</strong></td>
							<td><?= $lowongan->min_exp . " tahun"; ?></td>
						</tr>
						<tr>
							<td><strong>Max Usia</strong></td>
							<td><?= $lowongan->max_usia . " tahun"; ?></td>
						</tr>
						<tr>
							<td><strong>Min IQ</strong></td>
							<td><?= $lowongan->min_iq . ""; ?></td>
						</tr>
						<tr>
							<td><strong>Min CS</strong></td>
							<td><?= $lowongan->min_cs . ""; ?></td>
						</tr>
						<tr>
							<td><strong>Fresh Graduate</strong></td>
							<td>
								<?php
								if (!empty($lowongan->is_freshg)) {
									echo '<label class="label label-success"><i class="fa fa-check"></i></label>';
								} else {
									echo '<label class="label label-default"><i class="fa fa-minus"></i></label>';
								}
								?>
								<?php if (strlen($lowongan->is_freshg)) {
									echo ' <label class="label label-info">' . $lowongan->is_freshg . '</label>';
								} ?>
							</td>
						</tr>
						<tr>
							<td><strong>Full Time</strong></td>
							<td>
								<?php
								if ($lowongan->ttype == 'penuh') {
									echo '<label class="label label-success"><i class="fa fa-check"></i></label>';
								} else {
									echo '<label class="label label-default"><i class="fa fa-minus"></i></label>';
								}
								?>
							</td>
						</tr>
						<tr>
							<td><strong>Gambar</strong></td>
							<td><img src="<?php if (strlen($lowongan->gambar) > 5)  echo $this->cdn_url($lowongan->gambar); ?>" class="img-responsive img-rounded"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-12">
			<div class="block">
				<div class="block-title">
					<h2><strong>Deskripsi</strong></h2>
				</div>

				<div class="std">

					<td><?= $lowongan->deskripsi; ?></td>
				</div>
			</div>


		</div>
		<div class="col-md-6">
			<div class="block">
				<div class="block-title">
					<h2><strong>Kemampuan/Requirement</strong></h2>
				</div>

				<ul class="list-group">
					<?php if (isset($kemampuan[0]->id)) {
						foreach ($kemampuan as $k) { ?>
							<li class="list-group-item"><?= $k->nama ?></li>
						<?php	}
					} else { ?>
						<li class="list-group-item">-</li>
					<?php } ?>

				</ul>
			</div>
		</div>

		<div class="col-md-6">
			<div class="block">
				<div class="block-title">
					<h2><strong>Urutan Tes</strong></h2>
				</div>

				<ul class="list-group">
					<?php if (isset($tes_sequence[0]->id)) {
						$i = 1;
						foreach ($tes_sequence as $k) { ?>
							<li class="list-group-item"><?= $i . '. ' . $k->a_banksoal_nama.' (Passing Grade: '.$k->passing_grade.')' ?></li>
							<?php $i++;
						}
					} else { ?>
						<li class="list-group-item">-</li>
					<?php } ?>

				</ul>
			</div>
		</div>
		<div class="col-md-12">
			<div class="block">
				<p class="text-muted text-right" style="margin-bottom:0; font-size: smaller;">dibuat pada <?=$this->__dateIndonesia($lowongan->cdate, 'hari_tanggal_jam')?></p>
				<p class="text-muted text-right" style="font-size: smaller;">terakhir diperbarui pada <?=$this->__dateIndonesia($lowongan->ldate, 'hari_tanggal_jam')?></p>
			</div>
		</div>

	</div>


	<!-- END Content -->
</div>
