<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_assessment_type  extends CI_Model{
    
    function createAssessmentType($description, $code){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "description" => $description,
            "code" => $code
        );
        $this->db->insert("assessment_type", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveAssessmentType($retrieveType = false, $limit = false, $offset = 0, $ID = false, $description = NULL, $code = NULL, $sort = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["assessment_type.ID"] = $ID : null;
            ($description != NULL) ? $condition["assessment_type.description"] = $description : null;
            ($code != NULL) ? $condition["assessment_type.code"] = $code : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["assessment_type.ID"] = $ID : null;
            ($description != NULL) ? $likeCondition["assessment_type.description"] = $description : null;
            ($code != NULL) ? $likeCondition["assessment_type.code"] = $code : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        if($sort){
            foreach($sort as $key => $value){
                
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("assessment_type");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateAssessmentType($ID, $description = NULL, $code = NULL){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($description != NULL) ? $newData["description"] = $description : null;
        ($code != NULL) ? $newData["code"] = $code : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("assessment_type", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteAssessmentType($ID){
        if($ID != 1 && $ID != 131 && $ID != 132 && $ID != 133 && $ID != 134 && $ID != 135){
            $this->db->start_cache();
            $this->db->flush_cache();
            $this->db->where("ID", $ID);
            $result = $this->db->delete("assessment_type");
            $this->db->flush_cache();
            $this->db->stop_cache();
            return $result;
        }else{
            return false;
        }
    }
    
    
}
