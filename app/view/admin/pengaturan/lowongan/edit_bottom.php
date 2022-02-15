var growlPesan = '<h4>Error</h4><p>Tidak dapat diproses, silakan coba beberapa saat lagi!</p>';
var growlType = 'danger';
var drTable = {};
var ieid = '';
var api_url = '<?=base_url('api_admin/alamatongkir/'); ?>';
var c_count = 0;

var fileReaderAdd = new FileReader();
var fileReaderEdit = new FileReader();
var filterType = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
var ufsmax = 10;
var frmax = 400;
var wmax = 1080;
var hmax = 720;
var fext = '';

App.datatables();

var posisi = <?=json_encode($posisi)?>;

$(".datepicker").datepicker({format: "yyyy-mm-dd"});

function gritter(gpesan,gtype="info"){
	$.bootstrapGrowl(gpesan, {
		type: gtype,
		delay: 3456,
		allow_dismiss: true
	});
}

function priceFormat(){
	$(".rupiah-uang").unpriceFormat();
	$(".rupiah-uang").priceFormat({
		prefix: 'Rp',
		centsSeparator: ',',
		thousandsSeparator: '.',
		centsLimit: 0
	});
}


priceFormat();

window.toPlainFloat = function(mny){
	if(mny){
		return mny.replace( /^\D+/g, '').split('.').join("");
	}
}

$(".select2").select2();

$("#pdetail").off("change",".rupiah-uang");
$("#pdetail").on("change",".rupiah-uang",function(ev){
	ev.preventDefault();
	var selector = $(this).attr("data-selector");
	var nominal_typed = $(this).val();
	nominal_typed = toPlainFloat(nominal_typed);
	$('#'+selector).val(nominal_typed);
	console.log(nominal_typed);
});




//check image allowed
(function($) {
	$.fn.checkFileType = function(options) {
		var defaults = {
			allowedExtensions: [],
			success: function() {},
			error: function() {}
		};
		options = $.extend(defaults, options);
		return this.each(function() {
			$(this).on('change', function() {
				var value = $(this).val(),
				file = value.toLowerCase(),
				extension = file.substring(file.lastIndexOf('.') + 1);
				if ($.inArray(extension, options.allowedExtensions) == -1) {
					options.error();
					$(this).focus();
				} else {
					options.success();
				}
			});
		});
	};
})(jQuery);

function readURLImage(input,target) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#'+target).attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

// for file upload, convert base64 value of Image.src to file fake path
function DataURIToBlob(dataURI) {
  const splitDataURI = dataURI.split(',')
  const byteString = splitDataURI[0].indexOf('base64') >= 0 ? atob(splitDataURI[1]) : decodeURI(splitDataURI[1])
  const mimeString = splitDataURI[0].split(':')[1].split(';')[0]

  const ia = new Uint8Array(byteString.length)
  for (let i = 0; i < byteString.length; i++)
      ia[i] = byteString.charCodeAt(i)

  return new Blob([ia], { type: mimeString })
}


// attach fileReaderEdit onload
fileReaderEdit.onload = function (event) {
  var image = new Image();
	console.log('image.src',image.src);
  image.onload=function(){
    document.getElementById("imgeditori").src=image.src;
    var canvas=document.createElement("canvas");
    var context=canvas.getContext("2d");
		console.log('image.width: '+image.width);
		console.log('image.height: '+image.height);
		var sx = 1;
		if(image.width >= wmax && image.height >= hmax){
			if(image.width >= image.height){
				sx = wmax / image.width;
				sx = Math.round((sx + Number.EPSILON) * 100) / 100;
			}else{
				sx = hmax / image.height;
				sx = Math.round((sx + Number.EPSILON) * 100) / 100;
			}
		}else if(image.width >= wmax && image.height < hmax){
			sx = wmax / image.width;
			sx = Math.round((sx + Number.EPSILON) * 100) / 100;
		}else if(image.width < wmax && image.height >= hmax){
			sx = hmax / image.height;
			sx = Math.round((sx + Number.EPSILON) * 100) / 100;
		}
		canvas.width = image.width*sx;
		canvas.height = image.height*sx;

		console.log('canvas.width: '+canvas.width);
		console.log('canvas.height: '+canvas.height);
    context.drawImage(image, 0, 0, image.width, image.height, 0, 0, canvas.width, canvas.height);
    document.getElementById("imgedit").src = canvas.toDataURL();
  }
  image.src=event.target.result;
	console.log('final image.width: '+image.width);
	console.log('final image.height: '+image.height);
};

$("#igambar").on('change',function(e){
  e.preventDefault();
  readURLImage(this,'agambar');
});
$("#iegambar").on('change',function(e){
  e.preventDefault();
  readURLImage(this,'egambar');
});

//edit
$("#modal_edit").on("shown.bs.modal",function(e){
	//
});
$("#modal_edit").on("hidden.bs.modal",function(e){
	$("#modal_edit").find("form").trigger("reset");
});

$("#pjabatan").slideDown('slow');

$("#iea_jabatan_id").on('change', function(e){
	e.preventDefault();
	var id = $(this).val();
	var text = $(this).text();
	$("#ienama").val(text);
	$.get('<?=base_url("api_admin/pengaturan/jabatan/detail/")?>' + id).done(function(dt){
		if(dt.status == 200){
			$("#iemin_pendidikan").val(dt.data['min_pendidikan']);
			$("#iemax_usia").val(dt.data['max_usia']);
			$("#iemin_exp").val(dt.data['min_exp']);
			$("#iemin_iq").val(dt.data['min_iq']);
			$("#ienama").val(dt.data['nama']);

			$("#pjabatan").slideDown('slow');
		}
	}).fail(function(){
		console.log('fail to fetched');
	})
})

$("#iesgaji").val('<?=$lowongan->sgaji?>');
priceFormat();
$("#ieegaji").val('<?=$lowongan->egaji?>');
priceFormat();
$('.rupiah-uang').trigger('change');
$("#ieis_freshg").val('<?=$lowongan->is_freshg?>');
$("#ieis_favorite").val('<?=$lowongan->is_favorite?>');
$("#iebg_warna").val('<?=$lowongan->bg_warna?>');
$("#ieis_active").val('<?=$lowongan->is_active?>');
$("#iettype").val('<?=$lowongan->ttype?>');
$("#iea_company_id").val('<?=$lowongan->a_company_id?>').change();

$("#fedit").on("submit",function(e){
	e.preventDefault();
	NProgress.start();
	$(".btn-submit").prop("disabled",true);
	$(".icon-submit").addClass("fa-spin fa-circle-o-notch");

	for ( instance in CKEDITOR.instances ) CKEDITOR.instances[instance].updateElement();

	var fd = new FormData($(this)[0]);
	//delete current input file
	var imgedit = $("#imgedit").attr("src");
	if(imgedit.length>1){
		fd.delete("gambar");
		//put resized image into formdata
		fd.append("gambar", DataURIToBlob($("#imgedit").attr("src")), "attachmentimage.jpg");
	}

	var url = '<?=base_url("api_admin/pengaturan/lowongan/edit/"); ?>';
	$.ajax({
		type: $(this).attr('method'),
		url: url,
		data: fd,
		processData: false,
		contentType: false,
		success: function(respon){
			if(respon.status == 200){
				gritter('<h4>Berhasil</h4><p>Data berhasil diubah</p>','success');
				setTimeout(function(){
					window.location = '<=base_url_admin()?>pengaturan/lowongan/';
				},3456)
			}else{
				gritter('<h4>Gagal</h4><p>'+respon.message+'</p>','danger');
				$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
				$(".btn-submit").prop("disabled",false);
				NProgress.done();
			}
		},
		error:function(){
			gritter('<h4>Error</h4><p>Proses ubah data tidak bisa dilakukan, coba beberapa saat lagi</p>','danger');
			$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
			$(".btn-submit").prop("disabled",false);
			NProgress.done();
			return false;
		}
	});
});


// attach ifile listener
$(function() {
	$('#igambar').checkFileType({
		allowedExtensions: ['jpg', 'jpeg', 'ico', 'png', 'bmp'],
		success: function() {
			console.log('Max file after size: '+ufsmax+'MB');
			var flsz = $('#igambar')[0].files[0].size/1920/1080;
			flsz = flsz.toFixed(2);
			if(flsz > ufsmax){
				console.log('File too big, maximum is '+ufsmax+'MB');
				$('#igambar').val('');
				return false;
			}else if (flsz <= 0){
				console.log('Empty file');
				$('#igambar').val('');
				return false;
			}else{
				fileReaderAdd.readAsDataURL($('#igambar')[0].files[0]);
			}
		},
		error: function() {
			console.log('Invalid file image, please change your icon!');
		}
	});
});

// attach ifile listener
$(function() {
	$('#iegambar').checkFileType({
		allowedExtensions: ['jpg', 'jpeg', 'ico', 'png', 'bmp'],
		success: function() {
			console.log('Max file after size: '+ufsmax+'MB');
			var flsz = $('#iegambar')[0].files[0].size/1920/1080;
			flsz = flsz.toFixed(2);
			if(flsz > ufsmax){
				console.log('File too big, maximum is '+ufsmax+'MB');
				gritter('File too big, maximum is '+ufsmax+'MB', 'warning');
				setTimeout(function(){
					$('#egambar').attr('src','');
				},300)
				$('#iegambar').val('');
				return false;
			}else if (flsz <= 0){
				console.log('Empty file');
				gritter('File too small, minimum is 20KB', 'warning');
				setTimeout(function(){
					$('#egambar').attr('src','');
				},300)
				$('#iegambar').val('');
				return false;
			}else{
				fileReaderEdit.readAsDataURL($('#iegambar')[0].files[0]);
			}
		},
		error: function() {
			console.log('Invalid file image, please change your icon!');
		}
	});
});

$('.demo').each( function() {

	$(this).minicolors({
		control: $(this).attr('data-control') || 'hue',
		defaultValue: $(this).attr('data-defaultValue') || '',
		format: $(this).attr('data-format') || 'hex',
		keywords: $(this).attr('data-keywords') || '',
		inline: $(this).attr('data-inline') === 'true',
		letterCase: $(this).attr('data-letterCase') || 'lowercase',
		opacity: $(this).attr('data-opacity'),
		position: $(this).attr('data-position') || 'bottom',
		swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
		change: function(value, opacity) {
		if( !value ) return;
		if( opacity ) value += ', ' + opacity;
		if( typeof console === 'object' ) {
			console.log(value);
		}
		},
		theme: 'bootstrap'
	});

});

var tes_seq = <?=json_encode($blbsm)?>;

$.each(tes_seq,function(k,v){
	v.a_banksoal_utype = v.a_banksoal_utype.toLowerCase();
	$('#ites_a_banksoal_id_'+v.a_banksoal_utype).val(v.a_banksoal_id);
	$('#ites_is_rand_soal_'+v.a_banksoal_utype).val(v.is_rand_soal);
	$('#ites_is_rand_jawaban_'+v.a_banksoal_utype).val(v.is_rand_jawaban);
	$('#ites_passing_grade_'+v.a_banksoal_utype).val(v.passing_grade);
});

$('#ftes_seleksi').on('submit',function(e){
	e.preventDefault();
	NProgress.start();
	$('.btn-submit').prop('disabled',true);
	$('.icon-submit').addClass('fa-circle-o-notch fa-spin');

	var fd = new FormData($(this)[0]);

	$.ajax({
		method: 'post',
		url: '<?=base_url('api_admin/pengaturan/lowongan/urutan_tes/'.$lowongan->id)?>',
		data: fd,
		processData: false,
		contentType: false,
		cache: false,
		success: function(dt, status, jqXHR){
			if(dt.status == 200){
				gritter('<h4>Berhasil</h4><p>Perngaturan berhasil disimpan</p>','success');
			}else{
				gritter('<h4>Gagal</h4><p>['+dt.status+'] '+dt.message+'</p>','danger');
			}
		},
		error: function(){
			gritter('<h4>Error</h4><p>Tidak dapat melakukan perubahan data saat ini.</p>','warning');
		},
		complete: function(){
			$('.icon-submit').removeClass('fa-circle-o-notch fa-spin');
			$('.btn-submit').prop('disabled',false);
			NProgress.done();
		}
	})
})
