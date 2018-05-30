<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: access-control-allow-origin, authorization, content-type,x-requested-with");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include APPPATH . '/controllers/common.php';

//error_reporting(E_PARSE);
class User extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('users_model', 'users');
        $this->load->library('form_validation');        
    }

    public function index() {
        $user_list = $this->users->getuserlist();       
        $result=array('user'=>$user_list);
        echo json_encode($result);
    }

    public function getuser()
    {
        $reqHeaders = $this->input->request_headers();        
        $user_list = $this->users->getuserlist(array('token'=>$reqHeaders['Authorization']));       
        $result=array('user'=>$user_list);
        echo json_encode($result);
    }
   
    public function add() {   
                $post_request = file_get_contents('php://input');
                $_POST = json_decode($post_request, true);

        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|min_length[3]|max_length[20]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_exist_username_check');            
            $this->form_validation->set_rules('username', 'User Name', 'trim|required|min_length[3]|max_length[30]');
            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            } else {
                $data = array('firstname' => trim($this->input->post('firstname')),
                    'lastname' => trim($this->input->post('lastname')),
                    'email' => trim($this->input->post('email')),
                    'username' => trim($this->input->post('username')),
                    'password' => md5('test123')
                );

                if(isset($_POST['id']) && !empty($_POST['id']))
                {
                    $add_users = $this->users->update_users($data,$_POST['id']);
                }
                else
                {
                  $add_users = $this->users->save_users($data);  
                }
                
                if ($add_users == 1) {                   
                    echo json_encode(array('status' => 1,'msg'=>'User Saved Successfully'));
                } else {
                    echo json_encode(array('status' => 0, 'msg' => 'User Saved Not Successfully'));
                }
            }
        }
    }
    
    public function exist_username_check() {
            $check_exist = $this->users->check_exist_username(trim($this->input->post('username')), trim($this->input->post('id')));
            
            if (count($check_exist)) {
                $this->form_validation->set_message('exist_username_check', 'Already Exists Username');
                return FALSE;
            } else {
                return TRUE;
            }
        
    }

    
    public function delete($pk_cust_id = NULL) {
        if ($pk_cust_id != "") {
            $deleteUsers = $this->users->delete_user($pk_cust_id);
            if ($deleteUsers == "1") {
                $this->session->set_flashdata('SucMessage', 'User has been deleted successfully!!!');
            } else {
                $this->session->set_flashdata('ErrorMessages', 'User has not been deleted successfully!!!');
            }
            redirect(base_url() . 'admin/users/', 'refresh');
        } else {

            redirect(base_url() . 'admin/users/', 'refresh');
        }
    }
    
     public function change_users_active() {
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {

            $data = array('standing' => trim($this->input->post('standing'))
            );
            $id = trim($this->input->post('pk_cust_id'));
            $update_users = $this->users->update_users($data, $id);
            $standing=($this->input->post('standing')==1 ? 'Active' : 'Inactive');
            if ($update_users == 1) {                
                echo json_encode(array('status' => 1, 'msg' => "User $standing Successfully"));
            } else {
                echo json_encode(array('status' => 0, 'msg' => "User $standing Not Successfully"));
            }
        }
    }


}
