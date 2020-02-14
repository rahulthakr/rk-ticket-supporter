<?php
/**
 * Fired during plugin activation
 *
 * @link       https://planetwebzone.com
 * @since      1.0.0
 *
 * @package    TS
 * @subpackage TS/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TS
 * @subpackage TS/includes
 * @author     Planetwebzone <info@planetwebzone.com>
 */
class TS_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
            ob_start();
            /*Required tables create*/
            global $wpdb;
            global $database_db_version;
            $tickets_table_name = $wpdb->prefix . "ts_tickets";                    
            $query_tickets ="CREATE TABLE IF NOT EXISTS $tickets_table_name ( id int(11) NOT NULL AUTO_INCREMENT,"
                    . "`customer_id` int(11) NOT NULL,
                        `staff_id` int(11) NOT NULL,
                        `first_name` varchar(55) DEFAULT NULL,
                        `last_name` varchar(55) DEFAULT NULL,
                        `email` VARCHAR(355) NOT NULL,
                        `telephone` varchar(100) DEFAULT NULL,                        
                        `subject` varchar(255) DEFAULT NULL,
                        `category` varchar(255) DEFAULT NULL,
                        `description` text,
                        `attachment` text,
                        `status` tinyint(4) NOT NULL DEFAULT '0',
                        `auto_close_status` tinyint(4) NOT NULL DEFAULT '0',
                        `priority` tinyint(4) NOT NULL DEFAULT '0',
                        `private_ticket` tinyint(4) NOT NULL DEFAULT '0',
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (id))";
                    

            $tickets_logs_table_name = $wpdb->prefix . "ts_ticket_assigned";
            $query_tickets_logs ="CREATE TABLE IF NOT EXISTS $tickets_logs_table_name ( id int(11) NOT NULL AUTO_INCREMENT,"
                    . "`store_manager_id` int(11) NOT NULL,
                        `ticket_id` int(11) NOT NULL,
                        `status` tinyint(4) NOT NULL DEFAULT '1',
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (id))"; 
            
            $tickets_delete_table_name = $wpdb->prefix . "ts_ticket_delete_assigned";
            $query_tickets_delete ="CREATE TABLE IF NOT EXISTS $tickets_delete_table_name ( id int(11) NOT NULL AUTO_INCREMENT,"
                    . "`store_manager_id` int(11) NOT NULL,
                        `access_type` int(11) NOT NULL COMMENT '0= No access , 1 =Access',
                        `status` tinyint(4) NOT NULL DEFAULT '1',
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (id))"; 
            
            $tickets_comments_table_name = $wpdb->prefix . "ts_tickets_comments";
            $query_tickets_comments ="CREATE TABLE IF NOT EXISTS $tickets_comments_table_name ( id int(11) NOT NULL AUTO_INCREMENT,"
                    . "`ticket_id` int(11) NOT NULL,
                    `from_id` int(11) NOT NULL,
                    `to_id` int(11) NOT NULL,
                    `comment` text NOT NULL,
                    `attachment` text,
                    `private_ticket` tinyint(4) NOT NULL DEFAULT '0',
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (id))"; 
            
            $settings_table_name = $wpdb->prefix . "ts_settings";
            $query_settings ="CREATE TABLE IF NOT EXISTS $settings_table_name ( id int(11) NOT NULL AUTO_INCREMENT,"
                    . "`type` varchar(255) DEFAULT NULL,
                    `service` varchar(255) DEFAULT NULL,
                    `information` text NOT NULL,
                    `user_id` int(11) NOT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (id))"; 
            
            
            $canned_table_name = $wpdb->prefix . "ts_canned";
            $query_canned ="CREATE TABLE IF NOT EXISTS $canned_table_name ( id int(11) NOT NULL AUTO_INCREMENT,"
                    . "`title` varchar(500) DEFAULT NULL,                    
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (id))"; 
            
            
            
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $wpdb->query($query_tickets);
            $wpdb->query($query_tickets_comments);
            $wpdb->query($query_tickets_logs);
            $wpdb->query($query_tickets_delete);
            $wpdb->query($query_settings);
            $wpdb->query($query_canned);
            add_option("database_db_version", $database_db_version);
            /*End Required tables create*/
            
            
            
            /*Generate the manager role*/
            if(self::role_exists('ticket_manager') ==  false){
                
                add_role( 'ticket_manager', 'Ticket Executive',  array(
                    'read' => true, // true allows this capability
                    'edit_posts' => true, // Allows user to edit their own posts
                    'edit_pages' => true, // Allows user to edit pages
                    'edit_others_posts' => false, // Allows user to edit others posts not just their own
                    'create_posts' => false, // Allows user to create new posts
                    'manage_categories' => false, // Allows user to manage post categories
                    'publish_posts' => false, // Allows the user to publish, otherwise posts stays in draft mode
                    'edit_themes' => FALSE, // false denies this capability. User can’t edit your theme
                    'install_plugins' => false, // User cant add new plugins
                    'update_plugin' => false, // User can’t update any plugins
                    'update_core' => false // user cant perform core updates
                ));
            }
            if(self::role_exists('customer') ==  false){
                
                add_role( 'customer', 'Customer',  array(
                    'read' => true, // true allows this capability
                    'edit_posts' => false, // Allows user to edit their own posts
                    'edit_pages' => false, // Allows user to edit pages
                ));
            }
            
            /*Generate the customer page*/
            $check_page_exist = get_page_by_title('Customer Ticket', 'OBJECT', 'page');
            if(empty($check_page_exist)) {
                $page_id = wp_insert_post(
                    array(
                    'comment_status' => 'close',
                    'ping_status'    => 'close',
                    'post_author'    => 1,
                    'post_title'     => ucwords('Customer Ticket'),
                    'post_name'      => strtolower(str_replace(' ', '-', trim('Customer Ticket'))),
                    'post_status'    => 'publish',
                    'post_content'   => '[ts-customer-ticket-dashboard]',
                    'post_type'      => 'page'
                    )
                );
                if($page_id){
                    update_post_meta( $page_id, '_wp_page_template', 'customer-dashboard.php' );
                }
            }
            
            /*End Generate the customer page*/
            
            
            flush_rewrite_rules();
            
    }
    
    static function  role_exists( $role ) {

        if( ! empty( $role ) ) {
          return $GLOBALS['wp_roles']->is_role( $role );
        }
        return false;
    }
    
}
?>