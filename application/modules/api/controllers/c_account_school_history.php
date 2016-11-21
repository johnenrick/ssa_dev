<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_account_school_history extends API_Controller{
    /*
     * Functions
     * 1    - createAccountSchoolHistory
     * 2    - retrieveAccountSchoolHistory
     * 4    - updateAccountSchoolHistory
     * 8    - deleteAccountSchoolHistory
     */
    public function __construct() {
        parent::__construct();
        $this->load->model("m_account_school_history");
        $this->load->model("m_change_log");
    }
    
    public function createAccountSchoolHistory(){
        $accessNumber =1;
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('account_ID', 'Account School History Account ID', 'required');
            $this->form_validation->set_rules('datetime', 'School Year', 'required');
            $this->form_validation->set_rules('course_ID', 'Course', 'required');
            $this->form_validation->set_rules('year_level', 'Year Level', 'required');
            if($this->form_validation->run()){
                $result = $this->m_account_school_history->createAccountSchoolHistory(
                        $this->input->post("account_ID"),
                        $this->input->post("school_campus_maintainable_ID"), 
                        $this->input->post("datetime"), 
                        $this->input->post("course_ID"), 
                        $this->input->post("year_level"),
                        $this->input->post("section")
                        );
                
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Create AccountSchoolHistory",
                        "associated_ID" =>$result
                    )));
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create AccountSchoolHistory"
                    );
                }
            }else{
                $response["error"][] = array(
                  "status" => 2,
                  "message" => validation_errors().$this->input->post("datetime")
                );
                
            }
        }
        echo json_encode($response);
    }
    
    public function retrieveAccountSchoolHistory(){
        $accessNumber =2;
        $response = $this->generateResponse();
        $result = $this->m_account_school_history->retrieveAccountSchoolHistory(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("account_ID"),
                $this->input->post("shool_campus_maintainable_ID"), 
                $this->input->post("datetime"), 
                $this->input->post("course_ID"), 
                $this->input->post("year_level"),
                $this->input->post("section")
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
    public function updateAccountSchoolHistory(){
        $accessNumber = 4;
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'AccountSchoolHistory ID', 'required');
            $this->form_validation->set_rules('description', 'AccountSchoolHistory Description', 'is_unique[maintainable.description]');
            if($this->form_validation->run()){
                $result = $this->m_account_school_history->updateAccountSchoolHistory(
                        $this->input->post("ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("school_campus_maintainable_ID"),
                        $this->input->post("datetime"), 
                        $this->input->post("course_ID"), 
                        $this->input->post("year_level"),
                        $this->input->post("section")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update AccountSchoolHistory",
                        "associated_ID" => $this->input->post("ID")
                    )));
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
    public function deleteAccountSchoolHistory(){
        $accessNumber = 8;
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_account_school_history->deleteAccountSchoolHistory($this->input->post("ID"));
            if($result){
                $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete AccountSchoolHistory",
                    "associated_ID" => $this->input->post("ID")
                )));
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
