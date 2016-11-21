<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_schedule  extends CI_Model{
	public function retrieveSchedule($retrieveType = false, $limit = false, $offset = 0, $ID = NULL, $roomID = false, $subjectID = NULL, $subjecttype = false, $teacherID = false, $time_start = false, $time_end = false, $day = false, $schoolyear = false, $sectionID = NULL, $year_level = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("schedule.ID, room.ID as room_ID, room.building_ID as building_ID, room.description as room_name, subject.year_level as year_level, schedule.subject_ID, subject.course_ID as course_ID, subject.description as subject_name, schedule.time_start, schedule.time_end, schedule.day, schedule.school_year, schedule.section_ID, schedule.teacher_ID, schedule.type_ID"); //, section.description as section_name
        $this->db->join("room as room", "room.ID = schedule.room_ID", "inner");
        $this->db->join("subject as subject", "subject.ID = schedule.subject_ID", "inner");
        $condition = array();
        $likeCondition = array();
        ($ID != NULL) ? $condition["schedule.ID"] = $ID : null;
        ($roomID) ? $condition["schedule.room_ID"] = $roomID : null;
        ($subjectID != NULL) ? $condition["schedule.subject_ID"] = $subjectID : null;
        ($subjecttype) ? $condition["schedule.type_ID"] = $subjecttype : null;
        ($teacherID) ? $condition["schedule.teacher_ID"] = $teacherID : null;
        if(!$retrieveType){
            
            ($time_start) ? $condition["schedule.time_start"] = $time_start : null;
            ($time_end) ? $condition["schedule.time_end"] = $time_end : null;
            ($day) ? $condition["schedule.day"] = $day : null;
            ($schoolyear) ? $condition["schedule.school_year"] = $schoolyear : null;
            ($sectionID != NULL) ? $condition["schedule.section_ID"] = $sectionID : null;
            ($year_level) ? $condition["subject.year_level"] = $year_level : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            
            ($time_start) ? $likeCondition["schedule.time_start"] = $time_start : null;
            ($time_end) ? $likeCondition["schedule.time_end"] = $time_end : null;
            ($day) ? $condition["schedule.day"] = $day : null;
            ($schoolyear) ? $likeCondition["schedule.school_year"] = $schoolyear : null;
            ($sectionID != NULL) ? $likeCondition["schedule.section_ID"] = $sectionID : null;
            ($year_level) ? $likeCondition["subject.year_level"] = $year_level : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        //$this->db->order_by("schedule.subject_ID", "asc");
        $this->db->order_by("subject.year_level", "asc");
        //$this->db->order_by("schedule.room_ID", "asc");
        //$this->db->order_by("schedule.time_start", "asc");
        //$this->db->order_by("schedule.time_end", "asc");
        //$this->db->order_by("schedule.day", "asc");
        $result = $this->db->get("subject_schedule as schedule");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }

    function createSchedule($roomID = false, $subjectID = false, $subjecttype = false, $time_start = false, $time_end = false, $day = false, $schoolyear = false){
    	$this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "room_ID" => $roomID,
            "subject_ID" => $subjectID,
            "type_ID"   => $subjecttype,
            "time_start" => $time_start,
            "time_end" => $time_end,
            "day" => $day,
            "school_year" => $schoolyear
        );
        $this->db->insert("subject_schedule", $data);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $this->db->insert_id();
    }

    function updateSchedule($ID, $roomID = false, $subjectID = false, $subjecttype = false, $teacherID = false, $time_start = false, $time_end = false, $day = false, $schoolyear = false, $sectionID = NULL){
    	$this->db->start_cache();
        $this->db->flush_cache();
        ($roomID) ? $newData["room_ID"] = $roomID : null;
        ($subjectID) ? $newData["subject_ID"] = $subjectID : null;
        ($teacherID) ? $newData["teacher_ID"] = $teacherID : null;
        ($subjecttype) ? $newData["type_ID"] = $subjecttype : null;
        ($time_start) ? $newData["time_start"] = $time_start : null;
        ($time_end) ? $newData["time_end"] = $time_end : null;
        ($day) ? $newData["day"] = $day : null;
        ($schoolyear) ? $newData["school_year"] = $schoolyear : null;
        ($sectionID != NULL) ? $newData["section_ID"] = $sectionID : null;
        ($ID)? $this->db->where("ID", $ID) : $this->db->where("subject_ID", $subjectID);
        return $this->db->update("subject_schedule", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        
    }

    function deleteSchedule($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("subject_schedule");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    function retrieveTeacherList(){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("acc.ID, basic.first_name, basic.middle_name, basic.last_name")->from("account as acc")->where("account_type_ID", 6);
        $this->db->join("account_basic_information as basic", "basic.account_ID = acc.ID");
        $this->db->order_by("basic.last_name", "asc");
        $this->db->order_by("basic.first_name", "asc");
        $this->db->order_by("basic.middle_name", "asc");
        $result = $this->db->get()->result_array();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
}