<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_maintainable  extends CI_Model{
    
    function createMaintainable($matainableTypeID, $description){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "maintainable_type_ID" => $matainableTypeID,
            "description" => $description
        );
        $this->db->insert("maintainable", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveMaintainable($retrieveType = false, $limit = false, $offset = 0, $ID = false, $matainableTypeID = false, $description = false, $sort = false ){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["maintainable.ID"] = $ID : null;
            ($matainableTypeID) ? $condition["maintainable.maintainable_type_ID"] = $matainableTypeID : null;
            ($description) ? $condition["maintainable.description"] = $description : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["maintainable.ID"] = $ID : null;
           ($matainableTypeID) ? $likeCondition["maintainable.maintainable_type_ID"] = $matainableTypeID : null;
            ($description) ? $likeCondition["maintainable.description"] = $description : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
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
        $result = $this->db->get("maintainable");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateMaintainable($ID, $matainableTypeID = false, $description = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($matainableTypeID) ? $newData["maintainable_type_ID"] = $matainableTypeID : null;
        ($description) ? $newData["description"] = $description : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("maintainable", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteMaintainable($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("maintainable");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
