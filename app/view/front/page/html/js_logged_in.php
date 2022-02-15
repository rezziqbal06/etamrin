var current_menu = '<?=isset($this->current_menu) ? $this->current_menu : ""?>';

// file reader
var fileReaderUserFoto = new FileReader();
var ufsmax = 10;
var frmax = 400;
var wmax = 1080;
var hmax = 720;

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

// attach fileReaderUserFoto onload
fileReaderUserFoto.onload = function (event) {
  var image = new Image();
	console.log('image.src',image.src);
  image.onload=function(){
    document.getElementById("user_foto_ori").src=image.src;
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
    document.getElementById("user_foto_rsz").src = canvas.toDataURL();
  }
  image.src=event.target.result;
	console.log('final image.width: '+image.width);
	console.log('final image.height: '+image.height);
};

$("#user_foto").on('change',function(e){
  e.preventDefault();
  readURLImage(this,'user_foto_preview');
});

// attach user_foto listener
$(function() {
	$('#user_foto').checkFileType({
		allowedExtensions: ['jpg', 'jpeg', 'ico', 'png', 'bmp'],
		success: function() {
			// alert('Allowed extension icon!');
			console.log('Max file size: '+ufsmax+'MB');
			var flsz = $('#user_foto')[0].files[0].size/1920/1080;
			flsz = flsz.toFixed(2);
			if(flsz > ufsmax){
				console.log('File too big, maximum is '+ufsmax+'MB');
				$('#user_foto').val('');
				return false;
			}else if (flsz <= 0){
				console.log('unselected file');
				$('#ifoto').val('');
				return false;
			}else{
				fileReaderUserFoto.readAsDataURL($('#user_foto')[0].files[0]);
			}
		},
		error: function() {
			console.log('Invalid image file, please choose other file!');
		}
	})
});

$('.a-user-foto-ganti').on('click',function(e){
  e.preventDefault();
  if(confirm('Ingin ganti foto profil?')){
    $('#user_foto').trigger('click');
  }

});
$('#user_foto').on('change',function(){
	setTimeout(function(){
		$('#user_foto_form').trigger('submit');
	},678);

});

$("#user_foto_form").on('submit', function(e){
  e.preventDefault();

	var foto_rsz = $("#user_foto_rsz").attr("src");
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
	$('.a-user-foto-ganti img').attr('src', '<?=$this->cdn_url('skin/front/img/ajax-loader.gif')?>');

  var url = '<?=base_url("api_front/user/foto/ganti/")?>?apikey=kl17ie';
  var fd = new FormData();
  fd.append("foto", DataURIToBlob($("#user_foto_rsz").attr("src")), "pas-foto.jpg");
  $.ajax({
		type: $(this).attr('method'),
    url: url,
    data: fd,
    processData: false,
    contentType: false,
    success: function(respon){
      if(respon.status == 200){
        $('.a-user-foto-ganti img').attr('src', respon.data.foto);
				showToast("success","Berhasil","<p>Foto profil berhasil diganti</p>");
      }else{
        setTimeout(function(){
          showToast("warning","Gagal","<p>"+respon.message+"</p>");
        }, 666);
      }
			$(".icon-submit").removeClass("fa-spin");
			$(".icon-submit").removeClass("fa-circle-o-notch");
			$(".btn-submit").prop("disabled",false);
			NProgress.done();
    },
    error:function(){
      setTimeout(function(){
        showToast('danger',"Error","<p>Tidak dapat ganti foto saat ini, coba beberapa saat lagi</p>");
      }, 666);
  		$(".icon-submit").removeClass("fa-spin");
  		$(".icon-submit").removeClass("fa-circle-o-notch");
  		$(".btn-submit").prop("disabled",false);
  		NProgress.done();
    }
  });
});

console.log('current_menu: '+current_menu);
$.each($("#menu-left a"),function(k,v){
  if($(this).hasClass('menu_left_'+current_menu)){
    $(this).parent().parent().parent().parent().parent().find('.accordion-button').trigger('click');
    $('.menu_left_'+current_menu).parent().addClass('active');
    return;
  }
});

$('.btn-menu-left-show').on('click',function(e){
  e.preventDefault();
  console.log('left menu toggle');
  $('body').toggleClass('visible-show');
});
