<?php
/**
 * Fired during plugin role
 *
 * @link       https://www.maansawebworld.com
 * @since      1.0.0
 *
 * @package    Mwpl_ticket
 * @subpackage Mwpl_ticket/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all roles.
 *
 * @since      1.0.0
 * @package    Mwpl_ticket
 * @subpackage Mwpl_ticket/includes
 * @author     MWPL Team <info@maansawebworld.com>
 */

trait TS_Roles{
   
    private $role;
    private $user_id;
    
    function set_user_role(){      
       if(!function_exists('wp_get_current_user')) {
            include(ABSPATH . "wp-includes/pluggable.php"); 
        }
        $user_information = wp_get_current_user();         
        if(!empty($user_information)){
            if(!empty($user_information->roles[0])){
                $this->role = $user_information->roles[0];
            }
            $this->user_id = $user_information->ID;
        }
    }
    function get_user_role_information($key='role'){
        return $this->$key;
    }
    function is_store_manager(){
        $role = $this->get_user_role_information();
        return $role;
    }
   
    function get_users_list($type=array('Customer'),$search=''){
        $args = array(
            'role__in'     => $type,
            'meta_compare' => '',            
            'orderby'      => 'nicename',
            'order'        => 'ASC',
            'search'       => $search,
            'fields'       => 'all',
            'who'          => '',
        ); 
       
        $users = get_users($args);
        return $users;
    }
    function get_user_by_id($id=0){
        return get_userdata($id);
    }
    function get_user_by_info($field='id',$value=''){
        return get_user_by( $field,$value );
    }
    function get_user_field($id=0,$field='display_name'){
      $user =   get_userdata($id);
      if(!empty($user)){
          if(isset($user->$field)){
              return $user->$field;
          }
      }
    }
    function get_user_role($id=0){
        $user =   get_userdata($id);
        if(!empty($user)){
            $role = $user->roles[0];
            switch ($role){
                case "ticket_manager":
                    return "Executive";
                    break;
                case "administrator":
                    return "Admin";
                    break;
                case "customer":
                    return "Customer";
                    break;
                default :
                    return $role;
                    
            }
        }
    }
    
}
?>