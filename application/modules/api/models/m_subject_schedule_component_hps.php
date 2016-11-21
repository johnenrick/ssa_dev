<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_subject_schedule_component_hps  extends CI_Model{
    
    function createSubjectScheduleComponentHPS($subjectScheduleID, $subjectComponentID, $highestPossibleScore, $grading){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "subject_schedule_ID" => $subjectScheduleID,
            "subject_component_ID" => $subjectComponentID,
            "highest_possible_score" => $highestPossibleScore,
            "grading" => $grading
            
        );
        $this->db->insert("subject_schedule_component_hps", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveSubjectScheduleComponentHPS($retrieveType = false, $limit = false, $offset = 0, $sort = false, $ID = false, $subjectScheduleID = NULL, $subjectComponentID = NULL, $highestPossibleScore = NULL, $grading = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*, subject_component.percentage AS subject_component_percentage, subject_schedule_component_hps.ID");
        $this->db->join("subject_schedule", "subject_schedule.ID=subject_schedule_component_hps.subject_schedule_ID", "left");
        $this->db->join("subject_component", "subject_component.subject_ID=subject_schedule.subject_ID AND subject_component.academic_year=subject_schedule.school_year", "left");   
        $condition = array();
        $likeCondition = array();
        ($ID) ? $condition["subject_schedule_component_hps.ID"] = $ID : null;
        ($subjectScheduleID != NULL) ? $condition["subject_schedule_component_hps.subject_schedule_ID"] = $subjectScheduleID : null;
        ($subjectComponentID != NULL) ? $condition["subject_schedule_component_hps.subject_component_ID"] = $subjectComponentID : null;
        ($grading != NULL) ? $condition["subject_schedule_component_hps.grading"] = $grading : null;
        if(!$retrieveType){
            ($highestPossibleScore != NULL) ? $condition["subject_schedule_component_hps.highest_possible_score"] = $highestPossibleScore : null;
        }else{
            ($highestPossibleScore != NULL) ? $likeCondition["subject_schedule_component_hps.highest_possible_score"] = $highestPossibleScore : null;
        }
        (count($condition) > 0) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        if($sort){
            foreach($sort as $key => $value){
                $key = ($key == "description") ? "subject_schedule_component_hps.description" : $key;
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        $this->db->group_by("subject_schedule_component_hps.ID");
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("subject_schedule_component_hps");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateSubjectScheduleComponentHPS($ID, $subjectScheduleID = false, $subjectComponentID = false, $highestPossibleScore = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($subjectScheduleID) ? $newData["subject_schedule_component_hps.subject_schedule_ID"] = $subjectScheduleID : null;
        ($subjectComponentID) ? $newData["subject_schedule_component_hps.subject_component_ID"] = $subjectComponentID : null;
        ($highestPossibleScore !== NULL) ? $newData["subject_schedule_component_hps.highest_possible_score"] = $highestPossibleScore : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("subject_schedule_component_hps", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteSubjectScheduleComponentHPS($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("subject_schedule_component_hps");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function assessmentItemExistInUsed($assessmentItemID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("subject_schedule_component_hps_ID", $assessmentItemID);
        $result = $this->db->get("course_annual_fee");
        $this->db->start_cache();
        $this->db->flush_cache();
        if($result->num_rows()){
            return true;
        }
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("subject_schedule_component_hps_ID", $assessmentItemID);
        $result1 = $this->db->get("payment_subject_schedule_component_hps");
        $this->db->start_cache();
        $this->db->flush_cache();
        if($result1->num_rows()){
            return true;
        }
        
        return false;
    }
   
}
