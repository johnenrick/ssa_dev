<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_student_subject_component_score  extends CI_Model{
    
    function createStudentSubjectComponentScore($subjectScheduleComponentHPSID, $accountID, $score){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "subject_schedule_component_hps_ID" => $subjectScheduleComponentHPSID,
            "account_ID" => $accountID,
            "score" => $score
        );
        $this->db->insert("student_subject_component_score", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveStudentSubjectComponentScore($retrieveType = false, $limit = false, $offset = 0, $ID = false, $subjectScheduleComponentHPSID = false, $accountID = false, $score = false, $sort = false, $subjectScheduleID = false, $grading = false, $academicYear = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("student_subject_component_score.ID, subject_component.percentage AS subject_component_percentage, subject_schedule.subject_ID,subject_schedule.subject_ID AS subject_schedule_subject_ID");
        $this->db->join("subject_schedule_component_hps", "subject_schedule_component_hps.ID=student_subject_component_score.subject_schedule_component_hps_ID", "left");
        $this->db->join("subject_schedule", "subject_schedule.ID=subject_schedule_component_hps.subject_schedule_ID", "left");
        $this->db->join("subject_component", "subject_component.ID=subject_schedule_component_hps.subject_component_ID AND subject_component.academic_year=subject_schedule.school_year", "left");
        $condition = array();
        $likeCondition = array();
        ($subjectScheduleID) ? $condition["subject_schedule_component_hps.subject_schedule_ID"] = $subjectScheduleID : null;
        ($grading) ? $condition["subject_schedule_component_hps.grading"] = $grading : null;
        ($academicYear) ? $condition["subject_schedule.school_year"] = $academicYear : null;
        if(!$retrieveType){
            ($ID) ? $condition["student_subject_component_score.ID"] = $ID : null;
            ($subjectScheduleComponentHPSID) ? $condition["student_subject_component_score.subject_schedule_component_hps_ID"] = $subjectScheduleComponentHPSID : null;
            ($accountID) ? $condition["student_subject_component_score.account_ID"] = $accountID : null;
            ($score) ? $condition["student_subject_component_score.score"] = $score : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["student_subject_component_score.ID"] = $ID : null;
            ($subjectScheduleComponentHPSID) ? $likeCondition["student_subject_component_score.subject_schedule_component_hps_ID"] = $subjectScheduleComponentHPSID : null;
            ($accountID) ? $likeCondition["student_subject_component_score.account_ID"] = $accountID : null;
            ($score) ? $likeCondition["student_subject_component_score.score"] = $score : null;
            
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
        $this->db->group_by("student_subject_component_score.ID");
        $this->db->order_by("student_subject_component_score.subject_schedule_component_hps_ID", "asc");
        $result = $this->db->get("student_subject_component_score");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function retrieveNoStudentSubjectComponentScore($limit = false, $offset = 0, $score = false, $sort = false, $yearLevel = NULL, $fullName = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("account_basic_information.account_ID, student_subject_component_score.ID AS student_subject_component_score_ID, student_subject_component_score.score");
        //$this->db->join("course", "course.ID = student_subject_component_score.subject_schedule_component_hps_ID", "left");
        $this->db->join("student_subject_component_score", "student_subject_component_score.account_ID = account_basic_information.account_ID AND student_subject_component_score.score = ".$score, "left");
        $this->db->join("account", "account.ID = account_basic_information.account_ID", "left");
        $this->db->join("account_level", "account_level.account_ID = account_basic_information.account_ID AND account_level.academic_year = ".$score, "left");
        $condition = array();
        
        
        if($yearLevel == 0){
            $condition["account_level.ID is NULL"] = NULL;
        }else{
            ($score) ? $condition["account_level.academic_year ="] = $score : null;
            $condition["student_subject_component_score.ID is NULL"] = NULL;
            ($yearLevel !== NULL) ? $condition["account_level.year_level"] = $yearLevel : null;
        }
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
        if($fullName !== NULL){
            $fullName = str_replace(",", "", $fullName);
            $names = explode(" ",$fullName);
            foreach($names as $values){
                $this->db->like("concat(`first_name`,`last_name`,`middle_name`)", $values);
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
    function updateStudentSubjectComponentScore($ID, $subjectScheduleComponentHPSID = false, $accountID = false, $score = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($subjectScheduleComponentHPSID) ? $newData["subject_schedule_component_hps_ID"] = $subjectScheduleComponentHPSID : null;
        ($accountID) ? $newData["account_ID"] = $accountID : null;
        ($score) ? $newData["score"] = $score : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("student_subject_component_score", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteStudentSubjectComponentScore($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("student_subject_component_score");
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
