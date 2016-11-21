<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_file_uploaded  extends CI_Model{
    
    function createFileUploaded($moduleID, $path, $fileName, $extension){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "module_ID" => $moduleID,
            "path" => $path,
            "file_name" => $fileName,
            "extension" => $extension,
            "datetime" => time(),
        );
        $this->db->insert("file_uploaded", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID; 
    }
    function retrieveFileUploaded($retrieveType = false, $limit = false, $offset = 0, $ID = false, $moduleID = false, $path = false, $fileName = false, $extension = false, $datetime = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["file_uploaded.ID"] = $ID : null;
            ($moduleID) ? $condition["file_uploaded.module_ID"] = $moduleID : null;
            ($path) ? $condition["file_uploaded.path"] = $path : null;
            ($fileName) ? $condition["file_uploaded.file_name"] = $fileName : null;
            ($extension) ? $condition["file_uploaded.extension"] = $extension : null;
            ($datetime) ? $condition["file_uploaded.datetime"] = $datetime : null;
            
            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["file_uploaded.ID"] = $ID : null;
            ($moduleID) ? $likeCondition["file_uploaded.module_ID"] = $moduleID : null;
            ($path) ? $likeCondition["file_uploaded.path"] = $path : null;
            ($fileName) ? $likeCondition["file_uploaded.file_name"] = $fileName : null;
            ($extension) ? $likeCondition["file_uploaded.extension"] = $extension : null;
            ($datetime) ? $likeCondition["file_uploaded.datetime"] = $datetime : null;
            
            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("file_uploaded");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }
    
    function updateFileUploaded($ID, $moduleID = false, $path = false, $fileName = false, $extension = false, $datetime = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($moduleID) ? $newData["module_ID"] = $moduleID : null;
        ($path) ? $newData["path"] = $path : null;
        ($fileName) ? $newData["file_name"] = $fileName : null;
        ($extension) ? $newData["extension"] = $extension : null;
        ($datetime) ? $newData["datetime"] = $datetime : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("file_uploaded", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    function deleteFileUploaded($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("file_uploaded");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }
    
    
}
