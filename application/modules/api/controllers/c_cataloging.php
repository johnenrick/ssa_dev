<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_cataloging extends API_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model("m_cataloging");
        $this->load->model("m_change_log");
    }
    public function createMaterial(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('title', 'Material Title', 'required');
            $this->form_validation->set_rules('accessionNumber', 'Material Accession Number', 'required');
            $this->form_validation->set_rules('callNumber', 'Material Call Number', 'required');
            $this->form_validation->set_rules('publisher', 'Material Publisher', 'required');
            $this->form_validation->set_rules('copyright', 'Material Copyright', 'required');
            $this->form_validation->set_rules('materialAccessControl', 'Material Access Control', 'required');
            if($this->form_validation->run()){
                $result = $this->m_cataloging->createMaterial(
                        $this->input->post("controlNumber"),
                        $this->input->post("title"),
                        $this->input->post("author"),
                        $this->input->post("accessionNumber"),
                        $this->input->post("callNumber"),
                        $this->input->post("copyright"),
                        $this->input->post("edition"),
                        $this->input->post("materialAccessControl"),
                        1,
                        (($this->input->post("dateAcquired")==NULL)?0:strtotime($this->input->post("dateAcquired")." UTC")),
                        $this->input->post("supplier"),
                        $this->input->post("aeauthor1"),
                        $this->input->post("aeauthor2"),
                        $this->input->post("aeauthor3"),
                        $this->input->post("physicalDescription"),
                        $this->input->post("publisher"),
                        $this->input->post("subject1"),
                        $this->input->post("subject2"),
                        $this->input->post("subject3"),
                        $this->input->post("subject4"),
                        $this->input->post("subject5"),
                        $this->input->post("addedEntryTitle"),
                        $this->input->post("publisherAddress"),
                        $this->input->post("isbn"),
                        $this->input->post("notes"),
                        $this->input->post("location"),
                        $this->input->post("seriesTitle"),
                        $this->input->post("typeOfMaterial"),
                        $this->input->post("costPrice")
                        );

                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create Material"
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
    public function retrieveMaterial(){
        $response = $this->generateResponse();
        $result = $this->m_cataloging->retrieveMaterial(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("ID"),
                $this->input->post("title"),
                $this->input->post("author"),
                $this->input->post("accessionNumber"),
                $this->input->post("callNumber"),
                $this->input->post("publisher"),
                $this->input->post("subject"),
                $this->input->post("isbn"),
                $this->input->post("notes"),
                $this->input->post("keyword"),
                $this->input->post("sort")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_cataloging->retrieveMaterial(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0,
                $this->input->post("ID"),
                $this->input->post("title"),
                $this->input->post("author"),
                $this->input->post("accessionNumber"),
                $this->input->post("callNumber"),
                $this->input->post("publisher"),
                $this->input->post("subject"),
                $this->input->post("isbn"),
                $this->input->post("notes"),
                $this->input->post("keyword"),
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
    public function updateMaterial(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );

        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Material ID', 'required');

            ($this->input->post("title") != NULL) ? $this->form_validation->set_rules('title', 'Material Title', 'required') : null;
            ($this->input->post("author") != NULL) ? $this->form_validation->set_rules('author', 'Material Author', 'required') : null;
            ($this->input->post("accessionNumber") != NULL) ? $this->form_validation->set_rules('accessionNumber', 'Material Accession Number', 'required') : null;
            ($this->input->post("callNumber") != NULL) ? $this->form_validation->set_rules('callNumber', 'Material Call Number', 'required') : null;
            ($this->input->post("publisher") != NULL) ? $this->form_validation->set_rules('publisher', 'Material Publisher', 'required') : null;
            ($this->input->post("copyright") != NULL) ? $this->form_validation->set_rules('copyright', 'Material Copyright', 'required') : null;
            ($this->input->post("edition") != NULL) ? $this->form_validation->set_rules('edition', 'Material Edition', 'required') : null;
            ($this->input->post("materialAccessControl") != NULL) ? $this->form_validation->set_rules('materialAccessControl', 'Material Access Control', 'required') : null;
            $this->form_validation->set_rules('quantity', 'Material Quantity', 'required');
            if($this->form_validation->run()){
                $result = $this->m_cataloging->updateMaterial(
                        $this->input->post("ID"),
                        $this->input->post("title"),
                        $this->input->post("author"),
                        $this->input->post("accessionNumber"),
                        $this->input->post("callNumber"),
                        $this->input->post("copyright"),
                        $this->input->post("edition"),
                        $this->input->post("materialAccessControl"),
                        (($this->input->post("dateAcquired")==NULL)?0:strtotime($this->input->post("dateAcquired")." UTC")),
                        $this->input->post("supplier"),
                        $this->input->post("aeauthor1"),
                        $this->input->post("aeauthor2"),
                        $this->input->post("aeauthor3"),
                        $this->input->post("physicalDescription"),
                        $this->input->post("publisher"),
                        $this->input->post("subject1"),
                        $this->input->post("subject2"),
                        $this->input->post("subject3"),
                        $this->input->post("subject4"),
                        $this->input->post("subject5"),
                        $this->input->post("addedEntryTitle"),
                        $this->input->post("publisherAddress"),
                        $this->input->post("isbn"),
                        $this->input->post("notes"),
                        $this->input->post("location"),
                        $this->input->post("seriesTitle"),
                        $this->input->post("typeOfMaterial"),
                        $this->input->post("costPrice")
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
    public function deleteMaterial(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ // if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_cataloging->deleteMaterial($this->input->post("ID"));
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

    public function retrieveForExcel(){
        $response = $this->generateResponse();
        $result = $this->m_cataloging->retrieveForExcel();
        $response["data"] = $result;
        echo json_encode($response);
    }

}
