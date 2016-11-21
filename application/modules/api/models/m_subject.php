<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_subject  extends CI_Model{
	public function createSubject($courseID, $yearLevel, $subjecttype, $description){
		$this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "course_ID" => $courseID,
            "year_level" => $yearLevel,
            "type_ID"   => $subjecttype,
            "description" => $description
        );
        $this->db->insert("subject", $data);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $this->db->insert_id();
	}

	public function checkSubjectName($yearLevel, $name){
		$this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("description", $name);
        $this->db->where("year_level", $yearLevel);
        $this->db->flush_cache();
        $this->db->stop_cache();
        if(!empty($this->db->get("subject")->result_array())) return 1;
		else return 0;
	}

	public function retrieveSubject($retrieveType = false, $limit = false, $offset = 0, $ID = false, $courseID = false, $yearLevel = false, $subjecttype = false, $description = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("subject.ID, subject.description, course.description AS course_description");
        $this->db->join("course", "course.ID = subject.course_ID", "inner");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["subject.ID"] = $ID : null;
            ($courseID) ? $condition["subject.course_ID"] = $courseID : null;
            ($yearLevel) ? $condition["subject.year_level"] = $yearLevel : null;
            ($subjecttype) ? $condition["subject.type_ID"] = $subjecttype : null;
            ($description) ? $condition["subject.description"] = $description : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["subject.ID"] = $ID : null;
            ($courseID) ? $likeCondition["subject.course_ID"] = $courseID : null;
            ($yearLevel) ? $likeCondition["subject.year_level"] = $yearLevel : null;
            ($subjecttype) ? $likeCondition["subject.type_ID"] = $subjecttype : null;
            ($description) ? $likeCondition["subject.description"] = $description : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $this->db->order_by("subject.course_ID", "asc");
        $this->db->order_by("subject.year_level", "asc");
        $this->db->order_by("subject.description", "asc");
        $result = $this->db->get("subject");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }

    function updateSubject($ID, $courseID = false, $yearLevel = false, $subjecttype = false, $description = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($courseID) ? $newData["course_ID"] = $courseID : null;
        ($yearLevel) ? $newData["year_level"] = $yearLevel : null;
        ($subjecttype) ? $newData["type_ID"] = $subjecttype : null;
        ($description) ? $newData["description"] = $description : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("subject", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteSubject($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("subject");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    function addComponent($ID, $name, $value, $academicYear){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "subject_ID" => $ID,
            "description" => $name,
            "percentage"   => $value,
            "academic_year"   => $academicYear
        );
        $this->db->insert("subject_component", $data);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $this->db->insert_id();
    }

    function retrieveComponent($ID = NULL, $academicYear = NULL, $subjectID = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->_protect_identifiers=false;
        $this->db->select("sc.*, subject.description as subject_name");
        ($subjectID != NULL) ? $this->db->where("sc.subject_ID", $subjectID) : "";
        ($ID != NULL) ? $this->db->where("sc.ID", $ID) : "";
        $this->db->join("subject", "subject.ID = sc.subject_ID", "inner");
//        $this->db->join("subject_schedule", "subject_schedule.subject_ID = subject.ID", "left");
//        $this->db->join("subject_schedule_component_hps", "subject_schedule_component_hps.subject_schedule_ID= $subjectScheduleID AND subject_schedule_component_hps.grading=".($grading ? $grading : "0")." AND subject_schedule_component_hps.subject_component_ID=sc.ID", "left");
      
        ($academicYear != NULL) ? $this->db->where("academic_year", $academicYear) : null;
        $this->db->group_by("sc.ID");
        $this->db->order_by("sc.ID", "ASC");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $this->db->get("subject_component as sc")->result_array();
    }

    function updateComponent($ID, $subjectID = false, $percentage = false, $hps = false, $grading = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($subjectID)?  $newData["description"]  = $subjectID  : null;
        ($percentage)? $newData["percentage"]   = $percentage : null;
        ($hps !== false)?        ($grading > 1)? $newData["hps".$grading] = $hps : $newData["hps"] = $hps       : null;
        $this->db->where("ID", $ID);
        return $this->db->update("subject_component", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
    }

    function removeComponent($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("subject_component");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
}