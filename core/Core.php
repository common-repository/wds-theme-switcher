<?php
namespace WDS_Theme_Switcher\Core;

/**
 * Class Core
 * @package WDS_Theme_Switcher
 */
class Core
{
    private static $instance = null;
    private $config;

    /**
     * Core constructor.
     *
     * Close it for using like Singleton
     */
    private function __construct() {
        $this->config = \WDS_Theme_Switcher\Config::get_instance();
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
     * Get config
     *
     * @return \WDS_Theme_Switcher\Config
     */
    public function get_config() {
        return $this->config;
    }

    /**
     * Get themes list
     *
     * @return array
     */
    public function get_themes_list() {
        $themes = wp_get_themes();
        $wp_themes = array();

        foreach ( $themes as $theme ) {
            $name = $theme->get('Name');
            if ( isset( $wp_themes[ $name ] ) )
                $wp_themes[ $name . '/' . $theme->get_stylesheet() ] = $theme;
            else
                $wp_themes[ $name ] = $theme;
        }
        return $wp_themes;
    }

    /**
     * Set mobile theme
     */
    public function set_theme() {
        add_filter('stylesheet', array($this, 'get_theme_stylesheet'));
        add_filter('template', array($this, 'get_theme_template'));
    }

    /**
     * Get selected theme for device
     *
     * @param null $device
     * @return null
     */
    public function get_selected_theme($device = null) {

        require_once( $this->config->get_root_path('includes/Mobile-Detect/Mobile_Detect.php') );
        $detect = new \Mobile_Detect;

        $selected_theme = null;
        $options = get_option("wds-theme-switcher-settings");

        $switcher = \WDS_Theme_Switcher\Face::get_instance();
        // if plugin is not enabled
        if(!isset($options[$switcher->get_plugin_slug() . '_is_enabled'])
        || $options[$switcher->get_plugin_slug() . '_is_enabled'] === 'off') {
            return null;
        }

        // get selected theme name by device
        if ($device == 'mobile' || $detect->isMobile() && !$detect->isTablet()
        && isset($options[$switcher->get_plugin_slug() . '_mobile_theme'])) {
            $selected_theme = $options[$switcher->get_plugin_slug() . '_mobile_theme'];
        } else if($device == 'tablet' || $detect->isTablet()
            && isset($options[$switcher->get_plugin_slug() . '_tablet_theme'])) {
            $selected_theme = $options[$switcher->get_plugin_slug() . '_tablet_theme'];
        }

        $themes = $this->get_themes_list();
        if($selected_theme == null) return null;

        foreach($themes as $theme) {
            if ($theme['Name'] == $selected_theme) {
                return $theme;
            }
        }
    }

    /**
     * Get suitable stylesheet for device
     *
     * @param $stylesheet
     * @return mixed
     */
    public function get_theme_stylesheet($stylesheet) {
        $selected_theme = $this->get_selected_theme();
        if($selected_theme == null) return $stylesheet;

        return $selected_theme['Stylesheet'];
    }

    /**
     * Get suitable template for device
     *
     * @param $template
     * @return mixed
     */
    public function get_theme_template($template) {
        $selected_theme = $this->get_selected_theme();
        if($selected_theme == null) return $template;

        return $selected_theme['Template'];
    }
}