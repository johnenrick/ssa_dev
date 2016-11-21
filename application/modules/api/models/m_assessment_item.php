<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_assessment_item  extends CI_Model{
    
    function createAssessmentItem($assessmentTypeID, $generalLedgerID, $description, $tellering){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "assessment_type_ID" => $assessmentTypeID,
            "general_ledger_ID" => $generalLedgerID,
            "description" => $description,
            "tellering" => $tellering
        );
        $this->db->insert("assessment_item", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAssessmentItem($retrieveType = false, $limit = false, $offset = 0, $ID = false, $assessmentTypeID = NULL, $generalLedgerID = NULL, $description = NULL, $sort = false, $tellering = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("assessment_item.ID, assessment_item.description, assessment_type.description AS assessment_type_description, general_ledger.description AS general_ledger_description");
        $this->db->join("assessment_type", "assessment_type.ID = assessment_item.assessment_type_ID", "left");
        $this->db->join("general_ledger", "general_ledger.ID = assessment_item.general_ledger_ID", "left");
        $condition = array();
        $likeCondition = array();
        ($assessmentTypeID != NULL) ? $condition["assessment_item.assessment_type_ID"] = $assessmentTypeID : null;
        ($generalLedgerID != NULL) ? $condition["assessment_item.general_ledger_ID"] = $generalLedgerID : null;
        ($tellering != NULL) ? $condition["assessment_item.tellering"] = $tellering : null;
        if(!$retrieveType){
            ($ID) ? $condition["assessment_item.ID"] = $ID : null;
            ($description != NULL) ? $condition["assessment_item.description"] = $description : null;
        }else{
            ($ID) ? $likeCondition["assessment_item.ID"] = $ID : null;
            ($description != NULL) ? $likeCondition["assessment_item.description"] = $description : null;
        }
        (count($condition) > 0) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        if($sort){
            foreach($sort as $key => $value){
                $key = ($key == "description") ? "assessment_item.description" : $key;
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        $this->db->order_by("assessment_item.description", "asc");
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("assessment_item");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateAssessmentItem($ID, $assessmentTypeID = false, $generalLedgerID = false, $description = false, $tellering = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($assessmentTypeID) ? $newData["assessment_item.assessment_type_ID"] = $assessmentTypeID : null;
        ($generalLedgerID) ? $newData["assessment_item.general_ledger_ID"] = $generalLedgerID : null;
        ($description) ? $newData["assessment_item.description"] = $description : null;
        ($tellering) ? $newData["assessment_item.tellering"] = $tellering : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("assessment_item", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteAssessmentItem($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("assessment_item");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function assessmentItemExistInUsed($assessmentItemID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("assessment_item_ID", $assessmentItemID);
        $result = $this->db->get("course_annual_fee");
        $this->db->start_cache();
        $this->db->flush_cache();
        if($result->num_rows()){
            return true;
        }
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("assessment_item_ID", $assessmentItemID);
        $result1 = $this->db->get("payment_assessment_item");
        $this->db->start_cache();
        $this->db->flush_cache();
        if($result1->num_rows()){
            return true;
        }
        
        return false;
    }
   
}
