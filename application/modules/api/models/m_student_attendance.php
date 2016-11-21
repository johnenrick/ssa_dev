<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class m_student_attendance extends CI_Model {

    public function createStudentAttendance($accountID, $sectionID, $month, $present, $late, $schoolYear) {
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "account_ID" => $accountID,
            "section_ID" => $sectionID,
            "month" => $month,
            "present" => $present,
            "late" => $late,
            "school_year" => $schoolYear
        );
        $this->db->insert("student_attendance", $data);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $this->db->insert_id();
    }

    public function updateStudentAttendance($ID = NULL, $accountID = NULL, $sectionID = NULL, $month = NULL, $present = NULL, $late = NULL, $schoolYear = NULL) {
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        $condition = array();
        ($ID != NULL) ? $condition["ID"] = $ID : null;
        ($accountID != NULL) ? $condition["account_ID"] = $accountID : null;
        ($sectionID != NULL) ? $condition["section_ID"] = $sectionID : null;
        ($month != NULL) ? $condition["month"] = $month : null;
            
        ($present != NULL) ? $newData["present"] = $present : null;
        ($late != NULL) ? $newData["late"] = $late : null;
        $this->db->where($condition);
        $result = $this->db->update("student_attendance", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    public function retrieveStudentAttendance($accountID = false, $sectionID = false, $month = false, $present = false, $late = false, $schoolYear = false) {
        $this->db->start_cache();
        $this->db->flush_cache();
        $condition = array();
        ($accountID) ? $condition["account_ID"] = $accountID : null;
        ($sectionID) ? $condition["section_ID"] = $sectionID : null;
        ($present) ? $condition["present"] = $present : null;
        ($late) ? $condition["late"] = $late : null;
        ($month) ? $condition["month"] = $month : null;
        ($schoolYear) ? $condition["school_year"] = $schoolYear : null;
        (count($condition) > 0) ? $this->db->where($condition) : null;
        //$this->db->group_by("account_ID");
        $result = $this->db->get("student_attendance");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result->result_array();
    }
    public function deleteAttendance($accountID, $sectionID, $month, $schoolYear){
        $this->db->start_cache();
        $this->db->flush_cache();
        $condition = array();
        $condition["account_ID"] = $accountID;
        $condition["section_ID"] = $sectionID;
        $condition["month"] = $month;
        $condition["school_year"] = $schoolYear;
        $this->db->where($condition);
        $this->db->delete("student_attendance");
        $this->db->flush_cache();
        $this->db->stop_cache();
    }
}
