<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_circulation extends API_Controller{

    public function __construct() {
        parent::__construct();
        $this->load->model("m_circulation");
        $this->load->model("m_change_log");
    }
    public function createCirculation(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('borrowerID', 'circulation borrower ', 'required');
            $this->form_validation->set_rules('libraryUsers', 'circulation borrower type', 'required');
            $this->form_validation->set_rules('datetime', 'circulation date & time', 'required');
            $this->form_validation->set_rules('loanBookID', 'circulation Book ', 'required');
            if($this->form_validation->run()){
                date_default_timezone_set("UTC");
                $result = $this->m_circulation->createCirculation(
                        $this->input->post("borrowerID"),
                        $this->input->post("libraryUsers"),
                        strtotime ( $this->input->post("datetime")),
                        $this->input->post("loanBookID")
                        );

                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create circulation"
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
    public function retrieveCirculation(){
        $response = $this->generateResponse();
        $result = $this->m_circulation->retrieveCirculation(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("ID"),
                $this->input->post("borrowerID"),
                $this->input->post("datetime"),
                $this->input->post("group"),
                $this->input->post("sort"),
                $this->input->post("name"),
                $this->input->post("return_datetime"),
                $this->input->post("title"),
                $this->input->post("borrower_full_name")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit") != NULL){
                $response["result_count"] = count($this->m_circulation->retrieveCirculation(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0,
                $this->input->post("ID"),
                $this->input->post("borrowerID"),
                $this->input->post("datetime"),
                $this->input->post("group"),
                $this->input->post("sort"),
                $this->input->post("name"),
                $this->input->post("return_datetime"),
                $this->input->post("title"),
                $this->input->post("borrower_full_name")
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
    public function updateCirculation(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );

        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('loanBookID', 'circulation Book ', 'required');
            if($this->form_validation->run()){
                date_default_timezone_set("UTC");

                $result = $this->m_circulation->updateCirculation(
                        $this->input->post("borrowerID"),
                        $this->input->post("loanBookID"),
                        $this->input->post("bookID"),
                        $this->input->post("returndatetime")
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
    public function deleteCirculation(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ // if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_circulation->deletecirculation($this->input->post("ID"));
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


    /// RFID Scan

    public function retrieveDisplayID(){
        $response = $this->generateResponse();
        $result = $this->m_circulation->retrieveDisplayID($this->input->post("uid"),$this->input->post("id"));
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

    public function retrieveDisplayStudent(){
        $response = $this->generateResponse();
        switch($this->input->post("location")){
            case "gate-portal":
                $location = 1;
                break;
            case "library-portal":
                $location = 2;
                break;
            default:
                $location = 0;
        }

        switch($this->input->post("action")){
            case "in":
                $action = 1;
                break;
            case "out":
                $action = 2;
                break;
            default:
                $action = 0;
        }
        $result = $this->m_circulation->retrieveDisplayStudent($this->input->post("uid"),$location,$action);
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

    //report staff
    public function retrieveLibraryPortalList(){
        $response = $this->generateResponse();
        if($this->input->post("start_datetime") && $this->input->post("end_datetime"))
            $result = $this->m_circulation->retrieveLibraryPortalList(
                    $this->input->post("start_datetime"),
                    $this->input->post("end_datetime"),
                    $this->input->post("academic_year")
                    );
        else $result = false;

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
    public function retrieveCirculationList(){
        $response = $this->generateResponse();
        if($this->input->post("start_datetime") && $this->input->post("end_datetime"))
            $result = $this->m_circulation->retrieveCirculationList(
                    $this->input->post("start_datetime"),
                    $this->input->post("end_datetime")
                    );
        else $result = false;
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

    public function registerRFID(){
        $data = $this->input->post("list");
        $response = $this->generateResponse();
        $result = $this->m_circulation->registerRFID($data);

    }

}
