<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class m_circulation extends CI_Model{

    function createCirculation($borrowerID = false, $libraryUsers = false, $datetime = false, $loanBookID = false){

        $this->db->start_cache();
        $this->db->flush_cache();
        $data = array();

        $this->db->select('account_ID');
        $this->db->where('identification_number',$borrowerID);
        $this->db->from("account_basic_information");
        $res = $this->db->get();
        if($res->num_rows()) $borrowerID = $res->row_array()['account_ID'];
        else return false;
        $this->db->flush_cache();
        $this->db->stop_cache();
        foreach($loanBookID as $book)
        {
            array_push($data, array(
                        "borrower_ID" => $borrowerID,
                        "library_user_ID" => $libraryUsers,
                        "loan_datetime" => $datetime,
                        "book_ID" => $book
                    ));
            $this->db->start_cache();
            $this->db->flush_cache();
            $this->db->set("loaned","loaned+1",FALSE);
            $this->db->where("ID",$book);
            $this->db->where("loaned <= quantity");
            $result = $this->db->update("library_material");
            $this->db->flush_cache();
            $this->db->stop_cache();
        }
        $this->db->insert_batch("library_circulation", $data);
        $ID = $this->db->insert_id();
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $ID;
    }
    function retrieveCirculation($retrieveType = false, $limit = false, $offset = 0, $ID = false, $borrowerID = false, $datetime = false, $group = true, $sort = false, $name = false, $returnDatetime = NULL, $title = false, $borrowerFullName = false){ //retrieveType: 0 - normal, 1 - search
        $this->db->start_cache();
        $this->db->flush_cache();
        if($borrowerID)
        {
            $this->db->select('account_ID');
            $this->db->where('identification_number',$borrowerID);
            $this->db->from("account_basic_information");
            $res = $this->db->get();
            if($res->num_rows()) $borrowerID = $res->row_array()['account_ID'];
            else return false;
            $this->db->flush_cache();
            $this->db->stop_cache();
        }
        //$borrowerID = 1812;
        $this->db->_protect_identifiers=false;
        $this->db->select("loan.*, info.identification_number, info.first_name, info.last_name, info.middle_name, book.accession_number, book.title, book.quantity, book.loaned, control.period, control.unit, control.fine_rate, CONCAT(first_name,middle_name,last_name) AS borrower_full_name, (loan_datetime+control.period*86400) AS load_due_datetime", false);
        ($group)?0:$this->db->select("sum(case when loan.return_datetime = 0 then 0 else 1 end) as returned, count(loan.ID) as totalItem");
        $condition = array();
        $likeCondition = array();
        ($returnDatetime !== NULL) ? $condition["loan.return_datetime"] = $returnDatetime : null;
        ($title !== NULL) ? $likeCondition["book.title"] = $title: null;
        ($borrowerFullName !== NULL) ? $likeCondition["CONCAT(first_name,middle_name,last_name)"] = $borrowerFullName: null;
        if(!$retrieveType){
            ($ID) ? $condition["loan.ID"] = $ID : null;
            ($borrowerID) ? $condition["loan.borrower_ID"] = $borrowerID : null;
            //($datetime) ? $condition["loan.loan_datetime"] = $datetime : null;

            
        }else{
            if($name)
            {
                $likeCondition["info.first_name"] = $name;
                $likeCondition["info.middle_name"] = $name;
                $likeCondition["info.last_name"] = $name;
            }
            else{
                ($ID) ? $likeCondition["loan.ID"] = $ID : null;
                ($borrowerID) ? $likeCondition["loan.borrower_ID"] = $borrowerID : null;
                //($datetime) ? $condition["loan.loan_datetime"] = $datetime : null;

            }

            //(count($likeCondition) > 0) ? $this->db->like($likeCondition) : null;
            
        }
        (count($likeCondition) > 0) ? (($name)?$this->db->or_like($likeCondition):$this->db->like($likeCondition)) : null;
        (count($condition) > 0) ? $this->db->where($condition) : null;
        $this->db->from('library_circulation as loan');
        $this->db->join('library_material as book', 'book.ID = loan.book_ID','left');
        $this->db->join('library_material_access_control as control', 'control.ID = book.library_material_access_control_ID + loan.library_user_ID - 1','left');
        $this->db->join('account_basic_information as info', 'info.account_ID = loan.borrower_ID','left');

        if($borrowerID)
        {
            //if($datetime) $this->db->where("loan.return_datetime",0);
        }
       if($sort){
            foreach($sort as $key => $value){
                $this->db->order_by($key, ($value) ? "asc" : "desc");
            }
        }
        else {
            if($borrowerID) $this->db->order_by("loan.loan_datetime", "desc");
            else $this->db->order_by("loan.borrower_ID", "desc");
        }
        ($limit)?$this->db->limit($limit, $offset):0;
        ($group)?0:$this->db->group_by(array("loan.borrower_ID"));
        //$this->db->group_by("borrower_ID");
        $result = $this->db->get();

        $this->db->flush_cache();
        $this->db->stop_cache();
        if($result->num_rows()){
        /*    $res = ($ID && !$retrieveType) ? $result->row_array() : $result->result_array();
            for($x=0;$x<$result->num_rows();$x++)
            {
                $this->db->select('*');
                $this->db->where('ID',$res[$x]['borrower_ID']);
                $this->db->from("account_basic_information");
                $res[$x]['borrower_info'] = $this->db->get()->row_array();
                $this->db->flush_cache();
                $this->db->stop_cache();
                if($borrowerID && $datetime) break;
            }
            return $res;
        */
        $this->db->_protect_identifiers=true;
        return $result->result_array();

        }else{
            return false;
        }
    }

    function updateCirculation($borrowerID = false, $loanBookID = false, $bookID = false, $returndatetime = false){
        $this->db->start_cache();
        $this->db->flush_cache();
        $data1 = array();
        $data2 = array();

        if($borrowerID)
        {
            $this->db->select('account_ID');
            $this->db->where('identification_number',$borrowerID);
            $this->db->from("account_basic_information");
            $res = $this->db->get();
            if($res->num_rows()) $borrowerID = $res->row_array()['account_ID'];
            else return false;
            $this->db->flush_cache();
            $this->db->stop_cache();
        }


        $this->db->select("circulation.* , book.accession_number, book.title, book.quantity, book.loaned, control.period, control.unit, control.fine_rate");
        $this->db->where('circulation.ID',$loanBookID);
        $this->db->from("library_circulation as circulation");
        $this->db->join('library_material as book', 'book.ID = circulation.book_ID','left');
        $this->db->join('library_material_access_control as control', 'control.ID = book.library_material_access_control_ID - 1 + circulation.library_user_ID','left');
        $res = $this->db->get();
        if($res->num_rows()) $result = $res->row_array();
        $this->db->flush_cache();
        $this->db->stop_cache();


        $seconds = $returndatetime - $result['loan_datetime'];
         $days = floor($seconds/86400);
         $hrs = floor($seconds/3600);
         $mins = intval(($seconds / 60) % 60);
         $sec = intval($seconds % 60);
        if($days>0){
          //echo $days;exit;
              $hrs = str_pad($hrs,2,'0',STR_PAD_LEFT);
              $hours=$hrs-($days*24);
              $return_days = $days;
              $hrs = str_pad($hours,2,'0',STR_PAD_LEFT);
         }else{
          $return_days="";
          $hrs = str_pad($hrs,2,'0',STR_PAD_LEFT);
         }

         $mins = str_pad($mins,2,'0',STR_PAD_LEFT);
         $sec = str_pad($sec,2,'0',STR_PAD_LEFT);

        //echo "[d:".$days."_".$return_days."|h:".$hrs."|m:".$mins."|s:".$sec."]";
        //echo "[".(($return_days*24)+$hrs)."/".$result['period']."=".((($return_days*24)+$hrs)/$result['period'])."]";
        $fine_rate = $result;
        if($result['unit'])
        {
            $fine = 0;//($return_days/$result['period']) * $result['fine_rate'];
        }
        else{
            $fine = 0;//((($return_days*24)+$hrs)/$result['period']) * $result['fine_rate'];
        }

        $data1 = array(
                    "fine" => $fine,
                    "return_datetime" => (($returndatetime)?$returndatetime:null)
                );
        $this->db->flush_cache();
        $this->db->stop_cache();
        $this->db->where(array(
            'ID'=> $loanBookID
        ));
        $result = $this->db->update("library_circulation", $data1);

        $this->db->flush_cache();
        $this->db->stop_cache();
        $this->db->set('loaned', 'loaned-1', FALSE);
        $this->db->where(array('ID'=> $bookID, 'loaned >' => 0));
        $result = $this->db->update("library_material");
        $this->db->flush_cache();
        $this->db->stop_cache();



        return array("data"=>$fine_rate,"return_datetime"=>$returndatetime);
    }

    function deleteCirculation($ID){
        $this->db->start_cache();
        $this->db->flush_cache();
        $this->db->where("ID", $ID);
        $result = $this->db->delete("library_circulation");
        $this->db->flush_cache();
        $this->db->stop_cache();
        return $result;
    }


    /// RFID Scan
    function registerRFID($data=false){
        $this->db->start_cache();
        $this->db->flush_cache();

        $list = array();
        for($x=0;$x<sizeof($data);$x+=2){
            array_push($list,array('identification_number'=>$data[$x]['value'],'rfid'=>$data[$x+1]['value']));
        }
        $this->db->update_batch('account_basic_information', $list, 'identification_number');
        return true;
    }

    function retrieveDisplayID($uid=false,$id=false){
        $this->db->start_cache();
        $this->db->flush_cache();

        $this->db->select('*');
        if($uid) $this->db->where('rfid',$uid);
        else $this->db->where('identification_number',$id);
        $this->db->from("account_basic_information");
        $res = $this->db->get();

        $this->db->flush_cache();
        $this->db->stop_cache();

        if($res->num_rows()) return $res->row_array();
        else return false;
    }

    function retrieveDisplayStudent($uid,$location,$action){
        $this->db->start_cache();
        $this->db->flush_cache();

        $this->db->select('*');
        $this->db->where('rfid',$uid);
        $this->db->from("account_basic_information");
        $res = $this->db->get();

        $this->db->flush_cache();
        $this->db->stop_cache();
        if($res->num_rows()){
            $result = $res->row_array();
            $this->db->insert("student_log", array('account_ID' =>$result['account_ID'],'location'=>$location, 'in_out'=>$action));
            $this->db->flush_cache();
            $this->db->stop_cache();
        }
        if($res->num_rows()) return $result;
        else return false;
    }


    // Report
    function retrieveLibraryPortalList($start,$end, $academicYear){
        $this->db->start_cache();
        $this->db->flush_cache();

        $start = date("Y-m-d 00:00:00", $start);
        $end = date("Y-m-d 24:00:00", $end);
        $this->db->select('acct.description, accl.year_level, count(log.ID) as total');
        $condition = array(
            'log.datetime >='=> $start,
            'log.datetime <='=> $end,
            'log.location'=>2,
            'log.account_ID !='=>''
        );
        $this->db->where($condition);
        $this->db->join('account as acc', 'acc.ID = log.account_ID','left');
        $this->db->join('account_type as acct', 'acct.ID = acc.account_type_ID','left');
        $this->db->join('account_level as accl', 'accl.account_ID = log.account_ID AND accl.academic_year='.$academicYear,'left');
        $this->db->from("student_log as log");
        $this->db->order_by("accl.year_level");
        $this->db->group_by(array("acct.ID", "accl.year_level"));
        
        
        $res = $this->db->get();

        $this->db->flush_cache();
        $this->db->stop_cache();
        $result = $res->result_array();
        if($res->num_rows()) return $result;
        else return false;
    }
    function retrieveCirculationList($start,$end){
        $this->db->start_cache();
        $this->db->flush_cache();

        $start = strtotime("midnight", $start);
        $end = strtotime("tomorrow", $start) - 1;

        //echo $start." - ".$end;
        $this->db->select('acct.description, accl.year_level, count(log.ID) as total');
        $condition = array(
            'log.loan_datetime >='=> $start,
            'log.loan_datetime <='=> $end
        );
        $this->db->where($condition);
        $this->db->join('account as acc', 'acc.ID = log.borrower_ID','left');
        $this->db->join('account_type as acct', 'acct.ID = acc.account_type_ID','left');
        $this->db->join('account_level as accl', 'accl.account_ID = log.borrower_ID','left');
        $this->db->from("library_circulation as log");
        $this->db->order_by("accl.year_level");
        $this->db->group_by(array("accl.year_level"));
        $res = $this->db->get();

        $this->db->flush_cache();
        $this->db->stop_cache();
        $result = $res->result_array();
        if($res->num_rows()) return $result;
        else return false;
    }
}
