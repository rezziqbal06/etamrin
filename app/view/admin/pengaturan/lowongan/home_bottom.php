var growlPesan = '<h4>Error</h4><p>Tidak dapat diproses, silakan coba beberapa saat lagi!</p>';
var growlType = 'danger';
var drTable = {};
var ieid = '';

App.datatables();

$(".datepicker").datepicker({format: "yyyy-mm-d"});


function gritter(gpesan,gtype="info"){
	$.bootstrapGrowl(gpesan, {
		type: gtype,
		delay: 3456,
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
				{ "width": "20px", "targets": 0 },
				{ "width": "200px", "targets": 1 },
				{ "width": "160px", "targets": 2 },
				{ "width": "160px", "targets": 3 },
				{ "width": "80px", "targets": 4 },
				{ "width": "80px", "targets": 5 },
				{ "width": "60px", "targets": 6 },
				{ "width": "80px", "targets": 7 },
				{ "width": "60px", "targets": 8 },
				{ "width": "60px", "targets": 9 },
				{ "width": "180px", "targets": 10, "orderable": false }
			],
			"scrollX"       : true,
			"order"					: [[ 3, "desc" ]],
			"responsive"	  : false,
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?=base_url("api_admin/pengaturan/lowongan/"); ?>",
			"fnServerParams": function ( aoData ) {
				aoData.push(
					{ "name": "is_active", "value": $("#fl_is_active").val() },
					{ "name": "a_company_id", "value": $("#fl_a_company_id").val() },
					{ "name": "sdate", "value": $("#fl_sdate").val() },
					{ "name": "edate", "value": $("#fl_edate").val() },
					{ "name": "min_edate", "value": $("#fl_min_edate").val() },
					{ "name": "max_edate", "value": $("#fl_max_edate").val() },
					{ "name": "is_close", "value": $("#fl_is_close").val() }
				);
			},
			"fnServerData"	: function (sSource, aoData, fnCallback, oSettings) {
				oSettings.jqXHR = $.ajax({
					dataType 	: 'json',
					method 		: 'POST',
					url 		: sSource,
					data 		: aoData
				}).success(function (response, status, headers, config) {
					console.log(response);
					$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
					$(".btn-submit").prop("disabled",false);
					NProgress.done();

					$('#drTable > tbody').off('click', 'tr');
					$('#drTable > tbody').on('click', 'tr', function (e) {
						e.preventDefault();
						var id = $(this).find("td").html();
						ieid = id;
						$("#modal_option").modal("show");

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
	$('.dataTables_filter input').attr('placeholder', 'Cari nama posisi');
	$("#fl_do").on("click",function(e){
		e.preventDefault();
		drTable.ajax.reload();
	});
}


//option
$("#aedit").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	setTimeout(function(){
		window.location = '<?=base_url_admin("pengaturan/lowongan/edit/"); ?>'+ieid;
	},666);
});
$("#aurutan_tes").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	setTimeout(function(){
		window.location = '<?=base_url_admin("pengaturan/lowongan/tes/"); ?>'+ieid;
	},666);
});

//detail
$("#adetail").on("click",function(e){
	e.preventDefault();
	$("#modal_option").modal("hide");
	setTimeout(function(){
		window.location = '<?=base_url_admin("pengaturan/lowongan/detail/"); ?>'+ieid;
	},333);
});


//hapus
$("#bhapus").on("click",function(e){
	e.preventDefault();
	if(ieid){
		var c = confirm('apakah anda yakin?');
		if(c){
			NProgress.start();
			var url = '<?=base_url('api_admin/pengaturan/lowongan/hapus/'); ?>'+ieid;
			$.get(url).done(function(response){
				NProgress.done();
				if(response.status==200){
					gritter('<h4>Berhasil</h4><p>Data berhasil dihapus</p>','success');
					$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
					$(".btn-submit").prop("disabled",false);
					NProgress.done();
					$("#modal_option").modal("hide");
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
