<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class c_account extends API_Controller {
    /*
     * Functions
     * 1    - createAccount
     * 2    - retrieveAccountBasicInformation
     * 4    - updateAccount
     * 8    - deleteAccount
     * 16   - uploadAccountImage
     * 32   - createAccountGuardian
     * 64   - updateAccountGuardian
     * 128  - retrieveAccountGuardian
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper("user_info");
        $this->load->model("m_account");
        $this->load->model("m_change_log");
        //sleep(5);
    }
    public function createAccount(){
        $accessNumber = 1;
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not registrar
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            //$this->form_validation->set_rules('username', 'Account Username', 'required|is_unique[account.username]');
           
            $this->form_validation->set_rules('account_type_ID', 'Account Type', 'required');
            /*Account Information*/
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
//            $this->form_validation->set_rules('middle_name', 'Middle Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
//            $this->form_validation->set_rules('gender', 'Gender', 'required');
//            $this->form_validation->set_rules('birth_datetime', 'Birth Date', 'required');
//            $this->form_validation->set_rules('birth_place', 'Birth Place', 'required');
//            $this->form_validation->set_rules('religion_maintainable_ID', 'Religion', 'required');
//            $this->form_validation->set_rules('nationality_maintainable_ID', 'Position', 'required');
            $newIdentificationNumber = ($this->input->post("identification_number")) ? $this->input->post("identification_number") : $this->m_account->getNewStudentIdentificatonNumber();
            if($this->input->post("identification_number")){
                $this->form_validation->set_rules('identification_number', 'ID NUmber', 'required|is_unique[account_basic_information.identification_number]');
            }
            if($this->form_validation->run()){
                $result = $this->m_account->createAccount(
                        $newIdentificationNumber, //$this->input->post("username"),
                        $newIdentificationNumber, //$this->input->post("password"),
                        $this->input->post("account_type_ID"),
                        1
                        );
                if($result){
                    $accountInformationID = $this->m_account->createAccountBasicInformation(
                            $result, 
                            $newIdentificationNumber,
                            $this->input->post("first_name"),
                            $this->input->post("middle_name"),
                            $this->input->post("last_name"),
                            $this->input->post("gender"),
                            $this->input->post("birth_datetime"),
                            $this->input->post("birth_place"),
                            $this->input->post("religion_maintainable_ID"),
                            $this->input->post("nationality_maintainable_ID"), 
                            0
                            );
                    $this->uploadAccountImage($accountInformationID, API_Controller::STUDENT_MANAGEMENT);
                    $this->m_change_log->createChangeLog( API_Controller::STUDENT_MANAGEMENT , user_id() , json_encode(array(
                        "message" => "Create Account",
                        "associated_ID" =>$result
                    )));
                    
                    $response["data"] = $result;
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Failed to create Account"
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
    public function retrieveAccountBasicInformation(){
        $accessNumber = 2;
        $response = $this->generateResponse();
        $result = $this->m_account->retrieveAccountBasicInformation(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("ID"),
                $this->input->post("account_ID"),
                $this->input->post("identification_number"),
                $this->input->post("first_name"),
                $this->input->post("middle_name"),
                $this->input->post("last_name"),
                $this->input->post("gender"),
                $this->input->post("birth_datetime"),
                $this->input->post("birth_place"),
                $this->input->post("religion_maintainable_ID"),
                $this->input->post("nationality_maintainable_ID"),
                false,
                $this->input->post("account_type_ID"),
                $this->input->post("sort"),
                $this->input->post("in_account_type_ID"),
                $this->input->post("with_class_section"),
                $this->input->post("full_name"),
                $this->input->post("grade_level"),
                $this->input->post("class_section_description")
                );
        if($this->input->post("limit")){
            $response["result_count"] = count($this->m_account->retrieveAccountBasicInformation(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                0,
                0,
                $this->input->post("ID"),
                $this->input->post("identification_number"),
                $this->input->post("account_ID"),
                $this->input->post("first_name"),
                $this->input->post("middle_name"),
                $this->input->post("last_name"),
                $this->input->post("gender"),
                $this->input->post("birth_datetime"),
                $this->input->post("birth_place"),
                $this->input->post("religion_maintainable_ID"),
                $this->input->post("nationality_maintainable_ID"),
                false,
                $this->input->post("account_type_ID"),
                $this->input->post("sort"),
                $this->input->post("in_account_type_ID"),
                $this->input->post("with_class_section"),
                $this->input->post("full_name"),
                $this->input->post("grade_level"),
                $this->input->post("class_section_description")
                ));
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
    public function updateAccount(){
        $accessNumber = 4;
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not registrar
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('account_ID', 'Account ID', 'required');
            if($this->form_validation->run()){
                $result = $this->m_account->updateAccount(
                        $this->input->post("account_ID"),
                        $this->input->post("username"),
                        $this->input->post("password"),
                        $this->input->post("account_type_ID")
                        );
                $this->m_account->updateAccountBasicInformation(
                        $this->input->post("account_basic_information_ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("identification_number"),
                        $this->input->post("first_name"), 
                        $this->input->post("middle_name"), 
                        $this->input->post("last_name"), 
                        $this->input->post("gender"),
                        $this->input->post("birth_datetime"),
                        $this->input->post("birth_place"),
                        $this->input->post("religion_maintainable_ID"),
                        $this->input->post("nationality_maintainable_ID")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(2, user_id() , json_encode(array(
                        "message" => "Update Account",
                        "associated_ID" =>$this->input->post("ID")
                    )));
                    //$this->uploadAccountImage($accountBasicInformation["ID"],1);
                    $response["data"] = $this->input->post("account_ID");
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
    
    public function deleteAccount(){
        $accessNumber = 8;
        $response = $this->generateResponse();
        if(user_type() != 3){ // if not admin
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $result = $this->m_account->deleteAccount($this->input->post("ID"));
            if($result){
                $this->m_change_log->createChangeLog(2, user_id() , json_encode(array(
                    "message" => "Deleted Account",
                    "associated_ID" =>$this->input->post("ID")
                )));
                $response["data"] = $this->input->post("ID");
            }else{
               $response["error"][] = array(
                    "status" => 1, 
                    "message" => "Failed to delete"
                    );
            }
        }
        echo json_encode($response);
    }
    /**Account School History***/
    public function uploadAccountImage($itemID, $moduleID){
        $accessNumber = 16;
        /*check authority*/
        $this->load->model("m_file_uploaded");
        $itemData = $this->m_account->retrieveAccountBasicInformation(0, 0, 0, $itemID);
        if ($itemData) {
           /*create path if the path does not exist*/
            if (is_dir("assets/file_uploaded/account/".$itemID) == false ) {
                umask(0000);
                mkdir("assets/file_uploaded/account/".$itemID, 0777, true);
                umask();
            }
            $config = array();
            $config['upload_path'] = "assets/file_uploaded/account/".$itemID;
            $config['allowed_account_type_IDs'] = 'gif|jpg|png|jpeg';
            $uploadPath = "file_uploaded/account/".$itemID."/";
            $this->load->library('upload', $config);
            $this->upload->do_upload();
            $photoinfo = $this->upload->data();
            if($photoinfo["file_name"] != ""){
                /*creating a thumb nail*/
                $this->load->library('image_lib');
                $config['image_library']    = 'gd2';
                $config['source_image']     = $photoinfo["full_path"];
                $config['maintain_ratio']   = FALSE;
                $config['width']            = 200;
                $config['height']           = 200;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                /*getting file uploaded if existed*/
                
                if($itemData["image_file_uploaded_ID"] != "0" || $itemData["image_file_uploaded_ID"] != ""){
                    $fileInformation = $this->m_file_uploaded->retrieveAccountBasicInformation(false, false, false, $itemData["image_file_uploaded_ID"]);
                    if($fileInformation && file_exists("assets/".$fileInformation["path"].$fileInformation["file_name"].$fileInformation["extension"])){
                        unlink("assets/".$fileInformation["path"].$fileInformation["file_name"].$fileInformation["extension"]);
                    }
                }
                $fileID = $this->m_file_uploaded->createFileUploaded($moduleID, $uploadPath, $photoinfo["raw_name"], $photoinfo["file_ext"]);
                $this->m_account->updateAccountBasicInformation($itemID, false, false, false, false, false,  false, false, false, false, $fileID );
                return $fileID;
            }else{
                return $this->upload->display_errors();
            }
        }else{
            return false;
        }       
    }
    public function createAccountGuardian(){
        $accessNumber = 32;
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not registrar
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('account_ID', 'Account ID', 'required');
            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required');
            $this->form_validation->set_rules('relationship', 'Gender', 'required');
            if($this->form_validation->run()){
               
                $accountInformationID = $this->m_account->createAccountGuardian(
                        $this->input->post("account_ID"),
                        $this->input->post("first_name"),
                        $this->input->post("middle_name"),
                        $this->input->post("last_name"),
                        $this->input->post("relationship"),
						$this->input->post("address"),
                        $this->input->post("contact_number")
                        );

                $this->m_change_log->createChangeLog( API_Controller::STUDENT_MANAGEMENT , user_id() , json_encode(array(
                    "message" => "Create Account Guardian",
                    "associated_ID" =>$accountInformationID
                )));

                $response["data"] = $accountInformationID;
            }else{
                $response["error"][] = array(
                  "status" => 2,
                  "message" => validation_errors()
                );
            }
        }
        echo json_encode($response);
    }
    public function updateAccountGuardian(){
        $accessNumber = 64;
        $response = $this->generateResponse();
        if(user_type() != 3){ //if not registrar
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('ID', 'ID', 'required');
            if($this->form_validation->run()){
                $result = $this->m_account->updateAccountGuardian(
                        $this->input->post("ID"),
                        $this->input->post("account_ID"),
                        $this->input->post("first_name"), 
                        $this->input->post("middle_name"), 
                        $this->input->post("last_name"), 
                        $this->input->post("relationship"),
						$this->input->post("address"), 
                        $this->input->post("contact_number")
                        );
                if($result){
                    $this->m_change_log->createChangeLog(2, user_id() , json_encode(array(
                        "message" => "Update Account",
                        "associated_ID" =>$this->input->post("ID")
                    )));
                    //$this->uploadAccountImage($accountBasicInformation["ID"],1);
                    $response["data"] = $this->input->post("account_ID");
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
    public function retrieveAccountGuardian(){
        $accessNumber = 128;
        $response = $this->generateResponse();
        $result = $this->m_account->retrieveAccountGuardian(
                $this->input->post("retrieve_type"), // 1 - search, 0 - match
                $this->input->post("limit"),
                $this->input->post("offset"),
                $this->input->post("ID"),
                $this->input->post("account_ID"),
                $this->input->post("first_name"),
                $this->input->post("middle_name"),
                $this->input->post("last_name"),
                $this->input->post("relationship")
                );
        
        if($result){
            $response["data"] = $result;
            if($this->input->post("limit")){
                $response["result_count"] = count($this->m_account->retrieveAccountGuardian(
                    $this->input->post("retrieve_type"), // 1 - search, 0 - match
                    0,
                    0,
                    $this->input->post("ID"),
                    $this->input->post("account_ID"),
                    $this->input->post("first_name"),
                    $this->input->post("middle_name"),
                    $this->input->post("last_name"),
                    $this->input->post("relationship")
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
    

    public function changePassword(){
        $accessNumber = 254;
        $response = $this->generateResponse();
        if(!user_id()){ //if not registrar
            $response["error"][] = array(
                "status" => 1, 
                "message" => "Not Authorized"
                );
        }else{
            $this->load->library('form_validation');
            $this->form_validation->set_rules('old_password', 'Old Password', 'required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required');
            if($this->form_validation->run()){
                if($this->m_account->retrieveAccount(NULL, NULL, 0, user_id(), NULL, $this->input->post("old_password"))){
                    $this->m_account->updateAccount(user_id(), NULL, $this->input->post("new_password"));
                    $response["data"] = user_id();
                }else{
                    $response["error"][] = array(
                        "status" => 3,
                        "message" => "Invalid Old Password"
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
}
