<?php

namespace ToutSocialButtons\Fields;

/**
 * Defines all the code for the design form field.
 *
 * @link 		https://www.slushman.com
 * @since 		1.0.0
 * @package 	ToutSocialButtons\Fields
 */
class Design extends \ToutSocialButtons\Fields\Field {

	/**
	 * Class constructor.
	 *
	 * @since 		1.0.0
	 * @param 		string 		$context 		The field context. Options:
	 *                               				settings: plugin settings
	 *                               				metabox: in a metabox
	 *                               				widget: in widget form
	 * @param 		array 		$args 			The field arguments.
	 */
	public function __construct( $context, $args ) {

		$this->set_context( $context );
		$this->set_setting_name( $args );
		$this->set_settings( $args );

		$this->set_default_attributes();
		$this->set_attributes( $args );
		$this->set_value( $args );
		$this->set_name_attribute();

		$this->set_default_properties();
		$this->set_default_design_properties();
		$this->set_properties( $args );

		$this->output_label();
		$this->output_field();
		$this->output_description();
		$this->output_alert();

	} // __construct()

	/**
	 * Includes the field markup file.
	 *
	 * Uses the newest post as the preview page.
	 *
	 * @since 		1.0.0
	 */
	public function output_field() {

		$post_opts['numberposts'] 	= 1;
		$post_opts['post_status'] 	= 'publish';
		$posts 						= wp_get_recent_posts( $post_opts );

		if ( 0 === count( $posts ) ) { return; }

		$url 				= admin_url( 'customize.php' );
		$query 				= array( 'autofocus[' . $this->properties['customizer']['type'] . ']' => $this->properties['customizer']['focus'] );
		$query['url'] 		= get_permalink( $posts[0]['ID'] );
		$query['return'] 	= urlencode( add_query_arg( array( 'page' => $this->properties['customizer']['return'] ), admin_url( 'admin.php' ) ) );

		/**
		 * The tout_social_buttons_customizer_button_url filter.
		 *
		 * Allows for customizing the URL for the Customizer button in the plugin settings.
		 *
		 * @param 		array 		$query 		The query array.
		 */
		$query 	= apply_filters( 'tout_social_buttons_customizer_button_url', $query );
		$link 	= add_query_arg( $query, $url );

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'fields/partials/design.php' );

	} // output_field()

	/**
	 * Sets default properties specifically for checkbox fields.
	 *
	 * @since 		1.0.0
	 */
	protected function set_default_design_properties() {

		$this->default_properties['customizer']['return'] 	= TOUT_SOCIAL_BUTTONS_SETTINGS;
		$this->default_properties['customizer']['focus'] 	= 'tout_social_buttons_general';
		$this->default_properties['customizer']['type'] 	= 'section';
		$this->default_properties['link-label'] 			= __( 'Customize the Tout.Social buttons', 'tout-social-buttons' );

	} // set_default_design_properties()

} // class
