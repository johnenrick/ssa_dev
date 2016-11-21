<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_assessment_type extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_assessment_type");
        $this->load->model("m_change_log");
    }
    
    public function createAssessmentType(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),1) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('description', 'Assessment Type Description', 'required|is_unique[assessment_type.description]');
            $this->form_validation->set_rules('code', 'Assessment Type Description', 'required|is_unique[assessment_type.code]');
            if($this->form_validation->run()){
                $result = $this->m_assessment_type->createAssessmentType(
                        $this->input->post("description"),
                        $this->input->post("code")
                        );
                
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Create AssessmentType",
                        "associated_ID" =>$result
                    )));
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create AssessmentType"
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
    
    public function retrieveAssessmentType(){
        $response = $this->generateResponse();
        $result = $this->m_assessment_type->retrieveAssessmentType(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"),
                $this->input->post("description"),
                $this->input->post("code"),
                $this->input->post("sort")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_assessment_type->retrieveAssessmentType(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0, 
                $this->input->post("ID"),
                $this->input->post("description"),
                $this->input->post("code"),
                $this->input->post("sort")
                ));
            }
        }else{
            $response["error"][] = array(
                  "status" => 3,
                  "message" => "No result"
                );
        }
        echo json_encode($response);
    }
    public function updateAssessmentType(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            echo user_type();
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'AssessmentType ID', 'required');
            if($this->form_validation->run()){
                $result = $this->m_assessment_type->updateAssessmentType(
                        $this->input->post("ID"),
                        $this->input->post("description"),
                        $this->input->post("code")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update Assessment Type",
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
    public function deleteAssessmentType(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_assessment_type->deleteAssessmentType($this->input->post("ID"));
            if($result){
                $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Assessment Type",
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
