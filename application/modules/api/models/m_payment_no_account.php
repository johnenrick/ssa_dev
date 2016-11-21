<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_payment_no_account  extends CI_Model{
    
    function createPaymentNoAccount($paymentID, $lastName, $firstName, $middleName, $type){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "payment_ID" => $paymentID,
            "last_name" => $lastName,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "type" => $type
            
        );
        $this->db->insert("payment_no_account", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrievePaymentNoAccount($retrieveType = NULL, $limit = NULL, $offset = 0,  $sort = NULL, $ID = NULL,  $paymentID = NULL, $lastName = NULL, $firstName = NULL, $middleName = NULL, $type = NULL, $orderReceiptNumber = NULL ){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*, payment_no_account.ID");
        $this->db->join("payment", "payment.ID=payment_no_account.payment_ID", "left");
        $condition = array();
        $likeCondition = array();
        $condition["payment.payee_account_ID"] = -1;
        $condition["payment.payer_account_ID"] = -1;
        ($type != NULL) ? $condition["payment_no_account.type"] = $type : null;
        ($orderReceiptNumber != NULL) ? $condition["payment.order_receipt_number"] = $orderReceiptNumber : null;
        if(!$retrieveType){
            ($ID != NULL) ? $condition["payment_no_account.ID"] = $ID : null;
            ($paymentID != NULL) ? $condition["payment_no_account.payment_ID"] = $paymentID : null;
            ($lastName != NULL) ? $condition["payment_no_account.last_name"] = $lastName : null;
            ($firstName != NULL) ? $condition["payment_no_account.first_name"] = $firstName : null;
            ($middleName != NULL) ? $condition["payment_no_account.middle_name"] = $middleName : null;
            
            
        }else{
            ($ID != NULL) ? $likeCondition["payment_no_account.ID"] = $ID : null;
            ($paymentID != NULL) ? $likeCondition["payment_no_account.payment_ID"] = $paymentID : null;
            ($lastName != NULL) ? $likeCondition["payment_no_account.last_name"] = $lastName : null;
            ($firstName != NULL) ? $likeCondition["payment_no_account.first_name"] = $firstName : null;
            ($middleName != NULL) ? $likeCondition["payment_no_account.middle_name"] = $middleName : null;
            
            
        }
        (count($condition) > 0) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        if($sort){
            foreach($sort as $key => $value){
                $tableColumn = explode("__", $key);
                if(count($tableColumn) > 1){
                    $key = $tableColumn[0].".".$tableColumn[1];
                }
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("payment_no_account");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updatePaymentNoAccount($ID,  $paymentID = NULL, $lastName = NULL, $firstName = NULL, $middleName = NULL, $type = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($paymentID != NULL) ? $newData["payment_ID"] = $paymentID : null;
        ($lastName != NULL) ? $newData["last_name"] = $lastName : null;
        ($firstName != NULL) ? $newData["first_name"] = $firstName : null;
        ($middleName != NULL) ? $newData["middle_name"] = $middleName : null;
        ($type != NULL) ? $newData["type"] = $type : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("payment_no_account", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deletePaymentNoAccount($ID = NULL, $paymentID = NULL, $lastName = NULL, $firstName = NULL, $middleName = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $condition = array();
        ($ID != NULL) ? $condition["ID"] = $ID : null;
        ($paymentID != NULL) ? $condition["payment_ID"] = $paymentID : null;
        ($lastName != NULL) ? $condition["last_name"] = $lastName : null;
        ($firstName != NULL) ? $condition["first_name"] = $firstName : null;
        ($middleName != NULL) ? $condition["middle_name"] = $middleName : null;
        (count($condition) > 0 ) ? $this->db->where($condition) : null;
        $result = $this->db->delete("payment_no_account");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
