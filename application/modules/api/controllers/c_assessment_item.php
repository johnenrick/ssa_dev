<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_assessment_item extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_assessment_item");
        $this->load->model("m_change_log");
    }
    
    public function createAssessmentItem(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),1) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('assessment_type_ID', 'Assessment Type', 'required');
            $this->form_validation->set_rules('general_ledger_ID', 'General Ledger', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required|is_unique[assessment_item.description]');
            $this->form_validation->set_rules('tellering', 'Tellering', 'required');
            if($this->form_validation->run()){
                $result = $this->m_assessment_item->createAssessmentItem(
                        $this->input->post("assessment_type_ID"),
                        $this->input->post("general_ledger_ID"),
                        $this->input->post("description"),
                        $this->input->post("tellering")
                        );
                
                if($result){
                    
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create AssessmentItem"
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
    
    public function retrieveAssessmentItem(){
        $response = $this->generateResponse();
        $result = $this->m_assessment_item->retrieveAssessmentItem(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"),
                $this->input->post("assessment_type_ID"),
                $this->input->post("general_ledger_ID"),
                $this->input->post("description"),
                $this->input->post("sort"),
                $this->input->post("tellering")
                );
        if($result){
            $response["data"] = $result;
             if($this->input->post("limit")){
                $response["result_count"] = count($this->m_assessment_item->retrieveAssessmentItem(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0, 
                $this->input->post("ID"),
                $this->input->post("assessment_type_ID"),
                $this->input->post("general_ledger_ID"),
                $this->input->post("description"),
                $this->input->post("sort"),
                $this->input->post("tellering")
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
    public function updateAssessmentItem(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'AssessmentItem ID', 'required');
            $this->form_validation->set_rules('assessment_type_ID', 'Assessment Type', 'required');
            $this->form_validation->set_rules('general_ledger_ID', 'General Ledger', 'required');
            $this->form_validation->set_rules('tellering', 'Tellering', 'required');
//            if($this->input->post("description") != NULL){
//                $this->form_validation->set_rules('description', 'Description', 'required|is_unique[assessment_item.description]');
//            }
            if($this->form_validation->run()){
                $result = $this->m_assessment_item->updateAssessmentItem(
                        $this->input->post("ID"),
                        $this->input->post("assessment_type_ID"),
                        $this->input->post("general_ledger_ID"),
                        $this->input->post("description"),
                        $this->input->post("tellering")
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
    public function deleteAssessmentItem(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            if(!$this->m_assessment_item->assessmentItemExistInUsed($this->input->post("ID"))){
                $result = $this->m_assessment_item->deleteAssessmentItem($this->input->post("ID"));
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
