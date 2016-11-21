<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_subject extends FE_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function classScheduleManagement(){
       
        if(user_type() == 3){
            $this->loadPage("subject/schedule_management", "subject/schedule_management_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function classSubjectManagement(){
       
        if(user_type() == 3){
            $this->loadPage("subject/subject_management", "subject/subject_management_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function classList(){
        if(user_type() == 6){
            $this->loadPage("subject/faculty_class_management", "subject/faculty_class_management_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function gradingsystem(){
        if(user_type() == 6){
            $this->loadPage("subject/grading_system", "subject/grading_system_script", false);
        }else{
            header("Location: ".base_url());
        }
    }

    public function classAttendance(){
        if(user_type() == 6){
            $this->loadPage("subject/class_attendance", "subject/class_attendance_script", false);
        }else{
            header("Location: ".base_url());
        }
    }

    public function classDeportment(){
        if(user_type() == 6){
            $this->loadPage("subject/class_deportment", "subject/class_deportment_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function club_member(){
        if(user_type() == 3){
            $this->loadPage("subject/club_member", "subject/club_member_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    
}
