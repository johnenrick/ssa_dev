<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_account_assessment_item_discount  extends CI_Model{
    
    function createAccountAssessmentItemDiscount($assessmentItemDiscountID, $accountID, $assessmentItemID, $createdByAccountID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "assessment_item_discount_ID" => $assessmentItemDiscountID,
            "account_ID" => $accountID,
            "assessment_item_ID" => $assessmentItemID,
            "created_datetime" => time(),
            "created_by_account_ID" => $createdByAccountID
        );
        $this->db->insert("account_assessment_item_discount", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAccountAssessmentItemDiscount($retrieveType = false, $limit = false, $offset = 0, $ID = false, $assessmentItemDiscountID = false, $accountID = false, $assessmentItemID = false, $createdByAccountID = false, $createdDatetime = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["account_assessment_item_discount.ID"] = $ID : null;
            ($assessmentItemDiscountID) ? $condition["account_assessment_item_discount.assessment_item_discount_ID"] = $assessmentItemDiscountID : null;
            ($accountID) ? $condition["account_assessment_item_discount.account_ID"] = $accountID : null;
            ($assessmentItemID) ? $condition["account_assessment_item_discount.assessment_item_ID"] = $assessmentItemID : null;
            ($createdByAccountID) ? $condition["account_assessment_item_discount.created_by_account_ID"] = $createdByAccountID : null;
            ($createdDatetime) ? $condition["account_assessment_item_discount.created_datetime"] = $createdDatetime : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["account_assessment_item_discount.ID"] = $ID : null;
            ($assessmentItemDiscountID) ? $likeCondition["account_assessment_item_discount.assessment_item_discount_ID"] = $assessmentItemDiscountID : null;
            ($accountID) ? $likeCondition["account_assessment_item_discount.account_ID"] = $accountID : null;
            ($assessmentItemID) ? $likeCondition["account_assessment_item_discount.assessment_item_ID"] = $assessmentItemID : null;
            ($createdByAccountID) ? $likeCondition["account_assessment_item_discount.created_by_account_ID"] = $createdByAccountID : null;
            ($createdDatetime) ? $likeCondition["account_assessment_item_discount.created_datetime"] = $createdDatetime : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("account_assessment_item_discount");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateAccountAssessmentItemDiscount($ID, $assessmentItemDiscountID = false, $accountID = false, $assessmentItemID = false, $createdByAccountID = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($assessmentItemDiscountID) ? $newData["account_assessment_item_discount.assessment_item_discount_ID"] = $assessmentItemDiscountID : null;
        ($accountID) ? $newData["account_assessment_item_discount.account_ID"] = $accountID : null;
        ($assessmentItemID) ? $newData["account_assessment_item_discount.assessment_item_ID"] = $assessmentItemID : null;
        ($createdByAccountID) ? $newData["account_assessment_item_discount.created_by_account_ID"] = $createdByAccountID : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("account_assessment_item_discount", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteAccountAssessmentItemDiscount($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("account_assessment_item_discount");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
