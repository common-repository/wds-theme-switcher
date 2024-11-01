<?php
namespace WDS_Theme_Switcher;

/**
 * Config of plugin
 *
 * Class Config
 * @package WDS_Theme_Switcher
 */
class Config
{
    private static $instance = null;
    private $plugin_root_path = '';
    private $plugin_root_url = '';

    /**
     * Core constructor.
     *
     * Close it for using like Singleton
     */
    private function __construct() {
        $this->plugin_root_path = plugin_dir_path( __FILE__ );
        $this->plugin_root_url = plugin_dir_url( __FILE__ );
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Get plugin root path
     *
     * @param string $path
     * @return string
     */
    public function get_root_path($path = '') {
        return $this->plugin_root_path . $path;
    }

    /**
     * Get plugin root url
     *
     * @param string $url
     * @return string
     */
    public function get_root_url($url = '') {
        return  $this->plugin_root_url . $url;
    }
}