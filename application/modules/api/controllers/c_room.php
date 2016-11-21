<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_room extends API_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model("m_room");
		$this->load->model("m_change_log");
	}

	public function createRoom(){
		$response = $this->generateResponse();
		if(!$this->checkACL(user_type(),$this->input->post("module_ID")) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('description', 'Room Name', 'required');
            $this->form_validation->set_rules('building_ID', 'Building ID', 'required');
            $this->form_validation->set_rules('capacity', 'Capacity', 'required');
            if($this->form_validation->run()){
                if(!$this->m_room->checkRoomName($this->input->post('description'), $this->input->post('building_ID'))){/* returns 0 if available, else returns 1 */
                    $result = $this->m_room->createRoom(
                        $this->input->post('building_ID'),
                        $this->input->post('description'),
                        $this->input->post('capacity')
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
                            "message"   => "Failed to create room"
                        );
                    }
                }else{
                    $response["error"][] = array(
                      "status"  => 2,
                      "message" => "<p>".$this->input->post("description")." in Building ".$this->input->post("building_ID")." Already Exist</p>"
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

	public function retrieveRoom(){
        $response = $this->generateResponse();
        $result = $this->m_room->retrieveRoom(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"), 
                $this->input->post("building_ID"),
                $this->input->post("description"),
                $this->input->post("capacity")
                );
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_room->retrieveRoom(
                    $this->input->post("retrieve_type"), // 1 - search, 0 - match
                    0,
                    0, 
                    $this->input->post("ID"), 
                    $this->input->post("building_ID"),
                    $this->input->post("description"),
                    $this->input->post("capacity")
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

    public function updateRoom(){
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
            
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'Room ID', 'required');
            if($this->form_validation->run()){
                $response["debug"][] = $this->input->post('ID');
                if(!$this->m_room->checkRoomName($this->input->post('description'), $this->input->post('building_ID'), $this->input->post('ID'))){/* returns 0 if available, else returns 1 */
                    $result = $this->m_room->updateRoom(
                        $this->input->post("ID"),
                        $this->input->post('building_ID'),
                        $this->input->post('description'),
                        $this->input->post('capacity')
                    );
                    if($result){
                        /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                            "message" => "Update Room",
                            "associated_ID" => $this->input->post("ID")
                        )));*/
                        $response["data"] = $this->input->post("ID");
                    }else{
                        $response["error"][] = array(
                            "status" => 3,
                            "message" => "Failed to update"
                        );
                    }
                }else{
                    $response["error"][] = array(
                      "status"  => 2,
                      "message" => "<p>".$this->input->post("description")." in Building ".$this->input->post("building_ID")." Already Exist</p>"
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
    public function deleteRoom(){
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_room->deleteRoom($this->input->post("ID"));
            if($result){
                /*$this->m_change_log->createChangeLog(4, user_id() , json_encode(array(
                    "message" => "Delete Room",
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