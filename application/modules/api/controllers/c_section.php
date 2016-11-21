<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_section extends API_Controller{
    /*
     * Functions
     * 1    - createSection
     * 2    - retrieveSection
     * 4    - updateSection
     * 8    - deleteSection
     */
    public function __construct() {
        parent::__construct();
        $this->load->model("m_section");
        $this->load->model("m_change_log");
    }
    
    public function createSection(){
        
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('course_ID', 'Course ID', 'required');
            $this->form_validation->set_rules('year_level', 'year level', 'required');
            $this->form_validation->set_rules('description', 'Section Description', 'required|is_unique[section.description]');
            if($this->form_validation->run()){
                $result = $this->m_section->createSection(
                        $this->input->post("course_ID"),
                        $this->input->post("year_level"),
                        $this->input->post("description")
                        );
                
                if($result){
                    $this->load->model("m_section_adviser");
                    $this->m_section_adviser->createSectionAdviser($result, 0, $this->input->post("academic_year"));
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Create Section",
                        "associated_ID" =>$result
                    )));
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create Section"
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
    public function createSectionAdviser(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('section_ID', 'Section ID', 'required');
            $this->form_validation->set_rules('adviser_account_ID', 'Adviser Account ID', 'required');
            $this->form_validation->set_rules('academic_year', 'Academic Year', 'required');
            if($this->form_validation->run()){
                $this->load->model("m_section_adviser");
                $result = $this->m_section_adviser->createSectionAdviser(
                        $this->input->post("section_ID"),
                        $this->input->post("adviser_account_ID"),
                        $this->input->post("academic_year")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Create Section",
                        "associated_ID" =>$result
                    )));
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create Section"
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
    
    public function retrieveSection(){
        $response = $this->generateResponse();
        $result = $this->m_section->retrieveSection(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("course_ID"),
                $this->input->post("year_level"),
                $this->input->post("description"),
                $this->input->post("adviser"),
                $this->input->post("academic_year")
                );
        $response["debug"][] = $this->input->post("adviser");
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_section->retrieveSection(
                    $this->input->post("retrieve_type"), // 1 - search, 0 - match
                    0,
                    0, 
                    $this->input->post("ID"), 
                    $this->input->post("course_ID"),
                    $this->input->post("year_level"),
                    $this->input->post("description"),
                    $this->input->post("adviser"),
                    $this->input->post("academic_year")
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
    public function updateSection(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Section ID', 'required');
            if($this->form_validation->run()){
                $result = $this->m_section->updateSection(
                        $this->input->post("ID"),
                        $this->input->post("course_ID"),
                        $this->input->post("year_level"),
                        $this->input->post("description")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update Section",
                        "associated_ID" => $this->input->post("ID")
                    )));
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
    public function updateSectionAdviser(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Section Adviser ID', 'required');
            if($this->form_validation->run()){
                $this->load->model("m_section_adviser");
                $result = $this->m_section_adviser->updateSectionAdviser(
                        $this->input->post("ID"),
                        $this->input->post("section_ID"),
                        $this->input->post("adviser_account_ID"),
                        $this->input->post("academic_year")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update Section",
                        "associated_ID" => $this->input->post("ID")
                    )));
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
    public function deleteSection(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_section->deleteSection($this->input->post("ID"));
            if($result){
                $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Section",
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
