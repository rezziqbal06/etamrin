<footer class="footer-area ">
  <div class="container">
    <div class="row">
      <div class="col-12">

          <div class="footer-widget-area sp-y footer-top">
            <div class="row mtn-35 footer-desc">
              <div class="col-md-12 ">
                <div class="widget-item">
                  <div class="about-widget">
                    <a href="<?= base_url() ?>">
                      <img src="<?= $this->cdn_url($this->config->semevar->site_logo_big) ?>" alt="<?= $this->config->semevar->site_name ?>">
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="d-flex justify-content-md-end">
                  <ul class="" style="color:white;list-style-type:none;">
                    <li class="nav-item"><a href="<?=base_url()?>" title="Halaman Utama <?= $this->config->semevar->site_name ?>" class="nav-link active move2home text-white">Home</a></li>
                    <li class="nav-item"><a href="<?=base_url()?>" class="nav-link move2about text-white">About</a></li>
                    <li class="nav-item"><a href="<?=base_url()?>" class="nav-link move2workat text-white">Work at</a></li>
                    <li class="nav-item"><a href="<?= base_url('joblist') ?>" class="nav-link move2career text-white">Career</a></li>
                    <li class="nav-item"><a href="<?= base_url('login') ?>" class="nav-link text-white">Login/Register</a></li>
                  </ul>
                </div>
              </div>
              <div class="col-md-6">
                <div class="widget-item">
                  <div class="about-widget">
                    <a href="#" cl style="text-decoration: none;color:white">
                      <h3><b>Join Us</b></h3>
                    </a>
                    <h6>Connect with us</h6>
                    <div class="d-flex gap-3 justify-content-center">
                      <a target="_blank" href="https://www.linkedin.com/company/sumber-bintang-perkasa/?originalSubdomain=id"><i class="fa fa-linkedin fa-2x text-white"></i></a>
                      <!-- <a href="#"><i class="fa fa-twitter fa-2x text-white"></i></a> -->
                      <a target="_blank" href="https://www.instagram.com/karir_sbp/"><i class="fa fa-instagram fa-2x text-white"></i></a>
                      <a target="_blank" href="https://www.youtube.com/channel/UCKEdBsFuZkk1BvkVb5n-zHw"><i class="fa fa-youtube fa-2x text-white"></i></a>
                      <a target="_blank" href="https://www.tiktok.com/@sumberbintangperkasa"><i class="fab fa-tiktok fa-2x text-white"></i></a>
                      <!-- <a href=""><i class="fa fa-github fa-2x text-white"></i></a> -->
                    </div>

                  </div>
                </div>
              </div>

            </div>

          <!-- Start Footer Copyright Area -->
          <div class="footer-copyright-area">
            <div class="row align-items-center">
              <div class="col-md-12 text-center text-center">
                <div class="copyright-txt mt-sm-15">
                  <p><?= ($this->config->semevar->copyright) ?></p>
                </div>
              </div>
            </div>
          </div>
          <!-- End Footer Copyright Area -->
          </div>
      </div>
    </div>
</footer>
