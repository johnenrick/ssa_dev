<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_subject_schedule_component_hps extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_subject_schedule_component_hps");
        $this->load->model("m_change_log");
    }
    
    public function createSubjectScheduleComponentHPS(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),1) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('subject_schedule_ID', 'Subject Schedule ID', 'required');
            $this->form_validation->set_rules('subject_component_ID', 'Subject  Component ID', 'required');
            $this->form_validation->set_rules('highest_possible_score', 'Highest Possible Score', 'required');
            $this->form_validation->set_rules('grading', 'Grading', 'required');
            if($this->form_validation->run()){
                $result = $this->m_subject_schedule_component_hps->createSubjectScheduleComponentHPS(
                        $this->input->post("subject_schedule_ID"),
                        $this->input->post("subject_component_ID"),
                        $this->input->post("highest_possible_score"),
                        $this->input->post("grading")
                        );
                
                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create SubjectScheduleComponentHPS"
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
    
    public function retrieveSubjectScheduleComponentHPS(){
        $response = $this->generateResponse();
        $result = $this->m_subject_schedule_component_hps->retrieveSubjectScheduleComponentHPS(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("sort"),
                $this->input->post("ID"),
                $this->input->post("subject_schedule_ID"),
                $this->input->post("subject_component_ID"),
                $this->input->post("highest_possible_score"),
                $this->input->post("grading")
                );
        if($result){
            $response["data"] = $result;
             if($this->input->post("limit")){
                $response["result_count"] = count($this->m_subject_schedule_component_hps->retrieveSubjectScheduleComponentHPS(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0, 
                $this->input->post("sort"),
                $this->input->post("ID"),
                $this->input->post("subject_schedule_ID"),
                $this->input->post("subject_component_ID"),
                $this->input->post("highest_possible_score"),
                $this->input->post("grading")
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
    public function updateSubjectScheduleComponentHPS(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5 && user_type() != 6){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'SubjectScheduleComponentHPS ID', 'required');
            $this->form_validation->set_rules('highest_possible_score', 'Highest Possible Score', 'required');
            if($this->form_validation->run()){
                $result = $this->m_subject_schedule_component_hps->updateSubjectScheduleComponentHPS(
                        $this->input->post("ID"),
                        $this->input->post("subject_schedule_ID"),
                        $this->input->post("subject_component_ID"),
                        $this->input->post("highest_possible_score")
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
    public function deleteSubjectScheduleComponentHPS(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            if(!$this->m_subject_schedule_component_hps->assessmentItemExistInUsed($this->input->post("ID"))){
                $result = $this->m_subject_schedule_component_hps->deleteSubjectScheduleComponentHPS($this->input->post("ID"));
                if($result){
                    $response["data"] = $result;
                }else{
                   $response["error"][] = array(
                        "status" => 1, 
                        "message" => "Failed to delete"
                        );
                }
            }else{
                $response["error"][] = array(
                    "status" => 2, 
                    "message" => "Item is in use."
                    );
            }
        }
        echo json_encode($response);
    }
   
}
