<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_grading_system extends CI_Model{
	public function retrieveStudents($ID, $type_id = false, $subjectID = false, $sectionID = false, $accountID = false, $gender = false, $schoolYear = false){
		$this->db->start_cache();
        $this->db->flush_cache();
        $table_name = ($type_id == 1)? "class_section" : "club"; 
        $this->db->select("subject_type.*, abi.last_name, abi.first_name")->from($table_name." as subject_type");
        $this->db->join("account_basic_information as abi", "abi.account_ID = subject_type.account_ID", "left");
        $condition = array();
        ($ID) ? $condition["subject_type.ID"] = $ID : null;
        if($type_id != 1) ($subjectID) ? $condition["subject_type.subject_ID"] = $subjectID : null;
        ($sectionID) ? $condition["subject_type.section_ID"] = $sectionID : null;
        ($gender) ? $condition["abi.gender"] = $gender : null;
        ($accountID) ? $condition["subject_type.account_ID"] = $accountID : null;
        ($schoolYear) ? $condition["subject_type.school_year"] = $schoolYear : null;
        
        (count($condition) > 0) ? $this->db->where($condition) : null;
        
        $this->db->order_by("abi.last_name", "asc");
        $result = $this->db->get();
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return $result->result_array();
        }else{
            return false;
        }
	}

	public function retrieveStudentGrades($accountID, $subjectID = false, $schoolYear = false, $grading = false){
		$this->db->start_cache();
		$this->db->flush_cache();
		$result = array();
		$data = [];
        $temp_grading = 0;

		$this->db->select("component_ID, score");
		$this->db->where(array("account_ID" => $accountID, "grading" => $grading, "school_year" => $schoolYear));
		$result = $this->db->get("subject_component_score")->result_array();
		$this->db->flush_cache();
		$this->db->stop_cache();

		$this->db->select("grading, initial_grade, transmuted_grade");
		$this->db->where(array("account_ID" => $accountID, "subject_ID" => $subjectID, "grading" => $grading, "school_year" => $schoolYear));
		$data["grades"] = $this->db->get("student_grade")->row_array();
		$this->db->flush_cache();
		$this->db->stop_cache();

		if(count($result) > 0){
			for($x = 0; $x < count($result); $x++){
				$data[$result[$x]['component_ID']] = $result[$x];
			}
		}
		return $data;
	}

	public function insertSubjectScore($data){
		$this->db->start_cache();
        $this->db->flush_cache();
        $this->db->insert("subject_component_score", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
	}

	public function updateSubjectScore($data){
		$this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where(array("account_ID" => $data["account_ID"], "component_ID" => $data["component_ID"], "grading" => $data["grading"], "school_year" => $data["school_year"]));
        return $this->db->update("subject_component_score", array("score" => $data["score"]));
        $this->db->flush_cache();
        $this->db->stop_cache();
		
	}

	public function convertGrade($grade){
		$this->db->start_cache();
        $this->db->flush_cache();
        $newvalue = $this->db->query("SELECT * FROM transmutation ORDER BY ABS(grade - ".$grade.") LIMIT 1")->row_array();
        if($newvalue["grade"] > $grade) $newvalue["value"] = $newvalue["value"] - 1;
        return $newvalue["value"];
        $this->db->flush_cache();
        $this->db->stop_cache();
	}

	public function insertSubjectGrade($data){
		$this->db->start_cache();
        $this->db->flush_cache();
        $this->db->insert("student_grade", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
	}

	public function updateSubjectGrade($data){
		$this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where(array("subject_ID" => $data["subject_ID"], "account_ID" => $data["account_ID"], "grading" => $data["grading"], "school_year" => $data["school_year"]));
        return $this->db->update("student_grade", array("initial_grade" => $data["initial_grade"], "transmuted_grade" => $data["transmuted_grade"]));
        $this->db->flush_cache();
        $this->db->stop_cache();
		
	}

    public function retrieveStudentGrade($accountID = false, $grading = false, $schoolYear = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("student_grade.subject_ID, student_grade.transmuted_grade, student_grade.grading, subject.description")->from("student_grade");
        $this->db->join("subject", "subject.ID = student_grade.subject_ID", "left");
        $this->db->where(array("account_ID" => $accountID, "grading" => $grading));
        $result = $this->db->get();
        if($result->num_rows()){
            return $result->result_array();
        }else{
            return false;
        }
        $this->db->flush_cache();
        $this->db->stop_cache();

    }
}