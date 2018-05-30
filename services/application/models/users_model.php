<?php

class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('America/New_York');
        $this->load->helper('url');
        $this->load->library('table', 'session');
        $this->load->database();
    }
    function getuserlist($data) {
        $row=array();
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($data);        
        $query = $this->db->get();       
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return $row;
        }
    }
    function user_lists() {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', 1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();            
        } else {
            return array();
        }
    }


    public function save_users($set_data) {
        $this->db->insert('users', $set_data);
        return ($this->db->affected_rows() > 0);
    }

    public function update_users($set_data, $id) {
        $this->db->where('id', $id);
        $this->db->update("users", $set_data);
        return 1;
    }

    public function check_exist_username($username, $id = NULL) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        if ($id != "") {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
            return array();
        } else {
            return array();
        }
    }

    public function check_exist_vcnumber($vc_number, $pk_cust_id = NULL) {
        $this->db->select('*');
        $this->db->from('cust_mst');
        $this->db->where('vc_number', $vc_number);
        if ($pk_cust_id != "") {
            $this->db->where('md5(pk_cust_id) !=', $pk_cust_id);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
            return array();
        } else {
            return array();
        }
    }

    public function delete_user($pk_cust_id) {
        $this->db->where('md5(pk_cust_id)', $pk_cust_id);
        $this->db->delete("cust_mst");
        return ($this->db->affected_rows() > 0);
    }

    function get_user_mst() {
        $this->db->select('*');
        $this->db->from('user_mst');
        $this->db->where('pk_uid', $this->session->userdata('pk_uid'));
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
            return array();
        } else {
            return array();
        }
    }

    public function update_users_mst($set_data, $pk_uid) {
        $this->db->where('pk_uid', $pk_uid);
        $this->db->update("user_mst", $set_data);
        if ($this->db->affected_rows() > 0) {            
            $this->session->unset_userdata('emailid');
            $this->session->set_userdata('emailid',$set_data['emailid']);
            $this->session->unset_userdata('fname');
            $this->session->set_userdata('fname',$set_data['fname']);
            $this->session->unset_userdata('lname');
            $this->session->set_userdata('lname',$set_data['lname']);
            $this->session->unset_userdata('mobileno');
            $this->session->set_userdata('mobileno',$set_data['mobileno']);
            
        }
        return $this->db->affected_rows() > 0;
    }

}
