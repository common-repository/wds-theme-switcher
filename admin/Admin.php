<?php

namespace WDS_Theme_Switcher;
/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `Face.php`
 *
 * @package   Admin
 * @author    Web Design Sun Team <contact@webdesignsun.com>
 * @license   GPL-2.0+
 * @link      http://www.webdesignsun.com
 * @copyright Web Design Sun
 */

class Admin {

	/**
	 * Instance of this class.
	 *
	 * @var      object
	 *
	 * @since    1.0.0
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @var      string
	 *
	 * @since    1.0.0
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Instance of \WDS_Theme_Switcher\Core\Core class
	 * @var null|object
	 */
	protected $core = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$this->core = \WDS_Theme_Switcher\Core\Core::get_instance();
		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = \WDS_Theme_Switcher\Face::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		$this->plugin_name = $plugin->get_plugin_name();
		$this->version = $plugin->get_plugin_version();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		// Load admin style in dashboard for the At glance widget
		add_action( 'admin_head-index.php', array( $this, 'enqueue_admin_styles' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );


		/*
		 * Load dependencies
		 */
		$config = \WDS_Theme_Switcher\Config::get_instance();
		require_once( $config->get_root_path('admin/includes/PN_CMB.php') );

		/*
		 * Debug mode
		 */
		require_once( $config->get_root_path('admin/includes/debug.php') );

		/*
		 * Load Wp_Admin_Notice for the notices in the backend
		 * 
		 * First parameter the HTML, the second is the css class
		 */
		if ( !class_exists( 'WP_Admin_Notice' ) ) {
			require_once( $config->get_root_path('admin/includes/WP-Admin-Notice/WP_Admin_Notice.php') );
		}
		add_action( "cmb2_save_options-page_fields_{$this->plugin_slug}_options", array( $this, 'show_notice_after_saving' ) );

		$debug = new \Pn_Debug( );
		$debug->log( $this->plugin_name . ' ' . __( 'Loaded', $this->plugin_slug ) );
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
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    mixed    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		if ( !isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id || strpos( $_SERVER[ 'REQUEST_URI' ], 'index.php' ) || strpos( $_SERVER[ 'REQUEST_URI' ], get_bloginfo( 'wpurl' ) . '/wp-admin/' ) ) {
			wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array( 'dashicons' ), \WDS_Theme_Switcher\Face::VERSION );
			wp_enqueue_style( $this->plugin_slug . '-switchery-styles', plugins_url( 'assets/css/switchery.min.css', __FILE__ ), array(), \WDS_Theme_Switcher\Face::VERSION );
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    mixed    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		if ( !isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), \WDS_Theme_Switcher\Face::VERSION );
			wp_enqueue_script( $this->plugin_slug . '-switchery-script', plugins_url( 'assets/js/switchery.min.js', __FILE__ ), array(), \WDS_Theme_Switcher\Face::VERSION, TRUE );
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 *
	 * @return void
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Settings page in the menu
		 */
		$this->plugin_screen_hook_suffix = add_menu_page( __( 'WDS Theme Switcher', $this->plugin_slug ), $this->plugin_name, 'manage_options', $this->plugin_slug, array( $this, 'display_plugin_admin_page' ), 'dashicons-tablet', 90 );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
       * @param array $links
       * @return array
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
		    'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings' ) . '</a>',
		    ), $links
		);
	}

	/**
	 * Show notice after saving fields
	 */
	public function show_notice_after_saving() {
		$notice = new \WP_Admin_Notice(__('Fields are updated', $this->plugin_slug));
		// TODO: try to output message with hooks
		echo $notice->output();
	}
}
