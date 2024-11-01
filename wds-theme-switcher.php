<?php

/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   WDS_Theme_Switcher
 * @author    Web Design Sun Team <contact@webdesignsun.com>
 * @license   GPL-2.0+
 * @link      http://www.webdesignsun.com
 * @copyright Web Design Sun
 *
 * Plugin Name:       WDS_Theme_Switcher
 * Plugin URI:        http://www.wordpress.org/wds-theme-switcher
 * Description:       WDS Theme Switcher is developed for the convenience of changing the theme view while using different devices, whether it is a tablet or mobile.
 * Version:           1.0.0
 * Author:            Web Design Sun Team
 * Author URI:        http://www.webdesignsun.com
 * Text Domain:       wds-theme-switcher
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * WordPress-Plugin-Boilerplate-Powered: v1.2.0
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/*
 * ------------------------------------------------------------------------------
 * Public-Facing Functionality
 * ------------------------------------------------------------------------------
 */
require_once( plugin_dir_path( __FILE__ ) . 'Config.php' );
$wds_config = \WDS_Theme_Switcher\Config::get_instance();
require_once( $wds_config->get_root_path('includes/load_textdomain.php') );

/*
 * Init plugin
 */
require_once( $wds_config->get_root_path('core/Core.php') );
require_once( $wds_config->get_root_path('public/Face.php') );

add_action( 'plugins_loaded', array( '\\WDS_Theme_Switcher\\Face', 'get_instance' ) );
add_action( 'plugins_loaded', array( \WDS_Theme_Switcher\Core\Core::get_instance(), 'set_theme' ) );

/*
 * -----------------------------------------------------------------------------
 * Dashboard and Administrative Functionality
 * -----------------------------------------------------------------------------
*/

/*
 * The code below is intended to give the lightest footprint possible.
 */

if ( is_admin() && (!defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
	require_once( $wds_config->get_root_path('admin/Admin.php') );
	add_action( 'plugins_loaded', array( '\\WDS_Theme_Switcher\\Admin', 'get_instance' ) );
}

unset($wds_config);
