<?php
 function user_id(){
    $CI =& get_instance();
    return $CI->session->userdata('user_ID');
}
function user_type(){
    $CI =& get_instance();
    return $CI->session->userdata('user_type');
}
function user_first_name(){
    $CI =& get_instance();
    return $CI->session->userdata('first_name');
}
function user_last_name(){
    $CI =& get_instance();
    return $CI->session->userdata('last_name');
}
function user_middle_name(){
    $CI =& get_instance();
    return $CI->session->userdata('middle_name');
}