<?php
ob_start();
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://planetwebzone.com
 * @since      1.0.0
 *
 * @package    TS
 * @subpackage TS/middleware
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TS
 * @subpackage TS/middleware
 * @author     Planetwebzone<info@planetwebzone.com>
 */
trait TS_Authorized {
    
        function is_authorized($role=''){
            if($role == ""){
                @wp_redirect(site_url('/'));
                die();
            }
        }
        function is_customer_authorized($role=''){ 
            if($role!="customer"){                
                @wp_redirect(site_url('wp-login.php'));
                die();
            }
        }
        function is_customer_executive($role=''){ 
            if($role!="ticket_manager"){                
                @wp_redirect(site_url('wp-login.php'));
                die();
            }
        }
        function is_admin_authorized($role=''){ 
            if($role!="administrator"){                
                @wp_redirect(site_url('wp-login.php'));
                die();
            }
        }
        
}
?>



