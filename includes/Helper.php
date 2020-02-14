<?php

function get_instance(){
    $obj = new TS_Super_Loader();
    return $obj;
}
function get_captcha_key(){
    $ci = get_instance();
    $record  = get_general_settings_all();
    if(isset($record->g_captcha_code)) {
        return $record->g_captcha_code;
    }
}
function get_general_settings_all(){
    global $wpdb;
    $table_settings = $wpdb->prefix."ts_settings";
    $ci = get_instance();
    $general_seting = $ci->get_record_by_row($table_settings, array("type"=>"admin",'service'=>"general"));  
    if(!empty($general_seting)){
       if($general_seting->information!=""){
            return json_decode($general_seting->information);
        } 
    }
}
function get_email_settings_all(){
    global $wpdb;
    $table_settings = $wpdb->prefix."ts_settings";
    $ci = get_instance();
    $general_seting = $ci->get_record_by_row($table_settings, array("type"=>"admin",'service'=>"email"));  
    if(!empty($general_seting)){
       if($general_seting->information!=""){
            return json_decode($general_seting->information);
        } 
    }
}
?>
