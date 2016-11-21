<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_account_level extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_account_level");
        $this->load->model("m_change_log");
    }
    public function test(){
        echo date("6 1 Y");
    }
    public function createAccountLevel(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            //account should not have two year level on the same year
            $accountLevel = $this->m_account_level->retrieveAccountLevel(false, false, 0, NULL, 
                    $this->input->post("account_ID"), 
                    NULL, 
                    $this->input->post("academic_year")
                    );
            if($accountLevel){
                $response["error"][] = array(
                  "status" => 4,
                  "message" => $accountLevel
                );
                echo json_encode($response);
                exit();
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules('account_ID', 'Acount  ID', 'required');
            $this->form_validation->set_rules('course_ID', 'Acount  ID', 'required');
            $this->form_validation->set_rules('year_level', 'Year Level', 'required');
            $this->form_validation->set_rules('academic_year', 'Academic Year', 'required');
            
            if($this->form_validation->run()){
                $result = $this->m_account_level->createAccountLevel(
                        $this->input->post("account_ID"),
                        $this->input->post("course_ID"),
                        $this->input->post("year_level"),
                        $this->input->post("academic_year"),
                        $this->input->post("grade")
                        );
                
                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create AccountLevel"
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
    public function retrieveAccountLevel(){
        $response = $this->generateResponse();
        $result = $this->m_account_level->retrieveAccountLevel(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("account_ID"),
                $this->input->post("year_level"),
                $this->input->post("academic_year"),
                $this->input->post("grade"),
                $this->input->post("course_ID")
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
    public function updateAccountLevel(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Acount Level ID', 'required');
            
            if($this->form_validation->run()){
                $result = $this->m_account_level->updateAccountLevel(
                        $this->input->post("ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("year_level"),
                        $this->input->post("academic_year"),
                        $this->input->post("grade"),
                        $this->input->post("course_ID")
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
    public function deleteAccountLevel(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_account_level->deleteAccountLevel($this->input->post("ID"));
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
