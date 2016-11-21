<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_change_log  extends CI_Model{
    
    function createChangeLog($moduleID, $performedByID , $detail){ 
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "module_ID" => $moduleID,
            "performed_by_ID" => $performedByID,
            "datetime" => time(),
            "detail" => $detail,
        );
        $this->db->insert("change_log", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveChangeLog($retrieveType = false, $limit = false, $offset = 0, $ID = false, $moduleID = false, $performedByID = false, $datetime = false, $detail = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("change_log.ID, change_log.detail, change_log.performed_by_ID, category.performed_by_ID AS category_performed_by_ID");
        $this->db->join("account_information", "account_information.account_ID = change_log.performed_by_ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["change_log.ID"] = $ID : null;
            ($moduleID) ? $condition["change_log.module_ID"] = $moduleID : null;
            ($performedByID) ? $condition["change_log.performed_by_ID"] = $performedByID : null;
            ($datetime) ? $condition["change_log.datetime"] = $datetime : null;
            ($detail) ? $condition["change_log.detail"] = $detail : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["change_log.ID"] = $ID : null;
            ($moduleID) ? $likeCondition["change_log.module_ID"] = $moduleID : null;
            ($performedByID) ? $likeCondition["change_log.performed_by_ID"] = $performedByID : null;
            ($datetime) ? $likeCondition["change_log.datetime"] = $datetime : null;
            ($detail) ? $likeCondition["change_log.detail"] = $detail : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("change_log");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    
    
}
