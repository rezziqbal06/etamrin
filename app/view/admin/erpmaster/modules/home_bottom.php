$.fn.modal.Constructor.prototype.enforceFocus = function() {};

function gritter(pesan,jenis='info'){
	$.bootstrapGrowl(pesan,{
		type: jenis,
		delay: 3456,
		allow_dismiss: true
	});
}
App.datatables();
if(jQuery('#drTable').length>0){
	drTable = jQuery('#drTable')
	.on('preXhr.dt', function ( e, settings, data ){
		NProgress.start();
		$('.btn-submit').prop('disabled',true);
		$('.icon-submit').addClass('fa-spin fa-circle-o-notch');

	}).DataTable({
			"order"					: [[ 0, "asc" ]],
			"responsive"	  : true,
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?=base_url("api_admin/erpmaster/modules/")?>",
			"fnServerData"	: function (sSource, aoData, fnCallback, oSettings) {
				oSettings.jqXHR = $.ajax({
					dataType 	: 'json',
					method 		: 'POST',
					url 		: sSource,
					data 		: aoData
				}).done(function (response, status, headers, config) {
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
					NProgress.done();

					$('#drTable > tbody').off('click', 'tr');
					$('#drTable > tbody').on('click', 'tr', function (e) {
						e.preventDefault();
						NProgress.start();
						$('.btn-submit').prop('disabled',true);
						$('.icon-submit').addClass('fa-spin fa-circle-o-notch');

						var id = $(this).find("td").html();
						ieid = id;
						var url = '<?=base_url()?>api_admin/erpmaster/modules/detail/?id='+encodeURIComponent(ieid);
						$.get(url).done(function(response){
							if(response.status==200){
								$('.btn-submit').prop('disabled',false);
								$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
								NProgress.done();

								var dt = response.data;
								console.log(dt.identifier);
								//input nilai awal
								$("#ienation_code").val(dt.nation_code);
								$("#iename").val(dt.name);
								$("#ieidentifier").val(dt.identifier);
								$("#ieis_active").val(dt.is_active);
								$("#ieis_visible").val(dt.is_visible);
								$("#ieis_default ").val(dt.is_default );
								$("#iepriority").val(dt.priority);
								$("#ieutype").val(dt.utype);
								$("#iefa_icon").val(dt.fa_icon);
								$("#iehas_submenu").val(dt.has_submenu);
								$("#iepath").val(dt.path);

								$("#ielevel").val(dt.level);
								$("#ielevel").trigger("change");
								console.log("ielevel: ",$("#ielevel").val());

								$("#iechildren_identifier").prepend('<option value="'+dt.children_identifier+'">'+dt.children_identifier+'</option>');
								$("#iechildren_identifier").val(dt.children_identifier);

								console.log("iechildren_identifier: ",$("#iechildren_identifier").val());
								//tampilkan modal
								$("#modal_edit").modal("show");
							}else{
								gritter('<h4>Error</h4><p>Tidak dapat mengambil detail data</p>','warning');
								$('.btn-submit').prop('disabled',false);
								$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
								NProgress.done();
							}
						});
					});
					fnCallback(response);
				}).fail(function (response, status, headers, config) {
					gritter('<h4>Error</h4><p>Tidak dapat memproses data saat ini, coba lagi nanti</p>','warning');
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
					NProgress.done();
				});
			}
	});
	$('.dataTables_filter input').attr('placeholder', 'Cari');
}
$("#atambah").on("click",function(e){
	e.preventDefault();
	$("#modal_tambah").modal("show");
});
$("#modal_tambah").on("shown.bs.modal",function(e){
	//
});
$("#modal_tambah").on("hidden.bs.modal",function(e){
	$("#modal_tambah").find("form").trigger("reset");
});

$("#ftambah").on("submit",function(e){
	e.preventDefault();
	NProgress.start();
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-spin fa-circle-o-notch');

	var fd = new FormData($(this)[0]);
	var url = '<?=base_url("api_admin/erpmaster/modules/tambah/")?>';
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status==200){
				growlPesan = '<h4>Berhasil</h4><p>Proses tambah data telah berhasil!</p>';
				drTable.ajax.reload();
				growlType = 'success';
				$("#modal_tambah").modal("hide");
			}else{
				growlPesan = '<h4>Gagal</h4><p>'+respon.message+'</p>';
			}
			setTimeout(function(){
				$.bootstrapGrowl(growlPesan, {
					type: growlType,
					delay: 3456,
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
					delay: 3456,
					allow_dismiss: true
				});
			}, 666);
			return false;
		}
	});

});



//edit
$("#modal_edit").on("shown.bs.modal",function(e){
	console.log("Modal edit: show!");
});
$("#modal_edit").on("hidden.bs.modal",function(e){
	$("#modal_edit").find("form").trigger("reset");
});
$("#fedit").on("submit",function(e){
	e.preventDefault();
	NProgress.start();
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-spin fa-circle-o-notch');

	var fd = new FormData($(this)[0]);
	var url = '<?=base_url("api_admin/erpmaster/modules/edit/?id=")?>'+encodeURIComponent(ieid);
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status==200){
				gritter('<h4>Berhasil</h4><p>Proses ubah data telah berhasil!</p>','success');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
				NProgress.done();

				$("#modal_edit").modal("hide");
				drTable.ajax.reload();
			}else{
				gritter('<h4>Gagal</h4><p>'+respon.message+'</p>','danger');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
				NProgress.done();
			}
		},
		error:function(){
			gritter('<h4>Error</h4><p>Tidak bisa memproses sekarang, coba beberapa saat lagi</p>','warning');
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
			NProgress.done();
		}
	});
});

//hapus
$("#bhapus").on("click",function(e){
	e.preventDefault();
	if(ieid){
		var c = confirm('Are you sure?');
		if(c){
			NProgress.start();
			$('.btn-submit').prop('disabled',true);
			$('.icon-submit').addClass('fa-spin fa-circle-o-notch');

			var url = '<?=base_url('api_admin/erpmaster/modules/hapus/?id=')?>'+encodeURIComponent(ieid);
			$.get(url).done(function(response){
				if(response.status==200){
					gritter('<h4>Berhasil</h4><p>Data berhasil dihapus</p>','success');
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
					NProgress.done();

					$("#modal_edit").modal("hide");
					drTable.ajax.reload();
				}else{
					gritter('<h4>Gagal</h4><p>'+response.message+'</p>','danger');
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
					NProgress.done();
				}
			}).fail(function() {
				gritter('<h4>Error</h4><p>Tidak bisa memproses sekarang, coba beberapa saat lagi</p>','warning');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
				NProgress.done();
			});
		}
	}
});

$("#ilevel").on("change",function(e){
	e.preventDefault();

	var v = $(this).val();
	$("#ichildren_identifier").html('<option value="null">-</option>');
	if(v != 0 || v != "0"){
		NProgress.start();
		$('.btn-submit').prop('disabled',true);
		$('.icon-submit').addClass('fa-spin fa-circle-o-notch');

		$.get('<?=base_url('api_admin/erpmaster/modules/get/')?>').done(function(dt){
			if(dt.status == 200){
				if(dt.data.length>0){
					$.each(dt.data,function(k,v){
						var h = '';
						h += '<option value="'+v.identifier+'">'+v.identifier+' ('+v.name+')</option>';
						if(v.level == 0 || v.level == "0") h+= '-';
						if(v.level == 1 || v.level == "1") h+= '--';
						if(v.level == 2 || v.level == "2") h+= '---';
						h +=''+v.name+'</option>';
						$("#ichildren_identifier").append(h);
					});
				}
			}else{
				gritter("<h4>Gagal</h4><p>"+dt.message+"</p>","warning");
			}

			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
			NProgress.done();
		}).fail(function(){
			gritter("<h4>Error</h4><p>Untuk saat ini tidak dapat mengambil data</p>","warning");
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
			NProgress.done();
		});
	}
});
$("#ielevel").on("change",function(e){
	e.preventDefault();
	var v = $(this).val();
	$("#iechildren_identifier").empty();
	$("#iechildren_identifier").append('<option value="null">-</option>');
	if(v != 0 || v != "0"){
		NProgress.start();
		$('.btn-submit').prop('disabled',true);
		$('.icon-submit').addClass('fa-spin fa-circle-o-notch');

		$.get('<?=base_url('api_admin/erpmaster/modules/get/')?>').done(function(dt){
			if(dt.status == 200){
				if(dt.data.length>0){
					$.each(dt.data,function(k,v){
						var h = '';
						h += '<option value="'+v.identifier+'">';
						if(v.level == 0 || v.level == "0") h+= '-';
						if(v.level == 1 || v.level == "1") h+= '--';
						if(v.level == 2 || v.level == "2") h+= '---';
						h +=''+v.name+'</option>';
						$("#iechildren_identifier").append(h);
					});
				}
			}else{
				gritter("<h4>Gagal</h4><p>"+dt.message+"</p>","warning");
			}

			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
			NProgress.done();
		}).fail(function(){
			gritter("<h4>Error</h4><p>Untuk saat ini tidak dapat mengambil data</p>","warning");
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
			NProgress.done();
		});
	}
});

$("#areload").on("click",function(e){
	e.preventDefault();
	NProgress.start();
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-spin fa-circle-o-notch');
	$.get('<?=base_url('api_admin/erpmaster/modules/reload/')?>').done(function(dt){
		if(dt.status == 200){
			window.location.reload();
		}else{
			gritter('<h4>Error</h4><p>'+dt.message+'</p>','danger');
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
			NProgress.done();
		}
	}).fail(function(){
		gritter("<h4>Gagal</h4><p>Untuk saat ini tidak dapat re-reload session</p>","warning");
		$('.btn-submit').prop('disabled',false);
		$('.icon-submit').removeClass('fa-spin fa-circle-o-notch');
		NProgress.done();
	});
});

$(".select2").select2();
