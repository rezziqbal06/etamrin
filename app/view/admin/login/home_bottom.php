var login_try = 0;
$(function(){ Login.init(); });

function gritter(pesan,judul="info"){
	$.bootstrapGrowl(pesan, {
		type: judul,
		delay: 3456,
		allow_dismiss: true
	});
}

$("#form-login").on("submit",function(evt){
	evt.preventDefault();
	console.log('login')
	login_try++;
	var url = '<?=base_url_admin('login/auth'); ?>';
	var fd = {};
	fd.username = $("#iusername").val();
	fd.password = $("#ipassword").val();
	if(fd.username.length<=3){
		$("#iusername").focus();
		gritter("<h4>Info</h4><p>Email belum diisi atau terlalu pendek</p>",'info');
		return false;
	}
	if(fd.password.length<=4){
		$("#ipassword").focus();
		gritter("<h4>Info</h4><p>Kata sandi terlalu pendek</p>",'info');
		return false;
	}
	NProgress.start();
	$("#iusername").prop("disabled",true);
	$("#ipassword").prop("disabled",true);
	$(".btn-submit").prop("disabled",true);
	$("#icon-submit").removeClass("fa-chevron-right");
	$("#icon-submit").addClass("fa-circle-o-notch");
	$("#icon-submit").addClass("fa-spin");
	$.post(url,fd).done(function(dt){
		NProgress.set(0.6);
		if(dt.status == 200){
			gritter("<h4>Sukses</h4><p>Harap tunggu sementara mengarahkan ke dasbor</p>",'success');
			setTimeout(function(){
				NProgress.set(0.7)
			},2000);
			setTimeout(function(){
				NProgress.done();
				window.location =  '<?=base_url_admin('')?>';
			},3000);
		}else{
			$("#iusername").prop("disabled",false);
			$("#ipassword").prop("disabled",false);
			$(".btn-submit").prop("disabled",false);
			$("#icon-submit").addClass("fa-chevron-right");
			$("#icon-submit").removeClass("fa-circle-o-notch");
			$("#icon-submit").removeClass("fa-spin");
			NProgress.done();
			gritter("<h4>Gagal</h4><p>"+dt.message+"</p>",'danger');
			setTimeout(function(){
				$("#bsubmit").removeClass("fa-spin");
				if(login_try>2){
					window.location = '<?=base_url_admin('login')?>';
				}
			},3000);
		}
	}).error(function(){
		$("#iusername").prop("disabled",false);
		$("#ipassword").prop("disabled",false);
		$(".btn-submit").prop("disabled",false);
		$("#icon-submit").addClass("fa-chevron-right");
		$("#icon-submit").removeClass("fa-circle-o-notch");
		$("#icon-submit").removeClass("fa-spin");
		gritter("<h4>Error</h4><p>tidak dapat login sekarang, silahkan coba lagi nanti</p>",'warning');
		NProgress.done();
	});
});
