<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_course extends API_Controller{
    /*
     * Functions
     * 1    - createCourse
     * 2    - retrieveCourse
     * 4    - updateCourse
     * 8    - deleteCourse
     */
    public function __construct() {
        parent::__construct();
        $this->load->model("m_course");
        $this->load->model("m_change_log");
    }
    
    public function createCourse(){
        $response = $this->generateResponse();
        if(!checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('code', 'Code', 'required');
            $this->form_validation->set_rules('description', 'Course Description', 'required|is_unique[course.description]');
            if($this->form_validation->run()){
                $result = $this->m_course->createCourse(
                        $this->input->post("code"),
                        $this->input->post("description")
                        );
                
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Create Course",
                        "associated_ID" =>$result
                    )));
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create Course"
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
    
    public function retrieveCourse(){
        $response = $this->generateResponse();
        $result = $this->m_course->retrieveCourse(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("code"),
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
    public function updateCourse(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Course ID', 'required');
            $this->form_validation->set_rules('code', 'Code', 'required');
            $this->form_validation->set_rules('description', 'Course Description', 'is_unique[course.description]');
            if($this->form_validation->run()){
                $result = $this->m_course->updateCourse(
                        $this->input->post("ID"),
                        $this->input->post("parent_ID"),
                        $this->input->post("description"), 
                        $this->input->post("detail")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update Course",
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
    public function deleteCourse(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_course->deleteCourse($this->input->post("ID"));
            if($result){
                $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Course",
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
