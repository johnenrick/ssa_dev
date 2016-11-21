<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_account_level  extends CI_Model{
    
    function createAccountLevel($accountID, $courseID, $yearLevel, $academicYear, $grade){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "account_ID" => $accountID,
            "course_ID" => $courseID,
            "year_level" => $yearLevel,
            "academic_year" => $academicYear,
            "grade" => $grade
        );
        $this->db->insert("account_level", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAccountLevel($retrieveType = false, $limit = false, $offset = 0, $ID = NULL, $accountID = NULL, $yearLevel = NULL, $academicYear = NULL, $grade = NULL, $courseID = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID != NULL) ? $condition["account_level.ID"] = $ID : null;
            ($accountID != NULL) ? $condition["account_level.account_ID"] = $accountID : null;
            ($yearLevel != NULL) ? $condition["account_level.year_level"] = $yearLevel : null;
            ($academicYear != NULL) ? $condition["account_level.academic_year"] = $academicYear : null;
            ($grade != NULL) ? $condition["account_level.grade"] = $grade : null;
            ($courseID != NULL) ? $condition["account_level.course_ID"] = $courseID : null;
            
            (count($condition) > 0 != NULL) ? $this->db->where($condition) : null;
        }else{
            ($ID != NULL) ? $likeCondition["account_level.ID"] = $ID : null;
            ($accountID != NULL) ? $likeCondition["account_level.account_ID"] = $accountID : null;
            ($yearLevel != NULL) ? $likeCondition["account_level.year_level"] = $yearLevel : null;
            ($academicYear != NULL) ? $likeCondition["account_level.academic_year"] = $academicYear : null;
            ($grade != NULL) ? $likeCondition["account_level.grade"] = $grade : null;
            ($courseID != NULL) ? $likeCondition["account_level.course_ID"] = $courseID : null;
            
            (count($likeCondition) > 0 != NULL) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("account_level");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType != NULL) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateAccountLevel($ID, $accountID = NULL, $yearLevel = NULL, $academicYear = NULL, $grade = NULL, $courseID = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($yearLevel != NULL) ? $newData["year_level"] = $yearLevel : null;
        ($grade != NULL) ? $newData["grade"] = $grade : null;
        ($courseID != NULL) ? $newData["course_ID"] = $courseID : null;
        $this->db->where("ID", $ID);
        ($accountID !== NULL) ? $this->db->where("account_ID", $ID) : null;
        ($academicYear !== NULL) ? $this->db->where("academic_year", $academicYear) : null;
        $result = $this->db->update("account_level", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteAccountLevel($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("account_level");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
