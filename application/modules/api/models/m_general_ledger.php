<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_general_ledger  extends CI_Model{
    
    function createGeneralLedger($description, $code){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "code" => $code,
            "description" => $description
        );
        $this->db->insert("general_ledger", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveGeneralLedger($retrieveType = false, $limit = false, $offset = 0, $ID = false, $description = false, $code = false, $sort = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["general_ledger.ID"] = $ID : null;
            ($code) ? $condition["general_ledger.code"] = $code : null;
            ($description) ? $condition["general_ledger.description"] = $description : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["general_ledger.ID"] = $ID : null;
            ($code) ? $likeCondition["general_ledger.code"] = $code : null;
            ($description) ? $likeCondition["general_ledger.description"] = $description : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        if($sort){
            foreach($sort as $key => $value){
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        $this->db->order_by("general_ledger.description", "asc");
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("general_ledger");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateGeneralLedger($ID, $code = false, $description = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($code) ? $newData["code"] = $code : null;
        ($description) ? $newData["description"] = $description : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("general_ledger", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteGeneralLedger($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("general_ledger");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
