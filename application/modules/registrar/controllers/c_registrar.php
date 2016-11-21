<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_registrar extends FE_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function studentAccountManagement(){
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("registrar/student_account_management", "registrar/student_account_management_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function accountAdjustmentFee(){
        if(user_type() == 3 || user_type() == 6 || user_type() == 5){
            $this->loadPage("registrar/account_adjustment_fee", "registrar/account_adjustment_fee_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    
}
