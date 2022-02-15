<?php

/**
 * SEME Framework Upload File
 *
 * Penggunaan:
 * ====================================
 * $this->load("seme_upload","lib");
 *
 */

class Seme_Upload
{
    var $directory = '';
    var $sc;
    var $is_log = 1;
    public function __construct()
    {
        $this->directory = SEMEROOT;
    }

    /**
     * Load the library from controller and instantiate object with same name in controller
     * relatives to kero/lib
     * @param  string $a          location and name of view without .php suffix
     * @param  string $b          alias of instantiate object, default empty
     * @param  string $c          type of embedding. lib: autoinstantiate, otherwise include only
     * @return object             return this class
     */
    protected function lib($a, $b = "", $c = "lib")
    {
        if ($c == 'lib') {
            $lpath = strtr($this->directory . 'kero/lib/' . $a . '.php', "\\", "/");
            if (file_exists(strtolower($lpath))) {
                require_once(strtolower($lpath));
                $cname = basename($lpath, '.php');
                $method = new $cname();
                if (empty($b)) {
                    $b = $cname;
                }
                $b = strtolower($b);
                $this->{$b} = $method;
            } elseif (file_exists($lpath)) {
                require_once($lpath);
                $cname = basename($lpath, '.php');
                $method = new $cname();
                if (empty($b)) {
                    $b = $cname;
                }
                $b = strtolower($b);
                $this->{$b} = $method;
            } else {
                die("unable to load library on " . $lpath);
            }
        } else {
            if (file_exists(strtolower($this->directory . 'kero/lib/' . $a . '.php'))) {
                require_once(strtolower($this->directory . 'kero/lib/' . $a . '.php'));
            } elseif (file_exists($this->directory . 'kero/lib/' . $a . '.php')) {
                require_once($this->directory . 'kero/lib/' . $a . '.php');
            } else {
                die("unable to load library on " . strtolower($this->directory . 'kero/lib/' . $a . ".php x"));
            }
        }
        return $this;
    }


    /**
     * Upload product
     * @param  string   $keyname         key
     * @param  integer  $unique_id       id for table
     * @param  integer  $ke              counter
     * @param  string   $jenis           jenis ekstensi: document, image, audio, video
     * @param  int      $maks_ukuran     maksimal ukuran file
     * @param  string   $media_directory nama directory untuk menyimpan file
     * @param  int      $use_thumb       apakah ingin mengupload thumbnail
     * @param  int      $nation_code     nation code
     * @return object                    upload result object
     */
    public function upload_file($keyname, $unique_id = "0", $ke = "", $jenis = "document", $media_directory = "", $maks_ukuran = 2000000, $use_thumb = 0, $nation_code = 62)
    {
        $sc = new stdClass();
        $sc->status = 500;
        $sc->message = 'Error';
        $sc->file = '';
        $sc->thumb = '';
        $sc->log = '';
        $unique_id = (int) $unique_id;
        if (isset($_FILES[$keyname]['name'])) {
            if ($_FILES[$keyname]['size'] > $maks_ukuran) {
                $sc->status = 301;
                $sc->message = 'file Size too big, please try again';
                return $sc;
            }
            $filenames = pathinfo($_FILES[$keyname]['name']);
            if (isset($filenames['extension'])) {
                $fileext = strtolower($filenames['extension']);
            } else {
                $fileext = '';
            }

            if (strlen($media_directory) < 0) {
                $sc->status = 303;
                $sc->message = 'file media penyimpanan belum ditentukan';
                return $sc;
            }

            //validasi ekstensi dan penentuan nama file
            switch (strtolower($jenis)) {
                case 'document':
                    if (!in_array($fileext, array("pdf", "doc", "docx", "xls", "xlsx", "ppt", "pptx"))) {
                        $sc->status = 303;
                        $sc->message = 'Invalid file extension, please try other ' . $jenis . ' file.';
                        return $sc;
                    }
                    $filename = "$nation_code-$unique_id-$ke";
                    break;
                case 'image':
                    if (!in_array($fileext, array("jpg", "png", "jpeg", "gif"))) {
                        $sc->status = 303;
                        $sc->message = 'Invalid file extension, please try other ' . $jenis . ' file.';
                        return $sc;
                    }
                    $filename = "$nation_code-$unique_id-$ke";
                    if ($use_thumb) $filethumb = $filename . '-thumb';
                    break;
                case 'audio':
                    if (!in_array($fileext, array("mp3", "wav", "aac"))) {
                        $sc->status = 303;
                        $sc->message = 'Invalid file extension, please try other ' . $jenis . ' file.';
                        return $sc;
                    }
                    $filename = "$nation_code-$unique_id-$ke";
                    break;
                case 'video':
                    if (!in_array($fileext, array("mp4", "mov", "wmv", "mkv"))) {
                        $sc->status = 303;
                        $sc->message = 'Invalid file extension, please try other ' . $jenis . ' file.';
                        return $sc;
                    }
                    $filename = "$nation_code-$unique_id-$ke";
                    if ($use_thumb) $filethumb = $filename . '-thumb';
                    break;
                default:
                    $sc->status = 998;
                    $sc->message = 'Jenis file upload tidak ditemukan';
                    return $sc;
                    break;
            }

            //penempatan media penyimpanan
            $targetdir = 'media/' . $media_directory;
            $targetdircheck = realpath($this->directory . $targetdir);
            if (empty($targetdircheck)) {
                if (PHP_OS == "WINNT") {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir);
                    }
                } else {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir, 0775);
                    }
                }
            }

            $tahun = date("Y");
            $targetdir = $targetdir . DIRECTORY_SEPARATOR . $tahun;
            $targetdircheck = realpath($this->directory . $targetdir);
            if (empty($targetdircheck)) {
                if (PHP_OS == "WINNT") {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir);
                    }
                } else {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir, 0775);
                    }
                }
            }

            $bulan = date("m");
            $targetdir = $targetdir . DIRECTORY_SEPARATOR . $bulan;
            $targetdircheck = realpath($this->directory . $targetdir);
            if (empty($targetdircheck)) {
                if (PHP_OS == "WINNT") {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir);
                    }
                } else {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir, 0775);
                    }
                }
            }

            //validasi file ada atau tidak
            $filecheck = $this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename . '.' . $fileext;
            if (file_exists($filecheck)) {
                unlink($filecheck);
                $rand = rand(0, 999);
                $filename = "$nation_code-$unique_id-$ke-" . $rand;
                $filecheck = $this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename . '.' . $fileext;
                if (file_exists($filecheck)) {
                    unlink($filecheck);
                    $rand = rand(1000, 99999);
                    $filename = "$nation_code-$unique_id-$ke-" . $rand;
                }
            }

            //upload file
            if (($jenis == "image" || $jenis == "video") && $use_thumb) $filethumb = $filename . "-thumb." . $fileext;
            $filename = $filename . "." . $fileext;

            //magic uploading wkwk
            move_uploaded_file($_FILES[$keyname]['tmp_name'], $this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename);


            if (is_file($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename) && file_exists($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename)) {
                if ($jenis == "image") {
                    if (@mime_content_type($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename) == '/webp') {
                        $sc->status = 302;
                        $sc->message = 'WebP  format currently unsupported';
                        return $sc;
                    }
                }

                if (($jenis == "image" || $jenis == "video") && $use_thumb) {
                    if (file_exists($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filethumb) && is_file($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filethumb)) {
                        unlink($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filethumb);
                    }
                }

                if (file_exists($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename) && is_file($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename)) {
                    if (($jenis == "image" || $jenis == "video") && $use_thumb) {
                        $this->lib("wideimage/WideImage", 'wideimage', "inc");
                        WideImage::load($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename)->reSize(370)->saveToFile($this->directory . $targetdir . DIRECTORY_SEPARATOR . $filethumb);
                    }
                    $sc->status = 200;
                    $sc->message = 'Successful';
                    if (($jenis == "image" || $jenis == "video") && $use_thumb) $sc->thumb = str_replace("//", "/", $targetdir . '/' . $filethumb);
                    $sc->file = str_replace("//", "/", $targetdir . '/' . $filename);
                } else {
                    $sc->status = 997;
                    $sc->message = 'Failed';
                }
            } else {
                $sc->status = 999;
                $sc->message = 'Failed';
            }
        } else {
            $sc->status = 988;
            $sc->message = 'Keyname file does not exists';
        }
        if ($this->is_log) {
            $sc->log = 'API_Mobile/Produk::__uploadx -- INFO KeyName: ' . $keyname . ' uniqueID:' . $unique_id . ' ke:' . $ke . ' ' . ' jenis:' . $jenis . ' ' . ' maks ukuran:' . ' ' . $sc->status . ' ' . $sc->message;
        }
        return $sc;
    }

    /**
     * Upload product
     * @param  string   $dataurl         key
     * @param  integer  $unique_id       id for table
     * @param  integer  $ke              counter
     * @param  string   $jenis           jenis ekstensi: document, image, audio, video
     * @param  int      $maks_ukuran     maksimal ukuran file
     * @param  string   $media_directory nama directory untuk menyimpan file
     * @param  int      $use_thumb       apakah ingin mengupload thumbnail
     * @param  int      $nation_code     nation code
     * @return object                    upload result object
     */
    public function upload_file_from_dataurl($dataurl, $media_directory, $filename, $fileext = 'png')
    {
        $sc = new stdClass();
        $sc->status = 500;
        $sc->message = 'File Not found';
        $sc->file = '';
        $sc->thumb = '';
        $sc->log = '';
        if (isset($dataurl)) {
            //penempatan media penyimpanan
            $targetdir = 'media/' . $media_directory;
            $targetdircheck = realpath($this->directory . $targetdir);
            if (empty($targetdircheck)) {
                if (PHP_OS == "WINNT") {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir);
                    }
                } else {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir, 0775);
                    }
                }
            }
            $tahun = date("Y");
            $targetdir = $targetdir . DIRECTORY_SEPARATOR . $tahun;
            $targetdircheck = realpath($this->directory . $targetdir);
            if (empty($targetdircheck)) {
                if (PHP_OS == "WINNT") {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir);
                    }
                } else {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir, 0775);
                    }
                }
            }


            $bulan = date("m");
            $targetdir = $targetdir . DIRECTORY_SEPARATOR . $bulan;
            $targetdircheck = realpath($this->directory . $targetdir);
            if (empty($targetdircheck)) {
                if (PHP_OS == "WINNT") {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir);
                    }
                } else {
                    if (!is_dir($this->directory . $targetdir)) {
                        mkdir($this->directory . $targetdir, 0775);
                    }
                }
            }

            //validasi file ada atau tidak
            $filecheck = $this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename . '.' . $fileext;
            if (file_exists($filecheck)) {
                unlink($filecheck);
                $rand = rand(0, 999);
                $filename = $rand;
                $filecheck = $this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename . '.' . $fileext;
                if (file_exists($filecheck)) {
                    unlink($filecheck);
                    $rand = rand(1000, 99999);
                    $filename = $rand;
                }
            }

            $path = $this->directory . $targetdir . DIRECTORY_SEPARATOR . $filename . '.' . $fileext;
            $data = str_replace('data:image/png;base64,', '', $dataurl);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            $res = file_put_contents($path, $data);
            if ($res) {
                $sc->status = 200;
                $sc->message = 'Successful';
                $sc->file = str_replace("//", "/", $targetdir . '/' . $filename . '.' . $fileext);
                $sc->file = str_replace("\/", "/", $sc->file);
            } else {
                $sc->status = 997;
                $sc->message = 'Failed';
            }
        }

        return $sc;
    }
}
