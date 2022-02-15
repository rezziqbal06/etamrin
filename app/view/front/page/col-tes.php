<!DOCTYPE html>
<html class="no-js" lang="id">
<?php $this->getThemeElement('page/html/head',$__forward); ?>
<?php if($this->config->environment == 'production'){ ?>
  <?php $this->getThemeElement('page/html/gtag', $__forward) ?>
<?php } ?>
<body class="tes">
  <style>
  footer.footer-area {
    position: fixed;
    height: 2.5em;
    bottom: 0;
    width: 100%;
  }
  </style>
  <header>
    <div class="container">
      <div class="row g-0 align-items-center">
        <div class="col-12">
          <br>
        </div>
      </div>
    </div>
  </header>
  <div class="container container-dashboard">
    <div class="row">
      <div class="col-12">
        <div id="main-page">
          <?php $this->getThemeContent(); ?>
        </div>
      </div>
    </div>
  </div>

  <div style="display: none">
    <div>
      <div>
        <video autoplay="true" id="videoElement"></video>
        <canvas id="canvas"></canvas>
        <img id="imgPreview" src="" class="img-fluid" />
      </div>
    </div>
  </div>
  <footer class="footer-area pt-4" style="display: none;">
    <div class="footer-copyright-area">
      <div class="row">
        <div class="col text-center">
          <p class="lead">Sisa waktu: <span id="counter_down">sedang dihitung....</span></p>
        </div>
      </div>
    </div>
  </footer>

  <?php //$this->getThemeElement('page/html/footer',$__forward); ?>
  <?php //$this->getThemeElement('page/html/aside',$__forward); ?>

  <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
  <?php $this->getJsFooter(); ?>

  <!-- Load and execute javascript code used only in this page -->
  <script>
    var is_timeout = 0;
    // Set the date we're counting down to
    var countDownDate = new Date("Jan 5, 2022 15:37:25").getTime();

    // Update the count down every 1 second
    function startTimer(){
      var itsTimeToCountDown = setInterval(function() {
        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        // document.getElementById("counter_down").innerHTML = days + "d " + hours + "h "+ minutes + "m " + seconds + "s ";
        var strtime = '';
        if(hours>0){
          strtime += hours+' jam ';
        }
        if(minutes>0){
          strtime += minutes+' menit ';
        }
        if(seconds>=0){
          strtime += seconds+' detik ';
        }
        document.getElementById("counter_down").innerHTML = strtime;

        // If the count down is over, write some text
        if (distance < 0) {
          clearInterval(itsTimeToCountDown);
          document.getElementById("counter_down").innerHTML = "berakhir";
          is_timeout = 1;
          $('#form_tes').trigger('submit');
        }
      }, 1000);
    }
  </script>

  <script>
  var is_capture_failed = 0;
  console.log('bfore ready');
  var modal_pilihan = {};
  if(document.getElementById('modal_pilihan') !== null){
    var modal_pilihan = new bootstrap.Modal(document.getElementById('modal_pilihan'));
  }
  //check image allowed
  !function(n){n.fn.checkFileType=function(e){return e=n.extend({allowedExtensions:[],success:function(){},error:function(){}},e),this.each(function(){n(this).on("change",function(){var s=n(this).val().toLowerCase(),t=s.substring(s.lastIndexOf(".")+1);-1==n.inArray(t,e.allowedExtensions)?(e.error(),n(this).focus()):e.success()})})}}(jQuery);

  function DataURIToBlob(dataURI) {
    const splitDataURI = dataURI.split(',');
    const byteString = splitDataURI[0].indexOf('base64') >= 0 ? atob(splitDataURI[1]) : decodeURI(splitDataURI[1]);
    const mimeString = splitDataURI[0].split(':')[1].split(';')[0];

    const ia = new Uint8Array(byteString.length);
    for (let i = 0; i < byteString.length; i++)
        ia[i] = byteString.charCodeAt(i);

    return new Blob([ia], { type: mimeString });
  }

  $(document).ready(function(e){
    $('.header-btn-pilihan').on('click',function(){
      modal_pilihan.show();
    });
    var canvasWrapper = $(".off-canvas-wrapper");
    $(".btn-menu").on('click', function() {
      canvasWrapper.addClass('active');
      $("body").addClass('fix');
      console.log('add class');
    });

    $(".close-action > .btn-close, .off-canvas-overlay").on('click', function() {
      canvasWrapper.removeClass('active');
      $("body").removeClass('fix');
      console.log('remove class');
    });

    $('.main-menu').slicknav({
      appendTo: '.res-mobile-menu',
      closeOnClick: true,
      removeClasses: true,
      closedSymbol: '<i class="icon-arrows-plus"></i>',
      openedSymbol: '<i class="icon-arrows-minus"></i>'
    });

    <?php $this->getJsReady(); ?>

    let video2 = {};
    const video = document.querySelector("#videoElement");
    const cnv = document.createElement('canvas');
    const ctx = cnv.getContext('2d');
    const imgPreview = document.querySelector('#imgPreview');
    var is_mulai = 0;
    console.log(typeof imgPreview);

    function puntenKameranaAktifkeun(){
      $('#block-soal').empty();
      $('#block-soal').append(`
        <div class="container" style="min-height:30vh;">
          <div class="row">
            <div class="col-md-12">
              <h1>Perhatian</h1>
              <p>Tes ini memerlukan akses ke kamera, silakan izinkan akses kamera lalu <a href="#" onclick="window.location.reload()">muat ulang halaman ini</a>.</p>
              <p>Halaman web ini hanya mendukung Google Chrome dan Firefox, silakan install salah satu aplikasi browser tersebut pada HP atau Laptop Anda. Baru buka lagi halaman web ini.</p>
              <br>
              <h2>Cara izinkan kamera untuk Google Chrome</h2>
              <p>Berikut ini adalah cara untuk mengizinkan akses kamera di Google Chrome:</p>
              <ol>
                <li>Di kanan atas, klik Lainnya. Pengaturan.</li>
                <li>Di bawah "Privasi dan keamanan", klik Setelan situs.</li>
                <li>Klik Kamera atau Mikrofon.</li>
                <li>Pilih opsi yang Anda inginkan sebagai pengaturan default Anda.</li>
                <li>Tinjau situs Anda yang diblokir dan diizinkan.</li>
              </ol>
              <h2>Cara izinkan kamera untuk Firefox</h2>
              <p>Berikut ini adalah cara untuk mengizinkan akses kamera di Google Chrome:</p>
              <ol>
                <li>Di bilah Menu di bagian atas layar, klik Firefox dan pilih Preferensi.</li>
                <li>Klik Privasi & Keamanan dari menu sebelah kiri.</li>
                <li>Gulir ke bawah ke bagian Izin.</li>
                <li>Klik tombol <code>Pengaturan…</code> atau <code>Settings...</code> untuk opsi Kamera.</li>
                <li>Kemudian pilih <code>Izinkan</code> atau <code>Allow</code> pada alamat situs ini.</li>
                <li>Klik tombol Simpan Perubahan.</li>
              </ol>

            </div>
          </div>
        </div>
      `);
      $('#block-soal').show();
      modal.hide();
    }

    if (navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({video: true})
      .then(function(stream) {
        video.srcObject = stream;
        <?php if(isset($this->config->semevar->is_tes_camera_preview) && !empty($this->config->semevar->is_tes_camera_preview)){ ?>
          video2 = video.cloneNode(true);
          video2.setAttribute("id", "videoElement2");
          video2.setAttribute("style", "width: 100%; height: auto");
          document.getElementById("videoElementPreview").appendChild(video2);
          video2.srcObject = stream;
        <?php }else{ ?>
        console.log('"Tes Kamera Preview" is disabled or not exist on config');
        <?php } ?>
      })
      .catch(function(error) {
        console.log("Something went wrong!",error);
      });
    }

    function sendCapture(blobData, imageName=''){
      var fd = new FormData();
      fd.append('image', blobData, imageName);
      $.ajax({
        processData: false,
        contentType: false,
        url: '<?=base_url('api_front/tes/capture/'.$utype.'/?apikey='.$sess->user->token)?>',
        method: 'post',
        data: fd,
        success: function(dt){
          if(dt.status == 200){

          }else if(dt.status == 402){
            is_mulai=0;
            is_capture_failed=1;
            alert('Sesi pengisian soal telah berakhir, sistem akan memuat ulang halaman ini.');
            window.location.reload();
          }else if(dt.status == 1221){
            is_mulai=0;
            is_capture_failed=1;
            // showToast('danger',"Gagal Kirim","<p>["+dt.status+"] Tidak dapat mengirim jawaban ke server, nanti cobain lagi</p>");
            puntenKameranaAktifkeun();
          }else if(dt.status == 1249 || dt.status == 1239 || dt.status == 1229){
            showToast('danger',"Gagal Kirim","<p>["+dt.status+"] "+dt.message+"</p>");
          }else{
            showToast('danger',"Gagal Kirim","<p>["+dt.status+"] Tidak dapat mengirim jawaban ke server, nanti cobain lagi</p>");
            window.location.reload();
          }
        },
        error: function(d){
          showToast('warning',"Gagal Koneksi","<p>Koneksi ke server kita kurang baik saat ini, coba lagi nanti</p>");
        },
        completed: function(){

        }
      })
    }

    function mulaiLagi(){
      $.get('<?=base_url('api_front/tes/mulai/'.$utype.'/?apikey='.$sess->user->token)?>').done(function(dt){
        if(dt.status == 200){
          countDownDate = new Date(dt.data.time_start).getTime();;
          $('.footer-area').show('slow')
          $('#modal_tes_before').empty();
          setTimeout(function(){
            is_mulai=1;
            modal.hide();
            setTimeout(function(){
              $('.btn-submit').prop('disabled', false);
              $("#block-soal").slideDown('slow',function(){
              });
              startTimer();
            },1234);
          },1234);
        }else{
          showToast('danger',"Gagal","<p>["+dt.status+"] "+dt.message+"</p>");
          $(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
          NProgress.done();
        }
      }).fail(function(){
        showToast('warning',"Koneksi Tidak Stabil","<p>Tidak dapat memulai tes saat ini, cobalah beberapa saat lagi</p>");
        $(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
        NProgress.done();
      }).always(function(){
      });
    }

    var i = 0;
    function capture() {
      i++;
      cnv.width = video.videoWidth;
      cnv.height = video.videoHeight;
      ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
      var data = cnv.toDataURL('image/jpeg');
      imgPreview.src = data;
      var blob = DataURIToBlob(imgPreview.src);
      if (typeof blob === 'object'){
        $(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
        NProgress.done();
        if(is_mulai) sendCapture(blob, 'capture.jpg');
      }

      if(!is_capture_failed){
        setTimeout(function(){
          capture();
        },5000);
      }

    }

    capture();
    document.onkeydown = function(){
      switch (event.keyCode){
        case 116 : //F5 button
        event.returnValue = false;
        event.keyCode = 0;
        return false;
        case 82 : //R button
        if (event.ctrlKey){
          event.returnValue = false;
          event.keyCode = 0;
          return false;
        }
      }
    }

    var modal_tes_before = document.getElementById('modal_tes_before');
    var modal = new bootstrap.Modal(modal_tes_before)
    modal.show();

    $('.btn-tes-mulai').on('click',function(e){
      e.preventDefault();

      NProgress.start();
      $(".btn-tes-mulai").prop("disabled",true);
    	$(".icon-submit").addClass("fa-spin");
    	$(".icon-submit").addClass("fa-circle-o-notch");

      i++;
      cnv.width = video.videoWidth;
      cnv.height = video.videoHeight;
      ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
      var data = cnv.toDataURL('image/jpeg');
      imgPreview.src = data;
      var blob = DataURIToBlob(imgPreview.src);
      if (typeof blob === 'object'){
        var fd = new FormData();
        fd.append('image', blob, 'capture.jpg');
        $.ajax({
          processData: false,
          contentType: false,
          url: '<?=base_url('api_front/tes/capture/'.$utype.'/?apikey='.$sess->user->token)?>',
          method: 'post',
          data: fd,
          success: function(dt){
            if(dt.status == 200){
              is_mulai=1;
              is_capture_failed=0;
              mulaiLagi();
            }else if(dt.status == 402){
              is_mulai=0;
              is_capture_failed=1;
              alert('Sesi pengisian soal telah berakhir, sistem akan memuat ulang halaman ini.');
              window.location.reload();
            }else if(dt.status == 1221){
              is_mulai=0;
              is_capture_failed=1;
              puntenKameranaAktifkeun();
            }else if(dt.status == 1249 || dt.status == 1239 || dt.status == 1229){
              showToast('info',"Perhatian","<p>["+dt.status+"] "+dt.message+"</p>");
              setTimeout(function(){
                window.location.href='<?=base_url('kandidat/dashboard/')?>';
              },3456);
            }else{
              showToast('danger',"Gagal Kirim","<p>["+dt.status+"] Tidak dapat mengirim jawaban ke server, nanti cobain lagi</p>");
            }
            console.log('dt',dt);
          },
          error: function(d){
            showToast('warning',"Gagal Koneksi","<p>Koneksi ke server kita kurang baik saat ini, coba lagi nanti</p>");
          },
          completed: function(){
            $(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
            NProgress.done();
          }
        });
      }

    });

    $('#form_tes').on('submit',function(e){
      e.preventDefault();
      if(!is_timeout){
        if(!confirm('Apakah anda sudah yakin?')){
          return false;
        }
      }

      NProgress.start();
      $(".btn-submit").prop("disabled",true);
      $(".icon-submit").addClass("fa-spin fa-circle-o-notch");
      showToast('info',"Memproses...","<p>Kami sedang menyimpan jawaban anda, silakan tunggu...</p>");

      var fd = new FormData($(this)[0]);
      $.ajax({
        url: '<?=base_url('api_front/tes/jawab_selesai/'.$utype.'/?apikey='.$sess->user->token)?>',
        method: 'post',
        data: fd,
    		processData: false,
    		contentType: false,
        success: function(dt){
          if(dt.status == 200 || dt.status == 1208){
            capture();
            setTimeout(function(){
              window.location = '<?=base_url('tes/selesai/index/'.$utype.'/')?>';
            },3456);
          }else if(dt.status == 1249 || dt.status == 1239 || dt.status == 1229){
            showToast('info',"Perhatian","<p>["+dt.status+"] "+dt.message+"</p>");
            setTimeout(function(){
              window.location.href='<?=base_url('kandidat/dashboard/')?>';
            },3456);
          }else{
            showToast('danger',"Gagal Kirim","<p>["+dt.status+"] Tidak dapat mengirim jawaban ke server, nanti cobain lagi</p>");
            $(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
            $(".btn-submit").prop("disabled",false);
            setTimeout(function(){
              window.location.reload();
              NProgress.done();
            },2345);
          }
        },
        error: function(d){
          showToast('warning',"Gagal Koneksi","<p>Koneksi ke server kita kurang baik saat ini, nanti cobain lagi</p>");
          $(".icon-submit").removeClass("fa-spin fa-circle-o-notch");
          $(".btn-submit").prop("disabled",false);
          NProgress.done();
        },
        completed: function(){
        }
      });
    });

    <?php $this->getJsContent(); ?>

    <?php
    if(isset($sess->user->id)){
      $this->getThemeElement('page/html/js_logged_in',$__forward);
    }
    ?>
  });
  setTimeout(function(){
    $('.btn-tes-mulai').prop('disabled',false);
  },5000);
  </script>
</body>
</html>
