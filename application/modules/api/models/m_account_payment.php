<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_account_payment  extends CI_Model{
    
    function createAccountPayment($orderReceiptNumber, $payeeAccountID, $payerAccountID, $cashierID, $totalAmount, $mode, $datetime, $remarks, $status, $academicYear){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "order_receipt_number" => $orderReceiptNumber,
            "payee_account_ID" => $payeeAccountID,
            "payer_account_ID" => $payerAccountID,
            "cashier_ID" => $cashierID,
            "total_amount" => $totalAmount,
            "mode" => $mode,
            "datetime" => $datetime,
            "remarks" => $remarks,
            "status" => $status,
            "academic_year" => $academicYear
        );
        $this->db->insert("payment", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAccountPayment($retrieveType = false, $limit = false, $offset = 0, $ID = NULL, $orderReceiptNumber = NULL, $payeeAccountID = NULL, $payerAccountID = NULL, $cashierID = NULL, $totalAmount = NULL, $mode = NULL, $datetime = NULL, $remarks = NULL, $status = NULL, $identificationNumber = NULL, $sort = false, $orderReceiptNumberRange = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("payment.ID, payment.status");
        
        $this->db->select("payee_account_basic_information.first_name AS payee_first_name, payee_account_basic_information.middle_name AS payee_middle_name, payee_account_basic_information.last_name AS payee_last_name, payee_account_basic_information.identification_number AS payee_identification_number");
        $this->db->join("account_basic_information AS payee_account_basic_information", "payee_account_basic_information.account_ID=payment.payee_account_ID", "left");
        $this->db->select("payer_account_basic_information.first_name AS payer_first_name, payer_account_basic_information.middle_name AS payer_middle_name, payer_account_basic_information.last_name AS payer_last_name, payer_account_basic_information.identification_number AS payer_identification_number");
        $this->db->join("account_basic_information AS payer_account_basic_information", "payer_account_basic_information.account_ID=payment.payer_account_ID", "left");
        
        $this->db->select("payer_payment_no_account.first_name AS no_account_payer_first_name, payer_payment_no_account.middle_name AS no_account_payer_middle_name, payer_payment_no_account.last_name AS no_account_payer_last_name");
        $this->db->join("payment_no_account AS payer_payment_no_account", "payer_payment_no_account.payment_ID=payment.ID AND payer_payment_no_account.type=2", "left");
        $this->db->select("payee_payment_no_account.first_name AS no_account_payee_first_name, payee_payment_no_account.middle_name AS no_account_payee_middle_name, payee_payment_no_account.last_name AS no_account_payee_last_name");
        $this->db->join("payment_no_account AS payee_payment_no_account", "payee_payment_no_account.payment_ID=payment.ID AND payee_payment_no_account.type=1", "left");
        
        $this->db->select("cashier_account_basic_information.first_name AS cashier_first_name, cashier_account_basic_information.middle_name AS cashier_middle_name, cashier_account_basic_information.last_name AS cashier_last_name, cashier_account_basic_information.identification_number AS cashier_identification_number");
        $this->db->join("account_basic_information AS cashier_account_basic_information", "cashier_account_basic_information.account_ID=payment.cashier_ID", "left");
        $condition = array();
        $likeCondition = array();
        if($orderReceiptNumberRange !== NULL){
            $range = explode("-", $orderReceiptNumberRange);
            if(count($range)>1){
                $condition["payment.order_receipt_number >="] = $range[0];
                $condition["payment.order_receipt_number <="] = $range[1];
            }
        }
        if(!$retrieveType){
            ($ID!= NULL) ? $condition["payment.ID"] = $ID : null;
            ($orderReceiptNumber!= NULL) ? $condition["payment.order_receipt_number"] = $orderReceiptNumber : null;
            ($payeeAccountID!= NULL) ? $condition["payment.payee_account_ID"] = $payeeAccountID : null;
            ($payerAccountID!= NULL) ? $condition["payment.payer_account_ID"] = $payerAccountID : null;
            ($cashierID!= NULL) ? $condition["payment.cashier_ID"] = $cashierID : null;
            ($totalAmount!= NULL) ? $condition["payment.total_amount"] = $totalAmount : null;
            ($mode!= NULL) ? $condition["payment.mode"] = $mode : null;
            ($datetime!= NULL) ? $condition["payment.datetime"] = $datetime : null;
            ($remarks!= NULL) ? $condition["payment.remarks"] = $remarks : null;
            ($status!= NULL) ? $condition["payment.status"] = $status : null;
            ($identificationNumber!= NULL) ? $condition["account_basic_information.identification_number"] = $identificationNumber : null;
            
            
        }else{
            ($ID!= NULL) ? $likeCondition["payment.ID"] = $ID : null;
            ($orderReceiptNumber!= NULL) ? $likeCondition["payment.order_receipt_number"] = $orderReceiptNumber : null;
            ($payeeAccountID!= NULL) ? $likeCondition["payment.payee_account_ID"] = $payeeAccountID : null;
            ($payerAccountID!= NULL) ? $likeCondition["payment.payer_account_ID"] = $payerAccountID : null;
            ($cashierID!= NULL) ? $likeCondition["payment.cashier_ID"] = $cashierID : null;
            ($totalAmount!= NULL) ? $likeCondition["payment.total_amount"] = $totalAmount : null;
            ($mode!= NULL) ? $likeCondition["payment.mode"] = $mode : null;
            ($datetime!= NULL) ? $likeCondition["payment.datetime"] = $datetime : null;
            ($remarks!= NULL) ? $likeCondition["payment.remarks"] = $remarks : null;
            ($status!= NULL) ? $likeCondition["payment.status"] = $status : null;
            ($identificationNumber!= NULL) ? $likeCondition["account_basic_information.identification_number"] = $identificationNumber : null;
            
            
        }
        (count($condition) > 0) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
       // echo count($condition);
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
        $result = $this->db->get("payment");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ( ($ID != NULL ||$orderReceiptNumber != NULL) && !$retrieveType!= NULL) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function retrievePaymentAssessmentItem($retrieveType = false, $limit = false, $offset = 0, $ID = NULL, $paymentID = NULL, $assessmentItemID = NULL, $amount = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("payment_assessment_item.ID");
        $this->db->join("assessment_item", "assessment_item.ID=payment_assessment_item.assessment_item_ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID!= NULL) ? $condition["payment_assessment_item.ID"] = $ID : null;
            ($paymentID!= NULL) ? $condition["payment_assessment_item.payment_ID"] = $paymentID : null;
            ($assessmentItemID!= NULL) ? $condition["payment_assessment_item.assessment_item_ID"] = $assessmentItemID : null;
            ($amount!= NULL) ? $condition["payment_assessment_item.amount"] = $amount : null;
            
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID!= NULL) ? $likeCondition["payment_assessment_item.ID"] = $ID : null;
            ($paymentID!= NULL) ? $likeCondition["payment_assessment_item.payment_ID"] = $paymentID : null;
            ($assessmentItemID!= NULL) ? $likeCondition["payment_assessment_item.assessment_item_ID"] = $assessmentItemID : null;
            ($amount!= NULL) ? $likeCondition["payment_assessment_item.amount"] = $amount : null;
            
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("payment_assessment_item");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ( ($ID != NULL ) && !$retrieveType!= NULL) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateAccountPayment($ID, $orderReceiptNumber = NULL, $payeeAccountID = false, $payerAccountID = false, $cashierID = NULL, $totalAmount = false, $mode = false, $datetime = false, $remarks = false, $status = NULL, $academicYear = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($orderReceiptNumber!= NULL) ? $newData["payment.order_receipt_number"] = $orderReceiptNumber : null;
        ($payeeAccountID!= NULL) ? $newData["payment.payee_account_ID"] = $payeeAccountID : null;
        ($payerAccountID!= NULL) ? $newData["payment.payer_account_ID"] = $payerAccountID : null;
        ($cashierID!= NULL) ? $newData["payment.cashier_ID"] = $cashierID : null;
        ($totalAmount!= NULL) ? $newData["payment.total_amount"] = $totalAmount : null;
        ($mode!= NULL) ? $newData["payment.mode"] = $mode : null;
        ($datetime!= NULL) ? $newData["payment.datetime"] = $datetime : null;
        ($remarks!= NULL) ? $newData["payment.remarks"] = $remarks : null;
        ($status!= NULL) ? $newData["payment.status"] = $status : null;
        ($academicYear!= NULL) ? $newData["payment.academic_year"] = $academicYear : null;
        if(is_array($ID)){
            $this->db->where_in("ID", $ID);
        }else{
            $this->db->where("ID", $ID);
        }
        
        $result = $this->db->update("payment", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function batchCreatePaymentAssessmentItem($assessmentItemList){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->insert_batch("payment_assessment_item", $assessmentItemList);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function batchUpdatePaymentAssessmentItem($assessmentItemList){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->update_batch("payment_assessment_item", $assessmentItemList, "ID");
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function updatePaymentAssessmentItem($ID, $paymentID = NULL, $assessmentItemID = NULL, $amount = NULL, $remarks = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($paymentID!= NULL) ? $newData["payment_assessment_item.payment_ID"] = $paymentID : null;
        ($assessmentItemID!= NULL) ? $newData["payment_assessment_item.assessment_item_ID"] = $assessmentItemID : null;
        ($amount!= NULL) ? $newData["payment_assessment_item.amount"] = $amount : null;
        ($remarks!= NULL) ? $newData["payment_assessment_item.remarks"] = $remarks : null;
        $this->db->where("payment_assessment_item.ID", $ID);
        $result = $this->db->update("payment_assessment_item", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function batchDeletePaymentAssessmentItem($assessmentItemList){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where_in("ID", $assessmentItemList);
        $this->db->delete("payment_assessment_item", $assessmentItemList);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function deleteAccountPayment($ID, $remarks, $accountID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $condition = array();
        ($ID!= NULL) ? $condition["payment.ID"] = $ID : null;
        $this->db->where($condition);
        $result = $this->db->update("payment", array("status" => 3));
        if($result){
            $this->db->flush_cache();
            $this->db->stop_cache();
            $this->db->insert("payment_voided", array(
               "payment_ID" =>  $ID,
               "remarks" => $remarks,
                "datetime" => time(),
                "created_by_account_ID" => $accountID
            ));
        }
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function retrieveAccountPaymentAssessmentItem($retrieveType = false, $limit = false, $offset = 0, $ID = NULL, $orderReceiptNumber = NULL, $payeeAccountID = NULL, $payerAccountID = NULL, $cashierID = NULL, $totalAmount = NULL, $mode = NULL, $datetime = NULL, $remarks = NULL, $status = NULL, $identificationNumber = NULL, $assessmentItemID = NULL, $amount = NULL, $assessmentItemDescription = NULL, $startDatetime = NULL, $endDatetime = NULL, $sort = false, $assessmentTypeID = NULL, $payeeClassSection = NULL, $paymentAcademicYear = NULL, $excludeAssessmentItemID = NULL, $payeeAccountYearLevel = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("payment_assessment_item.ID, payment_assessment_item.ID AS payment_assessment_item_ID,payment_assessment_item.assessment_item_ID, payment_assessment_item.amount,payment_assessment_item.remarks, payment.remarks AS payment_remarks, payment.status, payment.order_receipt_number");
        $this->db->join("payment", "payment.ID=payment_assessment_item.payment_ID", "left");
        $this->db->join("assessment_item", "assessment_item.ID=payment_assessment_item.assessment_item_ID", "left");
        $this->db->join("account_basic_information", "account_basic_information.account_ID=payment.payee_account_ID", "left");
        
        $this->db->select("payee_account_basic_information.first_name AS payee_first_name, payee_account_basic_information.middle_name AS payee_middle_name, payee_account_basic_information.last_name AS payee_last_name, payee_account_basic_information.identification_number AS payee_identification_number");
        $this->db->join("account_basic_information AS payee_account_basic_information", "payee_account_basic_information.account_ID=payment.payee_account_ID", "left");
        $this->db->join("class_section AS payee_class_section", "payee_class_section.account_ID=payment.payee_account_ID AND payee_class_section.school_year=(select MAX(school_year) from class_section)", "left");
        $this->db->join("account_level AS payee_account_level", "payee_account_level.account_ID=payment.payee_account_ID AND payee_account_level.academic_year=(select MAX(academic_year) from account_level)", "left");

        $this->db->select("payer_account_basic_information.first_name AS payer_first_name, payer_account_basic_information.middle_name AS payer_middle_name, payer_account_basic_information.last_name AS payer_last_name, payer_account_basic_information.identification_number AS payer_identification_number");
        $this->db->join("account_basic_information AS payer_account_basic_information", "payer_account_basic_information.account_ID=payment.payer_account_ID", "left");
        
        $this->db->select("payer_payment_no_account.first_name AS no_account_payer_first_name, payer_payment_no_account.middle_name AS no_account_payer_middle_name, payer_payment_no_account.last_name AS no_account_payer_last_name");
        $this->db->join("payment_no_account AS payer_payment_no_account", "payer_payment_no_account.payment_ID=payment.ID AND payer_payment_no_account.type=2", "left");
        $this->db->select("payee_payment_no_account.first_name AS no_account_payee_first_name, payee_payment_no_account.middle_name AS no_account_payee_middle_name, payee_payment_no_account.last_name AS no_account_payee_last_name");
        $this->db->join("payment_no_account AS payee_payment_no_account", "payee_payment_no_account.payment_ID=payment.ID AND payee_payment_no_account.type=1", "left");
        
        $condition = array();
        $likeCondition = array();
        ($assessmentItemID!= NULL) ? $condition["payment_assessment_item.assessment_item_ID"] = $assessmentItemID : null;
        ($payeeClassSection != NULL) ? $condition["payee_class_section.section_ID"] = $payeeClassSection : null;
        ($payeeAccountYearLevel != NULL) ? $condition["payee_account_level.year_level"] = $payeeAccountYearLevel : null;
        ($payeeAccountID!= NULL) ? $condition["payment.payee_account_ID"] = $payeeAccountID : null;
        ($payerAccountID!= NULL) ? $condition["payment.payer_account_ID"] = $payerAccountID : null;
        ($startDatetime!= NULL) ? $condition["payment.datetime >="] = $startDatetime : null;
        ($endDatetime!= NULL) ? $condition["payment.datetime <="] = strtotime('+1 day', $endDatetime) : null;
		($paymentAcademicYear != NULL) ? $condition["payment.academic_year"] = $paymentAcademicYear : null;
        if($assessmentTypeID!=NULL){
            (is_array($assessmentTypeID)) ? $this->db->where_in("assessment_item.assessment_type_ID", $assessmentTypeID) : $condition["assessment_item.assessment_type_ID"] = $assessmentTypeID;
        }
        if($excludeAssessmentItemID !== NULL){
                $this->db->where_not_in("assessment_item.assessment_type_ID", $excludeAssessmentItemID);
        }
        if(!$retrieveType){
            ($ID!= NULL) ? $condition["payment_assessment_item.ID"] = $ID : null;
            ($orderReceiptNumber!= NULL) ? $condition["payment.order_receipt_number"] = $orderReceiptNumber : null;
            ($cashierID!= NULL) ? $condition["payment.cashier_ID"] = $cashierID : null;
            ($totalAmount!= NULL) ? $condition["payment.total_amount"] = $totalAmount : null;
            ($mode!= NULL) ? $condition["payment.mode"] = $mode : null;
            ($datetime!= NULL) ? $condition["payment.datetime"] = $datetime : null;
            ($remarks!= NULL) ? $condition["payment.remarks"] = $remarks : null;
            ($status!= NULL) ? $condition["payment.status"] = $status : null;
            ($identificationNumber!= NULL) ? $condition["payee_account_basic_information.identification_number"] = $identificationNumber : null;
            ($amount!= NULL) ? $condition["payment_assessment_item.amount"] = $amount : null;
            ($assessmentItemDescription!= NULL) ? $condition["assessment_item.description"] = $assessmentItemDescription : null;
            
        }else{
            ($ID!= NULL) ? $likeCondition["payment_assessment_item.ID"] = $ID : null;
            ($orderReceiptNumber!= NULL) ? $likeCondition["payment.order_receipt_number"] = $orderReceiptNumber : null;
            ($cashierID!= NULL) ? $likeCondition["payment.cashier_ID"] = $cashierID : null;
            ($totalAmount!= NULL) ? $likeCondition["payment.total_amount"] = $totalAmount : null;
            ($mode!= NULL) ? $likeCondition["payment.mode"] = $mode : null;
            ($datetime!= NULL) ? $likeCondition["payment.datetime"] = $datetime : null;
            ($remarks!= NULL) ? $likeCondition["payment.remarks"] = $remarks : null;
            ($status!= NULL) ? $likeCondition["payment.status"] = $status : null;
            ($identificationNumber!= NULL) ? $likeCondition["payee_account_basic_information.identification_number"] = $identificationNumber : null;
            ($amount!= NULL) ? $likeCondition["payment_assessment_item.amount"] = $amount : null;
            ($assessmentItemDescription!= NULL) ? $likeCondition["assessment_item.description"] = $assessmentItemDescription : null;
        }
        if($sort){
            foreach($sort as $key => $value){
                //different table
                $tableColumn = explode("__", $key);
                if(count($tableColumn) > 1){
                    $key = $tableColumn[0].".".$tableColumn[1];
                }
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        $condition["payment.status !="] = 3;
//        print_r($condition);
//        print_r($likeCondition);
        (count($condition) > 0) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        ($limit)?$this->db->limit($limit, $offset):0;
        $this->db->group_by("payment_assessment_item.ID");
        $this->db->distinct("payment_assessment_item.ID");
        $result = $this->db->get("payment_assessment_item");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ( ($ID != NULL ||$orderReceiptNumber != NULL) && !$retrieveType!= NULL) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function retrievePaymentAssessmentItemSummary($retrieveType = false, $limit = false, $offset = 0, $sort = NULL, $ID = NULL, $paymentID = NULL, $assessmentItemID = NULL, $assessmentItemTypeID = NULL, $startDatetime = NULL, $endDatetime = NULL, $amount = NULL, $tellering = NULL, $orderReceiptNumber = NULL){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("assessment_item.description, payment_assessment_item.assessment_item_ID, assessment_type.code as assessment_type_code, payment_assessment_item.assessment_item_ID, SUM(payment_assessment_item.amount) AS total_amount, payment.datetime");
        $this->db->join("assessment_item", "assessment_item.ID=payment_assessment_item.assessment_item_ID", "left");
        $this->db->join("assessment_type", "assessment_type.ID=assessment_item.assessment_type_ID", "left");
        $this->db->join("payment", "payment.ID=payment_assessment_item.payment_ID", "left");
        $condition = array();
        $likeCondition = array();
        ($tellering != NULL) ? $condition["assessment_item.tellering"] = $tellering : null;
        
        ($startDatetime!= NULL) ? $condition["payment.datetime >="] = $startDatetime : null;
        ($endDatetime!= NULL) ? $condition["payment.datetime <"] = strtotime('+1 day', $endDatetime) : null;
        if(!$retrieveType){
            ($ID!= NULL) ? $condition["payment_assessment_item.ID"] = $ID : null;
            ($paymentID!= NULL) ? $condition["payment_assessment_item.payment_ID"] = $paymentID : null;
            ($assessmentItemID!= NULL) ? $condition["payment_assessment_item.assessment_item_ID"] = $assessmentItemID : null;
            ($assessmentItemTypeID!= NULL) ? $condition["assessment_item.assessment_type_ID"] = $assessmentItemTypeID : null;
            ($amount!= NULL) ? $condition["payment_assessment_item.amount"] = $amount : null;
            ($orderReceiptNumber != NULL) ? $condition["payment.order_receipt_number"] = $orderReceiptNumber : null;
        }else{
            ($ID!= NULL) ? $likeCondition["payment_assessment_item.ID"] = $ID : null;
            ($paymentID!= NULL) ? $likeCondition["payment_assessment_item.payment_ID"] = $paymentID : null;
            ($assessmentItemID!= NULL) ? $likeCondition["payment_assessment_item.assessment_item_ID"] = $assessmentItemID : null;
            ($assessmentItemTypeID!= NULL) ? $condition["assessment_item.assessment_type_ID"] = $assessmentItemTypeID : null;
            ($amount!= NULL) ? $likeCondition["payment_assessment_item.amount"] = $amount : null;
            ($orderReceiptNumber != NULL) ? $likeCondition["payment.order_receipt_number"] = $orderReceiptNumber : null;
            
            
        }
        $condition["payment.status !="] = 3;
        (count($condition) > 0) ? $this->db->where($condition) : null;
        (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        $this->db->group_by("payment_assessment_item.assessment_item_ID");
        if($sort != NULL){
            foreach($sort as $key => $value){
                //different table
                $tableColumn = explode("__", $key);
                if(count($tableColumn) > 1){
                    $key = $tableColumn[0].".".$tableColumn[1];
                }
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("payment_assessment_item");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ( ($ID != NULL ) && !$retrieveType!= NULL) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
}
