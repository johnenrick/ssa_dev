<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_material_type extends API_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model("m_library_location");
        $this->load->model("m_change_log");
    }

    public function createMaterialAccessControl(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),1) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('description', 'Control Description', 'required');
            if($this->form_validation->run()){
                $result = $this->m_material_access_control->createMaterialAccessControl(
                        $this->input->post("description")
                        );

                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Create Material Type",
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

    public function retrieveMaterialAccessControl(){
        $response = $this->generateResponse();
        $result = $this->m_material_access_control->retrieveMaterialAccessControl(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("ID"),
                $this->input->post("description")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_material_access_control->retrieveMaterialAccessControl(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0,
                $this->input->post("ID"),
                $this->input->post("title"),
                $this->input->post("author"),
                $this->input->post("accessionNumber"),
                $this->input->post("callNumber"),
                $this->input->post("publisher"),
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

    public function updateMaterialAccessControl(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );

        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Control Missing', 'required');
            $this->form_validation->set_rules('description', 'Control Description', 'required');
            if($this->form_validation->run()){
                $result = $this->m_material_access_control->updateMaterialAccessControl(
                        $this->input->post("ID"),
                        $this->input->post("description")
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
    public function deleteMaterialAccessControl(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_material_access_control->deleteMaterialAccessControl($this->input->post("ID"),$this->input->post("libraryusers"));
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

    //library users

    public function retrieveLibraryUser(){
        $response = $this->generateResponse();
        $result = $this->m_material_access_control->retrieveLibraryUser(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("ID"),
                $this->input->post("description")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_material_access_control->retrieveLibraryUser(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0,
                $this->input->post("ID"),
                $this->input->post("description"),
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

}
