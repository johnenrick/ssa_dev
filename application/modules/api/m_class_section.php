<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_class_section  extends CI_Model{
    
    function createClassSection($sectionID, $accountID, $schoolYear){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "section_ID" => $sectionID,
            "account_ID" => $accountID,
            "school_year" => $schoolYear
        );
        $this->db->insert("class_section", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveClassSection($retrieveType = false, $limit = false, $offset = 0, $ID = false, $sectionID = false, $accountID = false, $schoolYear = false, $sort = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("class_section.ID, class_section.school_year");
        $this->db->join("course", "course.ID = class_section.section_ID", "left");
        $this->db->join("account_basic_information", "account_basic_information.account_ID = class_section.account_ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["class_section.ID"] = $ID : null;
            ($sectionID) ? $condition["class_section.section_ID"] = $sectionID : null;
            ($accountID) ? $condition["class_section.account_ID"] = $accountID : null;
            ($schoolYear) ? $condition["class_section.school_year"] = $schoolYear : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["class_section.ID"] = $ID : null;
            ($sectionID) ? $likeCondition["class_section.section_ID"] = $sectionID : null;
            ($accountID) ? $likeCondition["class_section.account_ID"] = $accountID : null;
            ($schoolYear) ? $likeCondition["class_section.school_year"] = $schoolYear : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
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
        $this->db->order_by("class_section.section_ID", "asc");
        $this->db->order_by("class_section.account_ID", "asc");
        $result = $this->db->get("class_section");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function retrieveNoClassSection($limit = false, $offset = 0, $schoolYear = false, $sort = false, $yearLevel = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("account_basic_information.account_ID, class_section.ID AS class_section_ID, class_section.school_year");
        //$this->db->join("course", "course.ID = class_section.section_ID", "left");
        $this->db->join("class_section", "class_section.account_ID = account_basic_information.account_ID AND class_section.school_year = ".$schoolYear, "left");
        $this->db->join("account", "account.ID = account_basic_information.account_ID", "left");
        $this->db->join("account_level", "account_level.account_ID = account_basic_information.account_ID AND account_level.academic_year = ".$schoolYear, "left");
        $condition = array();
        $condition["class_section.ID is NULL"] = NULL;
        ($schoolYear) ? $condition["account_level.academic_year ="] = $schoolYear : null;
        ($yearLevel) ? $condition["account_level.year_level"] = $yearLevel : null;
        
        //echo $schoolYear;
        $condition["account.account_type_ID"] = 4;
        (count($condition) > 0) ? $this->db->where($condition, null) : null;
        //print_r($sort);
        if($sort != false){
            foreach($sort as $key => $value){
                $tableColumn = explode("__", $key);
                if(count($tableColumn) > 1){
                    $key = $tableColumn[0].".".$tableColumn[1];
                }
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
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
    function updateClassSection($ID, $sectionID = false, $accountID = false, $schoolYear = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($sectionID) ? $newData["section_ID"] = $sectionID : null;
        ($accountID) ? $newData["account_ID"] = $accountID : null;
        ($schoolYear) ? $newData["school_year"] = $schoolYear : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("class_section", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteClassSection($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("class_section");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function retrieveStudentInfo($accountID = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("ID, account_ID, identification_number, first_name, middle_name, last_name, birth_place, birth_datetime");
        $this->db->where("account_ID", $accountID);
        $result = $this->db->get("account_basic_information")->row_array();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
}
