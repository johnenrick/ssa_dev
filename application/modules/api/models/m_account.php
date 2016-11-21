<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_account  extends CI_Model{
    function createAccount($username, $password , $accountTypeID, $status = 1){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "username" => $username,
            "password" => md5($password),
            "account_type_ID" => $accountTypeID,
            "status" => $status
        );
        $this->db->insert("account", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAccount($retrieveType = NULL, $limit = NULL, $offset = 0, $ID = NULL, $username = NULL, $password = NULL, $accountTypeID = NULL, $status = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("account.ID");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["account.ID"] = $ID : null;
            ($username) ? $condition["account.username"] = $username : null;
            ($password) ? $condition["account.password"] = md5($password) : null;
            ($accountTypeID) ? $condition["account.account_type_ID"] = $accountTypeID : null;
            ($status) ? $condition["account.status"] = $status : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["account.ID"] = $ID : null;
            ($username) ? $likeCondition["account.username"] = $username : null;
            ($password) ? $likeCondition["account.password"] = md5($password) : null;
            ($accountTypeID) ? $likeCondition["account.account_type_ID"] = $accountTypeID : null;
            ($status) ? $likeCondition["account.status"] = $status : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("account");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            
            return (( $ID || ($username && $password) ) && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function updateAccount($ID, $username = NULL, $password = NULL, $accountTypeID = NULL, $status = NULL ){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($username != NULL) ? $newData["username"] = $username : null;
        ($password != NULL) ? $newData["password"] = md5($password) : null;
        ($accountTypeID != NULL) ? $newData["account_type_ID"] = $accountTypeID : null;
        ($status != NULL) ? $newData["status"] = $status : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("account", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function deleteAccount($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("account");
        $this->db->flush_cache();
        $this->db->stop_cache();
        $this->db->where("account_ID", $ID);
        $this->db->delete("account_basic_information");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    /***ACCOUNT BASIC INFORMATION***/
    function createAccountBasicInformation($accountID, $identificationNumber = NULL, $firstName = NULL, $middleName = NULL, $lastName = NULL, $gender = NULL, $birthDatetime = NULL, $birthPlace = NULL, $religionMaintainableID = NULL, $nationalityMaintainableID = NULL, $imageFileUploadedID = 0){ 
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "account_ID" => $accountID,
            "identification_number" => $identificationNumber,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "gender" => $gender,
            "birth_datetime" => $birthDatetime,
            "birth_place" => $birthPlace,
            "religion_maintainable_ID" => $religionMaintainableID,
            "nationality_maintainable_ID" => $nationalityMaintainableID,
            "image_file_uploaded_ID" => $imageFileUploadedID,
            
        );
        if(!$identificationNumber){
            $data["identification_number"] = date("yn").sprintf('%05d', $accountID);
        }
        $this->db->insert("account_basic_information", $data);
        $ID = $this->db->insert_id();   
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAccountBasicInformation($retrieveType = false, $limit = false, $offset = 0, $ID = NULL, $accountID = NULL, $identificationNumber = NULL, $firstName = NULL, $middleName = NULL, $lastName = NULL, $gender = NULL, $birthDatetime = NULL, $birthPlace = NULL, $religionMaintainableID = NULL, $nationalityMaintainableID = NULL, $imageFileUploadedID = NULL, $accountTypeID  = NULL, $sort = NULL, $inAccountTypeID = NULL, $withAccountClassSection = NULL, $fullName = NULL, $gradeLevel = NULL, $classSectionDescription = NULL){ //retrieveType: 0 - normal, 1 - search
        $condition = array();
        $inCondition = array();
        $likeCondition = array();
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("account_basic_information.*, account.ID, account.account_type_ID, account_type.*");
        $this->db->select("account_basic_information.ID, account_basic_information.account_ID, account_type.description AS account_type_description");
        $this->db->join("account", "account.ID = account_basic_information.account_ID", "left");
        $this->db->join("account_type", "account_type.ID = account.account_type_ID", "left");
        
        if($withAccountClassSection != NULL){
            $this->db->select("section.year_level AS section_year_level, class_section.ID AS class_section_ID , section.ID AS section_ID,section.description AS secion_description, course.code AS course_code");
            $this->db->join("class_section", "class_section.account_ID = account.ID AND class_section.school_year='".$withAccountClassSection."'", "left");
            $this->db->join("section", "section.ID = class_section.section_ID", "left");
            $this->db->join("course", "course.ID = section.course_ID", "left");
            ($gradeLevel != NULL) ? $condition["section.year_level"] = $gradeLevel : null;
            ($classSectionDescription != NULL) ? $likeCondition["section.description"] = $classSectionDescription : null;
            
        }
        
        
        if(!$retrieveType){
            ($ID) ? $condition["account_basic_information.ID"] = $ID : null;
            ($identificationNumber) ? $condition["account_basic_information.identification_number"] = $identificationNumber : null;
            ($accountID) ? $condition["account_basic_information.account_ID"] = $accountID : null;
            ($firstName) ? $condition["account_basic_information.first_name"] = $firstName : null;
            ($middleName) ? $condition["account_basic_information.middle_name"] = $middleName : null;
            ($lastName) ? $condition["account_basic_information.last_name"] = $lastName : null;
            ($gender) ? $condition["account_basic_information.gender"] = $gender : null;
            ($birthDatetime) ? $condition["account_basic_information.birth_datetime"] = $birthDatetime : null;
            ($birthPlace) ? $condition["account_basic_information.birth_place"] = $birthPlace : null;
            ($religionMaintainableID) ? $condition["account_basic_information.religion_maintainable_ID"] = $religionMaintainableID : null;
            ($nationalityMaintainableID) ? $condition["account_basic_information.nationality_maintainable_ID"] = $nationalityMaintainableID : null;
            ($accountTypeID) ? $condition["account.account_type_ID"] = $accountTypeID : null;
            ($imageFileUploadedID) ? $condition["account_basic_information.image_file_uploaded_ID"] = $imageFileUploadedID : null;
        }else{
            ($ID) ? $likeCondition["account_basic_information.ID"] = $ID : null;
            ($identificationNumber) ? $likeCondition["account_basic_information.identification_number"] = $identificationNumber : null;
            ($firstName) ? $likeCondition["account_basic_information.first_name"] = $firstName : null;
            ($middleName) ? $likeCondition["account_basic_information.middle_name"] = $middleName : null;
            ($lastName) ? $likeCondition["account_basic_information.last_name"] = $lastName : null;
            ($gender) ? $likeCondition["account_basic_information.gender"] = $gender : null;
            ($birthDatetime) ? $likeCondition["account_basic_information.birth_datetime"] = $birthDatetime : null;
            ($birthPlace) ? $likeCondition["account_basic_information.birth_place"] = $birthPlace : null;
            ($religionMaintainableID) ? $likeCondition["account_basic_information.religion_maintainable_ID"] = $religionMaintainableID : null;
            ($nationalityMaintainableID) ? $likeCondition["account_basic_information.nationality_maintainable_ID"] = $nationalityMaintainableID : null;
            ($accountTypeID) ? $condition["account.account_type_ID"] = $accountTypeID : null;
            ($imageFileUploadedID) ? $likeCondition["account_basic_information.image_file_uploaded_ID"] = $imageFileUploadedID : null;
        }
        if($fullName !== NULL){
            $fullName = str_replace(",", "", $fullName);
            $names = explode(" ",$fullName);
            foreach($names as $values){
                $this->db->like("concat(`first_name`,`last_name`,`middle_name`)", $values);
            }
            
        }
        ($inAccountTypeID != NULL) ? $this->db->where_in("account.account_type_ID", $inAccountTypeID) : null;
        (count($inCondition) > 0) ? $this->db->where_in($inCondition) : null;
        (count($condition) > 0) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        if($sort){
            foreach($sort as $key => $value){
                $tableColumn = explode("__", $key);
                if(count($tableColumn) > 1){
                    $key = $tableColumn[0].".".$tableColumn[1];
                }
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }else{
            $this->db->order_by("account_basic_information.last_name", "asc");
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("account_basic_information");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return (($ID || $accountID || $identificationNumber) && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function updateAccountBasicInformation($ID = NULL, $accountID = NULL, $identificationNumber = NULL, $firstName = NULL, $middleName = NULL, $lastName = NULL, $gender = NULL, $birthDatetime = NULL, $birthPlace = NULL, $religionMaintainableID = NULL, $nationalityMaintainableID = NULL, $imageFileUploadedID = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($firstName) ? $newData["first_name"] = $firstName : null;
        ($middleName) ? $newData["middle_name"] = $middleName : null;
        ($lastName) ? $newData["last_name"] = $lastName : null;
        ($gender) ? $newData["gender"] = $gender : null;
        ($birthDatetime) ? $newData["birth_datetime"] = $birthDatetime : null;
        ($birthPlace) ? $newData["birth_place"] = $birthPlace : null;
        ($religionMaintainableID) ? $newData["religion_maintainable_ID"] = $religionMaintainableID : null;
        ($nationalityMaintainableID) ? $newData["nationality_maintainable_ID"] = $nationalityMaintainableID : null;
        ($imageFileUploadedID) ? $newData["image_file_uploaded_ID"] = $imageFileUploadedID : null;
        $condition = array();
        ($ID) ? $condition["ID"] = $ID : null;
        ($accountID) ? $condition["account_ID"] = $accountID : null;
        ($identificationNumber) ? $condition["identification_number"] = $identificationNumber : null;
        $result = NULL;
        if(count($condition)){
            $this->db->where($condition);
            $result = $this->db->update("account_basic_information", $newData);
        }
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    /**Account Guardian***/
    function createAccountGuardian($accountID = NULL, $firstName = NULL, $middleName = NULL, $lastName = NULL, $relationship = NULL, $address = NULL, $contactNumber = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "account_ID" => $accountID,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "last_name" => $lastName,
            "relationship" => $relationship,
			"address" => $address,
            "contact_number" => $contactNumber
        );
        $this->db->insert("account_guardian", $data);
        $ID = $this->db->insert_id();   
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAccountGuardian($retrieveType = NULL, $limit = NULL, $offset = 0, $ID = NULL, $accountID = NULL, $firstName = NULL, $middleName = NULL, $lastName = NULL, $relationship = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("account_guardian.ID");
        $this->db->join("account", "account.ID = account_guardian.account_ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["account_guardian.ID"] = $ID : null;
            ($accountID) ? $condition["account_guardian.account_ID"] = $accountID : null;
            ($firstName) ? $condition["account_guardian.first_name"] = $firstName : null;
            ($middleName) ? $condition["account_guardian.middle_name"] = $middleName : null;
            ($lastName) ? $condition["account_guardian.last_name"] = $lastName : null;
            ($relationship) ? $condition["account_guardian.relationship"] = $relationship : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["account_guardian.ID"] = $ID : null;
            ($accountID) ? $likeCondition["account_guardian.account_ID"] = $accountID : null;
            ($firstName) ? $likeCondition["account_guardian.first_name"] = $firstName : null;
            ($middleName) ? $likeCondition["account_guardian.middle_name"] = $middleName : null;
            ($lastName) ? $likeCondition["account_guardian.last_name"] = $lastName : null;
            ($relationship) ? $likeCondition["account_guardian.relationship"] = $relationship : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("account_guardian");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return (($ID ) && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function updateAccountGuardian($ID = NULL, $accountID = NULL, $firstName = NULL, $middleName = NULL, $lastName = NULL, $relationship = NULL, $address = NULL, $contactNumber = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($accountID) ? $newData["account_ID"] = $accountID : null;
        ($firstName) ? $newData["first_name"] = $firstName : null;
        ($middleName) ? $newData["middle_name"] = $middleName : null;
        ($lastName) ? $newData["last_name"] = $lastName : null;
        ($relationship) ? $newData["relationship"] = $relationship : null;
		($address) ? $newData["address"] = $address : null;
        ($contactNumber) ? $newData["contact_number"] = $contactNumber : null;
        $condition = array();
        ($ID) ? $condition["ID"] = $ID : null;
        $result = NULL;
        if(count($condition)){
            $this->db->where($condition);
            $result = $this->db->update("account_guardian", $newData);
        }
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function deleteAccountGuardian($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("account_guardian");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    public function getNewStudentIdentificatonNumber(){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->join("account", "account.ID = account_basic_information.account_ID", "left");
        $this->db->where("account_type_ID", 4);// 4 - student
        $this->db->order_by("account_basic_information.account_ID", "desc");
        $this->db->limit(1, 0);
        $result = $this->db->get("account_basic_information");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            $result = $result->row_array();
            return $result["identification_number"]+1;
        }else{
            return 15100001;
        }
        
    }
    
}
