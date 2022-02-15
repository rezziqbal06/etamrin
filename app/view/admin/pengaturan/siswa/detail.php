<div id="page-content">
	<!-- Static Layout Header -->
	<div class="content-header">
		<div class="row" style="padding: 0.5em 2em;">
			<div class="col-md-6">
				<div class="btn-group">
					<a href="<?=base_url_admin('pengaturan/jabatan/'); ?>" class="btn btn-default"><i class="fa fa-chevron-left"></i> Kembali</a>
				</div>
			</div>
			<div class="col-md-6" style="display:none;">
			</div>
		</div>
	</div>
	<ul class="breadcrumb breadcrumb-top">
		<li>Admin</li>
		<li>Pengaturan</li>
		<li>Jabatan</li>
		<li>#<?=$ajm->id?></li>
	</ul>
	<!-- END Static Layout Header -->

	<!-- Content -->
	<div class="row">
		<div class="col-md-12">
			<!-- Info Block -->
			<div class="block">
				<!-- Info Title -->
				<div class="block-title">
					<h2><strong></strong></h2>
				</div>
				<!-- END Info Title -->
				<h1><b><?=$ajm->nama; ?></b></h1>
				<div class="row">
					<div class="col-md-6">
						<?php if(isset($ajm->departemen->nama)){ ?>
						<h4><b>Departemen</b></h4>
						<p><?=$ajm->departemen->nama?></p>
						<?php } ?>

						<h4><b>Kemampuan</b></h4>
						<ul>
							<?php foreach($ajm->requirements as $r){ ?>
							<li><?=$r->nama?></li>
							<?php } ?>
						</ul>
					</div>
					<div class="col-md-6">
						<h4><b>Maks. Usia</b></h4>
						<p><?=$ajm->max_usia?> Tahun</p>
						<h4><b>Min. Pendidikan</b></h4>
						<p><?=$ajm->min_pendidikan?></p>
						<h4><b>Min. Pengalaman</b></h4>
						<p><?=$ajm->min_exp?> Tahun</p>
						<h4><b>Min. IQ</b></h4>
						<p><?=$ajm->min_iq?></p>
					</div>

					<div class="col-md-12">
						<p class="text-muted text-right" style="margin-bottom:0; font-size: smaller;">dibuat pada <?=$this->__dateIndonesia($ajm->cdate, 'hari_tanggal_jam')?></p>
						<p class="text-muted text-right" style="margin-bottom:0; font-size: smaller;">terakhir diperbarui pada <?=$this->__dateIndonesia($ajm->ldate, 'hari_tanggal_jam')?></p>
					</div>
				</div>

			</div>
			<!-- END Info Block -->
		</div>

	</div>
	<!-- END Content -->
</div>
