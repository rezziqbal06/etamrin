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

// file reader
var fileReaderFoto = new FileReader();
var ufsmax = 10;
var frmax = 400;
var wmax = 1080;
var hmax = 720;


//url get alamat
var provinsi_id = 0;
var kabkota_id = 0;
var provinsi = '';
var kabkota = '';
var kecamatan = '';
var base_url_alamat = 'https://alamat.thecloudalert.com/api/';

let today = new Date();
const days_to_subtract = 5110;
let max_date = new Date(today.valueOf()-(days_to_subtract*24*60*60*1000));
$(".input-datepicker").datepicker({
  uiLibrary: 'bootstrap4',
  format: 'yyyy-mm-dd',
  value: '<?=date("Y-m-d",strtotime("-15 years"))?>',
	endDate: max_date
});



$("#register_form").on('submit', function(e){
  e.preventDefault();
  var k = $('#ijk');
  if(k.val()==''){
    showToast('info',"Perhatian","<p>Silakan pilih jenis kelamin</p>");
		k.focus();
    return false;
  }
	var k1 = $('#ipassword');
  var k2 = $('#iulangpassword');
  if(k1.val() != k2.val()){
    showToast('info',"Perhatian","<p>Password dan Konfirmasi Password tidak sama.</p>");
		k1.focus();
    return false;
  }

	var k = $("#ipendidikan_terakhir :selected").val();
	if(k.length<=0){
		showToast('info',"Perhatian","<p>Pendidikan Terakhir belum diisi</p>");
    return false;
	}

	var k = $("#ialamat :selected").val();
	if(k.length<=0){
		showToast('info',"Perhatian","<p>Alamat belum dipilih / diisi</p>");
    return false;
	}

	var foto_rsz = $("#foto_rsz").attr("src");
	if(foto_rsz.length <= 3){
		showToast('info',"Perhatian","<p>File Pas foto belum dipilih.</p>");
		setTimeout(function(){
			showToast('info',"Info","<p>Silakan memilih file pas foto terlebih dahulu</p>");
			setTimeout(function(){
				$('#ifoto').trigger('click');
			},678);
		},678);
		return false;
	}

  NProgress.start();
	$(".btn-submit").prop("disabled", true);
	$(".icon-submit").addClass("fa-circle-o-notch fa-spin");

  var url = '<?=base_url("api_front/register/")?>?apikey=kl17ie';
  var fd = new FormData($(this)[0]);
  fd.append("provinsi",provinsi);
  fd.append("kecamatan",kecamatan);
  fd.append("kabkota",kabkota);

	//delete current input file
  fd.delete("foto");
  //put resized image into formdata
  fd.append("foto", DataURIToBlob($("#foto_rsz").attr("src")), "pas-foto.jpg");


  $.ajax({
		type: $(this).attr('method'),
    url: url,
    data: fd,
    processData: false,
    contentType: false,
    success: function(dt){
      if(dt.status == 200){
				showToast('success',"Berhasil","<p>Daftar berhasil, silakan tunggu..</p>");
        setTimeout(function(){
					if(typeof is_joblist !== 'undefined' && is_joblist){
						mr.hide();
						is_login = 1;

						$(".btn-submit").prop("disabled",false);
						$(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
						NProgress.done();

						$('.btn-apply').trigger('click');
					}else{
						setTimeout(function(){
							if(typeof dt.data.redirect_url !== 'undefined'){
								window.location = dt.data.redirect_url;
							}else{
								window.location = '<?=base_url('register/success')?>';
							}
	          });
					}
        }, 3456);
      }else{
        setTimeout(function(){
          showToast("danger","Gagal","<p>["+dt.status+"] "+dt.message+"</p>");
        }, 666);
    		$(".icon-submit").removeClass("fa-spin");
    		$(".icon-submit").removeClass("fa-circle-o-notch");
    		$(".btn-submit").prop("disabled",false);
    		NProgress.done();
      }
    },
    error:function(){
      setTimeout(function(){
        showToast('warning',"Error","<p>Pendaftaran tidak dapat diproses saat ini, cobalah beberapa saat lagi</p>");
      }, 666);
  		$(".icon-submit").removeClass("fa-spin");
  		$(".icon-submit").removeClass("fa-circle-o-notch");
  		$(".btn-submit").prop("disabled",false);
  		NProgress.done();
    }
  });
})




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

// attach fileReaderBefore onload
fileReaderFoto.onload = function (event) {
  var image = new Image();
	console.log('image.src',image.src);
  image.onload=function(){
    document.getElementById("foto_ori").src=image.src;
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
    document.getElementById("foto_rsz").src = canvas.toDataURL();
  }
  image.src=event.target.result;
	console.log('final image.width: '+image.width);
	console.log('final image.height: '+image.height);
};

$("#ifoto").on('change',function(e){
  e.preventDefault();
  readURLImage(this,'foto_preview');
});

$('.img-pas-foto-selector').on('click',function(e){
	e.preventDefault();
	$('#ifoto').trigger('click');
});


// attach ifoto listener
$(function() {
	$('#ifoto').checkFileType({
		allowedExtensions: ['jpg', 'jpeg', 'ico', 'png', 'bmp'],
		success: function() {
			// alert('Allowed extension icon!');
			console.log('Max file size: '+ufsmax+'MB');
			var flsz = $('#ifoto')[0].files[0].size/1920/1080;
			flsz = flsz.toFixed(2);
			if(flsz > ufsmax){
				console.log('File too big, maximum is '+ufsmax+'MB');
				$('#ifoto').val('');
				return false;
			}else if (flsz <= 0){
				console.log('unselected file');
				$('#ifoto').val('');
				return false;
			}else{
				fileReaderFoto.readAsDataURL($('#ifoto')[0].files[0]);
			}
		},
		error: function() {
			console.log('Invalid image file, please choose other file!');
		}
	})
});

$(".kabkota-select2").select2({
	ajax: {
		method: 'post',
		url: 'https://alamat.thecloudalert.com/api/cari/',
		dataType: 'json',
    delay: 250,
		data: function (params) {
      var query = {
        keyword: params.term
      }
      return query;
    },
    processResults: function (dt) {
      return {
        results:  $.map(dt.result, function (itm) {
          return {
            text: (itm.desakel+', '+itm.kecamatan+', '+itm.kabkota+', '+itm.provinsi+', '+itm.negara),
            id: (itm.desakel+', '+itm.kecamatan+', '+itm.kabkota+', '+itm.provinsi+', '+itm.negara)
          }
        })
      };
    },
    cache: true
	},
	formatNoMatches: function () {
  	return "Isi nama daerah / kecamatan";
  },
	"language": {
     "noResults": function(){
       return "Isi nama daerah / kecamatan";
     }
   }
});

$('#inoktp').on('keyup',function(){
	if($(this).val().length > 16){
		$(this).val( $(this).val().substring(0,16) );
	}
})
