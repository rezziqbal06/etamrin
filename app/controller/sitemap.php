<?php
class Sitemap extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setTheme('front');
    }
    private function __parseToXML($htmlStr)
    {
        $xmlStr=str_replace('<', '&lt;', $htmlStr);
        $xmlStr=str_replace('>', '&gt;', $xmlStr);
        $xmlStr=str_replace('"', '&quot;', $xmlStr);
        $xmlStr=str_replace("'", '&#39;', $xmlStr);
        $xmlStr=str_replace("&", '&amp;', $xmlStr);
        return $xmlStr;
    }
    private function __setXml($url='', $lastmod='2018-06-30', $cf='monthly', $priority='0.4')
    {
        $stc = new stdClass();
        $stc->loc = base_url($url);
        $stc->lastmod = $lastmod;
        $stc->changefreq = $cf;
        $stc->priority = $priority;
        return $stc;
    }
        
    public function index()
    {
        $ds = array();
        $tgl_skrg = '2020-06-28';
        $ds[] = $this->__setXml('', $tgl_skrg, 'daily', '1.0');
        $ds[] = $this->__setXml('jadwal/', $tgl_skrg, 'daily', '0.7');
        $ds[] = $this->__setXml('daftar/', $tgl_skrg, 'daily', '0.7');
        $ds[] = $this->__setXml('login/', $tgl_skrg, 'weekly', '0.3');
        $ds[] = $this->__setXml('lupa/', $tgl_skrg, 'weekly', '0.2');
        
        $data['ds'] = $ds;
        $this->loadLayout('sitemap', $data);
        $this->render(100);
    }
}
