<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_finance extends FE_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function tuitionFee(){
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("finance/tuition_fee", "finance/tuition_fee_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function assessmentType(){
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("finance/assessment_type", "finance/assessment_type_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function assessmentItem(){
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("finance/assessment_item", "finance/assessment_item_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function generalLedger(){
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("finance/general_ledger", "finance/general_ledger_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function studentYearlyDeductible(){
        if(user_type() == 3 || user_type() == 5){
            $this->loadPage("finance/student_yearly_deductible", "finance/student_yearly_deductible_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    
}
