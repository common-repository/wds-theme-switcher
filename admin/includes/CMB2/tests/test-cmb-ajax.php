<?php
/**
 * CMB2_Field tests
 *
 * @package   Tests_CMB2
 * @author    WebDevStudios
 * @license   GPL-2.0+
 * @link      http://webdevstudios.com
 */

require_once('cmb-tests-base.php');

class Test_CMB2_Ajax extends Test_CMB2 {

	/**
	 * Set up the test fixture
	 */
	public function setUp() {
		parent::setUp();

		$this->cmb = cmb2_get_metabox( array(
			'id'      => 'metabox_id',
			'hookup'  => false,
			'show_on' => array(
				'key'   => 'options-page',
				'value' => 'options-page-id',
			),
			'fields' => array(
				array(
					'id'   => 'test_embed',
					'type' => 'oembed',
				),
				array(
					'id'   => 'another_value',
					'type' => 'text',
				),
			),
		), 'options-page-id', 'options-page' );

		$this->oembed_args = array(
			'url'         => 'https://www.youtube.com/watch?v=NCXyEKqmWdA',
			'object_id'   => 'options-page-id',
			'object_type' => 'options-page',
			'oembed_args' => array( 'width' => '640' ),
			'field_id'    => 'test_embed',
			'src'         => 'https://www.youtube.com/embed/NCXyEKqmWdA?feature=oembed',
		);
	}

	public function tearDown() {
		parent::tearDown();
	}

	public function test_cmb2_ajax_instance() {
		$this->assertInstanceOf( 'CMB2_Ajax', cmb2_ajax() );
	}

	public function test_correct_properties() {
		$this->assertEquals( $this->oembed_args['object_id'], $this->cmb->object_id() );
		$this->assertEquals( $this->oembed_args['object_type'], $this->cmb->object_type() );
	}

	public function test_get_oembed() {
		$args = $this->oembed_args;

		$this->assertEquals(
			$this->expected_oembed_results( $args ),
			cmb2_get_oembed( $args )
		);

		// Test another oembed URL
		$args['url'] = 'https://twitter.com/Jtsternberg/status/703434891518726144';

		$expected = $this->is_connected()
			? sprintf( '<div class="embed-status"><blockquote class="twitter-tweet" data-width="550"><p lang="en" dir="ltr">That time we did Adele’s “Hello” at <a href="https://twitter.com/generationschch">@generationschch</a>…<a href="https://t.co/aq89T5VM5x">https://t.co/aq89T5VM5x</a></p>&mdash; Justin Sternberg (@Jtsternberg) <a href="%s">February 27, 2016</a></blockquote><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script><p class="cmb2-remove-wrapper"><a href="#" class="cmb2-remove-file-button" rel="%s">' . __( 'Remove Embed', 'cmb2' ) . '</a></p></div>', $args['url'], $args['field_id'] )
			: $this->no_connection_oembed_result( $args['url'] );

		$this->assertEquals(
			$expected,
			cmb2_get_oembed( $args )
		);
	}

	public function test_values_cached() {
		$expected = $this->is_connected() ? array(
			'_oembed_611cd8ff569bdf3f2bd77a47ba674606' => '<iframe width="640" height="360" src="https://www.youtube.com/embed/NCXyEKqmWdA?feature=oembed" frameborder="0" allowfullscreen></iframe>',
			'_oembed_time_611cd8ff569bdf3f2bd77a47ba674606' => 1456629747,
			'_oembed_e587db3cd3a82c8215553980b4f347c1' => '<blockquote class="twitter-tweet" data-width="550"><p lang="en" dir="ltr">That time we did Adele’s “Hello” at <a href="https://twitter.com/generationschch">@generationschch</a>…<a href="https://t.co/aq89T5VM5x">https://t.co/aq89T5VM5x</a></p>&mdash; Justin Sternberg (@Jtsternberg) <a href="https://twitter.com/Jtsternberg/status/703434891518726144">February 27, 2016</a></blockquote><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>',
			'_oembed_time_e587db3cd3a82c8215553980b4f347c1' => 1456629747,
		) : array(
			'_oembed_611cd8ff569bdf3f2bd77a47ba674606' => '{{unknown}}',
			'_oembed_e587db3cd3a82c8215553980b4f347c1' => '{{unknown}}',
		);

		$options = $this->get_option();

		foreach ( $expected as $key => $value ) {
			$this->assertTrue( array_key_exists( $key, $options ) );

			if ( 0 !== strpos( $key, '_oembed_time_' ) ) {
				$this->assertEquals( $expected[ $key ], $options[ $key ] );
			} else {
				$this->assertTrue( is_int( $value ) );
			}
		}
	}

	public function test_get_oembed_delete_with_expired_ttl() {
		add_filter( 'oembed_ttl', '__return_zero' );
		add_action( 'cmb2_save_options-page_fields', array( 'CMB2_Ajax', 'clean_stale_options_page_oembeds' ) );

		$new = array( 'another_value' => 'value' );
		$_POST = array_merge( $new, $this->get_option() );

		$this->cmb->save_fields();
		$options = $this->get_option();

		$this->assertEquals( $new, $options );
	}

	protected function get_option() {
		return cmb2_options( $this->oembed_args['object_id'] )->get_options();
	}

}
