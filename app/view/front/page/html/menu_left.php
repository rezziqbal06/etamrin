<style>
.accordion-item.hidden {
  display: none;
}
</style>
<div id="leftMenuAccordion" class="accordion" >
  <div class="accordion-item">
    <h2 class="accordion-header" id="menuUtamaHeading">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama" aria-expanded="false" aria-controls="menuUtama">
        Dashboard
      </button>
    </h2>
    <div id="menuUtama" class="accordion-collapse collapse" aria-labelledby="menuUtamaHeading" data-bs-parent="#leftMenuAccordion">
      <div class="accordion-body">
        <ul class="nav flex-column">
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/dashboard')?>" class="dropdown-item p-2 menu_left_kandidat_dashboard">Dashboard</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/verifikasi')?>" class="dropdown-item p-2 menu_left_kandidat_verifikasi_email">Verifikasi Email</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('logout')?>" class="dropdown-item p-2 menu_left_kandidat_logout">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="menuApplyLowonganHeading">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuApplyLowongan" aria-expanded="false" aria-controls="menuApplyLowongan">
        1. Apply Lowongan
      </button>
    </h2>
    <div id="menuApplyLowongan" class="accordion-collapse collapse" aria-labelledby="menuApplyLowonganHeading" data-bs-parent="#leftMenuAccordion">
      <div class="accordion-body">
        <ul class="nav flex-column">
          <li class="nav-item mb-1"><a href="<?=base_url('joblist/')?>" class="dropdown-item p-2">Pilih Lowongan</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item <?=(isset($sess->user->apply_statno) && $sess->user->apply_statno > 1) ? '' : 'hidden' ?>">
    <h2 class="accordion-header" id="menuTesAwalHeading">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuTesAwal" aria-expanded="false" aria-controls="menuTesAwal">
        2. Tes Awal
      </button>
    </h2>
    <div id="menuTesAwal" class="accordion-collapse collapse" aria-labelledby="menuTesAwalHeading" data-bs-parent="#leftMenuAccordion">
      <div class="accordion-body">
        <ul class="nav flex-column">
          <li class="nav-item mb-1"><a href="<?=base_url('tes/cs')?>" class="dropdown-item p-2 menu_left_tes_cs">Tes CS</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item <?=(!isset($sess->user->is_testawal_done) || empty($sess->user->is_testawal_done)) ? 'hidden' : '' ?>">
    <h2 class="accordion-header" id="menuDataPelamarHeading">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuDataPelamar" aria-expanded="false" aria-controls="menuDataPelamar">
        3. Data Pelamar
      </button>
    </h2>
    <div id="menuDataPelamar" class="accordion-collapse collapse" aria-labelledby="menuDataPelamarHeading" data-bs-parent="#leftMenuAccordion">
      <div class="accordion-body">
        <ul class="nav flex-column">
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/profil')?>" class="dropdown-item p-2 menu_left_kandidat_profile">Data Pribadi</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/riwayat/keluarga')?>" class="dropdown-item p-2 menu_left_kandidat_riwayat_keluarga">Data Keluarga</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/riwayat/pekerjaan')?>" class="dropdown-item p-2 menu_left_kandidat_riwayat_pekerjaan">Riwayat Pekerjaan</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/riwayat/formal')?>" class="dropdown-item p-2 menu_left_kandidat_riwayat_formal">Riwayat Pendidikan Formal</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/riwayat/informal')?>" class="dropdown-item p-2 menu_left_kandidat_riwayat_informal">Riwayat Pendidikan Non formal</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/riwayat/organisasi')?>" class="dropdown-item p-2 menu_left_kandidat_riwayat_organisasi">Riwayat Organisasi & Profesi</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/skill/bahasa')?>" class="dropdown-item p-2 menu_left_kandidat_skill_bahasa">Kemampuan Bahasa Asing</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/skill/komputer')?>" class="dropdown-item p-2 menu_left_kandidat_skill_komputer">Penguasaan Komputer</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/keterangan/referensi')?>" class="dropdown-item p-2 menu_left_kandidat_keterangan_referensi">Referensi &amp; Rekomendasi</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/keterangan/kenalan')?>" class="dropdown-item p-2 menu_left_kandidat_keterangan_kenalan">Kenalan</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/keterangan')?>" class="dropdown-item p-2 menu_left_kandidat_keterangan">Keterangan Lainnya</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/upload')?>" class="dropdown-item p-2 menu_left_kandidat_upload">Upload Persyaratan</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item <?=(!isset($sess->user->is_testawal_done) || empty($sess->user->is_testawal_done)) ? 'hidden' : '' ?>">
    <h2 class="accordion-header" id="headingPsikotest">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#psikotestMenu" aria-expanded="false" aria-controls="psikotestMenu">
        4. Psikotest
      </button>
    </h2>
    <div id="psikotestMenu" class="accordion-collapse collapse" aria-labelledby="headingPsikotest" data-bs-parent="#leftMenuAccordion">
      <div class="accordion-body">
        <ul class="nav flex-column">
          <li class="nav-item mb-1"><a href="<?=base_url('tes/iq')?>" class="dropdown-item p-2 menu_left_tes_iq">Tes Intelegensi</a></li>
          <li class="nav-item mb-1"><a href="<?=base_url('tes/kepribadian')?>" class="dropdown-item p-2 menu_left_tes_kepribadian">Tes Kepribadian</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item <?=(!isset($sess->user->is_testawal_done) || empty($sess->user->is_testawal_done)) ? 'hidden' : '' ?>">
    <h2 class="accordion-header" id="headingInterview">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuInterview" aria-expanded="false" aria-controls="menuInterview">
        5. Interview
      </button>
    </h2>
    <div id="menuInterview" class="accordion-collapse collapse" aria-labelledby="headingInterview" data-bs-parent="#leftMenuAccordion">
      <div class="accordion-body">
        <ul class="nav flex-column">
          <li class="nav-item mb-1"><a href="<?=base_url('interview')?>" class="dropdown-item p-2 menu_left_interview">Interview</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="accordion-item <?=((!isset($sess->user->is_testawal_done) || empty($sess->user->is_testawal_done)) && (isset($cam->is_process) && !empty($cam->is_process))) ? 'hidden' : '' ?>">
    <h2 class="accordion-header" id="menuKontrakHeading">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#menuKontrak" aria-expanded="false" aria-controls="menuKontrak">
        Hasil Seleksi
      </button>
    </h2>
    <div id="menuKontrak" class="accordion-collapse collapse" aria-labelledby="menuKontrakHeading" data-bs-parent="#leftMenuAccordion">
      <div class="accordion-body">
        <ul class="nav flex-column">
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/hasilseleksi')?>" class="dropdown-item p-2 menu_left_kandidat_hasilseleksi">Hasil Seleksi</a></li>
          <?php if(isset($this->config->semevar->verifikasi_data_enabled) && !empty($this->config->semevar->verifikasi_data_enabled) && empty($cam->is_process) && empty($cam->is_failed)){ ?>
          <li class="nav-item mb-1"><a href="<?=base_url('kandidat/verifikasi/data')?>" class="dropdown-item p-2 menu_left_kandidat_hasilseleksi">Verifikasi Data</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>

</div>
