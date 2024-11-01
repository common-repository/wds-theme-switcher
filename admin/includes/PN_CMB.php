<?php

/**
 * All the CMB related code.
 *
 * @package   WDS_Theme_Switcher
 * @author  Mte90 <mte90net@gmail.com>
 * @license   GPL-2.0+
 * @copyright 2014-2015
 * @since    1.0.0
 */
class Pn_CMB {

    /**
     * Initialize CMB2.
     *
     * @since     1.0.0
     */
    public function __construct() {
        $plugin = \WDS_Theme_Switcher\Face::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        /*
         * CMB 2 for metabox and many other cool things!
         * https://github.com/WebDevStudios/CMB2
         */
        require_once( plugin_dir_path( __FILE__ ) . 'CMB2/init.php' );
    }

}

new Pn_CMB();
