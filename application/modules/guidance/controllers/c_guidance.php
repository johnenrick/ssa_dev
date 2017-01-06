<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_guidance extends FE_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function portalReport(){
        if(user_type() == 9){
            $this->loadPage("portal_report", "portal_report_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function studentAttendance(){
        if(user_type() == 9){
            $this->loadPage("student_attendance", "student_attendance_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    
}
