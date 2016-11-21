<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_course_annual_fee  extends CI_Model{
    
    function createCourseAnnualFee($type, $courseID, $yearLevel, $academicYear, $assessmentItemID, $description, $amount){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "type" => $type,
            "course_ID" => $courseID,
            "year_level" => $yearLevel,
            "academic_year" => $academicYear,
            "assessment_item_ID" => $assessmentItemID,
            "description" => $description,
            "amount" => $amount
        );
        $this->db->insert("course_annual_fee", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveCourseAnnualFee($retrieveType = false, $limit = false, $offset = 0, $sort = false, $ID = NULL, $type = NULL, $courseID = NULL, $yearLevel = NULL, $academicYear = NULL, $assessmentItemID = NULL, $description = NULL, $amount = NULL, $assessmentItemDescription = NULL, $assessmentTypeID = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("course_annual_fee.ID, course_annual_fee.description AS description, course.description AS course_description, assessment_item.description AS assessment_item_description, assessment_type.description AS assessment_type_description");
        $this->db->join("course", "course.ID = course_annual_fee.course_ID", "left");
        $this->db->join("assessment_item", "assessment_item.ID = course_annual_fee.assessment_item_ID", "left");
        $this->db->join("assessment_type", "assessment_type.ID = assessment_item.assessment_type_ID", "left");
        $condition = array();
        $likeCondition = array();
        if($yearLevel != NULL){
            if(is_array($yearLevel)){
                $this->db->where_in("course_annual_fee.year_level",$yearLevel);
            }else{
                $condition["course_annual_fee.year_level"] = $yearLevel;
            }
        }
        if(!$retrieveType){
            ($ID != NULL) ? $condition["course_annual_fee.ID"] = $ID : null;
            ($type != NULL) ? $condition["course_annual_fee.type"] = $type : null;
            ($courseID != NULL) ? $condition["course_annual_fee.course_ID"] = $courseID : null;
            
            ($academicYear != NULL) ? $condition["course_annual_fee.academic_year"] = $academicYear : null;
            ($assessmentItemID != NULL) ? $condition["course_annual_fee.assessment_item_ID"] = $assessmentItemID : null;
            ($description != NULL) ? $condition["course_annual_fee.description"] = $description : null;
            ($amount != NULL) ? $condition["course_annual_fee.amount"] = $amount : null;
            ($assessmentItemDescription != NULL) ? $condition["assessment_item.description"] = $assessmentItemDescription : null;
            ($assessmentTypeID != NULL) ? $condition["assessment_item.assessment_type_ID"] = $assessmentTypeID : null;
            
            
        }else{
            ($ID != NULL) ? $likeCondition["course_annual_fee.ID"] = $ID : null;
            ($type != NULL) ? $likeCondition["course_annual_fee.type"] = $type : null;
            ($courseID != NULL) ? $likeCondition["course_annual_fee.course_ID"] = $courseID : null;
            
            ($academicYear != NULL) ? $likeCondition["course_annual_fee.academic_year"] = $academicYear : null;
            ($assessmentItemID != NULL) ? $likeCondition["course_annual_fee.assessment_item_ID"] = $assessmentItemID : null;
            ($description != NULL) ? $likeCondition["course_annual_fee.description"] = $description : null;
            ($amount != NULL) ? $likeCondition["course_annual_fee.amount"] = $amount : null;
            
            ($assessmentItemDescription != NULL) ? $likeCondition["assessment_item.description"] = $assessmentItemDescription : null;
            ($assessmentTypeID != NULL) ? $likeCondition["assessment_item.assessment_type_ID"] = $assessmentTypeID : null;
        }
        (count($condition) > 0 != NULL) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0 != NULL) ? $this->db->like($likeCondition) : null;
        if($sort){
            foreach($sort as $key => $value){
                $key = ($key == "description") ? "course_annual_fee.description" : $key;
                $tableColumn = explode("__", $key);
                if(count($tableColumn) > 1){
                    $key = $tableColumn[0].".".$tableColumn[1];
                }
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
       
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("course_annual_fee");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateCourseAnnualFee($ID, $type = NULL, $courseID = NULL, $yearLevel = NULL, $academicYear = NULL, $assessmentItemID = NULL, $description = NULL, $amount = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($type != NULL) ? $newData["type"] = $type : null;
        ($courseID != NULL) ? $newData["course_ID"] = $courseID : null;
        ($yearLevel != NULL) ? $newData["year_level"] = $yearLevel : null;
        ($academicYear != NULL) ? $newData["academic_year"] = $academicYear : null;
        ($assessmentItemID != NULL) ? $newData["assessment_item_ID"] = $assessmentItemID : null;
        ($description != NULL) ? $newData["description"] = $description : null;
        ($amount != NULL) ? $newData["amount"] = $amount : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("course_annual_fee", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteCourseAnnualFee($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("course_annual_fee");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function retrieveAccountAdjustment($retrieveType = false, $limit = false, $offset = 0, $sort = false, $ID = NULL, $identificationNumber = NULL, $lastName = NULL, $firstName = NULL, $middleName = NULL, $courseAnnualFeeID = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("account_basic_information.identification_number, account_basic_information.account_ID, account_basic_information.first_name, account_basic_information.last_name, account_basic_information.middle_name");
        $this->db->select("course_annual_fee.ID, course_annual_fee_selected_account.course_annual_fee_ID, course_annual_fee.description AS course_annual_fee_description");
        $this->db->join("course_annual_fee_selected_account", "course_annual_fee_selected_account.account_ID = account_basic_information.account_ID AND course_annual_fee_selected_account.course_annual_fee_ID='".$courseAnnualFeeID."'", "left");
        $this->db->join("course_annual_fee", "course_annual_fee.ID=course_annual_fee_selected_account.course_annual_fee_ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID != NULL) ? $condition["account_basic_information.ID"] = $ID : null;
            ($identificationNumber != NULL) ? $condition["account_basic_information.identification_number"] = $identificationNumber : null;
            ($lastName != NULL) ? $condition["account_basic_information.last_name"] = $lastName : null;
            ($firstName != NULL) ? $condition["account_basic_information.first_name"] = $firstName : null;
            ($middleName != NULL) ? $condition["account_basic_information.middle_name"] = $middleName : null;
        }else{
            ($ID != NULL) ? $likeCondition["course_annual_fee.ID"] = $ID : null;
            ($identificationNumber != NULL) ? $likeCondition["account_basic_information.identification_number"] = $identificationNumber : null;
            ($lastName != NULL) ? $likeCondition["account_basic_information.last_name"] = $lastName : null;
            ($firstName != NULL) ? $likeCondition["account_basic_information.first_name"] = $firstName : null;
            ($middleName != NULL) ? $likeCondition["account_basic_information.middle_name"] = $middleName : null;
        }
        (count($condition) > 0 != NULL) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0 != NULL) ? $this->db->like($likeCondition) : null;
        if($sort){
            foreach($sort as $key => $value){
                $tableColumn = explode("__", $key);
                if(count($tableColumn) > 1){
                    $key = $tableColumn[0].".".$tableColumn[1];
                }
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        $this->db->group_by("account_basic_information.ID");
        $this->db->order_by("course_annual_fee.description", "asc");
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("account_basic_information");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function createCourseAnnualFeeSelectedAccount($courseAnnualFeeID, $accountID, $academicYear){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "course_annual_fee_ID" => $courseAnnualFeeID,
            "account_ID" => $accountID,
            "academic_year" => $academicYear
        );
        $this->db->insert("course_annual_fee_selected_account", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function deleteCourseAnnualFeeSelectedAccount($accountID, $courseAnnualFeeSelectedAccount){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("account_ID", $accountID);
        $this->db->where("course_annual_fee_ID", $courseAnnualFeeSelectedAccount);
        $result = $this->db->delete("course_annual_fee_selected_account");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function retrieveCourseAnnualFeeSelectedAccount($retrieveType = false, $limit = false, $offset = 0, $sort = false, $ID = NULL, $accountID = NULL, $courseAnnualFeeID = NULL, $academicYear = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("course_annual_fee_selected_account.ID, course_annual_fee.ID AS course_annual_fee_ID, course_annual_fee.description, assessment_type.description AS assessment_type_description, assessment_item.description AS assessment_item_description");
        $this->db->join("course_annual_fee", "course_annual_fee.ID=course_annual_fee_selected_account.course_annual_fee_ID", "left");
        $this->db->join("assessment_item", "assessment_item.ID = course_annual_fee.assessment_item_ID", "left");
        $this->db->join("assessment_type", "assessment_type.ID = assessment_item.assessment_type_ID", "left");
        $condition = array();
        $likeCondition = array();
        ($academicYear != NULL) ? $condition["course_annual_fee.academic_year"] = $academicYear : null;
        ($academicYear != NULL) ? $condition["course_annual_fee_selected_account.academic_year"] = $academicYear : null;
        if(!$retrieveType){
            ($ID != NULL) ? $condition["course_annual_fee_selected_account.ID"] = $ID : null;
            ($accountID != NULL) ? $condition["course_annual_fee_selected_account.account_ID"] = $accountID : null;
            ($courseAnnualFeeID != NULL) ? $condition["course_annual_fee_selected_account.course_annual_fee_ID"] = $courseAnnualFeeID : null;
        }else{
            ($ID != NULL) ? $likeCondition["course_annual_fee_selected_account.ID"] = $ID : null;
            ($accountID != NULL) ? $likeCondition["course_annual_fee_selected_account.account_ID"] = $accountID : null;
            ($courseAnnualFeeID != NULL) ? $likeCondition["course_annual_fee_selected_account.course_annual_fee_ID"] = $courseAnnualFeeID : null;
        }
        (count($condition) > 0 != NULL) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0 != NULL) ? $this->db->like($likeCondition) : null;
        if($sort){
            foreach($sort as $key => $value){
                $tableColumn = explode("__", $key);
                if(count($tableColumn) > 1){
                    $key = $tableColumn[0].".".$tableColumn[1];
                }
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("course_annual_fee_selected_account");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
}
