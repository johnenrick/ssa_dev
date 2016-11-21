<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_account_assessment_item_discount extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_account_assessment_item_discount");
        $this->load->model("m_change_log");
    }
    
    public function createAccountAssessmentItemDiscount(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),1) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('assessment_item_discount_ID', 'Assessment Type', 'required');
            $this->form_validation->set_rules('account_ID', 'Account', 'required');
            $this->form_validation->set_rules('assessment_item_ID', 'Amount', 'required');
            
            if($this->form_validation->run()){
                $result = $this->m_account_assessment_item_discount->createAccountAssessmentItemDiscount(
                        $this->input->post("assessment_item_discount_ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("assessment_item_ID"),
                        user_id()
                        );
                
                if($result){
                    
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create AccountAssessmentItemDiscount"
                    );
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
    
    public function retrieveAccountAssessmentItemDiscount(){
        $response = $this->generateResponse();
        $result = $this->m_account_assessment_item_discount->retrieveAccountAssessmentItemDiscount(
                $this->input->post("retrieve_item"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"),
                $this->input->post("assessment_item_discount_ID"),
                $this->input->post("account_ID"),
                $this->input->post("assessment_item_ID"),
                user_id(),
                $this->input->post("createdDatetime")
                );
        if($result){
            $response["data"] = $result;
        }else{
            $response["error"][] = array(
                  "status" => 3,
                  "message" => "No result"
                );
        }
        echo json_encode($response);
    }
    public function updateAccountAssessmentItemDiscount(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            echo user_type();
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'AccountAssessmentItemDiscount ID', 'required');
            $this->form_validation->set_rules('assessment_item_discount_ID', 'Assessment Type', 'required');
            $this->form_validation->set_rules('account_ID', 'Account', 'required');
            $this->form_validation->set_rules('assessment_item_ID', 'Amount', 'required');
            if($this->form_validation->run()){
                $result = $this->m_account_assessment_item_discount->updateAccountAssessmentItemDiscount(
                        $this->input->post("ID"),
                        $this->input->post("assessment_item_discount_ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("assessment_item_ID")
                        );
                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to update"
                    );
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
    public function deleteAccountAssessmentItemDiscount(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_account_assessment_item_discount->deleteAccountAssessmentItemDiscount($this->input->post("ID"));
            if($result){
                
                $response["data"] = $result;
            }else{
               $response["error"][] = array(
                    "status" => 1, 
                    "message" => "Failed to delete"
                    );
            }
        }
        echo json_encode($response);
    }
    
}
