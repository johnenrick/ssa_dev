<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_schedule extends API_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("m_schedule");
		$this->load->model("m_change_log");
		date_default_timezone_set('Asia/Manila');
	}

	public function retrieveSchedule(){
        $response = $this->generateResponse();
        $result = $this->m_schedule->retrieveSchedule(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("room_ID"),
                $this->input->post("subject_ID"),
                $this->input->post('subject_type'),
                $this->input->post("teacher_ID"),
                $this->input->post("time_start"),
                $this->input->post("time_end"),
                $this->input->post("day"),
                $this->input->post("school_year"),
                $this->input->post("section_ID"),
                $this->input->post("year_level")
            );
        if($result){
            if($this->input->post("with_subject_schedule_component_hps")){
                $this->load->model("m_subject_schedule_component_hps");
                foreach($result as $key =>$value){
                    $result[$key]["subject_schedule_component_hps"] = $this->m_subject_schedule_component_hps->retrieveSubjectScheduleComponentHPS(false, false, 0, false, false, $value["ID"]);
                }
            }
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_schedule->retrieveSchedule(
                    $this->input->post("retrieve_type"), // 1 - search, 0 - match
                    0,
                    0, 
                    $this->input->post("ID"), 
                    $this->input->post("room_ID"),
	                $this->input->post("subject_ID"),
                    $this->input->post('subject_type'),
	                $this->input->post("teacher_ID"),
	                $this->input->post("time_start"),
	                $this->input->post("time_end"),
                	$this->input->post("day"),
	                $this->input->post("school_year"),
                    $this->input->post("section_ID"),
                    $this->input->post("year_level")
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

    public function createSchedule(){
    	$response = $this->generateResponse();
		if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library("form_validation");
            $this->form_validation->set_rules("course_ID", "Course ID", "required");
            $this->form_validation->set_rules("year_level", "Year Level", "required");
            $this->form_validation->set_rules("description", "Subject", "required");
            $this->form_validation->set_rules("subject_type", "Subject Type", "required");
            $this->form_validation->set_rules("time_start", "Time Start", "required");
            $this->form_validation->set_rules("time_end", "Time End", "required");
            $this->form_validation->set_rules("daysofweek[]", "Days of Week", "required");
            $this->form_validation->set_rules("room_ID", "Room ID", "required");
            $this->form_validation->set_rules("school_year", "School Year", "required");
            if($this->form_validation->run()){
            	$totaldayscount = 0;
            	$timestart = strtotime(("2015-06-01 ").$this->input->post("time_start"));
            	$timeend   = strtotime(("2015-06-01 ").$this->input->post("time_end"));
            	if(($timeend <= $timestart) || (($timeend - $timestart) < 1800)){
            		$response["error"][] = array(
	                  "status"	=> 2,
	                  "message"	=> "<p>Time End should be atleast 30 minutes after Time Start</p>"
	                );
            	}else{
            		//$roomSchedule = $this->m_schedule->retrieveSchedule(0, 0, 0, 0, $this->input->post("room_ID"), 0, 0, 0, 0, 0, $this->input->post("school_year"));
            		for($x = 0; $x < count($this->input->post("daysofweek")); $x++){
            			$totaldayscount += $this->input->post("daysofweek")[$x];
            		}
            		$result = $this->m_schedule->createSchedule(
            				$this->input->post("room_ID"),
            				$this->input->post("description"),	
                            $this->input->post("subject_type"),	            		
		            		$timestart,
		            		$timeend,
		            		$totaldayscount,
		            		$this->input->post("school_year")
		            	);
            		if($result){
                        /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                            "message"       => "Create Schedulet",
                            "associated_ID" =>$result
                        )));*/
                        $response["data"] = $result;
                    }else{
                        $response["error"][] = array(
                            "status"    => 3,
                            "message"   => "Failed to create Schedule"
                        );
                    }
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

    public function updateSchedule(){
    	$response = $this->generateResponse();
		if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library("form_validation");
            $this->form_validation->set_rules("ID", "Schedule ID", "required");
            if($this->form_validation->run()){
            	$totaldayscount = 0;
            	$timestart = strtotime(("2015-06-01 ").$this->input->post("time_start"));
            	$timeend   = strtotime(("2015-06-01 ").$this->input->post("time_end"));
            	if(($timeend <= $timestart) || (($timeend - $timestart) < 1800)){
            		$response["error"][] = array(
	                  "status"	=> 2,
	                  "message"	=> "<p>Time End should be atleast 30 minutes after Time Start</p>"
	                );
            	}else{
            		//$roomSchedule = $this->m_schedule->retrieveSchedule(0, 0, 0, 0, $this->input->post("room_ID"), 0, 0, 0, 0, 0, $this->input->post("school_year"));
            		for($x = 0; $x < count($this->input->post("daysofweek")); $x++){
            			$totaldayscount += $this->input->post("daysofweek")[$x];
            		}
            		$result = $this->m_schedule->updateSchedule(
            				$this->input->post("ID"),
            				$this->input->post("room_ID"),
            				$this->input->post("description"),	
                            $this->input->post("subject_type"), 
            				$this->input->post("teacher_ID"),	            		
		            		$timestart,
		            		$timeend,
		            		$totaldayscount,
		            		$this->input->post("school_year"),
                            $this->input->post("section_ID")
		            	);
            		if($result){
                        /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                            "message"       => "Create Schedulet",
                            "associated_ID" =>$result
                        )));*/
                        $response["data"] = $result;
                    }else{
                        $response["error"][] = array(
                            "status"    => 3,
                            "message"   => "Failed to update Schedule"
                        );
                    }
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

    public function deleteSchedule(){
    	$response = $this->generateResponse();
		if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
			$result = $this->m_schedule->deleteSchedule($this->input->post("ID"));
			if($result){
			    /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
			        "message" => "Delete Schedule",
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
	
    public function updateSectionSchedule(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library("form_validation");
            //$this->form_validation->set_rules('description', 'Subject ID', 'required');
            //if($this->form_validation->run()){
                $result = $this->m_schedule->updateSchedule(
                        $this->input->post("ID"),
                        $this->input->post("room_ID"),
                        $this->input->post("description"),  
                        $this->input->post("subject_type"), 
                        $this->input->post("teacher_ID"),                      
                        0,
                        0,
                        0,
                        $this->input->post("school_year"),
                        $this->input->post("section_ID")
                    );
                if($result){
                    /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                        "message"       => "Create Schedulet",
                        "associated_ID" =>$result
                    )));*/
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status"    => 3,
                        "message"   => "Failed to update Schedule"
                    );
                }
            //}
        }

        echo json_encode($response);
    }

    public function retrieveTeacherList(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_schedule->retrieveTeacherList();
            if($result){
                /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message"       => "Create Schedulet",
                    "associated_ID" =>$result
                )));*/
                $response["data"] = $result;
            }else{
                $response["error"][] = array(
                    "status"    => 3,
                    "message"   => "Failed to fetch teacher list"
                );
            }
        }
        echo json_encode($response);
    }

}