var growlPesan = '<h4>Error</h4><p>Tidak dapat diproses, silakan coba beberapa saat lagi!</p>';
var growlType = 'danger';
var drTable = {};
var ieid = '';
var api_url = '<?=base_url('api_admin/kelas/'); ?>';
var c_count = 0;
App.datatables();

function gritter(gpesan,gtype="info"){
	$.bootstrapGrowl(gpesan, {
		type: gtype,
		delay: 1000,
		allow_dismiss: true
	});
}

if(jQuery('#drTable').length>0){
	drTable = jQuery('#drTable')
	.on('preXhr.dt', function ( e, settings, data ){
		NProgress.start();
		$('.btn-submit').prop('disabled',true);
		$('.icon-submit').addClass('fa-spin fa-circle-o-notch');
	}).DataTable({
			"columnDefs": [
				{"targets": 3, "orderable": false}
			],
			"order"	: [[ 2, "desc" ]],
			"responsive"	  : true,
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?=base_url("api_admin/pengaturan/kelas/"); ?>",
			"fnServerParams": function ( aoData ) {
				aoData.push(
				);
			},
			"fnServerData"	: function (sSource, aoData, fnCallback, oSettings) {
				oSettings.jqXHR = $.ajax({
					dataType 	: 'json',
					method 		: 'POST',
					url 		: sSource,
					data 		: aoData
				}).success(function (response, status, headers, config) {
					$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
					$(".btn-submit").prop("disabled",false);
					NProgress.done();

					$('#drTable > tbody').off('click', 'tr');
					$('#drTable > tbody').on('click', 'tr', function (e) {
						e.preventDefault();
						var id = $(this).find("td").html();
						ieid = id;
						var url = '<?=base_url(); ?>api_admin/pengaturan/kelas/detail/'+id;
						$.get(url).done(function(response){
							if(response.status==200 || response.status=='200'){
								var dta = response.data;
								$.each(dta, function(k,v){
									$("#ie"+k).val(v);
								})
							
								$("#modal_option").modal("show");
							}else{
								gritter('<h4>Error</h4><p>Tidak dapat mengambil detail data</p>','warning');
							}
							$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
							$(".btn-submit").prop("disabled",false);
							NProgress.done();
						}).fail(function(){
							$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
							$(".btn-submit").prop("disabled",false);
							NProgress.done();
						});
					});
					fnCallback(response);
				}).error(function (response, status, headers, config) {
					gritter('<h4>Error</h4><p>Tidak dapat mengambil detail data</p>','warning');
					$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
					$(".btn-submit").prop("disabled",false);
					NProgress.done();
				});
			}
	});
	$('.dataTables_filter input').attr('placeholder', 'Cari');

	$("#fl_do").on("click",function(e){
		e.preventDefault();
		drTable.ajax.reload();
	});
}

//tambah
$("#atambah").on("click",function(e){
	e.preventDefault();
	$("#modal_tambah").modal("show");
	$("#bftambah").prop("disabled",false);
});

$("#modal_tambah").on("hidden.bs.modal",function(e){
	$("#modal_tambah").find("form").trigger("reset");
});


$("#ftambah").on("submit",function(e){
	e.preventDefault();
	if($("#bftambah").is(":disabled")){
		return false;
	}
	$(".btn-submit").prop("disabled",true);
	$(".icon-submit").addClass("fa-spin fa-circle-o-notch");
	var fd = new FormData($(this)[0]);

	var url = '<?=base_url("api_admin/pengaturan/kelas/baru/"); ?>';
	NProgress.start();
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status == 200){
				drTable.ajax.reload();
				$("#modal_tambah").modal("hide");
				gritter('<h4>Berhasil</h4><p>Data berhasil ditambahkan</p>','success');
				$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
				$(".btn-submit").prop("disabled",false);
				NProgress.done();
			}else{
				gritter('<h4>Gagal</h4><p>'+respon.message+'</p>','danger');
				$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
				$(".btn-submit").prop("disabled",false);
				NProgress.done();
			}
		},
		error:function(){
			gritter('<h4>Error</h4><p>Proses tambah data tidak bisa dilakukan, coba beberapa saat lagi</p>','warning');
			$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
			$(".btn-submit").prop("disabled",false);
			NProgress.done();
			return false;
		}
	});

});



//edit
$("#modal_edit").on("shown.bs.modal",function(e){
	//
});
$("#modal_edit").on("hidden.bs.modal",function(e){
	$("#modal_edit").find("form").trigger("reset");
});

$("#fedit").on("submit",function(e){
	e.preventDefault();
	var fd = new FormData($(this)[0]);

	NProgress.start();

	var url = '<?=base_url("api_admin/pengaturan/kelas/edit/"); ?>';
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			NProgress.done();
			if(respon.status == 200){
				$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
				$(".btn-submit").prop("disabled",false);
				NProgress.done();

				growlType = 'success';
				growlPesan = '<h4>Berhasil</h4><p>Proses ubah data telah berhasil!</p>';
				drTable.ajax.reload();
				$("#modal_edit").modal("hide");
			}else{
				growlType = 'danger';
				growlPesan = '<h4>Gagal</h4><p>'+respon.message+'</p>';
				setTimeout(function(){
					$.bootstrapGrowl(growlPesan, {
						type: growlType,
						delay: 3456,
						allow_dismiss: true
					});
				}, 666);

				$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
				$(".btn-submit").prop("disabled",false);
				NProgress.done();
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
			growlPesan = '<h4>Error</h4><p>Proses ubah data tidak bisa dilakukan, coba beberapa saat lagi</p>';
			growlType = 'danger';
			setTimeout(function(){
				$.bootstrapGrowl(growlPesan, {
					type: growlType,
					delay: 3456,
					allow_dismiss: true
				});
			}, 666);
			$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
			$(".btn-submit").prop("disabled",false);
			NProgress.done();
			return false;
		}
	});
});

//hapus
$("#bhapus").on("click",function(e){
	e.preventDefault();
	if(ieid){
		var c = confirm('apakah anda yakin?');
		if(c){
			NProgress.start();
			var url = '<?=base_url('api_admin/pengaturan/kelas/hapus/'); ?>'+ieid;
			$.get(url).done(function(response){
				NProgress.done();
				$("#modal_option").modal("hide");
				if(response.status==200){
					gritter('<h4>Berhasil</h4><p>Data berhasil dihapus</p>','success');
					$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
					$(".btn-submit").prop("disabled",false);
					NProgress.done();

					$("#modal_edit").modal("hide");
					drTable.ajax.reload();
				}else{
					gritter('<h4>Gagal</h4><p>'+response.message+'</p>','danger');
					$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
					$(".btn-submit").prop("disabled",false);
					NProgress.done();
				}
			}).fail(function() {
				gritter('<h4>Error</h4><p>Proses penghapusan tidak bisa dilakukan, coba beberapa saat lagi</p>','warning');
				$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
				$(".btn-submit").prop("disabled",false);
				NProgress.done();
			});
		}
	}
});

//option
$("#aedit").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	setTimeout(function(){
		$("#modal_edit").modal("show");
	},666);
});

//detail
$("#adetail").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	setTimeout(function(){
		window.location = '<?=base_url_admin("pengaturan/kelas/detail/"); ?>'+ieid;
	},333);
});

$("#btnNote").on('click', function(e){
	e.preventDefault();
	$("#noteModal").modal('show');
})

var progressBar = $('.progress-bar');
var percentVal = 0;
$('.progress').css('display', 'none');
function setProgress(caption='test', percent='30', color='bg-info'){
	percentVal = percent;
	if(percentVal == 0){
		$('.progress').css('display', 'none');
	} else {
		$('.progress').css('display', 'block');
	}
	setTimeout(function(){
		progressBar.css('width', percentVal+ '%').attr('aria-valuenow', percentVal+ '%').text(percentVal+ '%');

		progressBar.removeClass('bg-warning');
		progressBar.removeClass('bg-success');
		progressBar.removeClass('bg-info');
		progressBar.addClass(color);

		$('#caption').text(caption);
	},33);

}


$('#fimport').on('submit', function(e){
	e.preventDefault();
	var c = confirm('Data sebelumnya mungkin akan tertimpa dan terhapus.\rApakah anda yakin?');
	if(!c){
		return false;
	}
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-circle-o-notch');
	$('.icon-submit').addClass('fa-spin');
	var fd = new FormData($(this)[0]);
	var url = '<?=base_url("api_admin/pengaturan/kelas/import/")?>';
	setProgress('Mengirim file... Mohon untuk tidak keluar dari halaman', 30);
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		beforeSend: function(){
			setProgress('Mengirim file... Mohon untuk tidak keluar dari halaman', 30);
		},
		success: function(respon){

			if(respon.status == 200){
				setProgress(respon.message, 100, 'bg-success');
				setTimeout(function(){
					gritter('<h4>Berhasil</h4><p>Data berhasil diimport</p>','success');
					drTable.ajax.reload()
					setProgress('',0);
				},500)
				$("#fimport").trigger('reset');
			}else if(respon.status == 201){
				setProgress(respon.message,70, 'bg-info');
				setTimeout(function(){
					setProgress('Data produk berhasil diupdate',100, 'bg-success');
					setTimeout(function(){
						gritter('<h4>Berhasil</h4><p>Data berhasil diimport</p>','success');
						setProgress('',0);
						drTable.ajax.reload()
						$("#fimport").trigger('reset');
					},500)
				},1500)
			}else{
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-circle-o-notch');
				$('.icon-submit').removeClass('fa-spin');
				setProgress('',0);
				gritter('<h4>Gagal</h4><p>'+respon.message+'</p>','warning');
			}
		},
		error:function(){
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-circle-o-notch');
			$('.icon-submit').removeClass('fa-spin');
			setProgress('',0);
			gritter('<h4>Gagal</h4><p>Coba beberapa saat lagi</p>','danger');
			return false;
		}
	});
});
