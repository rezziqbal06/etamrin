<?php
class Manifest extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setTheme('front');
    }
    public function index()
    {
        $data = new stdClass();
        $data->short_name = $this->config->semevar->app_name;
        $data->name = $this->config->semevar->site_description;
        $data->start_url = "/";
        $data->background_color = "#3367D6";
        $data->display = "standalone";
        $data->scope = "/";
        $data->theme_color = "#3367D6";

        $favicon = 'favicon.png';
        $favicon_file = SEMEROOT.'/'.$favicon;
        if (file_exists($favicon_file)) {
            if (filesize($favicon_file)>1024) {
                $data->icons = array();
                $icon = new stdClass();
                $icon->src = '/'.$favicon;
                $icon->type = mime_content_type($favicon_file);
                list($width, $height, $type, $attr) = getimagesize($favicon_file);
                $icon->sizes = $width.'x'.$height;
                $data->icons[] = $icon;
            }
        }
        header("Content-Type: application/json");
        echo json_encode($data);
    }
}
