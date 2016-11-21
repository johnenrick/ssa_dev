<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_account_school_history  extends CI_Model{
    
    /***ACCOUNT SCHOOL HISTORY***/
    function createAccountSchoolHistory($accountID = false, $schoolCampusMaintainableID = false , $datetime = false, $courseID = false, $yearLevel = false, $section = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "account_ID" => $accountID,
            "school_campus_maintainable_ID" => $schoolCampusMaintainableID,
            "datetime" => $datetime,
            "course_ID" => $courseID,
            "year_level" => $yearLevel,
            "section" =>$section
        );
        $this->db->insert("account_school_history", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    
    function retrieveAccountSchoolHistory($retrieveType = false, $limit = false, $offset = 0, $ID = false, $accountID = false, $schoolCampusMaintainableID = false, $datetime = false, $courseID = false, $yearLevel = false, $section = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("account_school_history.ID, SCHOOL_CAMPUS.description AS school_campus_maintainable_description, COURSE.description AS course_maintainable_description");
        $this->db->join("maintainable AS SCHOOL_CAMPUS", "SCHOOL_CAMPUS.ID = account_school_history.school_campus_maintainable_ID", "left");
        $this->db->join("maintainable AS COURSE", "COURSE.ID = account_school_history.course_ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["account_school_history.ID"] = $ID : null;
            ($accountID) ? $condition["account_school_history.account_ID"] = $accountID : null;
            ($schoolCampusMaintainableID) ? $condition["account_school_history.school_campus_maintainable_ID"] = $schoolCampusMaintainableID : null;
            ($datetime) ? $condition["account_school_history.datetime"] = $datetime : null;
            ($courseID) ? $condition["account_school_history.course_ID"] = $courseID : null;
            ($yearLevel) ? $condition["account_school_history.year_level"] = $yearLevel : null;
            ($section) ? $condition["account_school_history.section"] = $section : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["account_school_history.ID"] = $ID : null;
            ($accountID) ? $likeCondition["account_school_history.account_ID"] = $accountID : null;
            ($schoolCampusMaintainableID) ? $likeCondition["account_school_history.school_campus_maintainable_ID"] = $schoolCampusMaintainableID : null;
            ($datetime) ? $likeCondition["account_school_history.datetime"] = $datetime : null;
            ($courseID) ? $likeCondition["account_school_history.course_ID"] = $courseID : null;
            ($yearLevel) ? $likeCondition["account_school_history.year_level"] = $yearLevel : null;
            ($section) ? $likeCondition["account_school_history.section"] = $section : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("account_school_history");
        
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateAccountSchoolHistory($ID, $accountID = false, $schoolCampusMaintainableID = false, $datetime = false, $courseID = false, $yearLevel = false, $section = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($accountID) ? $newData["account_ID"] = $accountID : null;
        ($schoolCampusMaintainableID) ? $newData["school_campus_maintainable_ID"] = $schoolCampusMaintainableID : null;
        ($datetime) ? $newData["datetime"] = $datetime : null;
        ($courseID) ? $newData["course_ID"] = $courseID : null;
        ($yearLevel) ? $newData["year_level"] = $yearLevel : null;
        ($section) ? $newData["section"] = $section : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("account_school_history", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteAccountSchoolHistory($ID = false, $accountID = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $condition = array();
        ($ID) ? $newData["ID"] = $ID : null;
        ($accountID) ? $newData["account_ID"] = $accountID : null;
        $result = false;
        if(count($condition)){
            $this->db->where($condition);
            $result = $this->db->delete("account_school_history");
        }
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
