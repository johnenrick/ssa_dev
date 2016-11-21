<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_account_payment extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_account_payment");
        $this->load->model("m_change_log");
    }
    public function createAccountPayment(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),1) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('order_receipt_number', 'OR Number', 'required|is_unique[payment.order_receipt_number]');
            $this->form_validation->set_rules('payee_account_ID', 'Payee', 'required');
            $this->form_validation->set_rules('payer_account_ID', 'Payer', 'required');
            $this->form_validation->set_rules('mode', 'Mode', 'required');
            $this->form_validation->set_rules('total_amount', 'Total Amount', 'required');
            if($this->form_validation->run() && $this->input->post("payment_assessment_item")){
                
                $currentTime = time();
                $result = $this->m_account_payment->createAccountPayment(
                        $this->input->post("order_receipt_number"),
                        $this->input->post("payee_account_ID"),
                        $this->input->post("payer_account_ID"),
                        user_id(),
                        $this->input->post("total_amount"),
                        $this->input->post("mode"),
                        $currentTime,
                        $this->input->post("remarks"),
                        2,
                        $this->input->post("academic_year")
                        );
                if($result){
                    $this->load->model("m_payment_no_account");
                    if($this->input->post("payee_account_ID") == -1){
                        $this->m_payment_no_account->createPaymentNoAccount(
                                $result, 
                                $this->input->post("payee_last_name"), 
                                $this->input->post("payee_first_name"), 
                                $this->input->post("payee_middle_name"),
                                1
                                );
                    }
                    if($this->input->post("payer_account_ID") == -1){
                        $this->m_payment_no_account->createPaymentNoAccount(
                                $result,
                                $this->input->post("payer_last_name"), 
                                $this->input->post("payer_first_name"), 
                                $this->input->post("payer_middle_name"),
                                2
                                );
                    }
                    if($this->input->post("payment_assessment_item")){
                        $paymentAssessmentList = array();
                        foreach($this->input->post("payment_assessment_item") as $key => $value){
                            $paymentAssessmentList[] = array(
                                "payment_ID" => $result,
                                "assessment_item_ID" => $value["assessment_item_ID"],
                                "amount" => $value["amount"],
                                "remarks" => $value["remarks"]
                            );
                        }
                        $this->m_account_payment->batchCreatePaymentAssessmentItem($paymentAssessmentList);
                    }
                    if($result){

                        $response["data"] = $result;
                    }else{
                        $response["error"][] = array(
                            "status" => 3,
                            "message" => "Failed to create AccountPayment"
                        );
                    }
                }
            }else{
                if(!$this->input->post("payment_assessment_item")){
                    $response["error"][] = array(
                      "status" => 4,
                      "message" => "No Particulars"
                    );
                }else{
                    $response["error"][] = array(
                      "status" => 2,
                      "message" => validation_errors()
                    );
                }
            }
        }
        echo json_encode($response);
    }
    
    public function retrieveAccountPayment(){
        $response = $this->generateResponse();
        $result = $this->m_account_payment->retrieveAccountPayment(
                $this->input->post("retrieve_item"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"),
                $this->input->post("order_receipt_number"),
                $this->input->post("payee_account_ID"),
                $this->input->post("payer_account_ID"),
                $this->input->post("cashier_ID"),
                $this->input->post("total_amount"),
                $this->input->post("mode"),
                $this->input->post("datetime"),
                $this->input->post("remarks"),
                $this->input->post("status"),
                NULL,
                $this->input->post("sort"),
                $this->input->post("order_receipt_number_range")
                );
        if($this->input->post("limit")){
            $response["result_count"] = count($this->m_account_payment->retrieveAccountPayment( 
                    $this->input->post("retrieve_item"), // 1 - search, 0 - match
                    NULL,
                    $this->input->post("offset"), 
                    $this->input->post("ID"),
                    $this->input->post("order_receipt_number"),
                    $this->input->post("payee_account_ID"),
                    $this->input->post("payer_account_ID"),
                    $this->input->post("cashier_ID"),
                    $this->input->post("total_amount"),
                    $this->input->post("mode"),
                    $this->input->post("datetime"),
                    $this->input->post("remarks"),
                    $this->input->post("status"),
                    NULL,
                    $this->input->post("sort"),
                    $this->input->post("order_receipt_number_range")
                    ));
        }
        if($result){
            $response["data"] = $result;
            if($this->input->post("ID") || $this->input->post("order_receipt_number")){
                $response["data"]["payment_assessment_item"] = $this->m_account_payment->retrievePaymentAssessmentItem(
                        false, 
                        false,
                        0, 
                        NULL, 
                        $response["data"]["ID"]);
            }
        }else{
            $response["error"][] = array(
                  "status" => 3,
                  "message" => "No result"
                );
        }
        echo json_encode($response);
    }
    public function updateAccountPayment(){
        $response = $this->generateResponse();
        if(user_type() != 5 && user_type() != 8){ //if not admin or IT
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized".user_type()
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('payee_account_ID', 'Payee', 'required');
            $this->form_validation->set_rules('payer_account_ID', 'Payer', 'required');
            //$this->form_validation->set_rules('mode', 'Mode', 'required');
            //$this->form_validation->set_rules('total_amount', 'Total Amount', 'required');
            if($this->form_validation->run() && count($this->input->post("payment_assessment_item"))){
                $result = $this->m_account_payment->updateAccountPayment(
                        $this->input->post("ID"),
                        $this->input->post("order_receipt_number"),
                        $this->input->post("payee_account_ID"),
                        $this->input->post("payer_account_ID"),
                        null,
                        $this->input->post("total_amount"),
                        $this->input->post("mode"),
                        $this->input->post("datetime"),
                        $this->input->post("remarks"),
                        false,
                        $this->input->post("academic_year")
                        );
                if($result){
                    $this->load->model("m_payment_no_account");
                    $this->m_payment_no_account->deletePaymentNoAccount(NULL, $this->input->post("ID"));
                    if($this->input->post("payee_account_ID") == -1){
                        $this->m_payment_no_account->createPaymentNoAccount(
                                $this->input->post("ID"), 
                                $this->input->post("payee_last_name"), 
                                $this->input->post("payee_first_name"), 
                                $this->input->post("payee_middle_name"),
                                1
                                );
                    }
                    if($this->input->post("payer_account_ID") == -1){
                        $this->m_payment_no_account->createPaymentNoAccount(
                                $this->input->post("ID"),
                                $this->input->post("payer_last_name"), 
                                $this->input->post("payer_first_name"), 
                                $this->input->post("payer_middle_name"),
                                2
                                );
                    }
                    $paymentAssessmentList = array();
                    if($this->input->post("payment_assessment_item")){
                        //add new
                        foreach($this->input->post("payment_assessment_item") as $key => $value){
                            $paymentAssessmentList[] = array(
                                "payment_ID" => $this->input->post("ID"),
                                "assessment_item_ID" => $value["assessment_item_ID"],
                                "amount" => $value["amount"],
                                "remarks" => $value["remarks"]
                            );
                        }
                        $this->m_account_payment->batchCreatePaymentAssessmentItem($paymentAssessmentList);
                      
                    }
                    if($this->input->post("removed_payment_assessment_item")){
                        //delete old
                        $removedPaymentAssessmentList = array();
                        foreach($this->input->post("removed_payment_assessment_item") as $key => $value){
                            $removedPaymentAssessmentList[] = $value["ID"];
                        }
                        $this->m_account_payment->batchDeletePaymentAssessmentItem($paymentAssessmentList);
                    }
                    if($this->input->post("updated_payment_assessment_item")){
                        //update old
                        foreach($this->input->post("updated_payment_assessment_item") as $key => $value){
//                            $updatedPaymentAssessmentList[] = array(
//                                "ID" => $value["ID"],
//                                NULL,
//                                "assessment_item_ID" => $value["assessment_item_ID"],
//                                "amount" => $value["amount"],
//                                "remarks" => $value["remarks"]
//                            );
                            $this->m_account_payment->updatePaymentAssessmentItem($value["ID"], NULL, $value["assessment_item_ID"], $value["amount"], $value["remarks"]);
                        }
                        
                    }
                    if($result){

                        $response["data"] = $result;
                    }else{
                        $response["error"][] = array(
                            "status" => 3,
                            "message" => "Failed to create AccountPayment"
                        );
                    }
                }
            }else{
                $response["error"][] = array(
                  "status" => 2,
                  "message" => validation_errors()
                );
            }
        }
        echo json_encode($response);
    }
    public function voidTransaction(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library("form_validation");
            $this->form_validation->set_rules('order_receipt_number', 'OR Number', 'required');
            $this->form_validation->set_rules('remarks', 'General Ledger', 'required');
            //getting payment ID
            if($this->form_validation->run()){
                $paymentDetail = $this->m_account_payment->retrieveAccountPayment(false, false, 0, false, $this->input->post("order_receipt_number"));
                if($paymentDetail){
                    if($paymentDetail["status"] == 3){
                        $response["error"][] = array(
                        "status" => 4, 
                        "message" => "Transaction has been voided already"
                        );
                    }else{
                        $result = $this->m_account_payment->deleteAccountPayment($paymentDetail["ID"], $this->input->post("remarks"), user_id());
                        if($result){

                            $response["data"] = $result;
                        }else{
                           $response["error"][] = array(
                                "status" => 5, 
                                "message" => "Failed to Void"
                                );
                        }
                    }
                }else{
                    $response["error"][] = array(
                        "status" => 3, 
                        "message" => "Transaction Not Found"
                        );
                }
            }else{
                $response["error"][] = array(
                        "status" => 3, 
                        "message" => validation_errors()
                        );
            }
            
        }
        echo json_encode($response);
    }
    public function retrieveAccountLedger(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $paymentLedgerDetail = $this->m_account_payment->retrieveAccountPaymentAssessmentItem(
                $this->input->post("retrieve_type"),
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("ID"),
                $this->input->post("order_receipt_number"),
                $this->input->post("payee_account_ID"),
                $this->input->post("payer_account_ID"),
                $this->input->post("cashier_ID"),
                $this->input->post("total_amount"),
                $this->input->post("mode"),
                $this->input->post("datetime"),
                $this->input->post("remarks"),
                $this->input->post("status"),
                $this->input->post("identification_number"),
                $this->input->post("assessment_item_ID"),
                $this->input->post("amount"),
                $this->input->post("assessment_item_description"),
                $this->input->post("start_datetime"),
                $this->input->post("end_datetime"),
                $this->input->post("sort"),
                $this->input->post("assessment_type_ID"),
                $this->input->post("payee_class_section_ID"),
                $this->input->post("payment_academic_year"),
                $this->input->post("exclude_assessment_type_ID"),
                $this->input->post("payee_account_year_level")
                );
            if($this->input->post("limit") || $this->input->post("retrieve_total_amount")){
                $limitlessResult = $this->m_account_payment->retrieveAccountPaymentAssessmentItem(
                    $this->input->post("retrieve_type"),
                    0,
                    0,
                    $this->input->post("ID"),
                    $this->input->post("order_receipt_number"),
                    $this->input->post("payee_account_ID"),
                    $this->input->post("payer_account_ID"),
                    $this->input->post("cashier_ID"),
                    $this->input->post("total_amount"),
                    $this->input->post("mode"),
                    $this->input->post("datetime"),
                    $this->input->post("remarks"),
                    $this->input->post("status"),
                    $this->input->post("identification_number"),
                    $this->input->post("assessment_item_ID"),
                    $this->input->post("amount"),
                    $this->input->post("assessment_item_description"),
                    $this->input->post("start_datetime"),
                    $this->input->post("end_datetime"),
                    $this->input->post("sort"),
                    $this->input->post("assessment_type_ID"),
                    $this->input->post("payee_class_section_ID"),
                    $this->input->post("payment_academic_year"),
                    $this->input->post("exclude_assessment_type_ID"),
                    $this->input->post("payee_account_year_level")
                    );
                $response["result_count"] = count($limitlessResult);
                $response["total_amount"] = 0;
                if($this->input->post("retrieve_total_amount") && $limitlessResult){
                    foreach($limitlessResult as $key => $value){
                        $response["total_amount"] += ($value["status"]!=3) ? $value["amount"] : 0;
                    }
                }
            }
            if($paymentLedgerDetail){
                $response["data"] = $paymentLedgerDetail;
                
                
            }else{
                $response["error"][] = array(
                      "status" => 3,
                      "message" => "No result"
                    );
            }
        }
        $response["debug"][] = $this->input->post("identification_number");
        
        echo json_encode($response);
    }
    public function retrieveAssessmentItemSummary(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $paymentLedgerDetail = $this->m_account_payment->retrievePaymentAssessmentItemSummary(
                $this->input->post("retrieve_type"),
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("sort"),
                $this->input->post("ID"),
                $this->input->post("payment_ID"),
                $this->input->post("assessment_item_ID"),
                $this->input->post("assessment_type_ID"),
                $this->input->post("start_datetime"),
                $this->input->post("end_datetime"),
                $this->input->post("amount"),
                $this->input->post("tellering"),
                $this->input->post("order_receipt_number")
                );
            
            if($this->input->post("limit") || $this->input->post("retrieve_total_amount")){
                $limitlessResult = $this->m_account_payment->retrievePaymentAssessmentItemSummary(
                    $this->input->post("retrieve_type"),
                    0,
                    0,
                    $this->input->post("sort"),
                    $this->input->post("ID"),
                    $this->input->post("payment_ID"),
                    $this->input->post("assessment_item_ID"),
                    $this->input->post("assessment_type_ID"),
                    $this->input->post("start_datetime"),
                    $this->input->post("end_datetime"),
                    $this->input->post("amount"),
                    $this->input->post("tellering"),
                    $this->input->post("order_receipt_number")
                    );
                
                $response["result_count"] = $limitlessResult ? count($limitlessResult) : 0;
                $response["total_amount"] = 0;
                if($this->input->post("retrieve_total_amount") && $limitlessResult){
                   
                    foreach($limitlessResult as $value){
                        $response["total_amount"] += $value["total_amount"]*1;
                    }
                }
            }
            $response["debug"] = $this->input->post("limit");
            if($paymentLedgerDetail){
                $response["data"] = $paymentLedgerDetail;
                
                
            }else{
                $response["error"][] = array(
                      "status" => 3,
                      "message" => "No result"
                    );
            }
        }
        echo json_encode($response);
    }
    public function retrievePaymentAssessmentItem(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $paymentLedgerDetail = $this->m_account_payment->retrieveAccountPaymentAssessmentItem(
                $this->input->post("retrieve_type"),
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("ID"),
                $this->input->post("order_receipt_number"),
                $this->input->post("payee_account_ID"),
                $this->input->post("payer_account_ID"),
                $this->input->post("cashier_ID"),
                $this->input->post("total_amount"),
                $this->input->post("mode"),
                $this->input->post("datetime"),
                $this->input->post("remarks"),
                $this->input->post("status"),
                $this->input->post("identificaion_number"),
                $this->input->post("assessment_item_ID"),
                $this->input->post("amount"),
                $this->input->post("assessment_item_description"),
                $this->input->post("start_datetime"),
                $this->input->post("end_datetime"),
                $this->input->post("sort"),
                $this->input->post("assessment_type_ID")
                );
            if($this->input->post("limit") || $this->input->post("retrieve_total_amount")){
                $limitlessResult = $this->m_account_payment->retrieveAccountPaymentAssessmentItem(
                    $this->input->post("retrieve_type"),
                    0,
                    0,
                    $this->input->post("ID"),
                    $this->input->post("order_receipt_number"),
                    $this->input->post("payee_account_ID"),
                    $this->input->post("payer_account_ID"),
                    $this->input->post("cashier_ID"),
                    $this->input->post("total_amount"),
                    $this->input->post("mode"),
                    $this->input->post("datetime"),
                    $this->input->post("remarks"),
                    $this->input->post("status"),
                    $this->input->post("identificaion_number"),
                    $this->input->post("assessment_item_ID"),
                    $this->input->post("amount"),
                    $this->input->post("assessment_item_description"),
                    $this->input->post("start_datetime"),
                    $this->input->post("end_datetime"),
                    $this->input->post("sort"),
                    $this->input->post("assessment_type_ID")
                    );
                $response["result_count"] = $limitlessResult ? count($limitlessResult) : 0;
                $response["total_amount"] = 0;
                if($this->input->post("retrieve_total_amount") && $limitlessResult){
                    foreach($limitlessResult as $value){
                        $response["total_amount"] += ($value["status"]!=3) ? $value["amount"]*1 : 0;
                    }
                }
            }
            if($paymentLedgerDetail){
                $response["data"] = $paymentLedgerDetail;
            }else{
                $response["error"][] = array(
                      "status" => 3,
                      "message" => "No result"
                    );
            }
        }
        echo json_encode($response);
    }
    public function retrievePaymentNoAccount(){
        $this->load->model("m_payment_no_account");
        $response = $this->generateResponse();
        $result = $this->m_payment_no_account->retrievePaymentNoAccount(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("sort"), 
                $this->input->post("ID"),
                $this->input->post("payment_ID"),
                $this->input->post("last_name"),
                $this->input->post("first_name"),
                $this->input->post("middle_name"),
                $this->input->post("type"),
                $this->input->post("order_receipt_number")
                
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $resultLimit = $this->m_payment_no_account->retrievePaymentNoAccount(
                    $this->input->post("retrieve_type"), // 1 - search, 0 - match
                    0,
                    0, 
                    $this->input->post("sort"), 
                    $this->input->post("ID"),
                    $this->input->post("payment_ID"),
                    $this->input->post("last_name"),
                    $this->input->post("first_name"),
                    $this->input->post("middle_name"),
                    $this->input->post("type"),
                    $this->input->post("order_receipt_number")
                    );
                $response["result_count"] = ($resultLimit) ? count($resultLimit) : 0;
            }
        }else{
            $response["error"][] = array(
                  "status" => 3,
                  "message" => "No result"
                );
        }
        echo json_encode($response);
    }
    public function noAccountToAccount(){
        $this->load->model("m_payment_no_account");
        $response = $this->generateResponse();
        $noAccountList = $this->m_payment_no_account->retrievePaymentNoAccount(
                0, // 1 - search, 0 - match
                0,
                0, 
                0,
                0,
                0,
                $this->input->post("last_name"),
                $this->input->post("first_name"),
                $this->input->post("middle_name")
                );
        if($noAccountList){
            foreach($noAccountList as $key => $value){
                $result = $this->m_payment_no_account->deletePaymentNoAccount(
                    NULL,
                    $value["payment_ID"]    
                    );
                if($result){
                    $this->m_account_payment->updateAccountPayment(
                        $value["payment_ID"],
                        NULL, 
                        $this->input->post("account_ID"), 
                        $this->input->post("account_ID")
                        );
                }
            }
        }else{
            $response["error"][] = array(
                  "status" => 3,
                  "message" => "No result"
                );
        }
        echo json_encode($response);
    }
}
