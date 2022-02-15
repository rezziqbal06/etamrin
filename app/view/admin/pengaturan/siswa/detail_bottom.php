<?php
	if(!isset($api_url)) $api_url = 'http://bandros.id/ongkir/';
?>
var growlPesan = '<h4>Error</h4><p>Tidak dapat diproses, silakan coba beberapa saat lagi!</p>';
var growlType = 'danger';
var api_url = '<?=$api_url; ?>';
var drTable = {};
var ieid = '';
App.datatables();

if(jQuery('#drTable').length>0){
	drTable = jQuery('#drTable')
	.on('preXhr.dt', function ( e, settings, data ){
		$("#modal-preloader").modal("hide");
		//$("#modal-preloader").modal("show");
	}).DataTable({
			"order"					: [[ 1, "asc" ]],
			"responsive"	  : true,
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?=base_url("api_admin/erpmaster/company"); ?>",
			"fnServerData"	: function (sSource, aoData, fnCallback, oSettings) {
				//$('body').removeClass('loaded');

				oSettings.jqXHR = $.ajax({
					dataType 	: 'json',
					method 		: 'POST',
					url 		: sSource,
					data 		: aoData
				}).success(function (response, status, headers, config) {
					console.log(response);
					$("#modal-preloader").modal("hide");
					//$('body').addClass('loaded');
					$('#drTable > tbody').off('click', 'tr');
					$('#drTable > tbody').on('click', 'tr', function (e) {
						e.preventDefault();
						var id = $(this).find("td").html();
						var url = '<?=base_url(); ?>api_admin/erpmaster/company/detail/'+id;
						$.get(url).done(function(response){
							if(response.status == 200 || response.status=='100'){
								var dta = response.result;
								ieid = dta.id;
								$("#ieid").val(dta.id);
								$("#iekode").val(dta.kode);
								$("#ieutype").val(dta.utype);
								$("#ienama").val(dta.nama);
								$("#ieinisial").val(dta.inisial);
								$("#ietelp").val(dta.telp);
								$("#iealamat").html(dta.alamat);
								$("#iekota").html(dta.kota);
								$("#ieis_active").val(dta.is_active);
								
								//$("#modal_edit").modal("show");
								$("#modal_option").modal("show");
								
							}else{
								growlType = 'danger';
								growlPesan = '<h4>Error</h4><p>Tidak dapat mengambil detail data</p>';
								$.bootstrapGrowl(growlPesan, {
									type: growlType,
									delay: 3456,
									allow_dismiss: true
								});
							}
						});
					});
					fnCallback(response);
				}).error(function (response, status, headers, config) {
					$("#modal-preloader").modal("hide");
					//console.log(response, response.responseText);
					//$('body').addClass('loaded');
					alert("Error");
				});
			},
	});
	$('.dataTables_filter input').attr('placeholder', 'Cari');
}

//tambah
$("#atambah").on("click",function(e){
	e.preventDefault();
	$("#modal_tambah").modal("show");
});
$("#modal_tambah").on("shown.bs.modal",function(e){
	$("#inegara").trigger("change");
	getProvinsi();
});
$("#modal_tambah").on("hidden.bs.modal",function(e){
	$("#modal_tambah").find("form").trigger("reset");
});
function getProvinsi(nama_provinsi){
	var url = api_url+'provinsi/';
	$("#iprovinsi").empty();
	$("#ieprovinsi").empty();
	$("#iprovinsi").html('<option value="">Loading...</option>');
	$.get(url).done(function(hasil){
		if(hasil.status == 1 || hasil.status == "1"){
			var isi = '<option value="">--Pilih--</option>';
			var isi2 = '';
			var selected = '';
			$.each(hasil.result,function(key,val){
				if(val.nama_provinsi == nama_provinsi) selected = val.id;
				isi += '<option value="'+val.id+'" data-value="'+val.nama_provinsi+'">'+val.nama_provinsi+'</option>';
				
				isi2 += '<option value="'+val.id+'" data-value="'+val.nama_provinsi+'">'+val.nama_provinsi+'</option>'; 
			});
			$("#iprovinsi").html(isi);
			$("#ieprovinsi").html(isi2);
			if(selected != '') $("#ieprovinsi").val(selected);
			$("#ikabkota").trigger("change");
			$("#iekabkota").trigger("change");
		}
	});
}

$("#ftambah").on("submit",function(e){
	e.preventDefault();
	var fd = new FormData($(this)[0]);
	var url = '<?=base_url("api_admin/erpmaster/company/tambah/"); ?>';
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status == 200){
				growlPesan = '<h4>Berhasil</h4><p>Proses tambah data telah berhasil!</p>';
				drTable.ajax.reload();
				growlType = 'success';
				$("#modal_tambah").modal("hide");
			}else{
				growlPesan = '<h4>Gagal</h4><p>'+respon.message+'</p>';
				growlType = 'danger';
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
	//
});
$("#modal_edit").on("hidden.bs.modal",function(e){
	$("#modal_edit").find("form").trigger("reset");
});

$("#fedit").on("submit",function(e){
	e.preventDefault();
	var fd = new FormData($(this)[0]);
	var url = '<?=base_url("api_admin/erpmaster/company/edit/"); ?>';
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
			$("#modal_edit").modal("hide");
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
			return false;
		}
	});
});

//hapus
$("#bhapus").on("click",function(e){
	e.preventDefault();
	<?php 
		//ambil hidden id dari form
	?>
	var id = eval($("#ieid").val());
	if(id){
		var c = confirm('apakah anda yakin?');
		if(c){
			var url = '<?=base_url('api_admin/erpmaster/company/hapus/'); ?>'+id;
			$.get(url).done(function(response){
				if(response.status == 200 || response.status == 200){
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
					delay: 3456,
					allow_dismiss: true
				});
			}).fail(function() {
				growlPesan = '<h4>Error</h4><p>Proses penghapusan tidak bisa dilakukan, coba beberapa saat lagi</p>';
				growlType = 'danger';
				$.bootstrapGrowl(growlPesan,{
					type: growlType,
					delay: 3456,
					allow_dismiss: true
				});
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
	},333);
});

//detail
$("#adetail").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	setTimeout(function(){
		window.location = '<?=base_url_admin("erpmaster/company/detail/"); ?>'+ieid;
	},333);
});