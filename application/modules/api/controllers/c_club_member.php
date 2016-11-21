<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_club_member extends API_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model("m_club_member");
        $this->load->model("m_change_log");
    }
    
    public function createClubMember(){
        $response = $this->generateResponse();
        if(!$this->checkACL(user_type(),1) && (API_Controller::STUDENT_MANAGEMENT)){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('schedule_ID', 'Schedule ID', 'required');
            $this->form_validation->set_rules('account_ID', 'Account ID', 'required');
            if($this->form_validation->run()){
                $result = $this->m_club_member->createClubMember(
                        $this->input->post("schedule_ID"),
                        $this->input->post("account_ID")
                        );
                
                if($result){
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create ClubMember"
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
    
    public function retrieveClubMember(){
        $response = $this->generateResponse();
        $result = $this->m_club_member->retrieveClubMember(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"), 
                $this->input->post("ID"),
                $this->input->post("schedule_ID"),
                $this->input->post("account_ID")
                );
        if($result){
            $response["data"] = $result;
             if($this->input->post("limit") != NULL){
                $response["result_count"] = count($result); /*count($this->m_club_member->retrieveClubMember(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0, 
                $this->input->post("ID"),
                $this->input->post("schedule_ID"),
                $this->input->post("account_ID")
                ));*/
             }
            
        }else{
            $response["error"][] = array(
                  "status" => 3,
                  "message" => "No result"
                );
        }
        echo json_encode($response);
    }
    public function updateClubMember(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){ //if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'ClubMember ID', 'required');
            $this->form_validation->set_rules('schedule_ID', 'Schedule ID', 'required');
//            if($this->input->post("description") != NULL){
//                $this->form_validation->set_rules('description', 'Description', 'required|is_unique[club_member.description]');
//            }
            if($this->form_validation->run()){
                $result = $this->m_club_member->updateClubMember(
                        $this->input->post("ID"),
                        $this->input->post("schedule_ID"),
                        $this->input->post("account_ID")
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
    public function deleteClubMember(){
        $response = $this->generateResponse();
        if(user_type() != 3 && user_type() != 5){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_club_member->deleteClubMember($this->input->post("schedule_ID"), $this->input->post("account_ID"));
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
