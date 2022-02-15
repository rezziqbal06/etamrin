<!DOCTYPE html>
<html class="no-js" lang="en">
<?php $this->getThemeElement("page/html/head", $__forward); ?>

<body class="g-sidenav-show   bg-gray-100">
	<div class="min-height-300 bg-primary position-absolute w-100"></div>

	<?php if ($this->admin_login) $this->getThemeElement("page/html/sidebar", $__forward); ?>
	<main class="main-content position-relative border-radius-lg ">
		<?php $this->getThemeElement("page/html/header", $__forward); ?>
		<div class="container-fluid py-4">
			<?php $this->getThemeContent(); ?>
			<!-- Main Container End -->

			<!-- Footer -->
			<?php $this->getThemeElement("page/html/footer", $__forward); ?>
			<!-- End Footer -->
		</div>
	</main>


	<!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
	<?php $this->getJsFooter(); ?>

	<!-- Load and execute javascript code used only in this page -->
	<script>
		var from_user_id = '';
		var from_user_nama = '';
		var to_user_id = '';
		var to_user_nama = '';
		var chat_active = 1;
		var last_pesan_id = 0;
		var iterator = 1;
		var base_url = '<?= base_url_admin() ?>';
		$(document).ready(function(e) {
			<?php $this->getJsReady(); ?>
			<?php $this->getThemeElement('page/html/script', $__forward); ?>
		});
		<?php $this->getJsContent(); ?>
	</script>
</body>

</html>