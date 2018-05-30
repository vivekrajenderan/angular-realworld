<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Webservice_model extends CI_Model {

    function __construct() {
        parent::__construct();
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
    
    public function save_users($set_data) {
        $this->db->insert('users', $set_data);
        return ($this->db->affected_rows() > 0)?$this->db->insert_id():'';
    }

    public function check_exist_username($username, $id = NULL) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        if ($id != "") {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
            return array();
        } else {
            return array();
        }
    }

}
