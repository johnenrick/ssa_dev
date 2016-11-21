<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_class_deportment extends API_Controller{

	public function __construct() {
        parent::__construct();
        $this->load->model("m_class_deportment");
        $this->load->model("m_change_log");
    }

    public function createClassDeportment(){
    	$respone = $this->generateResponse();
    	if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
        	$this->load->library("form_validation");
            $this->form_validation->set_rules("account_ID", "Account ID", "required");
            if($this->form_validation->run()){
            	$result = $this->m_class_deportment->createClassDeportment(
            		$this->input->post("deportment_ID"),
            		$this->input->post("account_ID"),
            		$this->input->post("section_ID"),
            		$this->input->post("value"),
            		$this->input->post("equivalent"),
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

    public function retrieveClassDeportment(){
    	$respone = $this->generateResponse();
		$result = $this->m_class_deportment->retrieveClassDeportment(
                	$this->input->post("deportment_ID"),
            		$this->input->post("account_ID"),
            		$this->input->post("section_ID"),
            		$this->input->post("value"),
            		$this->input->post("equivalent"),
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
    	echo json_encode($response);
    }

    public function updateClassDeportment(){
    	$respone = $this->generateResponse();
    	if($this->input->post()){
    		$this->load->library("form_validation");
    		$this->form_validation->set_rules("account_ID", "Account ID", "required");
    		if($this->form_validation->run()){
    			$result = $this->m_class_deportment->updateClassDeportment(
    				$this->input->post("deportment_ID"),
            		$this->input->post("account_ID"),
            		$this->input->post("section_ID"),
            		$this->input->post("value"),
            		$this->input->post("equivalent"),
            		$this->input->post("school_year")
    			);
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

    public function retrieveClassDeportmentDescription(){
    	$respone = $this->generateResponse();
		$result = $this->m_class_deportment->retrieveClassDeportmentDescription();
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

}