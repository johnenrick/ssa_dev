<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class m_room extends CI_Model {

    public function createRoom($buildingID, $description, $capacity) {
        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "building_ID" => $buildingID,
            "description" => $description,
            "capacity" => $capacity
        );
        $this->db->insert("room", $data);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $this->db->insert_id();
    }

    public function checkRoomName($name, $building, $ID = false) {
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("building_ID", $building);
        $this->db->where("description", $name);
        $ID ? $this->db->where("room.ID !=", $ID) : null;
                
        $this->db->flush_cache();
        $this->db->stop_cache();
        $result  = $this->db->get("room");
        if ($result->num_rows()){
            
            return 1;
        }else{
            return 0;
        }
    }

    public function retrieveRoom($retrieveType = false, $limit = false, $offset = 0, $ID = false, $buildingID = false, $description = false, $capacity = false) { //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*, room.ID");
        //$this->db->select("room.ID, room.description, course.description AS course_description");
        //$this->db->join("course", "course.ID = room.course_ID", "inner");
        $condition = array();
        $likeCondition = array();
        if (!$retrieveType) {
            ($ID) ? $condition["ID"] = $ID : null;                              // "room.ID"
            ($buildingID) ? $condition["building_ID"] = $buildingID : null;     // "room.building_ID"
            ($description) ? $condition["description"] = $description : null;   // "room.description"
            ($capacity) ? $condition["capacity"] = $capacity : null;            // "room.capacity"

            (count($condition) > 0) ? $this->db->where($condition) : null;
        } else {
            ($ID) ? $likeCondition["ID"] = $ID : null;
            ($buildingID) ? $likeCondition["building_ID"] = $buildingID : null;
            ($description) ? $likeCondition["description"] = $description : null;
            ($capacity) ? $likeCondition["capacity"] = $capacity : null;

            (count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
        }
        ($limit) ? $this->db->limit($limit, $offset) : 0;
        $this->db->order_by("building_ID", "asc");
        $this->db->order_by("description", "asc");
        $this->db->order_by("capacity", "asc");
        $result = $this->db->get("room");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if ($result->num_rows()) {
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        } else {
            return false;
        }
    }

    function updateRoom($ID, $buildingID = false, $description = false, $capacity = false) {
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($buildingID) ? $newData["building_ID"] = $buildingID : null;
        ($description) ? $newData["description"] = $description : null;
        ($capacity) ? $newData["capacity"] = $capacity : null;
        $this->db->where("ID", $ID);
        $result = $this->db->update("room", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    function deleteRoom($ID) {
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("room");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

}
