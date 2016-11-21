<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_club_member  extends CI_Model{
    
    function createClubMember($scheduleID, $accountID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "schedule_ID" => $scheduleID,
            "account_ID" => $accountID
        );
        $this->db->insert("club_member", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveClubMember($retrieveType = false, $limit = false, $offset = 0, $ID = false, $scheduleID = NULL, $accountID = NULL, $sort = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("account_basic_information.account_ID ,account_basic_information.identification_number, account_basic_information.first_name, account_basic_information.middle_name, account_basic_information.last_name, ");
        $this->db->join("account_basic_information", "account_basic_information.account_ID=club_member.account_ID", "left");
        $this->db->join("subject_schedule", "subject_schedule.ID=club_member.schedule_ID", "left");
        
        $this->db->select("section.year_level AS section_year_level, class_section.ID AS class_section_ID , section.ID AS section_ID, section.description AS secion_description, course.code AS course_code");
        $this->db->join("class_section", "class_section.account_ID = club_member.account_ID AND class_section.school_year=subject_schedule.school_year", "left");
        $this->db->join("section", "section.ID = class_section.section_ID", "left");
        $this->db->join("course", "course.ID = section.course_ID", "left");
        
        
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["club_member.ID"] = $ID : null;
            ($scheduleID != NULL) ? $condition["club_member.schedule_ID"] = $scheduleID : null;
            ($accountID != NULL) ? $condition["club_member.account_ID"] = $accountID : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["club_member.ID"] = $ID : null;
            ($scheduleID != NULL) ? $likeCondition["club_member.schedule_ID"] = $scheduleID : null;
            ($accountID != NULL) ? $likeCondition["club_member.account_ID"] = $accountID : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        if($sort){
            foreach($sort as $key => $value){
                
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $this->db->group_by("club_member.ID");
        $result = $this->db->get("club_member");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateClubMember($ID, $scheduleID = NULL, $accountID = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($scheduleID != NULL) ? $newData["schedule_ID"] = $scheduleID : null;
        ($accountID != NULL) ? $newData["account_ID"] = $accountID : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("club_member", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteClubMember($scheduleID, $accountID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("schedule_ID", $scheduleID);
        $this->db->where("account_ID", $accountID);
        $result = $this->db->delete("club_member");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
