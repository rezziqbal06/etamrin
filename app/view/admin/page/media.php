<!DOCTYPE html>
<html class="no-js" lang="en">
	<?php $this->getThemeElement("page/html/head",$__forward); ?>
	<body>
    <?php $this->getThemeContent(); ?>

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
			var base_url = '<?=base_url_admin()?>';
			$(document).ready(function(e){
				<?php $this->getJsReady(); ?>
				<?php $this->getThemeElement('page/html/script',$__forward); ?>
			});
			<?php $this->getJsContent(); ?>
		</script>
  </body>
</html>
