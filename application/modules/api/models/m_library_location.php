<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_library_location  extends CI_Model{

    function createMaterialAccessControl($description){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array();
        $x = 0;
        foreach($period as $user)
        {
            array_push($data, array(
                "description" => $description
            ));
            $x++;
        }
        $this->db->insert_batch("library_material_location", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID;
    }
    function retrieveMaterialAccessControl($retrieveType = false, $limit = false, $offset = 0, $ID = false, $description = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*, description");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["library_material_location.ID"] = $ID : null;
            ($description) ? $condition["library_material_location.description"] = $description : null;

            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["library_material_location.ID"] = $ID : null;
            ($description) ? $likeCondition["library_material_location.description"] = $description : null;

            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;

        $this->db->order_by("description", "asc");


        $result = $this->db->get("library_material_location");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return  $result->result_array();
        }else{
            return false;
        }
    }

    function updateMaterialAccessControl($ID = false, $description = false){

         $newData = array(
                    "description" => $description
                );
            $this->db->start_cache();
            $this->db->flush_cache();
            $this->db->where(array('ID'=> $ID));
            $result = $this->db->update('library_material_location', $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    function deleteMaterialAccessControl($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where(array('ID ' => $ID));
        $result = $this->db->delete("library_material_location");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    //library users
    function retrieveLibraryUser($retrieveType = false, $limit = false, $offset = 0, $ID = false, $description = false ){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["library_user.ID"] = $ID : null;
            ($description) ? $condition["library_user.description"] = $description : null;

            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["library_user.ID"] = $ID : null;
            ($description) ? $likeCondition["library_user.description"] = $description : null;

            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("library_user");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }



}
