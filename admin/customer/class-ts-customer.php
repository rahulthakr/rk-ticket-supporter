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
class TS_Customer_Account   {
    
    use TS_Common;
    use TS_Roles;
    use TS_Authorized;
    
    private $role;
    private $userid;
    private $table_ticket ='';
    private $table_comments ='';
    
    
    public function __construct($user_id, $role) {
        
        
        if(!isset($_SESSION)) {
            session_start();
        }
        
        $this->role = $role;
        $this->userid =  $user_id;        
        $this->is_customer_authorized($this->role);
        
        //$this->is_customer_authorized($this->role);       
        /*Ticket manage ajax*/       
        add_action('wp_ajax_ticketinfocustomer',array(&$this,'ticketinfocustomer_show_list'));
        add_action('wp_ajax_nopriv_ticketinfocustomer',array(&$this,'ticketinfocustomer_show_list'));
        
        /*Ticket History manage ajax*/       
        add_action('wp_ajax_ticketinfocustomerhistory',array(&$this,'ticketinfocustomer_history_show_list'));
        add_action('wp_ajax_nopriv_ticketinfocustomerhistory',array(&$this,'ticketinfocustomer_history_show_list'));
        
        /*Table name defined*/
        global $wpdb;
        $this->table_ticket=$wpdb->prefix."ts_tickets";
        $this->table_comments = $wpdb->prefix."ts_tickets_comments";
    }
    
    function show(){ 
        $this->load_view_admin('customer/view/dashboard.php');
    }
    function create(){
        $this->load_view_admin('customer/view/create.php');
    }
    function ticket_discussion($id=0){
        $record = $this->get_record_by_id($this->table_ticket, array('customer_id'=> $this->userid,'id'=>$id));
        if(empty($record)){
            $this->notice_front('Something went wrong.Please try again!','danger');
            @wp_redirect(site_url('customer-ticket')); 
            return false;
        }
        
        $ticket_executive_information='';
        if($record->staff_id){
            $ticket_executive_information = $this->get_user_by_id($record->staff_id);
        }
        $discussion =  $this->get_records($this->table_comments, array('ticket_id'=> $id,'private_ticket'=>0));
       
        $content['ticket_record'] = $record;
        $content['user'] = $this->get_user_by_id($this->userid);
        $content['assign_ticket'] =$ticket_executive_information;
        $content['discussion'] = $discussion;
        $content['call'] = $this;
        $this->load_view_admin('customer/view/discussion.php',$content);
    }
    
    function user_id(){
        return $this->userid;
    }
    
    /*Save tickets*/
    function generate_ticket(){
        if($_POST['subject'] ==""){
            $this->notice_front('Subject field is required','danger');
            @wp_redirect(site_url('customer-ticket/create/')); 
            return false;
        }
       $user =  $this->get_user_by_id($this->userid);
       
       if(!empty($user)){           
           if(isset($user->first_name)){
               $data['first_name'] = $user->first_name;
           }
           if(isset($user->last_name)){
               $data['last_name'] = $user->last_name;
           }
           if(isset($user->user_email)){
               $data['email'] = $user->user_email;
           }
           if(isset($user->billing_phone)){
               $data['telephone'] = $user->billing_phone;
           }
           
       }
       
        $data['attachment'] = $this->upload_file();
        $data['subject'] = $_POST['subject'];
        $data['category'] = $_POST['category'];
        $data['description'] = $_POST['description'];
        $data['customer_id'] = $this->userid;

        $insert = $this->insert_record($this->table_ticket,$data,1);     
        $subject= "New Ticket Generate #".$insert;
        $to = $this->get_user_field($this->userid,'user_email');        
        if($insert){
            $this->notfication_ticket_action($to, "New Ticket Generate #".$insert, "<p>Your ticket has been generated successfully!</p>");
            $this->notice_front('Ticket has been generated successfully!');
        }else{
            $this->notice_front('Please try again!','danger');
        }
        
    }
    
    function ticket_reply(){
        
        if($_POST['ticket'] ==""){
            $this->notice_front('Invalid request, Please try again!','danger');
            return false;
        }
        if($_POST['comment'] ==""){
            $this->notice_front('Reply field required','danger');
            return false;
        } 
		if(isset($_POST['auto_close_status'])){
        if($_POST['auto_close_status'] =="0" && $_POST['auto_close_status'] !=""){
           $auto_close_data['status'] = $_POST['auto_close_status'];
           $auto_close_data['auto_close_status'] = $_POST['auto_close_status'];
           $update = $this->update_record($this->table_ticket, $auto_close_data, array( 'id'=>$_POST['ticket'])); 
        }
		}		
        $ticket_belong_securiety_check = $this->get_record_exist($this->table_ticket, array('customer_id'=> $this->userid,'id'=>$_POST['ticket'])) ;
        if(!$ticket_belong_securiety_check){
            $this->notice_front('Invalid request, Please try again!','danger');
            return false;
        }
        $ticket_information = $this->get_record_by_id($this->table_ticket, array('customer_id'=> $this->userid,'id'=>$_POST['ticket']));
        
        
        
        
        $data['ticket_id'] = $_POST['ticket'];
        $data['from_id'] = $this->userid;
        $data['to_id'] = $ticket_information->staff_id;
        $data['comment'] = $_POST['comment'];
        $data['attachment'] = $this->upload_file();
        
        $insert = $this->insert_record($this->table_comments, $data);
        if($insert){
            $to = $this->get_user_field($ticket_information->staff_id,'user_email');
            $this->notfication_ticket_action($to, "Ticket new comment - #".$_POST['ticket'], "<h3>New Message 123.</h3><p>".$_POST['comment']."</p>");
            $this->notice_front('Reply posted successfully!');
        }else{
            $this->notice_front('Please try again!','danger');
        }
    }
    
    function ticket_close(){
        if($_POST['ticket'] ==""){
            $this->notice_front('Invalid request, Please try again!','danger');
            return false;
        }
        $ticket_belong_securiety_check = $this->get_record_exist($this->table_ticket, array('customer_id'=> $this->userid,'id'=>$_POST['ticket'])) ;
        if(!$ticket_belong_securiety_check){
            $this->notice_front('Invalid request, Please try again!','danger');
            return false;
        }
        $data['status'] =1;
        $update = $this->update_record($this->table_ticket, $data, array('customer_id'=> $this->userid,'id'=>$_POST['ticket']));
        if($update){
            $ticket_information = $this->get_record_by_id($this->table_ticket, array('id'=>$_POST['ticket']));
            $executive_to = $this->get_user_field($ticket_information->staff_id,'user_email');  
            $this->notfication_ticket_action($executive_to, "Ticket Closed - #".$_POST['ticket'], "<h3>Ticket has been closed.</h3>");
            
            $this->notice_front('Ticket has been closed successfully!');
        }else{
            $this->notice_front('Please try again!','danger');
        }
    }
   
    function ticketinfocustomer_show_list(){ 
        
        $aColumns = array("id","subject","status","");
        $condition=array();
        $condition[] =' customer_id =  '.$this->userid;
        if($_POST['search_subject'] !=""){
            $condition[]=" subject LIKE '%".$_POST['search_subject']."%' ";
        }
        if($_POST['search_status'] !=""){
            $condition[]=" status = ".$_POST['search_status'];
        }
        if(!empty($condition)){
            $condition = implode(" AND ", $condition);
        }
       
        if(!empty($condition)){           
            $info = $this->set_data_table_condition($aColumns,$condition);
        }else{ 
            $info = $this->set_data_table($aColumns);
        }
        
        $details = $this->get_lists_request($info['sLimit'], $onlyCount=false , $info['sOrder'],$info['sWhere']);
        $counts = $this->get_lists_request('', $onlyCount=true , $info['sOrder'],$info['sWhere']);
        $output = array(
                "sEcho" => intval($_REQUEST['sEcho']),
                "iTotalRecords" => count($details),
                "iTotalDisplayRecords" => $counts,
                "aaData" => array()
        ); 
        
        
        /*Users list*/        
        $usersAll = $this->get_users_list(['ticket_manager']);  
        if(!empty($details)):       
        foreach($details as $k=>$v){  
            $user_exist='';
            $status='<span class="btn btn-primarye"><i class="fa fa-dot-circle-o text-success"></i> Open</span>';
            if($v['status'] == "1"){
                $status = '<span class="btn btn-primarye ticket-close"><i class="fa fa-dot-circle-o text-danger"></i> Close</span>';
            }
            $buutton = '<a href="'. site_url('customer-ticket/?node_first=discussion&&node_second='.$v['id']).'">Discussion</a>';
            $output['aaData'][] = array($v['id'],  $v['subject'] ,$status,$buutton);        
        }
        endif;
        echo json_encode($output);
        die();
    }    
    function get_lists_request($sLimit, $onlyCount=false ,  $sOrder,$sWhere){
        global $wpdb;
        $table= $this->table_ticket;
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
                    $data[$i]['subject']=$r->subject;                                                      
                    $data[$i]['status']=$r->status;                           
                    $i+=1;  
               }
               return $data;
           }
        }
    }
    
    /*Ticket History*/
    function ticket_history(){ 
        $this->load_view_admin('customer/view/history/list.php');
    }
    function ticketinfocustomer_history_show_list(){
         
        
        $aColumns = array("id","subject","status","","");
        $condition=array();
        $condition[] =' customer_id =  '.$this->userid;
        if($_POST['search_subject'] !=""){
            $condition[]=" subject LIKE '%".$_POST['search_subject']."%' ";
        }
        if($_POST['search_status'] !=""){
            $condition[]=" status = ".$_POST['search_status'];
        }
        if(!empty($condition)){
            $condition = implode(" AND ", $condition);
        }
       
        if(!empty($condition)){           
            $info = $this->set_data_table_condition($aColumns,$condition);
        }else{ 
            $info = $this->set_data_table($aColumns);
        }
        
        $details = $this->get_lists_request($info['sLimit'], $onlyCount=false , $info['sOrder'],$info['sWhere']);
        $counts = $this->get_lists_request('', $onlyCount=true , $info['sOrder'],$info['sWhere']);
        $output = array(
                "sEcho" => intval($_REQUEST['sEcho']),
                "iTotalRecords" => count($details),
                "iTotalDisplayRecords" => $counts,
                "aaData" => array()
        ); 
        
        
        /*Users list*/        
        $usersAll = $this->get_users_list(['ticket_manager']);  
        if(!empty($details)):       
        foreach($details as $k=>$v){  
            $user_exist='';
            $status='<span class="btn btn-primary">Open</span>';
            if($v['status'] == "1"){
                $status = '<span class="btn btn-primary ticket-close">Close</span>';
            }
            
            $select='';
            if($v['status'] == "1"){
                $select.="<form method='post' action='' class='ts_short_form'>";
                $select.="<select name='ticket_reopen' class='form-control ts_form_submit'>";
                $select.="<option value=''>--Choose--</option>";
                $select.="<option value='0'>Re-open</option>";
                $select.="</select>";
                $select.="<input type='hidden' name='ticket_id' value='".$v['id']."'>";
                $select.="</form>";
            }
            
            $buutton = '<a href="'. site_url('customer-ticket/discussion/'.$v['id']).'">Discussion</a>';
            $output['aaData'][] = array($v['id'],  $v['subject'] ,$status,$select,$buutton);        
        }
        endif;
        echo json_encode($output);
        die();
    
    }
    
    function load_module() {
        $page_node_1='';
        $page_node_2=''; 
        $node='';
     // echo "Node First: ", get_query_var( 'node_first' )." Second: ".get_query_var( 'node_second' );
        
        if( false !== get_query_var( 'node_first' ) ){            
            $page_node_1 = get_query_var( 'node_first' );
        }
        if( false !== get_query_var( 'node_second' ) ){
            $page_node_2 = get_query_var( 'node_second' );
        }
        
        if($page_node_1!=""){
            $node = $page_node_1;
        }else{
            $node = $page_node_2;
        }
    // echo   $text= "Node First: ", get_query_var( 'node_first' )." Second: ".get_query_var( 'node_second' );    
	 // echo " <script> console.log('hghgh>". $node." '); </script>";
         switch ($node) {
            case 'create':
                $this->create();
                break;
            case 'discussion':
                $this->ticket_discussion($page_node_2);
                break;
            case 'history':
                $this->ticket_history();
                break;

            default:
                $this->show();
                break;
        } 
    }
    
}
