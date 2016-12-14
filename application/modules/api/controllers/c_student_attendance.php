<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_student_attendance extends API_Controller{

	public function __construct() {
        parent::__construct();
        $this->load->model("m_student_attendance");
        $this->load->model("m_change_log");
    }

    public function createStudentAttendance(){
    	$response = $this->generateResponse();
    	if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library("form_validation");
            $this->form_validation->set_rules("account_ID", "Account ID", "required");
            if($this->form_validation->run()){
                
            	$result = $this->m_student_attendance->createStudentAttendance(
            		$this->input->post("account_ID"),
            		$this->input->post("section_ID"),
            		$this->input->post("month"),
            		$this->input->post("present"),
            		$this->input->post("late"),
            		$this->input->post("school_year")
            	);
            	if($result){
                    /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message"       => "Create Room",
                        "associated_ID" =>$result
                    )));*/
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status"    => 3,
                        "message"   => "Failed to create student attenance"
                    );
                }
            }else{
            	$response["error"][] = array(
                  "status"	=> 2,
                  "message"	=> validation_errors()
                );
            }
        }
    	echo json_encode($response);
    }

    public function retrieveStudentAttendance(){
    	$response = $this->generateResponse();
            $result = $this->m_student_attendance->retrieveStudentAttendance(
                    $this->input->post("account_ID"),
                    $this->input->post("section_ID"),
                    $this->input->post("month"),
                    $this->input->post("present"),
                    $this->input->post("late"),
                    $this->input->post("school_year")
            );
        if($result){
            $response["data"] = $result;
            $response["result_count"] = count($result);
        }else{
            $response["error"][] = array(
                  "status" => 3,
                  "message" => "No result"
                );
        }
    	echo json_encode($response);
    }

    public function updateStudentAttendance(){
    	$response = $this->generateResponse();
    	if($this->input->post()){
    		$this->load->library("form_validation");
//    		if($this->form_validation->run()){
    			$result = $this->m_student_attendance->updateStudentAttendance(
    				$this->input->post("ID"),
                                $this->input->post("account_ID"),
                                $this->input->post("section_ID"),
                                $this->input->post("month"),
                                $this->input->post("present"),
                                $this->input->post("late"),
                                $this->input->post("school_year")
                                );
                        $response["debug"][] = $this->input->post("ID");
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
//    		}else{
//    			$response["error"][] = array(
//                            "status" => 2,
//                            "message" => validation_errors()
//                        );
//    		}
    	}else{
    		$response["error"][] = array(
                  "status" => 1,
                  "message" => "Wrong Method"
                );
    	}
    	echo json_encode($response);
    }
}