<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_section extends FE_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function sectionManagement(){
       
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("section/section_management", "section/section_management_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function classSectionManagement(){
       
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("section/class_section_management", "section/class_section_management_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    
}
