<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_student_log extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_student_log");
        $this->load->model("m_change_log");
    }
    public function test(){
        echo date("6 1 Y");
    }
    public function createStudentLog(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            //account should not have two year level on the same year
            $accountLevel = $this->m_student_log->retrieveStudentLog(false, false, 0, NULL, 
                    $this->input->post("account_ID"), 
                    NULL, 
                    $this->input->post("academic_year")
                    );
            if($accountLevel){
                $response["error"][] = array(
                  "status" => 4,
                  "message" => $accountLevel
                );
                echo json_encode($response);
                exit();
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules('account_ID', 'Acount  ID', 'required');
            $this->form_validation->set_rules('course_ID', 'Acount  ID', 'required');
            $this->form_validation->set_rules('year_level', 'Year Level', 'required');
            $this->form_validation->set_rules('academic_year', 'Academic Year', 'required');
            
            if($this->form_validation->run()){
                $result = $this->m_student_log->createStudentLog(
                        $this->input->post("account_ID"),
                        $this->input->post("course_ID"),
                        $this->input->post("year_level"),
                        $this->input->post("academic_year"),
                        $this->input->post("grade")
                        );
                
                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create StudentLog"
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
    public function retrieveStudentLog(){
        $response = $this->generateResponse();
        $startDatetime = $this->input->post("start_datetime");
        $endDatetime = $this->input->post("end_datetime");
        $response["debug"][] = $startDatetime;
        $response["debug"][] = strtotime("+1 day", $endDatetime);
        $result = $this->m_student_log->retrieveStudentLog(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("account_ID"),
                $startDatetime,
                $endDatetime,
                $this->input->post("in_out"),
                $this->input->post("location"),
                $this->input->post("account_name")
                );
        if($this->input->post("limit")){
            $response["result_count"] = count($this->m_student_log->retrieveStudentLog( 
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                NULL,
                NULL, 
                $this->input->post("ID"), 
                $this->input->post("account_ID"),
                $startDatetime,
                $endDatetime,
                $this->input->post("in_out"),
                $this->input->post("location"),
                $this->input->post("account_name")
            ));
        }
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
    public function updateStudentLog(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Acount Level ID', 'required');
            
            if($this->form_validation->run()){
                $result = $this->m_student_log->updateStudentLog(
                        $this->input->post("ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("year_level"),
                        $this->input->post("academic_year"),
                        $this->input->post("grade"),
                        $this->input->post("course_ID")
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
    public function deleteStudentLog(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),API_Controller::STUDENT_MANAGEMENT)){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_student_log->deleteStudentLog($this->input->post("ID"));
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
