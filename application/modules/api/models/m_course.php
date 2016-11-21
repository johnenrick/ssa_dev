<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_course  extends CI_Model{
    
    function createCourse($code, $description){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "code" => $code,
            "description" => $description
        );
        $this->db->insert("course", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveCourse($retrieveType = false, $limit = false, $offset = 0, $ID = false, $code = false, $description = false ){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["course.ID"] = $ID : null;
            ($code) ? $condition["course.code"] = $code : null;
            ($description) ? $condition["course.description"] = $description : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["course.ID"] = $ID : null;
           ($code) ? $likeCondition["course.code"] = $code : null;
            ($description) ? $likeCondition["course.description"] = $description : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("course");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateCourse($ID, $code = false, $description = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($code) ? $newData["code"] = $code : null;
        ($description) ? $newData["description"] = $description : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("course", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteCourse($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("course");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
