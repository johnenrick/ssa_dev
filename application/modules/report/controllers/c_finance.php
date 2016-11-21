<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_finance extends FE_Controller {
    
    public function __construct(){
        parent::__construct();
    }
    public function assessmentItemReport(){
        if(user_type() == 5 || user_type() == 3){
            $this->loadPage("report/finance/assessment_item_report", "report/finance/assessment_item_report_script", false);
        }else{
           
            header("Location: ".base_url());
        }
    }
    public function paymentHistory(){
        if(user_type() == 5 || user_type() == 3){
            $this->loadPage("report/finance/payment_history", "report/finance/payment_history_script", false);
        }else{
           
            header("Location: ".base_url());
        }
    }
    public function accountPaymentSummary(){
        if(user_type() == 5 || user_type() == 3){
            $this->loadPage("report/finance/account_payment_summary", "report/finance/account_payment_summary_script", false);
        }else{
           
            header("Location: ".base_url());
        }
    }
    
}
