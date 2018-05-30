<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class Bigblue extends CI_Controller {

    public function __construct() {

        parent::__construct();        
        $this->load->library('bbb');
    }


    public function index()
    {
        $meetingID="demo12344345";
        $name="Angularjs basicsa";
        $duration=1200;
        $logoutURL=base_url()."api/bigblue/successpage";
        $moderatorPW='mp';
        $attendeePW='ap';
        $welcome='Welcome to angularjs class';
         
        $this->bbb->create_meeting($meetingID, $name, $duration, $logoutURL, $moderatorPW, $attendeePW, $welcome);
    }

    public function join_meeting()
    {
        $meetingID='demo12344345';
        $userID='';
        $fullName='Vinoth Kumar';
        $password='mp';
        $this->bbb->join_meeting($meetingID, $userID, $fullName, $password);
    }

    public function get_meeting_info()
    {
        $meetingID='demo12344345';       
        $password='mp';
        $this->bbb->is_meeting_running($meetingID);
    }

}
