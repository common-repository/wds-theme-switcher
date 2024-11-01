<?php

namespace WDS_Theme_Switcher;
/**
 * WDS Theme Switcher.
 *
 * @package   Face
 * @author    Web Design Sun Team <contact@webdesignsun.com>
 * @license   GPL-2.0+
 * @link      http://www.webdesignsun.com
 * @copyright Web Design Sun
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `Admin.php`
 *
 * @package WDS_Theme_Switcher
 * @author  Web Design Sun Team <contact@webdesignsun.com>
 */
class Face {

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */
    const VERSION = '1.0.0';

    /**
     * Unique identifier for your plugin.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @var      string
     *
     * @since    1.0.0
     */
    protected static $plugin_slug = 'wds-theme-switcher';

    /**
     * Unique identifier for your plugin.
     *
     * @var      string
     *
     * @since    1.0.0
     */
    protected static $plugin_name = 'WDS Theme Switcher';

    /**
     * Instance of this class.
     *
     * @var      object
     *
     * @since    1.0.0
     */
    protected static $instance = null;

    /**
     * Instance of \WDS_Theme_Switcher\Core\Core class
     * @var null|object
     */
    protected $core = null;

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     1.0.0
     */
    private function __construct() {
        add_filter( 'body_class', array( $this, 'add_pn_class' ), 10, 3 );

        // Load public-facing style sheet and JavaScript.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_js_vars' ) );

        $this->core = \WDS_Theme_Switcher\Core\Core::get_instance();
    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_plugin_slug() {
        return self::$plugin_slug;
    }

    /**
     * Return the plugin name.
     *
     * @since    1.0.0
     *
     * @return    Plugin name variable.
     */
    public function get_plugin_name() {
        return self::$plugin_name;
    }

    /**
     * Return the version
     *
     * @since    1.0.0
     *
     * @return    Version const.
     */
    public function get_plugin_version() {
        return self::VERSION;
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
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     * @return void
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->get_plugin_slug() . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_script( $this->get_plugin_slug() . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
    }

    /**
     * Print the PHP var in the HTML of the frontend for access by JavaScript
     *
     * @since    1.0.0
     * @return void
     */
    public function enqueue_js_vars() {
        wp_localize_script( $this->get_plugin_slug() . '-plugin-script', 'wds_vars', array() );
    }

    /**
     * Add class in the body on the frontend
     *
     * @since    1.0.0
     * @param array $classes THe array with all the classes of the page.
     * @return array
     */
    public function add_pn_class( $classes ) {
        $classes[] = $this->get_plugin_slug();
        return $classes;
    }
}
