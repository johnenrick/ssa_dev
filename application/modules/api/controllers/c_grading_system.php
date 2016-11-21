<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_grading_system extends API_Controller{

	public function __construct() {
        parent::__construct();
        $this->load->model("m_grading_system");
        $this->load->model("m_change_log");
    }

    public function retrieveStudents(){ // retriveOptions 0 = class list, 1 = with grades
    	$response = $this->generateResponse();
    	$result["grades"] = array();
        if($this->input->post("section_ID") != 0 || $this->input->post("type_ID") == 2){
            $result["boys"] = $this->m_grading_system->retrieveStudents(
                    $this->input->post("ID"), 
                    $this->input->post("type_ID"),
                    $this->input->post("subject_ID"),
                    $this->input->post("section_ID"),
                    $this->input->post("account_ID"),
                    1,
                    $this->input->post("school_year")
                    );
            $result["girls"] = $this->m_grading_system->retrieveStudents(
                    $this->input->post("ID"), 
                    $this->input->post("type_ID"),
                    $this->input->post("subject_ID"),
                    $this->input->post("section_ID"),
                    $this->input->post("account_ID"),
                    2,
                    $this->input->post("school_year")
                    );
            if($this->input->post("retrieveOptions")){
                for($x = 0; $x < count($result["boys"]); $x++){
                    $result["grades"][$result["boys"][$x]["account_ID"]] = $this->m_grading_system->retrieveStudentGrades(
                        $result["boys"][$x]["account_ID"],
                        $this->input->post("subject_ID"),
                        $this->input->post("school_year"),
                        $this->input->post("grading")*1
                    );
                }
                for($x = 0; $x < count($result["girls"]); $x++){
                    $result["grades"][$result["girls"][$x]["account_ID"]] = $this->m_grading_system->retrieveStudentGrades(
                        $result["girls"][$x]["account_ID"],
                        $this->input->post("subject_ID"),
                        $this->input->post("school_year"),
                        $this->input->post("grading")*1
                    );
                }
            }
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

    public function insertSubjectScore(){
    	$response = $this->generateResponse();
    	if($this->input->post()){
    		$this->load->library("form_validation");
    		$this->form_validation->set_rules("account_ID", "Account ID", "required");
    		$this->form_validation->set_rules("component_ID", "Component ID", "required");
    		$this->form_validation->set_rules("new_value", "Score", "required");
    		if($this->form_validation->run()){
    			$data = array(
    				"account_ID" 	=> $this->input->post("account_ID"),
    				"component_ID"	=> $this->input->post("component_ID"),
                    "grading"       => $this->input->post("grading"),
    				"score"			=> $this->input->post("new_value"),
    				"school_year"	=> $this->input->post("school_year")
    			);
    			$result = $this->m_grading_system->insertSubjectScore($data);
    			if($result){
    				/*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                                "message" => "Inserted score of ".$this->input->post("account_ID")." on subject_component_score",
                                "associated_ID" =>$result
                            )));*/
    				$response["data"] = $result;
    			}else{
    				$response["error"][] = array(
	                  "status" => 3,
	                  "message" => "Cannot Insert"
	                );
    			}
    		}else{
    			$response["error"][] = array(
                  "status" => 2,
                  "message" => validation_errors()
                );
    		}
    	}else{
    		$response["error"][] = array(
                  "status" => 1,
                  "message" => "Wrong Method"
                );
    	}
    	echo json_encode($response);

    }

    public function updateSubjectScore(){
    	$response = $this->generateResponse();
    	if($this->input->post()){
    		$this->load->library("form_validation");
    		$this->form_validation->set_rules("account_ID", "Account ID", "required");
    		$this->form_validation->set_rules("component_ID", "Component ID", "required");
    		$this->form_validation->set_rules("new_value", "Score", "required");
    		if($this->form_validation->run()){
    			$data = array(
    				"account_ID" 	=> $this->input->post("account_ID"),
    				"component_ID"	=> $this->input->post("component_ID"),
                    "grading"       => $this->input->post("grading"),
    				"score"			=> $this->input->post("new_value"),
    				"school_year"	=> $this->input->post("school_year")
    			);
    			$result = $this->m_grading_system->updateSubjectScore($data);
    			if($result){
							/*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                                "message" => "Inserted score of ".$this->input->post("account_ID")." on subject_component_score",
                                "associated_ID" =>$result
                            )));*/
    				$response["data"] = $result;
    			}else{
					$response["error"][] = array(
	                  "status" => 3,
	                  "message" => "Cannot update"
	                );
    			}
    		}else{
    			$response["error"][] = array(
                  "status" => 2,
                  "message" => validation_errors()
                );
    		}
    	}else{
    		$response["error"][] = array(
                  "status" => 1,
                  "message" => "Wrong Method"
                );
    	}
    	echo json_encode($response);	
    }

    public function insertSubjectGrade(){
    	$response = $this->generateResponse();
    	if($this->input->post()){
    		$this->load->library("form_validation");
    		$this->form_validation->set_rules("account_ID", "Account ID", "required");
    		$this->form_validation->set_rules("subject_ID", "Subject ID", "required");
    		$this->form_validation->set_rules("initial_grade", "Initial Grade", "Grade");
    		if($this->form_validation->run()){
    			$transmutedGrade = $this->m_grading_system->convertGrade($this->input->post("initial_grade"));
    			$data = array(
    				"account_ID" 		=> $this->input->post("account_ID"),
    				"subject_ID"		=> $this->input->post("subject_ID"),
    				"grading"			=> $this->input->post("grading"),
    				"initial_grade" 	=> $this->input->post("initial_grade"),
    				"transmuted_grade"	=> $transmutedGrade,
    				"school_year"		=> $this->input->post("school_year")
    			);
    			$result = $this->m_grading_system->insertSubjectGrade($data);
    			if($result){
    				/*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                                "message" => "Inserted score of ".$this->input->post("account_ID")." on subject_component_score",
                                "associated_ID" =>$result
                            )));*/
    				$response["data"] = $data["transmuted_grade"];
    			}else{
    				$response["error"][] = array(
	                  "status" => 3,
	                  "message" => "Cannot Insert"
	                );
    			}
    		}else{
    			$response["error"][] = array(
                  "status" => 2,
                  "message" => validation_errors()
                );
    		}
    	}else{
    		$response["error"][] = array(
                  "status" => 1,
                  "message" => "Wrong Method"
                );
    	}
    	echo json_encode($response);

    }

    public function updateSubjectGrade(){
    	$response = $this->generateResponse();
    	if($this->input->post()){
    		$this->load->library("form_validation");
    		$this->form_validation->set_rules("account_ID", "Account ID", "required");
    		$this->form_validation->set_rules("subject_ID", "Subject ID", "required");
    		$this->form_validation->set_rules("grading", "Grading", "required");
    		$this->form_validation->set_rules("initial_grade", "Initial Grade", "Grade");
    		if($this->form_validation->run()){
    			$transmutedGrade = $this->m_grading_system->convertGrade($this->input->post("initial_grade"));
    			$data = array(
    				"account_ID" 		=> $this->input->post("account_ID"),
    				"subject_ID"		=> $this->input->post("subject_ID"),
    				"grading"			=> $this->input->post("grading"),
    				"initial_grade" 	=> $this->input->post("initial_grade"),
    				"transmuted_grade"	=> $transmutedGrade,
    				"school_year"		=> $this->input->post("school_year")
    			);
                ($this->input->post("transmuted_grade"))? $data["transmuted_grade"] = $this->input->post("transmuted_grade") : "";
    			$result = $this->m_grading_system->updateSubjectGrade($data);
    			if($result){
							/*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                                "message" => "Inserted score of ".$this->input->post("account_ID")." on subject_component_score",
                                "associated_ID" =>$result
                            )));*/
    				$response["data"] = $data["transmuted_grade"];
    			}else{
					$response["error"][] = array(
	                  "status" => 3,
	                  "message" => "Cannot update"
	                );
    			}
    		}else{
    			$response["error"][] = array(
                  "status" => 2,
                  "message" => validation_errors()
                );
    		}
    	}else{
    		$response["error"][] = array(
                  "status" => 1,
                  "message" => "Wrong Method"
                );
    	}
    	echo json_encode($response);	
    }

    public function convertGrade(){
        if($this->input->post()){
            $response["data"] = $this->m_grading_system->convertGrade($this->input->post("grade"));
        }else{
            $response["error"][] = array(
              "status" => 109,
              "message" => "Cannot convert"
            );
        }
    }

    public function retrieveStudentGrade(){
        $response = $this->generateResponse();
        $this->load->library("form_validation");
        $this->form_validation->set_rules("account_id", "Account ID", "required");
        $this->form_validation->set_rules("grading", "Grading Period", "required");
        $this->form_validation->set_rules("school_year", "School Year", "required");
        if($this->form_validation->run()){
            $result = $this->m_grading_system->retrieveStudentGrade(
                $this->input->post("account_id"),
                $this->input->post("grading"),
                $this->input->post("school_year")
            );
            if($result){
                $response["data"] = $result;
            }else{
                $response["error"][] = array(
                      "status" => 3,
                      "message" => "No result"
                    );
            }
        }else{
            $response["error"][] = array(
                  "status" => 2,
                  "message" => validation_errors()
                );
        }
        echo json_encode($response);
    }
}