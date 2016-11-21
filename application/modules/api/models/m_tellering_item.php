<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_tellering_item  extends CI_Model{
    
    function createTelleringItem($cashierID, $assessmentItemID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "cashier_ID" => $cashierID,
            "assessment_item_ID" => $assessmentItemID
        );
        $this->db->insert("tellering_item", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveTelleringItem($retrieveType = false, $limit = false, $offset = 0, $ID = NULL, $cashierID = NULL, $yearLevel = NULL, $academicYear = NULL, $grade = NULL, $assessmentItemID = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID != NULL) ? $condition["tellering_item.ID"] = $ID : null;
            ($cashierID != NULL) ? $condition["tellering_item.cashier_ID"] = $cashierID : null;
            ($assessmentItemID != NULL) ? $condition["tellering_item.assessment_item_ID"] = $assessmentItemID : null;
        }else{
            ($ID != NULL) ? $likeCondition["tellering_item.ID"] = $ID : null;
            ($cashierID != NULL) ? $likeCondition["tellering_item.cashier_ID"] = $cashierID : null;
            ($assessmentItemID != NULL) ? $likeCondition["tellering_item.assessment_item_ID"] = $assessmentItemID : null;
        }
        (count($condition) > 0 != NULL) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0 != NULL) ? $this->db->like($likeCondition) : null;
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("tellering_item");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType != NULL) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateTelleringItem($ID, $cashierID = NULL, $assessmentItemID = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($cashierID != NULL) ? $newData["description"] = $cashierID : null;
        ($assessmentItemID != NULL) ? $newData["assessment_item_ID"] = $assessmentItemID : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("tellering_item", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function deleteTelleringItem($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("tellering_item");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
