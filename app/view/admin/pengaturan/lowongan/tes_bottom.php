App.datatables();

var drTable = {};

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
			"order"					: [[ 1, "asc" ]],
			"responsive"	  : true,
			"bProcessing"		: true,
			"bServerSide"		: true,
			"sAjaxSource"		: "<?=base_url("api_admin/pengaturan/lowongan/tes/".$lowongan->id); ?>",
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
					console.log(response);
					$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
					$(".btn-submit").prop("disabled",false);
					NProgress.done();

					$('#drTable > tbody').off('click', 'tr');
					$('#drTable > tbody').on('click', 'tr', function (e) {
						e.preventDefault();
						var id = $(this).find("td").html();
						ieid = id;
						NProgress.start();
						$('.btn-submit').prop("disabled",true);
						$('.icon-submit').addClass("fa-spin fa-circle-o-notch");

						$.get('<?=base_url('api_admin/pengaturan/lowongan/tes_detail/'.$lowongan->id)?>/'+ieid).done(function(dt){
							if(dt.status == 200){
								$('#ieid').val(dt.data.id);
								$('#iea_banksoal_id').val(dt.data.a_banksoal_id);
								$('#ieb_lowongan_id').val(dt.data.b_lowongan_id);
								$('#ieurutan').val(dt.data.urutan);
								$('#iepassing_grade').val(dt.data.passing_grade);
								$("#modal_option").modal("show");

								$('#aedit').on('click',function(e){
									e.preventDefault();
									$('#modal_option').modal('hide');
									$("#iea_banksoal_id").prepend('<option value="'+dt.data.a_banksoal_id+'">-- tidak diubah--</option>')
									$("#iea_banksoal_id").val(dt.data.a_banksoal_id).trigger('change');
									setTimeout(function(){
										$('#modal_edit').modal('show');
									},456);
								});
							}else{
								//gritter('<h4>Error</h4><p>['+dt.status+'] '+dt.message+'</p>','danger');
							}
							$('.btn-submit').prop("disabled",false);
							$('.icon-submit').removeClass("fa-spin fa-circle-o-notch");
							NProgress.done();
						}).fail(function(){
							$('.btn-submit').prop("disabled",false);
							$('.icon-submit').removeClass("fa-spin fa-circle-o-notch");
							NProgress.done();
						})

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

$("#ia_banksoal_id").select2({
	ajax: {
		method: 'post',
		url: '<?=base_url("api_admin/ujian/banksoal/get/")?>',
		dataType: 'json',
    delay: 250,
		data: function (params) {
      var query = {
        keyword: params.term,
      }
      return query;
    },
    processResults: function (dt) {
      return {
        results:  $.map(dt, function (itm) {
          return {
            text: itm.text,
            id: itm.id
          }
        })
      };
    },
    cache: true
	}
});
$("#iea_banksoal_id").select2({
	ajax: {
		method: 'post',
		url: '<?=base_url("api_admin/ujian/banksoal/get/")?>',
		dataType: 'json',
    delay: 250,
		data: function (params) {
      var query = {
        keyword: params.term,
      }
      return query;
    },
    processResults: function (dt) {
      return {
        results:  $.map(dt, function (itm) {
          return {
            text: itm.text,
            id: itm.id
          }
        })
      };
    },
    cache: true
	}
});

//tambah data handler
$('.b-data-tambah-modal').on('click',function(e){
	e.preventDefault();
	$('#modal_tambah').modal('show');
});
$("#modal_tambah_form").on("submit",function(e){
	e.preventDefault();
	NProgress.start();
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-circle-o-notch fa-spin');

	var fd = new FormData($(this)[0]);
	var url = '<?=base_url('api_admin/pengaturan/lowongan/tes_tambah/'.$lowongan->id);?>';
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
					$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
					$("#modal_tambah").modal("hide");
					$("#modal_tambah_form").trigger('reset');
					NProgress.done();

					drTable.ajax.reload();
				},1234);
			}else{
				gritter('<h4>Gagal</h4><p>['+respon.status+'] '+respon.message+'</p>','danger');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
				NProgress.done();
			}
		},
		error:function(){
			gritter('<h4>Error</h4><p>Proses tambah data tidak bisa dilakukan, coba beberapa saat lagi</p>','warning');
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
			NProgress.done();
		}
	});
});
$("#modal_edit_form").on("submit",function(e){
	e.preventDefault();
	NProgress.start();
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-circle-o-notch fa-spin');

	var fd = new FormData($(this)[0]);
	var url = '<?=base_url('api_admin/pengaturan/lowongan/tes_edit/');?>'+encodeURIComponent(ieid);
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
					$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
					$("#modal_edit").modal("hide");
					NProgress.done();

					drTable.ajax.reload();
				},1234);
			}else{
				gritter('<h4>Gagal</h4><p>['+respon.status+'] '+respon.message+'</p>','danger');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
				NProgress.done();
			}
		},
		error:function(){
			gritter('<h4>Error</h4><p>Proses edit data tidak bisa dilakukan, coba beberapa saat lagi</p>','warning');
			$('.btn-submit').prop('disabled',false);
			$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
			NProgress.done();
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
			$('.btn-submit').prop('disabled',true);
			$('.icon-submit').addClass('fa-circle-o-notch fa-spin');

			var url = '<?=base_url('api_admin/pengaturan/lowongan/tes_hapus/'); ?>'+encodeURIComponent(ieid);
			$.get(url).done(function(response){
				if(response.status == 200){
					$("#modal_option").modal("hide");
					gritter('<h4>Berhasil</h4><p>Data berhasil dihapus</p>','success');
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
					NProgress.done();
					setTimeout(function(){
						drTable.ajax.reload();
					},1234);
				}else{
					gritter('<h4>Gagal</h4><p>['+respon.status+'] '+respon.message+'</p>','danger');
					$('.btn-submit').prop('disabled',false);
					$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
					NProgress.done();
				}
			}).fail(function() {
				gritter('<h4>Error</h4><p>Proses penghapusan tidak bisa dilakukan, coba beberapa saat lagi</p>','warning');
				$('.btn-submit').prop('disabled',false);
				$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
				NProgress.done();
			});
		}
	}
});
