<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_club extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_club");
        $this->load->model("m_change_log");
    }
    
    public function createClub(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('subject_ID', 'section ID', 'required');
            $this->form_validation->set_rules('account_ID', 'Class Section Account', 'required');
            $this->form_validation->set_rules('school_year', 'year level', 'required');
            if($this->form_validation->run()){
                if($this->input->post("previous_subject_ID")){
                    $this->m_club->deleteClub($this->input->post("previous_subject_ID"));
                }
                $existing = $this->m_club->retrieveClub(
                        0, // 1 - search, 0 - match
                        0,
                        0, 
                        0, 
                        $this->input->post("subject_ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("school_year")
                    );
                if(!$existing){
                    
                    if($this->input->post("subject_ID")*1){
                    $result = $this->m_club->createClub(
                            $this->input->post("subject_ID"),
                            $this->input->post("account_ID"),
                            $this->input->post("school_year")
                            );
                    }else{
                        $result = 1;
                    }

                    if($result ){
                        
                            /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                                "message" => "Create ClassSection",
                                "associated_ID" =>$result
                            )));*/
                        
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
                    "message" => "Account Already Added"
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
    
    public function retrieveClub(){
        $response = $this->generateResponse();
        $result = $this->m_club->retrieveClub(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("subject_ID"),
                $this->input->post("account_ID"),
                $this->input->post("school_year")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_club->retrieveClub(
                    $this->input->post("retrieve_type"), // 1 - search, 0 - match
                    0,
                    0, 
                    $this->input->post("ID"), 
                    $this->input->post("subject_ID"),
                    $this->input->post("account_ID"),
                    $this->input->post("school_year")
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
    public function retrieveNoClub(){
        $response = $this->generateResponse();
        $result = $this->m_club->retrieveNoClub(
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("school_year")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_club->retrieveNoClub(
                    0,
                    0, 
                    $this->input->post("ID"), 
                    $this->input->post("school_year")
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
    public function updateClub(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'ClassSection ID', 'required');
            if($this->form_validation->run()){
                $result = $this->m_club->updateClub(
                        $this->input->post("ID"),
                        $this->input->post("subject_ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("school_year")
                        );
                if($result){
                    /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update ClassSection",
                        "associated_ID" => $this->input->post("ID")
                    )));*/
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
    public function deleteClub(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_club->deleteClub($this->input->post("ID"));
            if($result){
                /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete ClassSection",
                    "associated_ID" => $this->input->post("ID")
                )));*/
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
