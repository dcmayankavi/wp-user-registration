<?php
/**
 * Plugin Name: User Registration
 * Description: This plugin is use to register a new user from frontend.
 * Version: 1.0.0
 * Author: Dinesh Chouhan
 * Author URI: http://dineshchouhan.com
 * Text Domain: user-registration
 *
 * @package User Registration
 * @author Dinesh Chouhan
 */

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Define Required Constants
 */

define( 'USER_REGISTRATION_VER', '1.0.0' );
define( 'USER_REGISTRATION_FILE', __FILE__ );
define( 'USER_REGISTRATION_BASE', plugin_basename( USER_REGISTRATION_FILE ) );
define( 'USER_REGISTRATION_DIR', plugin_dir_path( USER_REGISTRATION_FILE ) );
define( 'USER_REGISTRATION_URL', plugins_url( '/', USER_REGISTRATION_FILE ) );

require_once 'class-user-registration-loader.php';
