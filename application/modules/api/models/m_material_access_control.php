<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_material_access_control  extends CI_Model{

    function createMaterialAccessControl($description,$userID,$period,$unit,$fineRate){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array();
        $x = 0;
        foreach($period as $user)
        {
            array_push($data, array(
                "description" => $description,
                "library_user_ID" => $userID[$x],
                "period" => $period[$x],
                "unit" => $unit[$x],
                "fine_rate" => $fineRate[$x]
            ));
            $x++;
        }
        $this->db->insert_batch("library_material_access_control", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID;
    }
    function retrieveMaterialAccessControl($retrieveType = false, $limit = false, $offset = 0, $ID = false, $description = false, $byusers = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*, description");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID && !$byusers) ? $condition["library_material_access_control.ID"] = $ID : null;
            ($description) ? $condition["library_material_access_control.description"] = $description : null;

            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID && !$byusers) ? $likeCondition["library_material_access_control.ID"] = $ID : null;
            ($description) ? $likeCondition["library_material_access_control.description"] = $description : null;

            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        if($byusers)
        {
            $array = array('ID <' => $ID+$byusers, 'ID >' => $ID-1);
            $this->db->where($array);
        }
        else
        {
            $this->db->group_by("description");
            $this->db->order_by("description", "asc");

        }
        $result = $this->db->get("library_material_access_control");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return  $result->result_array();
        }else{
            return false;
        }
    }

    function updateMaterialAccessControl($ID = false, $description = false, $userID = false, $period = false, $unit = false, $fineRate = false){

        $newData = array();
        $x = 0;
        foreach($period as $user)
        {
            $newData = array(
                    "description" => $description,
                    "period" => $period[$x],
                    "unit" => $unit[$x],
                    "fine_rate" => $fineRate[$x]
                );
            $this->db->start_cache();
            $this->db->flush_cache();
            $this->db->where(array('ID'=> $ID+$x, 'library_user_ID'=>$userID[$x]));
            $result = $this->db->update('library_material_access_control', $newData);
            $x++;
        }
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    function deleteMaterialAccessControl($ID,$users){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where(array('ID <' => $ID+$users, 'ID >' => $ID-1));
        $result = $this->db->delete("library_material_access_control");
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
