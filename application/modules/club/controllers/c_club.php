<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_club extends FE_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function clubManagement(){
       
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("club/club_management", "club/club_management_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
}
