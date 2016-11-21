<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_student_subject_component_score extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_student_subject_component_score");
        $this->load->model("m_change_log");
    }
    
    public function createStudentSubjectComponentScore(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('subject_schedule_component_hps_ID', 'Subject Schedule Component', 'required|greater_than[0]');
            $this->form_validation->set_rules('account_ID', 'Account', 'required');
            $this->form_validation->set_rules('score', 'Score', 'required');
            if($this->form_validation->run()){
                $existing = $this->m_student_subject_component_score->retrieveStudentSubjectComponentScore(
                        0, // 1 - search, 0 - match
                        0,
                        0, 
                        0, 
                        $this->input->post("subject_schedule_component_hps_ID"),
                        $this->input->post("account_ID")
                    );
                if(!$existing){
                    $result = $this->m_student_subject_component_score->createStudentSubjectComponentScore(
                            $this->input->post("subject_schedule_component_hps_ID"),
                            $this->input->post("account_ID"),
                            $this->input->post("score")
                            );
                    if($result ){
                        $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                            "message" => "Create StudentSubjectComponentScore",
                            "associated_ID" =>$result
                        )));
                        
                        $response["data"] = $result;
                    }else{
                        $response["error"][] = array(
                            "status" => 3,
                            "message" => "Failed to create Class Section"
                        );
                    }
                }else{
                    $response["error"][] = array(
                    "status" => 4,
                    "message" => "Component Already Added"
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
    
    public function retrieveStudentSubjectComponentScore(){
        $response = $this->generateResponse();
        $result = $this->m_student_subject_component_score->retrieveStudentSubjectComponentScore(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("subject_schedule_component_hps_ID"),
                $this->input->post("account_ID"),
                $this->input->post("score"),
                $this->input->post("sort"),
                $this->input->post("subject_schedule_ID"),
                $this->input->post("grading"),
                $this->input->post("academic_year")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_student_subject_component_score->retrieveStudentSubjectComponentScore(
                    $this->input->post("retrieve_type"), // 1 - search, 0 - match
                    0,
                    0, 
                    $this->input->post("ID"), 
                    $this->input->post("subject_schedule_component_hps_ID"),
                    $this->input->post("account_ID"),
                    $this->input->post("score"),
                    false,
                    $this->input->post("subject_schedule_ID"),
                    $this->input->post("grading"),
                    $this->input->post("academic_year")
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
    public function retrieveNoStudentSubjectComponentScore(){
        $response = $this->generateResponse();
        $result = $this->m_student_subject_component_score->retrieveNoStudentSubjectComponentScore(
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("score"),
                $this->input->post("sort"),
                $this->input->post("year_level"),
                $this->input->post("full_name")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_student_subject_component_score->retrieveNoStudentSubjectComponentScore(
                    0,
                    0,
                    $this->input->post("score"),
                    $this->input->post("sort"),
                    $this->input->post("year_level"),
                    $this->input->post("full_name")
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
    public function updateStudentSubjectComponentScore(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 6){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'StudentSubjectComponentScore ID', 'required');
            if($this->form_validation->run()){
                $result = $this->m_student_subject_component_score->updateStudentSubjectComponentScore(
                        $this->input->post("ID"),
                        $this->input->post("subject_schedule_component_hps_ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("score")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update StudentSubjectComponentScore",
                        "associated_ID" => $this->input->post("ID")
                    )));
                    $response["data"] = $this->input->post("ID");
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
    public function deleteStudentSubjectComponentScore(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_student_subject_component_score->deleteStudentSubjectComponentScore($this->input->post("ID"));
            if($result){
                $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete StudentSubjectComponentScore",
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
    
    public function retrieveStudentInfo(){
        $response = $this->generateResponse();
        $result = $this->m_student_subject_component_score->retrieveStudentInfo($this->input->post("account_id"));
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
}
