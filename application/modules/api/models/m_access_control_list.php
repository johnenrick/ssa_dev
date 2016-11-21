<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_access_control_list  extends CI_Model{
    
    function createAccessControlList($moduleID, $accountID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "module_ID" => $moduleID,
            "account_ID" => $accountID
        );
        $this->db->insert("access_control_list", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAccessControlList($retrieveType = 0, $limit= 0, $offset = 0, $ID= NULL, $moduleID = NULL, $accountID = NULL, $sort = false ){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $this->db->select("access_control_list.ID, access_control_list.account_ID, module.description, module.description, module.ID AS module_ID, module.link AS module_link");
        $this->db->join("module", "module.ID=access_control_list.module_ID", "left");
        $this->db->select("parent_module.ID AS parent_module_ID, parent_module.description AS parent_module_description, parent_module.link AS parent_module_link");
        $this->db->join("module AS parent_module", "parent_module.ID=module.parent_ID", "left");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID != NULL) ? $condition["access_control_list.ID"] = $ID : null;
            ($moduleID != NULL) ? $condition["access_control_list.module_ID"] = $moduleID : null;
            ($accountID != NULL) ? $condition["access_control_list.account_ID"] = $accountID : null;
            
            
        }else{
            ($ID != NULL) ? $likeCondition["access_control_list.ID"] = $ID : null;
           ($moduleID != NULL) ? $likeCondition["access_control_list.module_ID"] = $moduleID : null;
            ($accountID != NULL) ? $likeCondition["access_control_list.account_ID"] = $accountID : null;
            
            
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
        $result = $this->db->get("access_control_list");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType != NULL) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    function retrieveModule($ID= NULL, $parentID = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        ($ID != NULL) ? $condition["module.ID"] = $ID : null;
        ($parentID != NULL) ? $condition["module.parent_ID"] = $parentID : null;
        (count($condition) > 0) ? $this->db->where($condition) : null;
        $result = $this->db->get("module");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType != NULL) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
        
    }
    function updateAccessControlList($ID, $moduleID= NULL, $accountID= NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($moduleID != NULL) ? $newData["module_ID"] = $moduleID : null;
        ($accountID != NULL) ? $newData["account_ID"] = $accountID : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("access_control_list", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteAccessControlList($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("account_ID", $ID);
        $result = $this->db->delete("access_control_list");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    function batchCreateAccessControlList($list){
        $this->db->start_cache();
        $this->db->flush_cache();
        $result = $this->db->insert_batch("access_control_list", $list);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
