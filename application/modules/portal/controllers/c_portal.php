<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_portal extends FE_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("api/m_account");
        $this->load->model("api/m_change_log");

        //sleep(5);
    }
    public function test(){
        echo time();
    }
    public function logInAccount(){
        $response = $this->generateResponse();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Account Username', 'required');
        $this->form_validation->set_rules('password', 'Account Password', 'required');

        if($this->form_validation->run()){
            $result = $this->m_account->retrieveAccount(false, false, 0, false, $this->input->post("username"), $this->input->post("password"));
            if($result){
                $accountInformation = $this->m_account->retrieveAccountBasicInformation(false, false, false, false, $result["ID"]);
                $this->session->set_userdata("user_type", $result["account_type_ID"]);
                $this->session->set_userdata("user_ID", $result["ID"]);
                $this->session->set_userdata("first_name", $accountInformation["first_name"]);
                $this->session->set_userdata("last_name", $accountInformation["last_name"]);
                $this->session->set_userdata("middle_name", $accountInformation["middle_name"]);
                if(user_type() == 3){
                    $response["data"] = base_url()."registrar/c_registrar/studentAccountManagement";
                }else if(user_type() == 5){
                    $response["data"] = base_url()."teller/c_teller/tellerTransaction";
                }else if(user_type() == 6){
                    $response["data"] = base_url()."subject/c_subject/classList";
                }else if(user_type() == 8){
                    $response["data"] = base_url()."teller/c_teller/fixTransaction";
                }else if(user_type() == 9){
                    $response["data"] = base_url()."guidance/c_guidance/portalReport";
                }else{
                    $response["data"] = base_url();
                }
            }else{
                $response["error"][] = array(
                    "status" => 2,
                    "message" => "Username and Password doesn't match!"
                  );
            }
        }else{
            $response["error"][] = array(
              "status" => 1,
              "message" => validation_errors()
            );
        }
        echo json_encode($response);
    }
    public function logOutAccount(){
        $response = $this->generateResponse();
        $this->session->set_userdata(array("user_type" => 0, "user_ID" => 0));
        $response["data"] = true;
        header("Location: ".base_url());
    }
    public function index(){

        $this->loadPage("portal/landing_page", "landing_page_script", $data = array());
    }

    public function portal(){
            $this->loadPage("portal/portal", "portal/portal_script", false);
    }
    public function studentInformation(){
            $this->loadPage("portal/student_information", "portal/student_information_script", false);
    }
    public function registration(){
        if(user_type() == 3 || user_type() == 7){
            $this->loadPage("portal/registration", "portal/registration_script", false);
        }else{
            header("Location: ".base_url());
        }
    }

}
