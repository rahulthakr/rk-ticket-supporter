<?php
 /**
 *
 * @link              http://planetwebzone.com
 * @since             1.0.0
 * @package           ticket-supporter
 * Plugin Name:   Tickets Supporter 
 * Plugin URI: http://planetwebzone.com
 * Description: Decription here
 * Version: 1.0.0
 * Author: Planetwebzone
 * Author URI: https://planetwebzone.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:  ticket-supporter
 * Domain Path: /languages
 *
 * @package  support-tickets-ts
 * @author Rahul Thakur
 */
// If this file is called directly, abort.
if (!defined('WPINC'))
{
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('TS_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ts-activator.php
 */
function activate_ts()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-ts-activator.php';
    TS_Activator::activate();
}
//use TS_Admin_Manage_Ticket;
//use TS_Public;
//use TS_Common; 
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ts-deactivator.php
 */
function deactivate_ts()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-ts-deactivator.php';
    TS_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ts');
register_deactivation_hook(__FILE__, 'deactivate_ts');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-ts.php';

/*Common Constant*/
define('PRD_DIR', basename(dirname(__FILE__)));
define('PRD_ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('PRD_INCLUDES_PATH', PRD_ROOT_PATH . 'includes/');
define('PRD_ADMIN_PATH', PRD_ROOT_PATH . 'admin/');
define('PRD_PUBLIC_PATH', PRD_ROOT_PATH . 'public/');
define('PRD_PLUGIN_URL', plugins_url(PRD_DIR));
define('PRD_PLUGIN_URL_ADMIN', PRD_PLUGIN_URL . DIRECTORY_SEPARATOR . 'admin/');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ts()
{

    $plugin = new TS();
    $plugin->run();

}
run_ts();
 

// Schedule an action if it's not already scheduled
if (!wp_next_scheduled('myprefix_my_cron_action'))
{
    wp_schedule_event(time() , 'Every minute', 'myprefix_my_cron_action');
}

// Hook into that action that'll fire weekly
add_action('myprefix_my_cron_action', 'auto_ticketClose_function_to_run');
function auto_ticketClose_function_to_run()
{
    TS_Public::corn_ticketClose();

}

