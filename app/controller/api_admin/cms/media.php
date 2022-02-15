<?php
class Media extends JI_Controller{
	var $module = "cms_media";
	var $page = "cms_media";

	var $resize_step_one = 220;
	var $resize_step_two_a = 180;
	var $resize_step_two_b = 180;

	public function __construct(){
		parent::__construct();
		$this->lib("sene_json_engine");
		$this->load("api_admin/a_media_model",'media');
		$this->setTheme("admin/");
		$this->user_login = 0;
		$this->admin_login = 0;
	}

	private function __createThumbnail($img_source,$img_dest){
		$rszmode = 'w';
		list($width, $height, $type, $attr) = getimagesize($img_source);
		if($height>$width){
			$rszmode = 'h';
		}
		if(file_exists($img_dest) && is_file($img_dest)) unlink($img_dest);
		$white = WideImage::load($img_source)->allocateColor(255,255,255);
		WideImage::load($img_source)->resize($this->resize_step_one)->saveToFile($img_dest);
		WideImage::load($img_dest)->crop('center', 'top', $this->resize_step_two_a, $this->resize_step_two_b)->saveToFile($img_dest);
	}

	private function __thumbnail_filename($filetowrite,$suffix="-thumb"){
		$pi = pathinfo($filetowrite);
		$filetowrite2 = $pi['filename'].$suffix.'.'.$pi['extension'];
		return $filetowrite2;
	}

	public function __json_out($dt){
		$data = array();
		$data['status'] = (int) $this->status;
		$data['message'] = $this->message;
		$data['result'] = $dt;

		header('Content-Type: application/json');
		echo json_encode($data);
	}

	private function __uploadCmsMedia(){
		$fldr = $this->config->semevar->media_upload;
		$folder = SEMEROOT.DIRECTORY_SEPARATOR.$fldr.DIRECTORY_SEPARATOR;
		$folder = str_replace('\\','/',$folder);
		$folder = str_replace('//','/',$folder);
		$ifol = realpath($folder);
		//die($folder);
		if(!$ifol) mkdir($folder);
		$ifol = realpath($folder);
		//die($ifol);

		//reset files
		reset($_FILES);
		$temp = current($_FILES);
		if (is_uploaded_file($temp['tmp_name'])){
			if (isset($_SERVER['HTTP_ORIGIN'])) {
				// same-origin requests won't set an origin. If the origin is set, it must be valid.
				header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
			}
			header('Access-Control-Allow-Credentials: true');
			header('P3P: CP="There is no P3P policy."');
			// Sanitize input
			if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
				header("HTTP/1.0 500 Invalid file name.");
				return 0;
			}
			// Verify extension
			if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png","jpeg"))) {
				header("HTTP/1.0 500 Invalid extension.");
				return 0;
			}
			if(mime_content_type($temp['tmp_name']) == 'webp'){
				header("HTTP/1.0 500 Unsupported image format.");
				return 0;
			}

			// Create magento style media directory
			$name1 = date("Y");
			$name2 = date("m");
			if(PHP_OS == "WINNT"){
				if(!is_dir($ifol)) mkdir($ifol);
				$ifol = $ifol.DIRECTORY_SEPARATOR.$name1.DIRECTORY_SEPARATOR;
				if(!is_dir($ifol)) mkdir($ifol);
				$ifol = $ifol.DIRECTORY_SEPARATOR.$name2.DIRECTORY_SEPARATOR;
				if(!is_dir($ifol)) mkdir($ifol);
			}else{
				if(!is_dir($ifol)) mkdir($ifol,0775);
				$ifol = $ifol.DIRECTORY_SEPARATOR.$name1.DIRECTORY_SEPARATOR;
				if(!is_dir($ifol)) mkdir($ifol,0775);
				$ifol = $ifol.DIRECTORY_SEPARATOR.$name2.DIRECTORY_SEPARATOR;
				if(!is_dir($ifol)) mkdir($ifol,0775);
			}
			$filename = str_replace(" ","-",strtolower($temp['name']));
			$filetowrite = $ifol.$filename;
			$filetowrite2 = $this->__thumbnail_filename($filetowrite);

			//move uploaded file
			if(file_exists($filetowrite)) unlink($filetowrite);
			move_uploaded_file($temp['tmp_name'], $filetowrite);
			if(file_exists($filetowrite)){
				//load library
				$this->lib("wideimage/WideImage",'wideimage',"inc");
				//check if file exists
				if(file_exists($ifol.$filetowrite2)) unlink($ifol.$filetowrite2);

				$this->__createThumbnail($filetowrite, $ifol.$filetowrite2, 100, 100);

				// WideImage::load($filetowrite)->resize(370)->saveToFile($ifol.$filetowrite2);
				return $fldr."/".$name1."/".$name2."/".$filename;
			}else{
				return 0;
			}
		} else {
			// Notify editor that the upload failed
			//header("HTTP/1.0 500 Server Error");
			return 0;
		}
	}

	public function index($utype="kaskecil"){
		$this->status = 400;
		$this->message = 'Login';
		$s = $this->__init();

		$data = array();
		if($this->admin_login){
			$this->status = 200;
			$this->message = 'Berhasil';
			$folder = $this->input->get('folder');
			if(empty($folder)) $folder = '';
			$folder = trim($folder,'/');
			$folder = '/'.$folder;

			$root = new stdClass();
			$root->folder = '/';
			$data['folders'] = $this->media->getFolder();
			if(empty($data['folders'])){
				$data['folders'] = array();
				$data['folders'][] = $root;
			}
			$files = $this->media->getByFolder($folder);
			foreach($files as &$f){
				$fe = explode('/',$f->nama);
				$f->filename = end($fe);
				$fd = $f->filename;
				$fn = basename($f->filename);
				$fe = explode('.', $f->filename);
				$ext = end($fe);
				$fn = str_replace('.'.$ext,'',$fd);
				$f->filethumb = $fn.'-thumb.'.$ext;
				$f->thumb = rtrim($f->nama,$f->filename).$f->filethumb;

				$f->tgl = date("l, j F Y",strtotime($f->cdate));
			}
			$data['files'] = $files;
		}
		$this->__json_out($data);
	}
	public function add(){
		$this->status = 400;
		$this->message = 'Login';
		$s = $this->__init();
		//$this->debug($s);
		//die();
		$data = new stdClass();
		if($this->admin_login){
			$filename = $this->__uploadCmsMedia();
			if($filename){
				$folder = $this->input->post('folder');
				if(empty($folder)) $folder = '';
				$folder = ltrim($folder,'/');
				$folder = '/'.$folder;

				$di = array();
				$di['b_user_id'] = $s['sess']->admin->id;
				$di['folder'] = $folder;
				$di['nama'] = str_replace('//','/',$filename);
				$di['cdate'] = 'NOW()';
				$di['is_active'] = 1;
				$res = $this->media->set($di);
				if($res){
					$this->status = 200;
					$this->message = 'Berhasil';
				}else{
					$this->message = 'Gagal insert ke database';
					$this->status = 101;
				}
			}else{
				$this->message = 'Gagal upload';
				$this->status = 102;
			}
		}
		$this->__json_out($data);
	}
	public function move(){
		$s = $this->__init();
		$data = new stdClass();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			$this->__json_out($data);
			die();
		}
		$id = (int) $this->input->post('id');
		if($id<=0){
			$this->status = 904;
			$this->message = 'Invalid Media ID';
			$this->__json_out($data);
			die();
		}

		$folder = $this->input->post('folder');
		if(empty($folder)) $folder = '/';
		$folder = str_replace("//","/",$folder);

		$m = $this->media->getById($id);
		if(isset($m->id)){
			$du = array();
			$du['folder'] = $folder;
			$res = $this->media->update($id,$du);
			if($res){
				$this->status = 200;
				$this->message = 'Berhasil';
			}else{
				$this->message = 'Gagal updated ke database';
				$this->status = 901;
			}
		}else{
			$this->message = 'Gagal upload';
			$this->status = 902;
		}
		$this->__json_out($data);
	}

	public function del($id=""){
		$id = (int) $id;
		$this->status = 400;
		$this->message = 'Login';
		$s = $this->__init();
		$data = new stdClass();
		if($this->admin_login && ($id)>0){
			$m = $this->media->getById($id);
			if(isset($m->id)){
				$flnm  = $m->nama;
				$fldr  = SEMEROOT.DIRECTORY_SEPARATOR.$flnm;
				if(file_exists($fldr)) unlink($fldr);

				$fe = explode('/',$m->nama);
				$filename = end($fe);
				$fd = $filename;
				$fn = basename($filename);
				$fe = explode('.', $filename);
				$ext = end($fe);
				$fn = str_replace('.'.$ext,'',$fd);
				$filethumb = $fn.'-thumb.'.$ext;
				$thumb = rtrim($m->nama,$filename).$filethumb;

				if(file_exists($thumb)) unlink($thumb);

				if(empty($folder)) $folder = '/';

				$res = $this->media->del($id);
				if($res){
					$this->status = 200;
					$this->message = 'Berhasil';
				}else{
					$this->message = 'Gagal hapus database';
					$this->status = 101;
				}
			}else{
				$this->message = 'media tidak ditemukan';
				$this->status = 102;
			}
		}
		$this->__json_out($data);
	}
	public function regenerate($utype="crop",$width="",$height=""){
		$this->status = 400;
		$this->message = 'Login';
		$s = $this->__init();
		$data = array();
		$data['jumlah'] = 0;
		$data['berhasil'] = 0;
		$data['gagal'] = 0;

		if($this->admin_login){
			$this->status = 200;
			$this->message = 'Berhasil';
			$media = $this->media->get();
			$data['jumlah'] = count($media);
			//$this->debug($media);
			//die();
			$this->lib("wideimage/WideImage",'wideimage',"inc");
			if(empty($width)) $width = 370;
			if(empty($height)) $height = 240;
			foreach($media as $med){
				$media_file = SEMEROOT.DIRECTORY_SEPARATOR.$med->nama;
				$media_file = str_replace('\\','/',$media_file);
				$media_file = str_replace('//','/',$media_file);
				if(file_exists($media_file)){
					$ext = strtolower(pathinfo($media_file, PATHINFO_EXTENSION));
					$media_file_thumb = substr($media_file,0,strlen($media_file)-(strlen($ext)+1)).'-thumb.'.$ext;
					$media_file_crop = substr($media_file,0,strlen($media_file)-(strlen($ext)+1)).'-crop.'.$ext;

					if(file_exists($media_file_thumb)) unlink($media_file_thumb);
					WideImage::load($media_file)->resize($width)->saveToFile($media_file_thumb,90);
					if(strtolower($utype)=='crop'){
						if(file_exists($media_file_crop)) unlink($media_file_crop);
						if(false){
							// Load the original image
							$original = WideImage::load($media_file_thumb);
							$resized  = $original->resizeDown($width, null); // Do whatever resize or crop you need to do
							$original->destroy(); // free some memory (original image not needed any more)

							// Create an empty canvas with the resized image sizes
							$img = WideImage::createTrueColorImage($width, $height);
							$bg  = $img->allocateColor(255,255,255);
							$img->fill(0,0,$bg);

							// Finally merge and do whatever you need...
							$img->merge($resized)->saveToFile($media_file_crop);
							$resized->destroy();
							$img->destroy();
						}else{
							WideImage::load($media_file_thumb)->crop('center', 'top', $width, $height)->saveToFile($media_file_crop,90);
						}
					}
					$data['berhasil']++;
				}else{
					$data['gagal']++;
				}
			}
		}else{
		}
		$this->__json_out($data);
	}
	public function rebuild_thumb(){
		$images = $this->media->get();
		$this->lib("wideimage/WideImage",'wideimage',"inc");
		echo 'Done';
		echo '<br />======<br />';
		if(count($images)){
			foreach($images as $image){
				$file_image = SEMEROOT.DIRECTORY_SEPARATOR.$image->nama;
				echo $image->nama.': ';
				if(file_exists($file_image)){
					$file_thumbnail = $this->__thumbnail_filename($file_image);
					if(file_exists($file_thumbnail)) unlink($file_thumbnail);
					WideImage::load($file_image)->resize($this->resize_step_one)->saveToFile($file_thumbnail);
					WideImage::load($file_thumbnail)->crop('center', 'top', $this->resize_step_two_a, $this->resize_step_two_b)->saveToFile($file_thumbnail);
					echo 'success';
				}else{
					echo 'not found';
				}
				echo '<br />';
			}
		}
	}
	public function rename(){
		$s = $this->__init();
		$data = new stdClass();
		if(!$this->admin_login){
			$this->status = 400;
			$this->message = 'Session telah expired, silakan login lagi';
			$this->__json_out($data);
			die();
		}

		$folder = $this->input->post('folder');
		if(strlen($folder)==1) $folder = '/';
		$folder = str_replace("//","/",$folder);
		if(strlen($folder)==1){
			$this->message = 'Folder utama tidak dapat dirubah namanya';
			$this->status = 909;
			$this->__json_out($data);
			die();
		}

		$folder_new = $this->input->post('folder_new');
		if(strlen($folder_new)==1) $folder_new = '/';
		$folder_new = str_replace("//","/",$folder_new);
		if(strlen($folder_new)==1){
			$this->message = 'Folder baru minimal harus 2 huruf';
			$this->status = 910;
			$this->__json_out($data);
			die();
		}
		if($folder_new[0] != '/') $folder_new = '/'.$folder_new;

		if(strlen($folder)>1 && strlen($folder_new)>1){
			$du['folder'] = $folder_new;
			$res = $this->media->updateFolder($folder,$du);
			if($res){
				$this->status = 200;
				$this->message = 'Berhasil';
			}else{
				$this->message = 'Gagal updated ke database';
				$this->status = 901;
			}
		}else{
			$this->message = 'Unknown error occured';
			$this->status = 911;
		}
		$this->__json_out($data);
	}
}
