<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_section_adviser  extends CI_Model{
    
    function createSectionAdviser($sectionID, $adviserAccountID, $academicYear){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "section_ID" => $sectionID,
            "adviser_account_ID" => $adviserAccountID,
            "academic_year" => $academicYear
        );
        $this->db->insert("section_adviser", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveSectionAdviser($retrieveType = false, $limit = false, $offset = 0, $ID = false, $sectionID = false, $adviserAccountID = false, $academicYear = false, $adviser = false, $schoolYear = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("section_adviser.ID, section_adviser.academic_year, course.academic_year AS course_academic_year");
        $this->db->join("course", "course.ID = section_adviser.section_ID", "left");
        $this->db->join("section_adviser_adviser", "section_adviser_adviser.section_adviser_ID = section_adviser.ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["section_adviser.ID"] = $ID : null;
            ($sectionID) ? $condition["section_adviser.section_ID"] = $sectionID : null;
            ($adviserAccountID) ? $condition["section_adviser.adviser_account_ID"] = $adviserAccountID : null;
            ($academicYear) ? $condition["section_adviser.academic_year"] = $academicYear : null;
            ($adviser) ? $condition["section_adviser_adviser.adviser"] = $adviser : null;
            ($schoolYear) ? $condition["section_adviser_adviser.academic_year"] = $schoolYear : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["section_adviser.ID"] = $ID : null;
            ($sectionID) ? $likeCondition["section_adviser.section_ID"] = $sectionID : null;
            ($adviserAccountID) ? $likeCondition["section_adviser.adviser_account_ID"] = $adviserAccountID : null;
            ($academicYear) ? $likeCondition["section_adviser.academic_year"] = $academicYear : null;
            ($adviser) ? $likeCondition["section_adviser_adviser.adviser"] = $adviser : null;
            ($schoolYear) ? $likeCondition["section_adviser_adviser.academic_year"] = $schoolYear : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $this->db->order_by("section_adviser.section_ID", "asc");
        $this->db->order_by("section_adviser.adviser_account_ID", "asc");
        $this->db->order_by("section_adviser.academic_year", "asc");
        $result = $this->db->get("section_adviser");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateSectionAdviser($ID, $sectionID = false, $adviserAccountID = false, $academicYear = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($sectionID) ? $newData["section_ID"] = $sectionID : null;
        ($adviserAccountID) ? $newData["adviser_account_ID"] = $adviserAccountID : null;
        ($academicYear) ? $newData["academic_year"] = $academicYear : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("section_adviser", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteSectionAdviser($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("section_adviser");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
