<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WDS_Theme_Switcher
 * @author    Web Design Sun Team <contact@webdesignsun.com>
 * @license   GPL-2.0+
 * @link      http://www.webdesignsun.com
 * @copyright Web Design Sun
 */
?>

<div class="wrap">

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<div class="wrap left-column-settings-page">
	    <?php
		$themes = $this->core->get_themes_list();
		$themes_names = array(
			'' => __("Default theme", $this->plugin_slug)
		);
		foreach($themes as $theme) {
			$themes_names[$theme['Name']] = $theme['Name'];
		}
		$cmb = new_cmb2_box( array(
			'id' => $this->plugin_slug . '_options',
			'hookup' => false,
			'show_on' => array( 'key' => 'options-page', 'value' => array( $this->plugin_slug ), ),
			'show_names' => true,
		) );

		$cmb->add_field( array(
			'name' => __( 'Enable', $this->plugin_slug ),
			'id' => $this->plugin_slug . '_is_enabled',
			'type' => 'checkbox',
		) );
		$cmb->add_field( array(
			'name' => __( 'Select mobile theme', $this->plugin_slug ),
			'desc' => __( 'Select a theme that will be displayed at the <strong>mobile</strong> devices', $this->plugin_slug ),
			'id' => $this->plugin_slug . '_mobile_theme',
			'type' => 'select',
			'options' => $themes_names,
		) );
		$cmb->add_field( array(
			'name' => __( 'Select tablet theme', $this->plugin_slug ),
			'desc' => __( 'Select a theme that will be displayed at the <strong>tablet</strong> devices', $this->plugin_slug ),
			'id' => $this->plugin_slug . '_tablet_theme',
			'type' => 'select',
			'options' => $themes_names,
		) );

	    cmb2_metabox_form( $this->plugin_slug . '_options', $this->plugin_slug . '-settings' );
	    ?>
	</div>

    <div class="right-column-settings-page metabox-holder">
	<div class="postbox col-xs-2">
	    <h3 class="hndle"><span><?php echo get_admin_page_title(); ?></span></h3>
	    <div class="inside">
			<a href="http://www.webdesignsun.com"><img src="<?php echo $this->core->get_config()->get_root_url('assets/logo.png'); ?>" alt="Web Design Sun"></a>
	    </div>
	</div>
    </div>
</div>
