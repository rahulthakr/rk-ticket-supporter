<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://planetwebzone.com
 * @since      1.0.0
 *
 * @package    TS
 * @subpackage TS/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    TS
 * @subpackage TS/includes
 * @author     Planetwebzone <info@planetwebzone.com>
 */
class TS_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
            remove_role( 'ticket_manager' );
	}

}
