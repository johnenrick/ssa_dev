<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_club  extends CI_Model{
    
    function createClub($subjectID, $accountID, $schoolYear){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "subject_ID" => $subjectID,
            "account_ID" => $accountID,
            "school_year" => $schoolYear
        );
        $this->db->insert("club", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveClub($retrieveType = false, $limit = false, $offset = 0, $ID = false, $subjectID = false, $accountID = false, $schoolYear = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("club.ID, club.school_year");
        $this->db->join("course", "course.ID = club.subject_ID", "left");
        $this->db->join("account_basic_information", "account_basic_information.account_ID = club.account_ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["club.ID"] = $ID : null;
            ($subjectID) ? $condition["club.subject_ID"] = $subjectID : null;
            ($accountID) ? $condition["club.account_ID"] = $accountID : null;
            ($schoolYear) ? $condition["club.school_year"] = $schoolYear : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["club.ID"] = $ID : null;
            ($subjectID) ? $likeCondition["club.subject_ID"] = $subjectID : null;
            ($accountID) ? $likeCondition["club.account_ID"] = $accountID : null;
            ($schoolYear) ? $likeCondition["club.school_year"] = $schoolYear : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $this->db->order_by("club.subject_ID", "asc");
        $this->db->order_by("club.account_ID", "asc");
        $result = $this->db->get("club");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function retrieveNoClub($limit = false, $offset = 0, $schoolYear = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("account_basic_information.account_ID, club.ID AS club_ID, club.school_year");
        //$this->db->join("course", "course.ID = club.subject_ID", "left");
        $this->db->join("club", "club.account_ID = account_basic_information.account_ID", "left");
        $this->db->join("account", "account.ID = account_basic_information.account_ID", "left");
        $condition = array();
        $condition["club.ID"] = NULL;
        ($schoolYear) ? $condition["club.school_year"] = $schoolYear : null;
        $condition["account.account_type_ID"] = 4;
        (count($condition) > 0) ? $this->db->where($condition, null) : null;
        
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("account_basic_information");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return  $result->result_array();
        }else{
            return false;
        }
    }
    function updateClub($ID, $subjectID = false, $accountID = false, $schoolYear = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($subjectID) ? $newData["subject_ID"] = $subjectID : null;
        ($accountID) ? $newData["account_ID"] = $accountID : null;
        ($schoolYear) ? $newData["school_year"] = $schoolYear : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("club", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteClub($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("club");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
    
}
