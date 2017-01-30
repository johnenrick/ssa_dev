<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_student_log  extends CI_Model{
    
    function createStudentLog($accountID, $datetime, $inOut, $location){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "account_ID" => $accountID,
            "datetime" => $datetime,
            "in_out" => $inOut,
            "location" => $location
        );
        $this->db->insert("student_log", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveStudentLog($retrieveType = false, $limit = false, $offset = 0, $ID = false, $accountID = false, $startDatetime = false, $endDatetime = false, $inOut = false, $location = NULL, $accountName = NULL, $blockStudentID = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->_protect_identifiers=false;
        $this->db->select("*");
        $this->db->select("student_log.ID, CONCAT(account_basic_information.first_name, ' ', account_basic_information.middle_name, '  ', account_basic_information.last_name) AS account_full_name");
        $this->db->join("account_basic_information ", "account_basic_information.account_ID=student_log.account_ID", "left");
        $condition = array();
        $likeCondition = array();
        ($location != NULL) ? $condition["student_log.location"] = $location : null;
        ($ID) ? $condition["student_log.ID"] = $ID : null;
        ($accountID) ? $condition["student_log.account_ID"] = $accountID : null;
        ($inOut) ? $condition["student_log.in_out"] = $inOut : null;
        
        ($startDatetime) ? $condition["student_log.datetime >="] = date("Y-m-d 00:00:00", $startDatetime) : null;
        ($endDatetime) ? $condition["student_log.datetime <"] = date("Y-m-d 00:00:00", strtotime("+1 day", $endDatetime)) : null;
        
        if($accountName != NULL){
            $explodedName = explode(" ", $accountName);
            for($x = 0; $x < count($explodedName); $x++){
                $likeCondition["CONCAT(account_basic_information.first_name, account_basic_information.middle_name, account_basic_information.last_name)"] = $explodedName[$x];
            }
        }
        ($blockStudentID) ? $this->db->where_in($blockStudentID) : null;
        (count($condition) > 0) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        ($limit)?$this->db->limit($limit, $offset):0;
        
        $this->db->order_by("student_log.datetime", "asc");
        $this->db->order_by("student_log.account_ID", "asc");
        $this->db->order_by("student_log.in_out", "asc");
        $this->db->group_by("student_log.ID");
        $result = $this->db->get("student_log");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateStudentLog($ID, $accountID = false, $datetime = false, $inOut = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($accountID) ? $newData["account_ID"] = $accountID : null;
        ($datetime) ? $newData["datetime"] = $datetime : null;
        ($inOut) ? $newData["in_out"] = $inOut : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("student_log", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteStudentLog($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("student_log");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
