<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_class_deportment extends CI_Model{
	public function createClassDeportment($deportmentID, $accountID, $sectionID, $value, $equivalent, $schoolYear){
		$this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "class_deportment_ID"   => $deportmentID,
            "account_ID"   	        => $accountID,
            "section_ID"         	=> $sectionID,
            "value"                 => $value,
            "equivalent"            => $equivalent,
            "school_year"           => $schoolYear
        );
        $this->db->insert("class_deportment_grade", $data);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $this->db->insert_id();
	}

	public function updateClassDeportment($deportmentID, $accountID, $sectionID, $value = false, $equivalent = false, $schoolYear){
		$this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($value) ? $newData["value"] = $value : null;
        ($equivalent) ? $newData["equivalent"] = $equivalent : null;
        $this->db->where(array("class_deportment_ID" => $deportmentID, "account_ID" => $accountID, "section_ID" => $sectionID, "school_year" => $schoolYear));
        $result = $this->db->update("class_deportment_grade", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
	}

	public function retrieveClassDeportment($deportmentID = false, $accountID = false, $sectionID = false, $value = false, $equivalent = false, $schoolYear = false){
		$this->db->start_cache();
        $this->db->flush_cache();
        $condition = array();
        $this->db->select("cdg.*, cd.description");
        $this->db->join("class_deportment as cd", "cd.ID = cdg.class_deportment_ID", "left");
        ($accountID) ? $condition["account_ID"] = $accountID : null;
        ($sectionID) ? $condition["section_ID"] = $sectionID : null;
        ($value) ? $condition["value"] = $value : null;
        ($equivalent) ? $condition["equivalent"] = $equivalent : null;
        ($deportmentID) ? $condition["class_deportment_ID"] = $deportmentID : null;
        ($schoolYear) ? $condition["school_year"] = $schoolYear : null;
        (count($condition) > 0) ? $this->db->where($condition) : null;
        //$this->db->group_by("account_ID");
        $result = $this->db->get("class_deportment_grade as cdg");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result->result_array();
	}

    public function retrieveClassDeportmentDescription(){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->order_by("ID");
        $result = $this->db->get("class_deportment")->result_array();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
}