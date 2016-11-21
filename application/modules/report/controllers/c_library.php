<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_library extends FE_Controller {

    public function __construct(){
        parent::__construct();
    }
    public function portalReport(){
        if(user_type() == 7){
            $this->loadPage("report/library/portal_report", "report/library/portal_report_script", false);
        }else{

            header("Location: ".base_url());
        }
    }
    public function circulationReport(){
        if(user_type() == 7){
            $this->loadPage("report/library/circulation_report", "report/library/circulation_report_script", false);
        }else{

            header("Location: ".base_url());
        }
    }

}
