<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

error_reporting(0);
require APPPATH . '/libraries/REST_Controller.php';

//error_reporting(E_PARSE);
class Users extends REST_Controller {

    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit;
        }
        parent::__construct();
        $this->load->model('api/webservice_model','apimodel');        
        $this->load->library('form_validation');        
    }

    public function login_post()
    {

        $post_request = file_get_contents('php://input');
        $request = json_decode($post_request, true);
        $_POST=$request;
        $users=isset($request['user'])?$request['user']:array();
        
        // Mandatory Fields validation
        $mandatoryKeys = array('email' => 'Email', 'password' => 'Password');
        $nonMandatoryValueKeys = array('');
        $check_request = mandatoryArray($users, $mandatoryKeys, $nonMandatoryValueKeys);
        if (!empty($check_request)) {
            // Return Error Result
            $this->response(array('errors' => array("msg" => $check_request["msg"])), $check_request["statusCode"]);
        } else {

            $this->form_validation->set_rules('user[email]', 'Email', 'trim|required|valid_email'); 
            $this->form_validation->set_rules('user[password]', 'Password', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) {
                $this->response(array('errors' => array('msg'=>validation_errors())), 422);
               
            } else {
                $get_user_list = $this->apimodel->getuserlist(array('email'=>$users['email'],'password'=>md5($users['password'])));
                if (count($get_user_list) > 0) {                    
                    $this->response(array('user' => $get_user_list), 200);
                } else {                    
                    $this->response(array('errors' => array('msg'=>"Invalid Credential")), 422);
                }
            }

        }
    }
   
    public function register_post() {   
        $post_request = file_get_contents('php://input');
        $request = json_decode($post_request, true); 
        $_POST=$request;
        $users=isset($request['user'])?$request['user']:array();       
        
        // Mandatory Fields validation
        $mandatoryKeys = array('firstname'=>'First Name','lastname'=>'Last Name','email' => 'Email', 'password' => 'Password');
        $nonMandatoryValueKeys = array('');
        $check_request = mandatoryArray($users, $mandatoryKeys, $nonMandatoryValueKeys);
        if (!empty($check_request)) {
            // Return Error Result
            $this->response(array('errors' => array("msg" => $check_request["msg"])), $check_request["statusCode"]);
        } else {
            $this->form_validation->set_rules('user[firstname]', 'First Name', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('user[lastname]', 'Last Name', 'trim|required|min_length[3]|max_length[20]');
            $this->form_validation->set_rules('user[email]', 'Email', 'trim|required|valid_email');            
            $this->form_validation->set_rules('user[username]', 'User Name', 'trim|required|min_length[3]|max_length[30]|callback_exist_username_check');
            $this->form_validation->set_rules('user[password]', 'Password', 'trim|required|min_length[3]|max_length[30]');
            if ($this->form_validation->run() == FALSE) {
                $this->response(array('errors' => array('msg'=>validation_errors())), 422);
            } else {
                $data = array('firstname' => trim($users['firstname']),
                    'lastname' => trim($users['lastname']),
                    'email' => trim($users['email']),
                    'username' => trim($users['username']),
                    'password' => trim(md5($users['password'])),
                    'token'=>time()
                );

                $userid = $this->apimodel->save_users($data); 
                if ($userid!="") {     
                    $get_user_list = $this->apimodel->getuserlist(array('id'=>$userid));
                    $this->response(array('user' => $get_user_list), 200);
                } else {
                    $this->response(array('errors' => array('msg'=>"Registration Not Successfully")), 422);
                }
            }
        }
    }

    public function exist_username_check() {

            $check_exist = $this->apimodel->check_exist_username(isset($_POST['user']['username'])?$_POST['user']['username']:"", isset($_POST['user']['id'])?$_POST['user']['id']:"");
            
            if (count($check_exist)) {
                $this->form_validation->set_message('exist_username_check', 'Already Exists Username');
                return FALSE;
            } else {
                return TRUE;
            }
        
    }

}
