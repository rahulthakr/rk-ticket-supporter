<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://planetwebzone.com
 * @since      1.0.0
 *
 * @package    TS
 * @subpackage TS/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TS
 * @subpackage TS/admin
 * @author     Planetwebzone<info@planetwebzone.com>
 */
class TS_Admin_Manage_Canned  {
    
    use TS_Common;
    use TS_Roles;
    
    private $role;
    private $userid;
    private $table_canned ='';
    
    public function __construct($user_id, $role) {
        $this->role = $role;
        $this->userid =  $user_id;
        
        /*Ticket manage ajax*/
        add_action('wp_ajax_cannedmanage',array(&$this,'show_list'));
        add_action('wp_ajax_nopriv_cannedmanage',array(&$this,'show_list'));
        
       
        
        /*Table name defined*/
        global $wpdb;
        $this->table_canned=$wpdb->prefix."ts_canned";
    }
    
    function create(){
        $this->load_view_admin('manage_admin/view/canned/new.php');
    }
    
    function show(){  
        $this->load_view_admin('manage_admin/view/canned/list.php');
    }
    function edit(){
        
        if(!isset($_GET['id'])){
            $this->notice('Invalid Request, Please try again!','error');
            @wp_redirect(admin_url('admin.php?page=ts_ticket_canned'));
            return false;
        }
        if($_GET['id'] ==""){
            $this->notice('Invalid Request, Please try again!','error');
            @wp_redirect(admin_url('admin.php?page=ts_ticket_canned'));
            return false;
        }  
        $id = $_GET['id'];
        $content['r'] = $this->get_record_by_id($this->table_canned, array('id'=>$id));
        $this->load_view_admin('manage_admin/view/canned/edit.php',$content);
    }
    function delete(){
        
        if(!isset($_GET['id'])){
            $this->notice('Invalid Request, Please try again!','error');
            @wp_redirect(admin_url('admin.php?page=ts_ticket_canned'));
            return false;
        }
        if($_GET['id'] ==""){
            $this->notice('Invalid Request, Please try again!','error');
            @wp_redirect(admin_url('admin.php?page=ts_ticket_canned'));
            return false;
        }  
        $id = $_GET['id'];
        $delete = $this->delete_record($this->table_canned, array('id'=>$id));
        if($delete){
            $this->notice('Canned has been deleted successfully!');
            @wp_redirect(admin_url('admin.php?page=ts_ticket_canned'));
            return false;
        }else{
            $this->notice('Please try again!','error');
            @wp_redirect(admin_url('admin.php?page=ts_ticket_canned'));
            return false;
        }
    }
    
    function show_list(){ 
        
        $aColumns = array("id", "title");
        $info = $this->set_data_table($aColumns);
        $details = $this->get_lists_request($info['sLimit'], $onlyCount=false , $info['sOrder'],$info['sWhere']);
        $counts = $this->get_lists_request('', $onlyCount=true , $info['sOrder'],$info['sWhere']);
        $output = array(
                "sEcho" => intval($_REQUEST['sEcho']),
                "iTotalRecords" => count($details),
                "iTotalDisplayRecords" => $counts,
                "aaData" => array()
        ); 
        if(!empty($details)):       
        foreach($details as $k=>$v){
            $button = '<a title="Edit" href="admin.php?page=ts_ticket_canned_edit&id='.$v['id'].'"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
            $button.= '<a title="Delete" onclick="confirmdel('.$v['id'].')" href="javascript:void(0);"><i class="fa fa-window-close"></i></a>';
            
            $output['aaData'][] = array($v['id'], $v['title'],$button);        
        }
        endif;
        echo json_encode($output);
        die();
    }    
    function get_lists_request($sLimit, $onlyCount=false ,  $sOrder,$sWhere){
        global $wpdb;
        $table= $this->table_canned;
        if($onlyCount==true){
           $query="SELECT *FROM $table  $sWhere  $sOrder ";               
           $sql=$wpdb->get_results($query);               
           return count($sql);
        }else{
           $query="SELECT *FROM $table  $sWhere  $sOrder $sLimit";   
           $get=$wpdb->get_results($query); 
           if(count($get) > 0){
               $i=0;
               foreach($get as $r){
                    $data[$i]['id']=$r->id;
                    $data[$i]['title']=$r->title;
                    $i+=1;  
               }
               return $data;
           }
        }
    }
    
    
    /*Action*/
    function save(){
        if(sanitize_text_field($_POST['title']) ==""){
            $this->notice('Title field is required, Please try again!','error');
            return false;
        }
        $data['title'] = sanitize_text_field($_POST['title']);
        $insert = $this->insert_record($this->table_canned, $data);
        if($insert){
            $this->notice('New canned saved successfully!');
        }else{
            $this->notice('Please try again!','error');
        }
    }
    function edit_save(){
        if(sanitize_text_field($_POST['title']) ==""){
            $this->notice('Title field is required, Please try again!','error');
            return false;
        }
        $data['title'] = sanitize_text_field($_POST['title']);
        $update = $this->update_record($this->table_canned, $data,array("id"=>$_POST['canned_edit']));
        if($update){
            $this->notice('Canned has been updated successfully!');
        }else{
            $this->notice('Please try again!','error');
        }
    }
}
