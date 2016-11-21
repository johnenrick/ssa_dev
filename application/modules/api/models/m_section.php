<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_section  extends CI_Model{
    
    function createSection($courseID, $yearLevel, $description, $adviser, $schoolYear){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "course_ID" => $courseID,
            "year_level" => $yearLevel,
            "description" => $description
        );
        $this->db->insert("section", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveSection($retrieveType = false, $limit = false, $offset = 0, $ID = false, $courseID = false, $yearLevel = false, $description = false, $adviser = NULL, $schoolYear = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("section.ID, section.description, course.description AS course_description, adviser_account_basic_information.first_name AS adviser_first_name, adviser_account_basic_information.middle_name AS adviser_middle_name, adviser_account_basic_information.last_name AS adviser_last_name");
        $this->db->join("course", "course.ID = section.course_ID", "left");
        $this->db->join("section_adviser", "section_adviser.section_ID = section.ID" .  ( $schoolYear ? " AND section_adviser.academic_year=".$schoolYear : ""), "left");
        $this->db->join("account_basic_information AS adviser_account_basic_information", "adviser_account_basic_information.account_ID=section_adviser.adviser_account_ID", "left");
        $condition = array();
        $likeCondition = array();
        ($adviser != NULL) ? $condition["section_adviser.adviser_account_ID"] = $adviser : null;
        if(!$retrieveType){
            ($ID) ? $condition["section.ID"] = $ID : null;
            ($courseID) ? $condition["section.course_ID"] = $courseID : null;
            ($yearLevel) ? $condition["section.year_level"] = $yearLevel : null;
            ($description) ? $condition["section.description"] = $description : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["section.ID"] = $ID : null;
            ($courseID) ? $likeCondition["section.course_ID"] = $courseID : null;
            ($yearLevel) ? $likeCondition["section.year_level"] = $yearLevel : null;
            ($description) ? $likeCondition["section.description"] = $description : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $this->db->order_by("section.course_ID", "asc");
        $this->db->order_by("section.year_level", "asc");
        $this->db->order_by("section.description", "asc");
        $this->db->group_by("section.ID");
        $result = $this->db->get("section");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateSection($ID, $courseID = false, $yearLevel = false, $description = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($courseID) ? $newData["course_ID"] = $courseID : null;
        ($yearLevel) ? $newData["year_level"] = $yearLevel : null;
        ($description) ? $newData["description"] = $description : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("section", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteSection($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("section");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
