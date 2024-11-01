<?php

namespace WDS_Theme_Switcher;

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * @package   WDS_Theme_Switcher
 * @author    Web Design Sun Team <contact@webdesignsun.com>
 * @license   GPL-2.0+
 * @link      http://www.webdesignsun.com
 * @copyright Web Design Sun
 */
// If uninstall not called from WordPress, then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

class Uninstall {
  public function __construct() {}

  public function run() {
    global $wpdb;

    $face = \WDS_Theme_Switcher\Face::get_instance();
    delete_option("{$face->plugin_slug}_options");

    $debug = new \Pn_Debug();
    $debug->log("$face->plugin_name is uninstalled");
  }
}

$uninstall = new Uninstall();
$uninstall->run();