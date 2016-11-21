<?php

/* Created by John Enrick Pleños */
class API_Controller extends CI_Controller{
	
    /*Database ID*/
    const ACCESS_CONTROL_LIST = 1;
    const STUDENT_MANAGEMENT = 2;
    const PERSONAL_INFORMATION = 3;
	
    public function generateResponse($data = false, $error = array()){
        return array(
            "data" => $data,
            "error" => $error
        );
    }
    public function checkACL($userType, $moduleID){
        return 1;
    }

    
}

