<style>
.select2-container {
  padding-top: 0.25em;
  padding-bottom: 0.25em;
  border-radius: 0.25rem;
  border-bottom: 1px #acacac solid;
}

.select2-container--default .select2-selection--single{
  border: none;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
  font-size: 1rem;
}
.select2-results__option .select2-results__message {
  content: 'Isi nama daerah / kecamatan'
}
</style>

<img id="foto_ori" src="" style="display: none;" />
<img id="foto_rsz" src="" style="display: none;" />

<form id="register_form" action="" method="post">
  <div class="row">
    <div class="col-3 col-sm-4 col-md-4 col-lg-3 col-xl-3">
      <div class="">
        <img id="foto_preview" src="<?=$this->cdn_url('media/pas-foto-template.jpg')?>" class="img-fluid img-pas-foto-selector" />
        <label class="img-pas-foto-tips" for="ifoto">Sentuh / klik pada gambar untuk memilih file pas foto.</label>
      </div>
      <div class="mb-3">
        <input id="ifoto" type="file" accept=".png,.jpg,.jpeg" style="height: 0px;" />
      </div>
    </div>
    <div class="col-9 col-sm-8 col-md-8 col-lg-9 col-xl-9">
      <div class="row">
        <div class="col-12 col-md-12 col-sm-12 col-lg-7">
          <div class="mb-3">
            <label class="form-label" for="inoktp">No KTP *</label>
            <input class="form-control" id="inoktp" type="number" name="noktp" placeholder="Nomor KTP" minlength="16" maxlength="16" required />
          </div>
        </div>
        <div class="col-12 col-sm-12 col-lg-5 ">
          <div class="mb-3">
            <label class="form-label" for="ijk">Jenis Kelamin *</label>
            <select class="form-control" id="ijk" name="jk" required>
              <option value="">--pilih--</option>
              <option value="1">Laki-laki</option>
              <option value="0">Perempuan</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="mb-3">
            <label class="form-label" for="inama">Nama Lengkap *</label>
            <input class="form-control" id="inama" type="text" name="fnama" placeholder="Nama Lengkap tanpa gelar" maxlength="150" required />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="mb-3">
            <label class="form-label" for="icnama">Nama Panggilan *</label>
            <input class="form-control" id="icnama" type="text" name="cnama" placeholder="Nama Panggilan" maxlength="30" required />
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-12 col-sm-12 col-lg-5">
      <div class="mb-3">
        <label class="form-label" for="iagama">Agama *</label>
        <select id="iagama" name="agama" class="form-control">
          <option value="Islam">Islam</option>
          <option value="Katolik">Katolik</option>
          <option value="Kristen">Kristen</option>
          <option value="Hindu">Hindu</option>
          <option value="Budha">Budha</option>
          <option value="Konghucu">Konghucu</option>
        </select>
      </div>
    </div>
    <div class="col-6 col-sm-6 col-lg-4 ">
      <div class="mb-3">
        <label class="form-label" for="itinggi_badan">Tinggi Badan (CM)</label>
        <input class="form-control" id="itinggi_badan" type="number" name="tinggi_badan" min="0" max="250" placeholder="centimeter" required />
      </div>
    </div>
    <div class="col-6 col-sm-6 col-lg-3 ">
      <div class="mb-3">
        <label class="form-label" for="iberat_badan">Berat Badan (KG)</label>
        <input class="form-control" id="iberat_badan" type="number" name="berat_badan" min="0" max="250" placeholder="kilogram"  required />
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-md-6 col-sm-6">
      <div class="mb-3">
        <label class="form-label" for="itlahir">Tempat Lahir *</label>
        <input class="form-control" id="itlahir" type="text" name="tlahir" placeholder="Nama Kota, Negara" required />
      </div>
    </div>
    <div class="col-12 col-md-6 col-sm-6">
      <div class="mb-3">
        <label class="form-label" for="ibdate">Tgl Lahir *</label>
        <input class="form-control input-datepicker" id="ibdate" type="text" name="bdate" placeholder="TTTT-BB-HH" data-dateformat="yyyy-mm-dd" autocomplete="off" required />
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-sm-6 col-md-6">
      <div class="mb-3">
        <label class="form-label" for="ikerja_exp_y">Pengalaman Kerja * <a href="#" class="text-info" data-bs-toggle="modal" data-bs-target="#modal_help_guide"><i class="fa fa-info-circle"></i></a></label>
        <input class="form-control" id="ikerja_exp_y" type="number" name="kerja_exp_y" min="0" max="99" placeholder="Total tahun bekerja" required />
      </div>
    </div>
    <div class="col-12 col-sm-6 col-md-6">
      <div class="mb-3">
        <label class="form-label" for="ipendidikan_terakhir">Pendidikan Terakhir *</label>
        <select class="form-control" id="ipendidikan_terakhir" name="pendidikan_terakhir" required>
          <option value="">--pilih--</option>
          <?php foreach($this->config->semevar->pendidikans as $k=>$v){ if($v<3) continue; ?>
          <option value="<?=$k?>"><?=$k?></option>
          <?php } ?>
        </select>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="mb-3">
        <label class="form-label" for="ialamat">Alamat KTP *</label>
        <select class="form-control kabkota-select2" id="idomisili_alamat" name="domisili" required>
          <option value="">--pilih--</option>
        </select>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="mb-3">
        <label class="form-label" for="ialamat">Alamat Sekarang *</label>
        <select class="form-control kabkota-select2" id="ialamat" name="alamat" required>
          <option value="">--pilih--</option>
        </select>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-12 col-md-6 col-sm-6">
      <div class="mb-3">
        <label class="form-label" for="iemail">Email *</label>
        <input class="form-control" id="iemail" type="email" name="email" placeholder="Email" required />
      </div>
    </div>
    <div class="col-12 col-md-12 col-sm-12 col-lg-6">
      <div class="mb-3">
        <label class="form-label" for="itelp">No HP / WhatsApp *</label>
        <input class="form-control" id="itelp" type="number" name="telp" placeholder="Nomor HP / Telepon" required />
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-md-6 col-sm-6">
      <div class="mb-3">
        <label class="form-label" for="ipassword">Password *</label>
        <input class="form-control" id="ipassword" type="password" name="password" placeholder="Password" minlength="5" maxlength="24" required />
      </div>
    </div>

    <div class="col-12 col-md-6 col-sm-6">
      <div class="mb-3">
        <label class="form-label" for="iulangpassword">Konfirmasi Password *</label>
        <input class="form-control" id="iulangpassword" type="password" name="password_confirm" minlength="5" maxlength="24"  placeholder="Konfirmasi Password" required />
      </div>
    </div>
  </div>

  <div class="col-12">
    <?php $this->getThemeElement('page/_notice_agreement',$__forward); ?>
  </div>

  <!-- Message Notification -->


  <div class="row">
    <div class="col">
      <div class="form-message"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="mb-3">
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary btn-submit">
            Daftar <i class="fa fa-save icon-submit"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <br>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="mt-5">
        <div class="d-grid gap-2 text-center">
          <a href="<?=base_url()?>login" class="login a-login-to">Login</a>
          atau
          <a href="<?=base_url()?>lupa" class="lupa">Lupa Password</a>
        </div>
      </div>
    </div>
  </div>

</form>
