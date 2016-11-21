<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_library extends FE_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function cataloging(){
        if(user_type() == 7){
            $this->loadPage("library/cataloging", "library/cataloging_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function circulation(){
        if(user_type() == 7){
            $this->loadPage("library/circulation", "library/circulation_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function materialAccessControl(){
        if(user_type() == 7){
            $this->loadPage("library/material_access_control", "library/material_access_control_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function borrowedMaterial(){
        if(user_type() == 7){
            $this->loadPage("library/borrowed_material", "library/borrowed_material_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function search(){
            $this->loadPage("library/search", "library/search_script", false);
    }

    public function portal(){
            $this->loadPage("library/portal", "library/portal_script", false);
    }

}
