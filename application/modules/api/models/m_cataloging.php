<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_cataloging  extends CI_Model{

    function createMaterial($control_number,$title,$author,$accession_number,$call_number,$copyright,$edition,$library_material_access_control_ID,$quantity,$dateAcquired,$supplier,$aeauthor1,$aeauthor2,$aeauthor3,$physicalDescription,$publisher,$subject1,$subject2,$subject3,$subject4,$subject5,$addedEntryTitle,$publisherAddress,$isbn,$notes,$location,$seriesTitle,$typeOfMaterial,$costPrice){

        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array(
            "control_number" => $control_number,
            "title" => $title,
            "author" => $author,
            "accession_number" => $accession_number,
            "call_number" => $call_number,
            "publisher" => $publisher,
            "copyright" => $copyright,
            "edition" => $edition,
            "library_material_access_control_ID" => $library_material_access_control_ID,
            "quantity" => $quantity,
            "date_acquired" => $dateAcquired,
            "supplier" => $supplier,
            "ae_author1" => $aeauthor1,
            "ae_author2" => $aeauthor2,
            "ae_author3" => $aeauthor3,
            "physical_description" => $physicalDescription,
            "subject1" => $subject1,
            "subject2" => $subject2,
            "subject3" => $subject3,
            "subject4" => $subject4,
            "subject5" => $subject5,
            "added_entry_title" => $addedEntryTitle,
            "publisher_address" => $publisherAddress,
            "isbn" => $isbn,
            "notes" => $notes,
            "location" => $location,
            "series_title" => $seriesTitle,
            "type_of_material" => $typeOfMaterial,
            "cost_price" => $costPrice
        );
        $this->db->insert("library_material", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID;
    }
    function retrieveMaterial($retrieveType = false, $limit = false, $offset = 0, $ID = false, $title = false, $author = false, $accession_number = false, $call_number = false, $publisher = false, $subject=false,$isbn=false,$notes=false,$keyword=false, $sort = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("*");
        $condition = array();
        $likeCondition = array();
        if(!$retrieveType){
            ($ID) ? $condition["library_material.ID"] = $ID : null;
            ($title) ? $condition["library_material.title"] = $title : null;
            ($author) ? $condition["library_material.author"] = $author : null;
            ($author) ? $condition["library_material.ae_author1"] = $author : null;
            ($author) ? $condition["library_material.ae_author2"] = $author : null;
            ($author) ? $condition["library_material.ae_author3"] = $author : null;
            ($accession_number) ? $condition["library_material.accession_number"] = $accession_number : null;
            ($call_number) ? $condition["library_material.call_number"] = $call_number : null;
            ($publisher) ? $condition["library_material.publisher"] = $publisher : null;
            ($subject) ? $condition["library_material.subject1"] = $subject : null;
            ($subject) ? $condition["library_material.subject2"] = $subject : null;
            ($subject) ? $condition["library_material.subject3"] = $subject : null;
            ($subject) ? $condition["library_material.subject4"] = $subject : null;
            ($subject) ? $condition["library_material.subject5"] = $subject : null;
            ($isbn) ? $condition["library_material.isbn"] = $isbn : null;
            ($notes) ? $condition["library_material.notes"] = $notes : null;


            (count($condition) > 0) ? $this->db->where($condition) : null;
        }else{
            ($ID) ? $likeCondition["library_material.ID"] = $ID : null;
            ($title) ? $likeCondition["library_material.title"] = $title : null;
            ($author) ? $likeCondition["library_material.author"] = $author : null;
            ($accession_number) ? $likeCondition["library_material.accession_number"] = $accession_number : null;
            ($call_number) ? $likeCondition["library_material.call_number"] = $call_number : null;
            ($publisher) ? $likeCondition["library_material.publisher"] = $publisher : null;

            ($author) ? $likeCondition["library_material.ae_author1"] = $author : null;
            ($author) ? $likeCondition["library_material.ae_author2"] = $author : null;
            ($author) ? $likeCondition["library_material.ae_author3"] = $author : null;
            ($subject) ? $likeCondition["library_material.subject1"] = $subject : null;
            ($subject) ? $likeCondition["library_material.subject2"] = $subject : null;
            ($subject) ? $likeCondition["library_material.subject3"] = $subject : null;
            ($subject) ? $likeCondition["library_material.subject4"] = $subject : null;
            ($subject) ? $likeCondition["library_material.subject5"] = $subject : null;
            ($isbn) ? $likeCondition["library_material.isbn"] = $isbn : null;
            ($notes) ? $likeCondition["library_material.notes"] = $notes : null;
            if($keyword){
                $likeCondition["library_material.title"] = $keyword;
                $likeCondition["library_material.author"] = $keyword;
                $likeCondition["library_material.accession_number"] = $keyword;
                $likeCondition["library_material.call_number"] = $keyword;
                $likeCondition["library_material.publisher"] = $keyword;
                $likeCondition["library_material.ae_author1"] = $keyword;
                $likeCondition["library_material.ae_author2"] = $keyword;
                $likeCondition["library_material.ae_author3"] = $keyword;
                $likeCondition["library_material.subject1"] = $keyword;
                $likeCondition["library_material.subject2"] = $keyword;
                $likeCondition["library_material.subject3"] = $keyword;
                $likeCondition["library_material.subject4"] = $keyword;
                $likeCondition["library_material.subject5"] = $keyword;
                $likeCondition["library_material.isbn"] = $keyword;
                $likeCondition["library_material.notes"] = $keyword;
            }

            (count($likeCondition) > 0) ? (($keyword)?$this->db->or_like($likeCondition):$this->db->like($likeCondition)) : null;
        }
        if($sort){
            foreach($sort as $key => $value){
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        $result = $this->db->get("library_material");
        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
            return ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
        }else{
            return false;
        }
    }

    function updateMaterial($ID=false,$title=false,$author=false,$accession_number=false,$call_number=false,$copyright=false,$edition=false,$library_material_access_control_ID=false,$dateAcquired=false,$supplier=false,$aeauthor1=false,$aeauthor2=false,$aeauthor3=false,$physicalDescription=false,$publisher=false,$subject1=false,$subject2=false,$subject3=false,$subject4=false,$subject5=false,$addedEntryTitle=false,$publisherAddress=false,$isbn=false,$notes=false,$location=false,$seriesTitle=false,$typeOfMaterial=false,$costPrice=false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $newData = array();
        ($title) ? $newData["title"] = $title : null;
        ($author) ? $newData["author"] = $author : null;
        ($accession_number) ? $newData["accession_number"] = $accession_number : null;
        ($call_number) ? $newData["call_number"] = $call_number : null;
        ($copyright) ? $newData["copyright"] = $copyright : null;
        ($edition) ? $newData["edition"] = $edition : null;
        ($library_material_access_control_ID) ? $newData["library_material_access_control_ID"] = $library_material_access_control_ID : null;
        ($dateAcquired) ? $newData["date_acquired"] = $dateAcquired :  $newData["date_acquired"] = 0;
        ($supplier) ? $newData["supplier"] = $supplier : null;
        ($aeauthor1) ? $newData["ae_author1"] = $aeauthor1 : null;
        ($aeauthor2) ? $newData["ae_author2"] = $aeauthor2 : null;
        ($aeauthor3) ? $newData["ae_author3"] = $aeauthor3 : null;
        ($physicalDescription) ? $newData["physical_description"] = $physicalDescription : null;
        ($publisher) ? $newData["publisher"] = $publisher : null;
        ($subject1) ? $newData["subject1"] = $subject1 : null;
        ($subject2) ? $newData["subject2"] = $subject2 : null;
        ($subject3) ? $newData["subject3"] = $subject3 : null;
        ($subject4) ? $newData["subject4"] = $subject4 : null;
        ($subject5) ? $newData["subject5"] = $subject5 : null;
        ($addedEntryTitle) ? $newData["added_entry_title"] = $addedEntryTitle : null;
        ($publisherAddress) ? $newData["publisher_address"] = $publisherAddress : null;
        ($isbn) ? $newData["isbn"] = $isbn : null;
        ($notes) ? $newData["notes"] = $notes : null;
        ($location) ? $newData["location"] = $location : null;
        ($seriesTitle) ? $newData["series_title"] = $seriesTitle : null;
        ($typeOfMaterial) ? $newData["type_of_material"] = $typeOfMaterial : null;
        ($costPrice) ? $newData["cost_price"] = $costPrice : null;

        $this->db->where("ID", $ID);
        $result = $this->db->update("library_material", $newData);
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    function deleteMaterial($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("library_material");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }

    function retrieveForExcel(){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->select("accession_number as accession, date_acquired as date, call_number as class, author, title, publisher_address as place, publisher, copyright, notes as remarks");
        $this->db->order_by("accession_number", "asc");
        $result = $this->db->get("library_material");
        $this->db->start_cache();
        $this->db->flush_cache();
        //echo sizeof($result->result_array());
        return $result->result_array();
    }

}
