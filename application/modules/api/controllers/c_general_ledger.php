<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_general_ledger extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_general_ledger");
        $this->load->model("m_change_log");
    }
    public function createGeneralLedger(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('code', 'GeneralLedger Code', 'required');
            $this->form_validation->set_rules('description', 'GeneralLedger Description', 'required|is_unique[general_ledger.description]');
            if($this->form_validation->run()){
                $result = $this->m_general_ledger->createGeneralLedger(
                        $this->input->post("description"),
                        $this->input->post("code")
                        );
                
                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create GeneralLedger"
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
    public function retrieveGeneralLedger(){
        $response = $this->generateResponse();
        $result = $this->m_general_ledger->retrieveGeneralLedger(
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
                $response["result_count"] = count($this->m_general_ledger->retrieveGeneralLedger(
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
    public function updateGeneralLedger(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'GeneralLedger ID', 'required');
            
            ($this->input->post("description") != NULL) ? $this->form_validation->set_rules('description', 'GeneralLedger Description', 'is_unique[general_ledger.description]') : null;
            if($this->form_validation->run()){
                $result = $this->m_general_ledger->updateGeneralLedger(
                        $this->input->post("ID"),
                        $this->input->post("description"), 
                        $this->input->post("code")
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
    public function deleteGeneralLedger(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_general_ledger->deleteGeneralLedger($this->input->post("ID"));
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
