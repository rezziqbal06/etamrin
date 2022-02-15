<?php
class Logout extends SENE_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data = array();
        $sess = $this->getKey();
        if (!is_object($sess)) {
            $sess = new stdClass();
        }
        if(!empty($sess->user->id) && !empty($sess->user->id) && isset($this->config->semevar->is_user_log) && !empty($this->config->semevar->is_user_log)){
          $this->load('b_user_log_model','ulog');
          $this->ulog->set(array('cdate' => date('Y-m-d H:i:s'), 'b_user_id' => $b_user_id, 'a_itemlog_id' => $a_itemlog_id));
        }

        $sess->user = new stdClass();
        if(isset($sess->jobs)) $sess->jobs = new stdClass();
        $this->setKey($sess);
        redir(base_url("login"), 0);
    }
}
