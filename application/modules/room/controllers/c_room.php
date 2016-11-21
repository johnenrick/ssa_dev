<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_room extends FE_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function roomManagement(){
       
        if(user_type() == 3){
            $this->loadPage("room/room_management", "room/room_management_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
}
