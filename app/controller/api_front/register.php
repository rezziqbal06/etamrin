<?php
/**
* API for Register
*/
class Register extends JI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->lib("seme_log");
        $this->lib('seme_email');
        $this->load("api_front/a_usermodule_model", "aum");
        $this->load("api_front/b_user_model", 'bu');
        $this->load("api_front/b_user_usermodule_model", "buum");
        $this->load("api_front/b_user_pendidikan_model", "bupdm");
    }

    public function __passGen($password)
    {
        $password = preg_replace('/[^a-zA-Z0-9]/', '', $password);
        return password_hash($password, PASSWORD_BCRYPT);
    }
    public function __passClear($password)
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $password);
    }

    public function __passwordGenerateLink($user_id)
    {
        $this->lib("conumtext");
        $token = $this->conumtext->genRand($type="str", $min=18, $max=24);
        $this->bu->setToken($user_id, $token, $kind="api_web");
        return base_url('account/password/reset/'.$token);
    }
    public function __genTokenMobile($user_id)
    {
        $user_id = (int) $user_id;
        $this->lib("conumtext");
        $token = $this->conumtext->genRand($type="str", $min=7, $max=11);
        return $token;
    }
    public function __genRegKode($user_id, $api_reg_token)
    {
        $this->lib("conumtext");
        $try = 3;
        $min = 5;
        $max = 5;
        while($try < 0 || strlen($api_reg_token) != 6){
          $api_reg_token = $this->conumtext->genRand($type="str", $min, $max);
          $try--;
        }
        $this->bu->setToken($user_id, $api_reg_token, $kind="api_reg");
        return $api_reg_token;
    }

    /**
    * generate Link Email
    */
    public function __activateGenerateLink($user_id, $token="")
    {
      $this->lib("conumtext");
      $this->load("api_front/b_user_model",'bu');
      $try = 3;
      $min = 5;
      $max = 5;
      while($try < 0 || strlen($token) != 6){
        $token = $this->conumtext->genRand($type="str", $min, $max);
        $try--;
      }
      $this->bu->setToken($user_id, $token, $kind="api_reg");
      return $token;
    }

    public function __uploadUserImage($b_user_id)
    {
        /*******************
        * Only these origins will be allowed to upload images *
        ******************/
        $folder = SEMEROOT.DS.$this->media_user.DS;
        $folder = str_replace('\\', '/', $folder);
        $folder = str_replace('//', '/', $folder);
        $ifol = realpath($folder);
        if (!$ifol) {
            mkdir($folder);
        }
        $ifol = realpath($folder);
        //die($ifol);

        reset($_FILES);
        $temp = current($_FILES);
        if (isset($temp['tmp_name'])) {
            if (is_uploaded_file($temp['tmp_name'])) {
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
                if (mime_content_type($temp['tmp_name']) == 'webp') {
                    header("HTTP/1.0 500 WebP currently unsupported.");
                    return 0;
                }
                // Verify extension
                $ext = pathinfo($temp['name'], PATHINFO_EXTENSION);
                if (!in_array(strtolower($ext), array("jpeg","gif", "jpg", "png"))) {
                    header("HTTP/1.0 500 Invalid extension.");
                    return 0;
                }

                // Create magento style media directory
                $year = date("Y");
                $month = date("m");
                if (PHP_OS == "WINNT") {
                    if (!is_dir($ifol)) {
                        mkdir($ifol);
                    }
                    $ifol = $ifol.DS.$year.DS;
                    if (!is_dir($ifol)) {
                        mkdir($ifol);
                    }
                    $ifol = $ifol.DS.$month.DS;
                    if (!is_dir($ifol)) {
                        mkdir($ifol);
                    }
                } else {
                    if (!is_dir($ifol)) {
                        mkdir($ifol, 0775, true);
                    }
                    $ifol = $ifol.DS.$year.DS;
                    if (!is_dir($ifol)) {
                        mkdir($ifol, 0775, true);
                    }
                    $ifol = $ifol.DS.$month.DS;
                    if (!is_dir($ifol)) {
                        mkdir($ifol, 0775, true);
                    }
                }

                // Accept upload if there was no origin, or if it is an accepted origin
                $rand = rand(0, 9999);
                $name = $b_user_id.'-'.$rand;
                $filetowrite = $ifol.$name.'.'.$ext;
                $filetowrite = str_replace('//', '/', $filetowrite);
                if (file_exists($filetowrite)) {
                    $rand = rand(0, 999);
                    $name = $b_user_id.'-'.$rand;
                    $filetowrite = $ifol.$name.'.'.$ext;
                    $filetowrite = str_replace('//', '/', $filetowrite);
                    if (file_exists($filetowrite)) {
                        $rand = rand(1000, 99999);
                        $name = $b_user_id.'-'.$rand;
                        $filetowrite = $ifol.$name.'.'.$ext;
                        $filetowrite = str_replace('//', '/', $filetowrite);
                    }
                }
                move_uploaded_file($temp['tmp_name'], $filetowrite);
                if (file_exists($filetowrite)) {
                    $this->lib("wideimage/WideImage", "inc");
                    WideImage::load($filetowrite)->reSize(300)->saveToFile($filetowrite);
                    WideImage::load($filetowrite)->crop('center', 'center', 300, 300)->saveToFile($filetowrite);
                    return $this->media_user."/".$year."/".$month."/".$name.'.'.$ext;
                } else {
                    return 0;
                }
            } else {
                // Notify editor that the upload failed
                //header("HTTP/1.0 500 Server Error");
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function __daftarAfter($nama,$email)
    {
        $str = 'Halo '.$nama.',

Terima kasih telah mendaftar dalam program BDE 2.0 yang diselenggarakan oleh Kemenparekraf dan Naikkelas.id

Anda terdaftar dengan email: '.$email.'

Data Anda telah kami terima dan akan masuk proses kurasi, apabila Anda lolos pada tahap selanjutnya, Anda akan kami hubungi melalui Telp / WhatsApp untuk proses konfirmasi dan informasi agenda inkubasi.

Bagi yang tidak lolos, bukan berarti bisnis Anda tidak bagus. Hanya saja kuota per batch saat ini dibatasi maximal 100 peserta, namun data Anda tetap masuk dalam database pelaku ekraf.

Demikian, terima kasih. :)

Salam Kreatif!';
        return $str;
    }

    private function __lupaAfter($nama, $link)
    {
        $str = 'Halo '.$nama.',

Anda atau Seseorang telah meminta untuk reset password, silahkan klik link berikut '.$link.' untuk reset password, apabila Anda tidak merasa reset password, mohon abaikan pesan ini. :)

Salam Kreatif!';
        return $str;
    }

    public function __userEmailVerification($email,$nama,$replacer){
      $this->seme_email->flush();
      $this->seme_email->replyto($this->config->semevar->app_name, $this->config->semevar->app_name);
      $this->seme_email->from($this->config->semevar->email_from, $this->config->semevar->app_name);
      $this->seme_email->subject('Kode untuk Verifikasi Email Pendaftaran Calon Karyawan '.$this->config->semevar->app_name);
      $this->seme_email->to($email, $nama);
      $this->seme_email->template('user_email_verification');
      $this->seme_email->replacer($replacer);
      $this->seme_email->send();
      if ($this->is_log) {
          $this->seme_log->write('API_Front/user::daftar -- send email to: '.$email);
      }
    }

    public function index()
    {
        //initial
        $token = '';
        $user_id = 0;
        $register_success = 0;
        $user = new stdClass();
        $dt = $this->__init();

        //default response
        $data = array();
        $data['apisess'] = '';
        $data['redirect_url'] = base_url("register");
        $data['user'] = new stdClass();

        //check api_key
        $apikey = $this->input->get('apikey');
        $c = $this->apikey_check($apikey);
        if (empty($c)) {
            $this->status = 400;
            $this->message = 'Missing or invalid API key';
            $this->__json_out($data);
            die();
        }
        foreach($_POST as $k=>$spost){
          $_POST[$k] = strip_tags($spost);
        }

        //flags
        $reg_from = 'online';
        $is_telp_valid = 0;
        $is_email_valid = 0;
        $is_password_valid = 0;
        $is_google_id = 0;
        $is_apple_id = 0;
        $is_fb_id = 0;
        $is_telp = 0;

        //populate input
        $noktp = trim(strip_tags($this->input->post("noktp",'')));
        if(strlen($noktp) != 16){
          $this->status = 1731;
          $this->message = 'Nomor KTP tidak valid '.strlen($noktp);
          $this->__json_out($data);
          die();
        }

        $telp = $this->input->post("telp");
        if(strlen($telp) <=5 || strlen($telp) >= 14){
          $this->status = 1731;
          $this->message = 'Nomor Telp tidak valid';
          $this->__json_out($data);
          die();
        }

        $email = trim(strip_tags($this->input->post("email")));
        $cnama = trim(strip_tags($this->input->post("cnama")));
        $fb_id = $this->input->post("fb_id");
        $google_id = $this->input->post("google_id");
        $apple_id = $this->input->post("apple_id");
        $fnama = strip_tags($this->input->post("fnama"));
        $password = $this->input->post("password");
        $password_confirm = $this->input->post("password_confirm");
        $alamat = trim(strip_tags($this->input->post("alamat",'')));
        $domisili = trim(strip_tags($this->input->post("domisili",'')));
        $desakel = $this->input->post("desakel",'');
        $kecamatan = $this->input->post("kecamatan",'');
        $kabkota = $this->input->post("kabkota",'');
        $provinsi = $this->input->post("provinsi",'');
        $negara = $this->input->post("negara",'');
        $link_fb = $this->input->post("link_fb",'');
        $link_ig = $this->input->post("link_ig",'');
        $fcm_token = $this->input->post("fcm_token");
        $kelamin = $this->input->post("kelamin");
        $bdate = $this->input->post("bdate",'');
        $kerja_exp_y = (int) $this->input->post("kerja_exp_y",'0');
        $pendidikan_terakhir = $this->input->post("pendidikan_terakhir",'');
        $tinggi_badan = $this->input->post("tinggi_badan",'');
        $berat_badan = $this->input->post("berat_badan",'');
        $tlahir = $this->input->post("tlahir",'');
        $jk = $this->input->post("jk",'');
        $agama = $this->input->post("agama",'');

        //fillup
        $alamats = array_reverse(explode(', ',$alamat));
        if(isset($alamats[0])) $negara = $alamats[0];
        if(isset($alamats[1])) $provinsi = $alamats[1];
        if(isset($alamats[2])) $kabkota = $alamats[2];
        if(isset($alamats[3])) $kecamatan = $alamats[3];
        if(isset($alamats[4])) $desakel = $alamats[4];
        unset($alamats);
        $alamat = '';

        //fillup
        $domisilis = array_reverse(explode(', ',$domisili));
        if(isset($domisilis[0])) $domisili_negara = $domisilis[0];
        if(isset($domisilis[1])) $domisili_provinsi = $domisilis[1];
        if(isset($domisilis[2])) $domisili_kabkota = $domisilis[2];
        if(isset($domisilis[3])) $domisili_kecamatan = $domisilis[3];
        if(isset($domisilis[4])) $domisili_desakel = $domisilis[4];
        unset($domisilis);
        $alamat = '';

        //validation
        if (strlen($fnama)==0) {
            $fnama = "";
        }
        if (strlen($email)>4) {
            $is_email_valid = 1;
        } else {
            $email = '';
        }
        if ($is_email_valid) {
            $cem = $this->bu->checkEmail($email);
            if (isset($cem->id)) {
                $this->status = 1700;
                $this->message = 'Email '.$email.' telah digunakan';
                $this->__json_out($data);
                die();
            }
        }else{
          $this->status = 1707;
          $this->message = 'Email tidak valid';
          $this->__json_out($data);
          die();
        }

        $cem = $this->bu->checkNoKtp($noktp);
        if (isset($cem->id)) {
            $this->status = 1708;
            $this->message = 'Nomor KTP sudah digunakan';
            $this->__json_out($data);
            die();
        }

        $cem = $this->bu->checkTelp($telp);
        if (isset($cem->id)) {
            $this->status = 1709;
            $this->message = 'Nomor HP ini sudah digunakan';
            $this->__json_out($data);
            die();
        }

        if (strlen($telp)>4) {
            $is_telp_valid = 1;
        } else {
            $telp = '';
        }
        if (strlen($fcm_token)<=100) {
            $fcm_token = "";
        }

        if (strlen($fb_id)>12) {
            $is_fb_id = 1;
        } else {
            $fb_id = '';
        }
        if (strlen($google_id)>9) {
            $is_google_id = 1;
        } else {
            $google_id = '';
        }
        if (strlen($apple_id)>5) {
            $is_apple_id = 1;
        } else {
            $apple_id = '';
        }

        if(strlen($bdate)!=10){
          $this->status = 994;
          $this->message = 'Tanggal lahir wajib diisi';
          $this->__json_out($data);
          die();
        }
        $dn = new DateTime('now');
        $bd = new DateTime($bdate);

        if($kerja_exp_y<=0){
          $kerja_exp_y=0;
        }

        //next logic
        if (!empty($is_fb_id) && empty($is_google_id) && empty($is_google_id)) {
            $reg_from = 'facebook';
        } elseif (empty($is_fb_id) && !empty($is_google_id) && empty($is_google_id)) {
            $reg_from = 'google';
        } elseif (empty($is_fb_id) && empty($is_google_id) && !empty($is_apple_id)) {
            $reg_from = 'apple';
        } else {
            $reg_from = 'online';
        }
        if (strlen($password)>3) {
            $is_password_valid = 1;
        }

        //populate insert
        $di = array();
        $di['utype'] = 'personal';
        $di['email'] = 'null';
        $di['fnama'] = $fnama;
        $di['cnama'] = $cnama;
        $di['bdate'] = $bdate;
        $di['alamat'] = strip_tags($alamat);
        $di['kecamatan'] = $kecamatan;
        $di['kabkota'] = $kabkota;
        $di['provinsi'] = $provinsi;
        $di['social_fb'] = $link_fb;
        $di['social_ig'] = $link_ig;
        $di['lnama'] = "";
        $di['telp'] = 'null';
        $di['fb_id'] = 'null';
        $di['apple_id'] = 'null';
        $di['google_id'] = 'null';
        $di['fcm_token'] = $fcm_token;
        $di['cdate'] = 'NOW()';
        $di['foto'] = 'media/user/default.png';
        $di['api_reg_token'] = "";
        $di['api_web_token'] = "";
        $di['api_mobile_token'] = "";
        $di['password'] = $this->__passGen($password);
        $di['jk'] = $kelamin;
        $di['noktp'] = $noktp;
        $di['kerja_exp_y'] = $kerja_exp_y;
        $di['pendidikan_terakhir'] = $pendidikan_terakhir;
        $di['desakel'] = $desakel;
        $di['kecamatan'] = $kecamatan;
        $di['kabkota'] = $kabkota;
        $di['provinsi'] = $provinsi;
        $di['negara'] = $negara;
        $di['domisili_desakel'] = $domisili_desakel;
        $di['domisili_kecamatan'] = $domisili_kecamatan;
        $di['domisili_kabkota'] = $domisili_kabkota;
        $di['domisili_provinsi'] = $domisili_provinsi;
        $di['domisili_negara'] = $domisili_negara;
        $di['tinggi_badan'] = $tinggi_badan;
        $di['berat_badan'] = $berat_badan;
        $di['tlahir'] = $tlahir;
        $di['agama'] = $agama;
        $di['jk'] = $jk;
        $di['apply_statno'] = '0';
        $di['umur'] = $bd->diff($dn)->y;

        //registration flow
        if ($reg_from == 'google') {
            //only put correct value
            if (strlen($google_id)>4) {
                $di['google_id'] = $google_id;
            }
            if (strlen($email)>4) {
                $di['email'] = $email;
            }
            if (strlen($telp)>4) {
                $di['telp'] = $telp;
            }

            //check if already registered
            $user = $this->bu->auth_sosmed($fb_id, $google_id, $apple_id, $email, $telp);
            if (isset($user->id)) {
                $this->status = 401;
                $this->message = 'User already registered using Google ID, please login';
                $this->__json_out($data);
                die();
            }

            //lock table
            $this->bu->trans_start();
            $user_id = $this->bu->getLastId();
            $kode = $this->bu->genKode();

            //insert to db
            $di['id'] = $user_id;
            $di['kode'] = $kode;
            $res = $this->bu->register($di);
            if ($res) {
                //commit table
                $this->bu->trans_commit();
                $register_success = 1;
                if ($this->is_log) {
                    $this->seme_log->write("API_Front/user::daftar -- using Google ID: $google_id, SUCCEED");
                }
            } else {
                //rollback table
                $this->bu->trans_commit();
                if ($this->is_log) {
                    $this->seme_log->write("API_Front/user::daftar -- using Google ID: $google_id, FAILED");
                }
            }
            //release table
            $this->bu->trans_end();
        } elseif ($reg_from == 'apple') {
            //only put correct value
            if (strlen($apple_id)>4) {
                $di['apple_id'] = $apple_id;
            }
            if (strlen($email)>4) {
                $di['email'] = $email;
            }
            if (strlen($telp)>4) {
                $di['telp'] = $telp;
            }

            //check if already registered
            $user = $this->bu->auth_sosmed($fb_id, $google_id, $apple_id, $email, $telp);
            if (isset($user->id)) {
                $this->status = 401;
                $this->message = 'User already registered using Apple ID, please login';
                $this->__json_out($data);
                die();
            }

            //lock table
            $this->bu->trans_start();
            $user_id = $this->bu->getLastId();
            $kode = $this->bu->genKode();

            //insert to db
            $di['id'] = $user_id;
            $di['kode'] = $kode;
            $res = $this->bu->register($di);
            if ($res) {
                //commit table
                $this->bu->trans_commit();
                $register_success = 1;
                if ($this->is_log) {
                    $this->seme_log->write("API_Front/user::daftar -- using apple ID: $apple_id, SUCCEED");
                }
            } else {
                //rollback table
                $this->bu->trans_commit();
                if ($this->is_log) {
                    $this->seme_log->write("API_Front/user::daftar -- using apple ID: $apple_id, FAILED");
                }
            }
            //release table
            $this->bu->trans_end();
        } elseif ($reg_from=='facebook') {
            //only put correct value
            if (strlen($fb_id)>4) {
                $di['fb_id'] = $fb_id;
            }
            if (strlen($email)>4) {
                $di['email'] = $email;
            }
            if (strlen($telp)>4) {
                $di['telp'] = $telp;
            }

            //check if already registered
            $user = $this->bu->auth_sosmed($fb_id, $google_id, $apple_id, $email, $telp);
            if (isset($user->id)) {
                $this->status = 401;
                $this->message = 'User already registered, please login';
                $this->__json_out($data);
                die();
            }

            //lock table
            $this->bu->trans_start();
            $user_id = $this->bu->getLastId();
            $kode = $this->bu->genKode();

            //insert to db
            $di['id'] = $user_id;
            $di['kode'] = $kode;
            $res = $this->bu->register($di);
            if ($res) {
                //commit table
                $this->bu->trans_commit();
                $register_success = 1;
                if ($this->is_log) {
                    $this->seme_log->write("API_Front/user::daftar -- using FB ID: $fb_id, FAILED");
                }
            } else {
                //rollback table
                $this->bu->trans_rollback();
                if ($this->is_log) {
                    $this->seme_log->write("API_Front/user::daftar -- using FB ID: $fb_id, FAILED");
                }
            }

            //release table
            $this->bu->trans_end();
        } elseif ($reg_from=='online') {
            if (strlen($email)<=4 && strlen($telp)<=4) {
                $this->status = 105;
                $this->message = 'Email or Phone number are required';
                $this->__json_out($data);
                die();
            }
            $use_email=0;
            if (strlen($email)>4) {
                $di['email'] = $email;
                $use_email = 1;
            }
            $use_phone=0;
            if (strlen($telp)>4) {
                $di['telp'] = $telp;
                $use_phone = 1;
            }
            if (!empty($use_email) && !empty($use_phone)) {
                $res = $this->bu->checkEmailTelp($email, $telp);
                if (isset($res->id)) {
                    $this->status = 1701;
                    $this->message = 'Email and phone number already used';
                    $this->__json_out($data);
                    die();
                }
            } elseif (!empty($use_email) && empty($use_phone)) {
                $res = $this->bu->checkEmail($email);
                if (isset($res->id)) {
                    $this->status = 1702;
                    $this->message = 'Email already used';
                    $this->__json_out($data);
                    die();
                }
            } elseif (empty($use_email) && !empty($use_phone)) {
                $res = $this->bu->checkTelp($telp);
                if (isset($res->id)) {
                    $this->status = 1703;
                    $this->message = 'Phone number already registered';
                    $this->__json_out($data);
                    die();
                }
            }

            //password
            if (!$is_password_valid) {
                $this->status = 1704;
                $this->message = 'Password not match or password length too short';
                $this->__json_out($data);
                die();
            }

            //lock table
            $this->bu->trans_start();
            $user_id = $this->bu->getLastId();
            $kode = $this->bu->genKode();

            //insert to db
            $di['id'] = $user_id;
            $di['kode'] = $kode;

            //insert to db
            $res = $this->bu->register($di);
            if ($res) {
                //commit table
                $this->bu->trans_commit();
                $register_success = 1;

                $foto = $this->__uploadUserImage($user_id);
                if(strlen($foto)){
                  $this->bu->update($user_id, array('foto'=>str_replace('//','/',$foto)));
                }
            } else {
                //rollback table
                $this->bu->trans_rollback();
            }
            //release table
            $this->bu->trans_end();

            if ($this->is_log) {
                $this->seme_log->write("API_Front/user::daftar -- using normal flow");
            }
        } else {
            $this->status = 1705;
            $this->message = 'Registration method undefined. Please specify Appled ID or Google ID or Facebook ID or Email Password combination.';
            if ($this->is_log) {
                $this->seme_log->write("API_Front/user::daftar -- status: ".$this->status." - ".$this->message);
            }
        }

        //after success
        if ($register_success && !empty($user_id)) {
            $this->status = 200;
            $this->message = 'registration successful, please check your inbox or spam before login';
            $token = $this->__genTokenMobile($user_id);
            $user = $this->bu->getById($user_id);
            $du = array();
            $du['api_mobile_token'] = $token;
            $du['api_mobile_date'] = date("Y-m-d");

            //cast to $user object
            $user->api_mobile_token = $token;
            $user->api_mobile_date = $du['api_mobile_date'];

            //doing send email and generates
            if ($this->email_send && strlen($email)>4) {
                $api_reg_token = $this->__activateGenerateLink($user_id, $user->api_reg_token);

                $replacer = $this->_emailReplacer();
                $replacer['site_logo'] = $this->cdn_url($this->config->semevar->site_logo);
                $replacer['app_name'] = $this->config->semevar->app_name;
                $replacer['company_name'] = $this->config->semevar->app_name;
                $replacer['site_name'] = $this->config->semevar->app_name;
                $replacer['email_reply'] = $this->config->semevar->email_reply;
                $replacer['fnama'] = $user->fnama;
                $replacer['activation_code'] = $api_reg_token;
                $du['api_reg_token'] = $api_reg_token;
                $du['api_reg_date'] = date("Y-m-d");

                $this->__userEmailVerification($user->email,$user->fnama,$replacer);

                //cast to $user object
                $user->api_reg_token = $api_reg_token;
                $user->api_reg_date = $du['api_reg_date'];
            }
            if ($this->wa_send && strlen($telp)>5) {
                $war = $this->wagate->send($telp, $this->__daftarAfter($user->fnama,$user->email));
                if ($this->is_log) {
                    $this->seme_log->write('API_Front/user::daftar -- wagateway: '.$war);
                }
            }
            if (count($du)) {
                $this->bu->update($user_id, $du);
            }

            $sess = $dt['sess'];
            if (!is_object($sess)) {
                $sess = new stdClass();
            }
            if (!isset($sess->user)) {
                $sess->user = new stdClass();
            }
            //$this->seme_log->write('API_Front/User::daftar -- user: '.json_encode($user));
            $sess->user = $user;
            $sess->user->modules = $this->buum->getUserModules($user->id);
            $sess->user->menus = new stdClass();
            $sess->user->menus->left = array();

            //get modules
            $modules = $this->aum->getAllParent();
            foreach ($modules as &$module) {
                $childs = $this->aum->getChild();
                $mos = array();
                if (count($childs)>0) {
                    foreach ($sess->user->modules as $m) {
                        foreach ($childs as $cs) {
                            //$this->debug($cs);
                            //die();
                            if (empty($m->module) && strtolower($m->rule)=="allowed_except") {
                                $mos[] = $cs;
                            } elseif (($cs->identifier == $m->module) && (strtolower($m->rule)=="allowed")) {
                                $mos[] = $cs;
                            }
                        }
                    }
                }
                $module->childs = $mos;
            }
            unset($module);

            //set module to session
            $allowed_all = 0;
            foreach ($modules as $mo) {
                foreach ($sess->user->modules as $m) {
                    if (empty($m->module) && strtolower($m->rule)=="allowed_except") {
                        $allowed_all = 1;
                        break;
                    } elseif (($m->module==$mo->identifier) && (strtolower($m->rule)=="allowed")) {
                        $sess->user->menus->left[$mo->identifier] = $mo;
                    }
                }
                unset($m);
                if ($allowed_all) {
                    $sess->user->menus->left[$mo->identifier] = $mo;
                }
            }
            unset($mo);

            $this->setKey($sess);
        } else {
            $this->status = 1706;
            $this->message = 'Failed save user to database, please try again';
            if ($this->is_log) {
                $this->seme_log->write("API_Front/user::daftar -- status: ".$this->status." - ".$this->message);
            }
        }

        //only manipulating
        if ($this->status == 200 && isset($user->id)) {
            $image = $this->__uploadUserImage($user->id);
            if (strlen($image)>4) {
                $dux = array();
                $dux['image'] = str_replace("//", "/", $image);
                $this->bu->update($user->id, $dux);
            }

            //add base url to image
            if (isset($user->image)) {
                $user->image = $this->cdn_url($image);
            }

            //remove unecessary properties
            unset($user->api_mobile_token);
            unset($user->api_web_token);
            unset($user->api_reg_token);
            unset($user->password);
            $user->apisess = $token;

            //put to response
            $data['apisess'] = $token;
            $data['user'] = $user;
            $data['sess'] = $dt['sess'];
            $data['redirect_url'] = base_url("register/success");
            if(isset($dt['sess']->jobs->id)){
              if($dt['sess']->jobs->id > 0){
                $data['redirect_url'] = base_url("jobs/detail/".$dt['sess']->jobs->id);
              }
            }
            if ($this->is_log) {
                $this->seme_log->write("API_Front/user::daftar -- Image Uploaded");
            }
        }
        if ($this->is_log) {
            $this->seme_log->write("API_Front/user::daftar -- status: ".$this->status." - ".$this->message);
        }
        //output as json
        $this->__json_out($data);
    }
    public function testSendEmail(){
      $replacer = $this->_emailReplacer();
      $replacer['site_logo'] = $this->cdn_url($this->config->semevar->site_logo);
      $replacer['app_name'] = $this->config->semevar->app_name;
      $replacer['company_name'] = $this->config->semevar->app_name;
      $replacer['site_name'] = $this->config->semevar->app_name;
      $replacer['email_reply'] = $this->config->semevar->email_reply;
      $replacer['fnama'] = 'Daeng Rosanda';
      $replacer['activation_code'] = 'ABCD00';
      $email = 'daengrosanda@gmail.com';
      $nama = 'Daeng Rosanda';
      $this->__userEmailVerification($email,$nama,$replacer);
    }
}
