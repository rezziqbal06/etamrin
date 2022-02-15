<main class="main-content  mt-0">
	<section>
		<div class="page-header min-vh-100">
			<div class="container">
				<div class="row">
					<div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
						<div class="card card-plain">
							<div class="card-header pb-0 text-start">
								<h4 class="font-weight-bolder">Login</h4>
								<p class="mb-0">Masukan username dan password untuk masuk</p>
							</div>
							<div class="card-body">
								<form id="form-login" role="form">
									<div class="mb-3">
										<input id="iusername" type="text" class="form-control form-control-lg" placeholder="Username" aria-label="Username">
									</div>
									<div class="mb-3">
										<input id="ipassword" type="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password">
									</div>
									<div class="form-check form-switch d-none">
										<input class="form-check-input" type="checkbox" id="rememberMe">
										<label class="form-check-label" for="rememberMe">Remember me</label>
									</div>
									<div class="text-center">
										<button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0 btn-submit"><i class="icon-submit fa fa-login"></i> Masuk</button>
									</div>
								</form>
							</div>
							<div class="card-footer text-center pt-0 px-lg-2 px-1 d-none">
								<p class="mb-4 text-sm mx-auto">
									Don't have an account?
									<a href="javascript:;" class="text-primary text-gradient font-weight-bold">Sign up</a>
								</p>
							</div>
						</div>
					</div>
					<div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
						<div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('<?= base_url("media/bg-login.jpg") ?>');
          background-size: cover;">
							<span class="mask bg-gradient-primary opacity-6"></span>
							<h4 class="mt-5 text-white font-weight-bolder position-relative">"Ahlan wa Sahlan Fii Etamrin"</h4>
							<p class="text-white position-relative"><?= $this->config->semevar->site_name_long ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>