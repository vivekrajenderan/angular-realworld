<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access-control-allow-origin, authorization, content-type,x-requested-with");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include APPPATH . '/controllers/common.php';

//error_reporting(E_PARSE);
class Tags extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('common_model', 'common');
        $this->load->library('form_validation');        
    }

    public function index() {
        $tag_lists = $this->common->tag_lists();       
        $result=array('tags'=>$tag_lists);
        echo json_encode($result);
    }   
    
}
