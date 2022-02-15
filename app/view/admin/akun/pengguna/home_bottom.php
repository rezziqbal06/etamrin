var ieid = '';
var growlPesan = '<h4>Error</h4><p>Tidak dapat diproses, silakan coba beberapa saat lagi!</p>';
var growlType = 'danger';
var api_url = '<?=base_url('api_admin/akun/'); ?>';
var drTable = {};
App.datatables();

function gritter(gpesan,gtype="info"){
	$.bootstrapGrowl(gpesan, {
		type: gtype,
		delay: 2500,
		allow_dismiss: true
	});
}

if(jQuery('#drTable').length>0){
	drTable = jQuery('#drTable')
	.on('preXhr.dt', function ( e, settings, data ){
		NProgress.start();
	}).DataTable({
			"order"					: [[ 0, "desc" ]],
			"scrollX"	  		: true,
			"responsive"	  : false,
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?=base_url("api_admin/akun/pengguna/"); ?>",
			"fnServerData"	: function (sSource, aoData, fnCallback, oSettings) {
				oSettings.jqXHR = $.ajax({
					dataType 	: 'json',
					method 		: 'POST',
					url 		: sSource,
					data 		: aoData
				}).success(function (response, status, headers, config) {
					console.log(response);
					NProgress.done();
					$('#drTable > tbody').off('click', 'tr');
					$('#drTable > tbody').on('click', 'tr', function (e) {
						e.preventDefault();
						NProgress.start();
						var id = $(this).find("td").html();
						ieid = id;
						var url = '<?=base_url(); ?>api_admin/akun/pengguna/detail/'+id;
						$.get(url).done(function(response){
							if(response.status == 200){
								var dta = response.data;
								//input nilai awal
								$("#ieid").val(dta.id);
								$("#ieid1").val(dta.id);
								$("#ieid2").val(dta.id);
								$("#ieid3").val(dta.id);
								$("#id_access").val(dta.id);
								$("#nama_access").html(" - " + dta.nama);
								$("#ieusername").val(dta.username);
								$("#ienama").val(dta.nama);
								$("#ieemail").val(dta.email);
								$("#iea_departemen_id").val(dta.a_departemen_id);
								$("#ieis_active").val(dta.is_active);
								$("#ieis_notif_interview").val(dta.is_notif_interview);
								$("#iefoto").val(dta.foto);
								$("#iewelcome_message").val(dta.welcome_message);

								//tampilkan modal
								$("#modal_option").modal("show");
							}else{
								growlType = 'info';
								growlPesan = '<h4>Error</h4><p>Tidak dapat mengambil detail data</p>';
								$.bootstrapGrowl(growlPesan, {
									type: growlType,
									delay: 2500,
									allow_dismiss: true
								});
							}
							NProgress.done();
						}).fail(function(){
							NProgress.done();
						});
					});
					fnCallback(response);
				}).error(function (response, status, headers, config) {
					NProgress.done();
					gritter("<h4>Error</h4><p>Tidak dapat mengambil data dari server</p>",'warning');
				});
			},
	});
	$('.dataTables_filter input').attr('placeholder', 'Cari');
}
$("#atambah").on("click",function(e){
	e.preventDefault();
	$("#modal_tambah").modal("show");
});
$("#modal_tambah").on("shown.bs.modal",function(e){
	//
	$("#ftambah").off("submit");
	$("#ftambah").on("submit",function(e){
		e.preventDefault();

		var p1 = $("#ipassword").val();
		var p2 = $("#irepassword").val();
		if(p1 != p2){
			$.bootstrapGrowl('Password tidak sama, ulangi', {
				type: 'danger',
				delay: 2500,
				allow_dismiss: true
			});
			$("#ipassword").focus();
			return false;
		}

		NProgress.start();
		$('.btn-submit').prop('disabled',true);
		$('.icon-submit').addClass('fa-circle-o-notch');
		$('.icon-submit').addClass('fa-spin');

		var fd = new FormData($(this)[0]);
		var url = '<?=base_url('api_admin/akun/pengguna/tambah/');?>';
		$.ajax({
			type: 'post',
			url: url,
			data: fd,
			processData: false,
			contentType: false,
			success: function(respon){
				if(respon.status == 200){
					gritter('<h4>Berhasil</h4><p>Data berhasil ditambahkan</p>','success');
					setTimeout(function(){
						$('.btn-submit').prop('disabled',false);
						$('.icon-submit').removeClass('fa-circle-o-notch');
						$('.icon-submit').removeClass('fa-spin');
						$("#modal_tambah").modal("hide");
						NProgress.done();
						drTable.ajax.reload();
					},1234);
				}else{
					gritter('<h4>Gagal</h4><p>'+respon.message+'</p>','danger');
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-circle-o-notch');
					$('.icon-submit').removeClass('fa-spin');
					NProgress.done();
				}
			},
			error:function(){
				gritter('<h4>Error</h4><p>Proses tambah data tidak bisa dilakukan, coba beberapa saat lagi</p>','warning');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-circle-o-notch');
				$('.icon-submit').removeClass('fa-spin');
				NProgress.done();
			}
		});
	});
	$("#btambah_submit").off("click");
	$("#btambah_submit").on("click",function(e){
		e.preventDefault();
		$("#ftambah").trigger("submit");
	});
});

$("#modal_tambah").on("hidden.bs.modal",function(e){
	$("#modal_tambah").find("form").trigger("reset");
});


//edit

$("#modal_hak_akses").on("shown.bs.modal",function(e){
	$("#fmodal_hak_akses").off("submit");
	$("#fmodal_hak_akses").on("submit",function(e) {
		e.preventDefault();
		var fd = new FormData($(this)[0]);
		var url = api_url + "pengguna/hak_akses/";

		$.ajax({
			type: 'post',
			url: url,
			data: fd,
			processData: false,
			contentType: false,
			success: function(respon) {
				if (respon.status == 200) {
					growlPesan = '<h4>Berhasil</h4><p>'+respon.message+'</p>';
					drTable.ajax.reload();
					growlType = 'success';
					$("#modal_hak_akses").modal("hide");
				}else {
					growlPesan = '<h4>Gagal</h4><p>'+respon.message+'</p>';
					growlType = 'danger';
				}
				setTimeout(function(){
					$.bootstrapGrowl(growlPesan, {
						type: growlType,
						delay: 2500,
						allow_dismiss: true
					});
				}, 666);
			},
			error:function(){
				gritter('<h4>Error</h4><p>Proses tambah hak akses tidak bisa dilakukan, coba beberapa saat lagi</p>','warning');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-circle-o-notch');
				$('.icon-submit').removeClass('fa-spin');
				NProgress.done();
			}
		});
	})

	$("#btambah_access").off("click");
	$("#btambah_access").on("click",function(e){
		e.preventDefault();
		$("#fmodal_hak_akses").trigger("submit");
	});
});

$("#modal_edit").on("shown.bs.modal",function(e){
	//
});
$("#modal_edit").on("hidden.bs.modal",function(e){
	$("#modal_edit").find("form").trigger("reset");
});
$("#fedit").on("submit",function(e){
	e.preventDefault();
	NProgress.start();
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-circle-o-notch');
	$('.icon-submit').addClass('fa-spin');
	var fd = new FormData($(this)[0]);
	var url = '<?=base_url("api_admin/akun/pengguna/edit/"); ?>';
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status == 200){
				gritter('<h4>Berhasil</h4><p>Proses ubah data telah berhasil!</p>','success');
				setTimeout(function(){
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-circle-o-notch');
					$('.icon-submit').removeClass('fa-spin');
					$("#modal_edit").modal("hide");
					NProgress.done();
					drTable.ajax.reload();
				},1234);
			}else{
				gritter('<h4>Gagal</h4><p>'+respon.message+'</p>','danger');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-circle-o-notch');
				$('.icon-submit').removeClass('fa-spin');
				NProgress.done();
			}
		},
		error:function(){
			gritter('<h4>Error</h4><p>Proses ubah data tidak bisa dilakukan, coba beberapa saat lagi</p>','warning');
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-circle-o-notch');
			$('.icon-submit').removeClass('fa-spin');
			NProgress.done();
		}
	});
});

//edit
$("#modal_edit_password").on("shown.bs.modal",function(e){
	//
});
$("#modal_edit_password").on("hidden.bs.modal",function(e){
	$("#modal_edit_password").find("form").trigger("reset");
});
$("#feditpass").on("submit",function(e){
	e.preventDefault();
	var p1 = $("#inewpassword").val();
	var p2 = $("#irenewpassword").val();
	if(p1.length <= 4){ //>
		$.bootstrapGrowl('Password terlalu pendek, ulangi', {
			type: 'danger',
			delay: 2500,
			allow_dismiss: true
		});
		$("#inewpassword").focus();
		return false;
	}
	if(p1 != p2){
		$.bootstrapGrowl('Password tidak sama, ulangi', {
			type: 'danger',
			delay: 2500,
			allow_dismiss: true
		});
		$("#inewpassword").focus();
		return false;
	}

	NProgress.start();
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-circle-o-notch');
	$('.icon-submit').addClass('fa-spin');

	var fd = new FormData($(this)[0]);
	var url = '<?=base_url("api_admin/akun/pengguna/editpass/"); ?>'+ieid;
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status == 200){
				gritter('<h4>Berhasil</h4><p>Password berhasil diganti</p>','success');
				setTimeout(function(){
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-circle-o-notch');
					$('.icon-submit').removeClass('fa-spin');
					NProgress.done();
					$("#modal_edit_password").modal("hide");
					drTable.ajax.reload();
				},1234);
			}else{
				gritter('<h4>Gagal</h4><p>'+respon.message+'</p>','danger');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-circle-o-notch');
				$('.icon-submit').removeClass('fa-spin');
				NProgress.done();
			}
		},
		error:function(){
			gritter('<h4>Error</h4><p>Proses ganti password data tidak dapat dilakukan, coba beberapa saat lagi</p>','danger');
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-circle-o-notch');
			$('.icon-submit').removeClass('fa-spin');
			NProgress.done();
		}
	});
});

//edit
$("#modal_edit_wm").on("shown.bs.modal",function(e){
	//
});
$("#modal_edit_wm").on("hidden.bs.modal",function(e){
	$("#modal_edit_wm").find("form").trigger("reset");
});
$("#fedit_wm").on("submit",function(e){
	e.preventDefault();

	var fd = new FormData($(this)[0]);
	var url = '<?=base_url("api_admin/akun/pengguna/edit/"); ?>'+ieid;
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status == 200){
				growlType = 'success';
				growlPesan = '<h4>Berhasil</h4><p>Proses ubah data telah berhasil!</p>';
				drTable.ajax.reload();
			}else{
				growlType = 'danger';
				growlPesan = '<h4>Gagal</h4><p>'+respon.message+'</p>';
			}
			$("#modal_edit_wm").modal("hide");
			setTimeout(function(){
				$.bootstrapGrowl(growlPesan, {
					type: growlType,
					delay: 2500,
					allow_dismiss: true
				});
			}, 666);
		},
		error:function(){
			growlPesan = '<h4>Error</h4><p>Proses ubah data tidak bisa dilakukan, coba beberapa saat lagi</p>';
			growlType = 'warning';
			setTimeout(function(){
				$.bootstrapGrowl(growlPesan, {
					type: growlType,
					delay: 2500,
					allow_dismiss: true
				});
			}, 666);
			return false;
		}
	});
});

//option
$("#akaryawan").on("click",function(e){
	e.preventDefault();
	if(confirm('Apakah anda yakin?')){
		NProgress.start();
		$('.btn-submit').prop('disabled',false);
		$('.icon-submit').removeClass('fa-circle-o-notch');
		$('.icon-submit').removeClass('fa-spin');
		$.get('<?=base_url('api_admin/akun/pengguna/karyawan/')?>'+encodeURIComponent(ieid)).done(function(dt){
			gritter('<h4>Berhasil</h4><p>Akun berhasil dipindahkan jadi karyawan</p>','success');
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-circle-o-notch');
			$('.icon-submit').removeClass('fa-spin');
			NProgress.done();
			setTimeout(function(){
			$("#modal_option").modal("hide");
				drTable.ajax.reload(null,false);
			},1234);
		}).fail(function(){
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-circle-o-notch');
			$('.icon-submit').removeClass('fa-spin');
			NProgress.done();
		});
	}
});

$("#ahak_akses").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	$("#fmodal_hak_akses input[type=checkbox]").prop("checked", false);
	setTimeout(function(){
		var tmp = $.ajax({
			type: "POST",
			url: api_url + "pengguna/pengguna_module",
			data: "id=" + ieid,
			async: false,
			success: function(data) {
				return data;
			}
		});
		var mod = tmp.responseJSON;
		for (var i = 0; i < mod.length; i++) { //>
			$("#" + mod[i]).prop("checked", true);
		}
		$("#modal_hak_akses").modal("show");
	},333);
});

//hapus
$("#ahapus").on("click",function(e){
	e.preventDefault();
	var id = ieid;
	if(id){
		var c = confirm('apakah anda yakin?');
		if(c){
			var url = '<?=base_url('api_admin/akun/pengguna/hapus/'); ?>'+id;
			$.get(url).done(function(response){
				if(response.status == 200 || response.status == 200){
					$("#modal_option").modal("hide");
					growlType = 'success';
					growlPesan = '<h4>Berhasil</h4><p>Data berhasil dihapus</p>';
				}else{
					growlType = 'danger';
					growlPesan = '<h4>Gagal</h4><p>'+response.message+'</p>';
				}
				drTable.ajax.reload();
				$("#modal_edit").modal("hide");
				$.bootstrapGrowl(growlPesan,{
					type: growlType,
					delay: 2500,
					allow_dismiss: true
				});
			}).fail(function() {
				growlPesan = '<h4>Error</h4><p>Proses penghapusan tidak bisa dilakukan, coba beberapa saat lagi</p>';
				growlType = 'danger';
				$.bootstrapGrowl(growlPesan,{
					type: growlType,
					delay: 2500,
					allow_dismiss: true
				});
			});
		}
	}
});

$("#bhapus").on("click",function(e){
	e.preventDefault();
	$("#ahapus").trigger("click");
});

//option
$("#aedit").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	setTimeout(function(){
		$("#modal_edit").modal("show");
	},333);
});

//detail
$("#adetail").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	setTimeout(function(){
		//$("#modal_edit").modal("show");
		alert('masih dalam pengembangan');
	},333);
});

//edit_password
$("#aedit_password").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal('hide');
	$("#modal_edit_password").modal('show');
});

//edit_welcomemessage
$("#aedit_wm").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal('hide');
	$("#modal_edit_wm").modal('show');
});

//edit_foto
$("#bprofil_foto").on("click",function(e){
	e.preventDefault();
	$("#modal_profil_foto").modal('show');
});
$("#fmodal_profil_foto").on("submit",function(e){
	e.preventDefault();
	var fd = new FormData($(this)[0]);
	var url = '<?=base_url('api_admin/akun/pengguna/edit_foto/');?>'+ieid;
	$.ajax({
		type: 'post',
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status == 200){
				growlPesan = '<h4>Berhasil</h4><p>Proses edit foto telah berhasil!</p>';
				drTable.ajax.reload();
				growlType = 'success';
				$("#modal_profil_foto").modal("hide");
			}else{
				growlPesan = '<h4>Gagal</h4><p>'+respon.message+'</p>';
				growlType = 'danger';
			}
			setTimeout(function(){
				$.bootstrapGrowl(growlPesan, {
					type: growlType,
					delay: 2500,
					allow_dismiss: true
				});
			}, 666);
		},
		error:function(){
			growlPesan = '<h4>Error</h4><p>Proses tambah data tidak bisa dilakukan, coba beberapa saat lagi</p>';
			growlType = 'warning';
			setTimeout(function(){
				$.bootstrapGrowl(growlPesan, {
					type: growlType,
					delay: 2500,
					allow_dismiss: true
				});
			}, 666);
			return false;
		}
	});
});
