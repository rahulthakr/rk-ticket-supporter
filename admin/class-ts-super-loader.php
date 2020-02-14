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
class TS_Super_Loader {
	
        use TS_Roles;
        use TS_Authorized;
        use TS_Common;
        
	public function __construct() {
            /*Set user roles variables*/
            
	}
        
}

