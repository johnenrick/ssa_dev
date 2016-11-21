<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_access_control_list extends API_Controller{
    /*
     * Functions
     * 1    - createAccessControlList
     * 2    - retrieveModule
     * 4    - batchReplaceAccessContolList
     * 8    - retrieveAccessControlList
     * 16   - updateAccessControlList
     * 32   - deleteAccessControlList
     */
    public function __construct() {
        parent::__construct();
        $this->load->model("m_access_control_list");
        $this->load->model("m_change_log");
    }
    public function createAccessControlList(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('account_ID', 'AccessControlList Type ID', 'required');
            $this->form_validation->set_rules('module_ID', 'AccessControlList Description', 'required|is_unique[access_control_list.module_ID]');
            if($this->form_validation->run()){
                $result = $this->m_access_control_list->createAccessControlList(
                        $this->input->post("module_ID"),
                        $this->input->post("account_ID")
                        );
                
                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create AccessControlList"
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
    public function retrieveModule(){
        $response = $this->generateResponse();
        $accountID = $this->input->post("account_ID");
        $result = $this->m_access_control_list->retrieveModule(
                $this->input->post("ID"), 
                $this->input->post("parent_ID"),
                $accountID
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("retrieve_all")){
                foreach($response["data"] as $key => $value){
                    $response["data"][$key]["sub_module"] = $this->m_access_control_list->retrieveModule(
                        NULL, 
                        $value["ID"]
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
    public function batchReplaceAccessContolList(){
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('account_ID', 'AccessControlList account ID', 'required');
            if($this->form_validation->run()){
                $this->m_access_control_list->deleteAccessControlList(
                        $this->input->post("account_ID")
                        );
                if($this->input->post("access_control_list")){
                    $this->m_access_control_list->batchCreateAccessControlList(
                            $this->input->post("access_control_list")
                            );
                }
                if(1){
                    $response["data"] = 1;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create AccessControlList"
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
    public function retrieveAccessControlList(){
        $response = $this->generateResponse();
        $accountID = $this->input->post("account_ID");
        $result = $this->m_access_control_list->retrieveAccessControlList(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("module_ID"),
                $accountID
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("retrieve_all")){
                foreach($response["data"] as $key => $value){
                    $response["data"][$key]["sub_module"] = $this->m_access_control_list->retrieveModule(
                        NULL, 
                        $value["module_ID"]
                        );
                }
            }
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_access_control_list->retrieveAccessControlList(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0, 
                $this->input->post("ID"), 
                $this->input->post("module_ID"),
                $accountID
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
    
    public function updateAccessControlList(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'AccessControlList ID', 'required');
            
            ($this->input->post("module_ID") != NULL) ? $this->form_validation->set_rules('module_ID', 'AccessControlList Description', 'is_unique[access_control_list.module_ID]') : null;
            if($this->form_validation->run()){
                $result = $this->m_access_control_list->updateAccessControlList(
                        $this->input->post("ID"),
                        $this->input->post("module_ID"), 
                        $this->input->post("account_ID")
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
    public function deleteAccessControlList(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_access_control_list->deleteAccessControlList($this->input->post("ID"));
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
