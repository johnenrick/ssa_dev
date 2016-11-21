<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_subject extends API_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("m_subject");
		$this->load->model("m_change_log");
	}

	public function createSubject(){
        $response = $this->generateResponse();
        if (!$this->checkACL(user_type(), $this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)) { //if not admin
            $response["error"][] = array(
                "status" => 1,
                "message" => "Not Authorized"
            );
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('description', 'Subject Name', 'required');
            $this->form_validation->set_rules('course_ID', 'Course ID', 'required');
            $this->form_validation->set_rules('subject_type', 'Subject Type', 'required');
            $this->form_validation->set_rules('year_level', 'Year Level', 'required');
            if ($this->form_validation->run()) {
                if (!$this->m_subject->checkSubjectName($this->input->post("year_level"), strtoupper($this->input->post('description')), $this->input->post("school_year"))) { /* returns 0 if available, else returns 1 */
                    $result = $this->m_subject->createSubject(
                            $this->input->post('course_ID'), $this->input->post('year_level'), $this->input->post('subject_type'), strtoupper($this->input->post('description'))
                    );
                    if ($result) {
                        /* $this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                          "message"       => "Create Subject",
                          "associated_ID" =>$result
                          ))); */
                        for ($x = 0; $x < count($this->input->post("component_description")); $x++) {
                            if (!empty($this->input->post("component_description")[$x]) && !empty($this->input->post("component_percentage")[$x]))
                                $this->m_subject->addComponent($result, strtoupper($this->input->post("component_description")[$x]), $this->input->post("component_percentage")[$x], $this->input->post("academic_year"));
                        }
                        $response["data"] = $result;
                    }else {
                        $response["error"][] = array(
                            "status" => 3,
                            "message" => "Failed to create Subject"
                        );
                    }
                } else {
                    $response["error"][] = array(
                        "status" => 2,
                        "message" => "<p>" . $this->input->post("description") . " Already Exist</p>"
                    );
                }
            } else {
                $response["error"][] = array(
                    "status" => 2,
                    "message" => validation_errors()
                );
            }
        }
        echo json_encode($response);
    }

    public function checkSubjectName(){
		$response = $this->generateResponse();
		if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('description');
            if($this->form_validation->run()){
            	$result = $this->m_subject->checkSubjectName($this->input->post('description')); /* returns 0 if available, else returns 1 */
            	$response["data"] = $result;
            }else{
            	 $response["error"][] = array(
                  "status"	=> 2,
                  "message"	=> validation_errors()
                );
            }
        }
		echo json_encode($response);
	}

	public function retrieveSubject(){
        $response = $this->generateResponse();
        $result = $this->m_subject->retrieveSubject(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("course_ID"),
                $this->input->post("year_level"),
                $this->input->post('subject_type'),
                $this->input->post("description")
                );
        if($result){
            if($this->input->post("has_component")){
                $response["debug"][]  = $this->input->post("ID");
                $response["debug"][]  = $this->input->post("academic_year");
                $result["component"] = $this->m_subject->retrieveComponent(NULL, $this->input->post("academic_year"), $this->input->post("ID"));
            }
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_subject->retrieveSubject(
                    $this->input->post("retrieve_type"), // 1 - search, 0 - match
                    0,
                    0, 
                    $this->input->post("ID"), 
                    $this->input->post("course_ID"),
                    $this->input->post("year_level"),
                    $this->input->post('subject_type'),
                    $this->input->post("description")
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

    public function updateSubject(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Subject ID', 'required');
            if($this->form_validation->run()){
                $result = $this->m_subject->updateSubject(
                        $this->input->post("ID"),
                        $this->input->post("course_ID"),
                        $this->input->post("year_level"),
                        $this->input->post('subject_type'),
                        $this->input->post("description")
                        );
                if($result){
                    /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message" => "Update Subject",
                        "associated_ID" => $this->input->post("ID")
                    )));*/
                    for($x = 0; $x < count($this->input->post("component_description")); $x++){
                        if(!empty($this->input->post("component_description")[$x]) && !empty($this->input->post("component_percentage")[$x])) {
                            $this->m_subject->addComponent(
                                    $this->input->post("ID"), 
                                    strtoupper($this->input->post("component_description")[$x]), 
                                    $this->input->post("component_percentage")[$x],
                                    $this->input->post("academic_year")
                                    ); 
                        }
                    }
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
    public function deleteSubject(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_subject->deleteSubject($this->input->post("ID"));
            if($result){
                /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Subject",
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

    public function retrieveComponent(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 6){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_subject->retrieveComponent($this->input->post("ID"), $this->input->post("academic_year"), $this->input->post("subject_ID"));

            if($result){
                /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Subject",
                    "associated_ID" => $this->input->post("ID")
                )));*/
                $response["data"] = $result;
            }else{
                $response["error"][] = array(
                    "status" => 1, 
                    "message" => "Failed to fetch"
                );
            }
        }
        echo json_encode($response);
    }

    public function removeComponent(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_subject->removeComponent($this->input->post("ID"));

            if($result){
                /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Subject",
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

    public function updateComponent(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 6){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_subject->updateComponent(
                $this->input->post("ID"),
                strtoupper($this->input->post("description")),
                $this->input->post("percentage"),
                $this->input->post("hps"),
                $this->input->post("grading")
            );

            if($result){
                /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Subject",
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