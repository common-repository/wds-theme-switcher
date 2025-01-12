<?php

/**
 * Load the plugin text domain for translation.
 *
 * @since    1.0.0
 * @package   WDS_Theme_Switcher
 * @author  Mte90 <mte90net@gmail.com>
 * @license   GPL-2.0+
 * @copyright 2014-2015
 * 
 * @return void
 */
function pn_load_plugin_textdomain() {
	$plugin = \WDS_Theme_Switcher\Face::get_instance();
	$domain = $plugin->get_plugin_slug();
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'pn_load_plugin_textdomain', 1 );
