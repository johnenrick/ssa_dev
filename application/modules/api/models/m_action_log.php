<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_action_log  extends CI_Model{
    
    function createActionLog($accountID, $apiControllerID, $associatedEntryID, $accessCode){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "account_ID" => $accountID,
            "api_controller_ID" => $apiControllerID,
            "associated_entry_ID" => $associatedEntryID,
            "access_code" => $accessCode,
            "datetime" => time()
        );
        $this->db->insert("action_log", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function checkACL($accountID, $tableID, $noParent = true){
        $this->db->start_cache();
        $this->db->flush_cache();
        
        if(!$noParent){
            
            $this->db->join("module", "module.parent_ID=access_control_list.module_ID AND module_table", "left");
            $this->db->join("module_table", "module_table.module_ID=module.ID", "left");
            
        }else{
            $this->db->join("module_table", "module_table.module_ID=access_control_list.module_ID", "left");
        }
        $condition = array();
        $condition["module_table.table_ID"] = $tableID;
        $condition["access_control_list.account_ID"] = $accountID;
        
                
        
        (count($condition) > 0) ? $this->db->where($condition) : null;
        $result = $this->db->get("access_control_list");
        
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result->num_rows();
    }
    
}
