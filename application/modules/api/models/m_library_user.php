<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_library_user  extends CI_Model{

    function createMaterialAccessControl($description,$period,$unit,$fineRate){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "description" => $description,
            "period" => $period,
            "unit" => $unit,
            "fine_rate" => $fineRate
        );
        $this->db->insert("library_material_access_control", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID;
    }
    function retrieveMaterialAccessControl($retrieveType = false, $limit = false, $offset = 0, $ID = false, $description = false ){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["library_material_access_control.ID"] = $ID : null;
            ($description) ? $condition["library_material_access_control.description"] = $description : null;

            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["library_material_access_control.ID"] = $ID : null;
            ($description) ? $likeCondition["library_material_access_control.description"] = $description : null;

            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("library_material_access_control");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }

    function updateMaterialAccessControl($ID = false, $description = false, $period = false, $unit = false, $fineRate = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($description) ? $newData["description"] = $description : null;
        ($period) ? $newData["period"] = $period : null;
        $newData["unit"] = $unit;
        ($fineRate) ? $newData["fine_rate"] = $fineRate : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("library_material_access_control", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    function deleteMaterialAccessControl($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("library_material_access_control");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }


}
