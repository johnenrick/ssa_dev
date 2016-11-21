<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_teller extends FE_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");
    }
    public function tellerTransaction(){
       
        if(user_type() == 5){
            $this->loadPage("teller/teller_transaction", "teller/teller_transaction_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function paymentListReport(){ 
        if(user_type() == 5){
            $this->loadPage("teller/payment_report", "teller/payment_report_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function accountStatement(){
       
        if(user_type() == 5){
            $this->loadPage("teller/account_statement", "teller/account_statement_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function noAccountPayee(){
       
        if(user_type() == 5){
            $this->loadPage("teller/no_account_payee", "teller/no_account_payee_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    public function fixTransaction(){
       
        if(user_type() == 8){
            $this->loadPage("teller/fix_transaction", "teller/fix_transaction_script", false);
        }else{
            header("Location: ".base_url());
        }
    }
    
}
