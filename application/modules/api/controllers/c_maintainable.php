<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_maintainable extends API_Controller{
    /*
     * Functions
     * 1    - createMaintainable
     * 2    - retrieveMaintainable
     * 4    - updateMaintainable
     */
    public function __construct() {
        parent::__construct();
        $this->load->model("m_maintainable");
        $this->load->model("m_change_log");
    }
    
    public function createMaintainable(){
        $accessNumber = 1;
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),1) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('maintainable_type_ID', 'Maintainable Type ID', 'required');
            $this->form_validation->set_rules('description', 'Maintainable Description', 'required|is_unique[maintainable.description]');
            if($this->form_validation->run()){
                $result = $this->m_maintainable->createMaintainable(
                        $this->input->post("maintainable_type_ID"),
                        $this->input->post("description")
                        );
                
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Create Maintainable",
                        "associated_ID" =>$result
                    )));
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create Maintainable"
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
    
    public function retrieveMaintainable(){
        $accessNumber = 2;
        $response = $this->generateResponse();
        $result = $this->m_maintainable->retrieveMaintainable(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("maintainable_type_ID"),
                $this->input->post("description")
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
    public function updateMaintainable(){
        $accessNumber = 4;
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Maintainable ID', 'required');
            $this->form_validation->set_rules('description', 'Maintainable Description', 'is_unique[maintainable.description]');
            if($this->form_validation->run()){
                $result = $this->m_maintainable->updateMaintainable(
                        $this->input->post("ID"),
                        $this->input->post("parent_ID"),
                        $this->input->post("description"), 
                        $this->input->post("detail")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update Maintainable",
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
    public function deleteMaintainable(){
        $accessNumber = 8;
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_maintainable->deleteMaintainable($this->input->post("ID"));
            if($result){
                $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Maintainable",
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
