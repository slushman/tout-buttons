<?php

/**
 * The frontend functionality of the plugin.
 *
 * @link 			https://www.slushman.com
 * @since 			1.0.0
 * @package 		ToutSocialButtons\Frontend
 * @author 			Slushman <chris@slushman.com>
 */

namespace ToutSocialButtons\Frontend;

class Frontend {

	/**
	 * The active buttons.
	 *
	 * @since 		1.0.0
	 * @access 		protected
	 * @var 		array 		$buttons 		Array of active buttons.
	 */
	private $buttons;

	/**
	 * The plugin settings.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		array 			$settings 		The plugin settings.
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.0.0
	 */
	public function __construct() {

		$this->set_settings();
		//$this->set_buttons();

	} // __construct()

	/**
	 * Registers all the WordPress hooks and filters related to this class.
	 *
	 * @hooked 		init
	 * @since 		1.0.0
	 */
	public function hooks() {

		add_action( 'wp_enqueue_scripts', 	array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', 	array( $this, 'enqueue_scripts' ) );
		add_filter( 'the_content', 			array( $this, 'add_buttons_to_content' ), 19, 1 );
		add_shortcode( 'toutsocialbuttons', array( $this, 'shortcode' ) );
		add_action( 'tout_social_buttons_button_set_wrap_begin', array( $this, 'pretext' ) );
		//add_action( 'wp_footer', 			array( $this, 'inline_scripts' ) );

		/**
		 * Action instead of template tag.
		 *
		 * Usage:
		 * do_action( 'toutsocialbuttons' );
		 *
		 * @link 		http://nacin.com/2010/05/18/rethinking-template-tags-in-plugins/
		 */
		add_action( 'toutsocialbuttons', 	array( $this, 'shortcode' ) );

	} // hooks()

	/**
	 * Adds the tout buttons after the post content.
	 *
	 * @hooked 		the_content
	 * @since 		1.0.0
	 * @param 		mixed 		$content 		The current content.
	 * @return 		mixed 						The content plus the tout buttons.
	 */
	public function add_buttons_to_content( $content ) {

		global $wp_current_filter;

		if ( empty( $content ) ) { return $content; }
		if ( is_preview() ) { return $content; }
		if ( is_home() ) { return $content; }
		if ( is_front_page() ) { return $content; }
		if ( in_array( 'get_the_excerpt', (array) $wp_current_filter ) ) { return $content; }
		if ( ! is_main_query() ) { return $content; }

		$current_post_type = get_post_type();

		if ( ! $this->check_post_type( $current_post_type ) ) { return $content; }
		if ( ! $this->check_auto_post( $current_post_type ) ) { return $content; }

		return $content . $this->display_buttons();

	} // add_buttons_to_content()

	/**
	 * Returns whether the buttons should automatically appear at the bottom
	 * of posts or not.
	 *
	 * The filter allows extensions to change this check based on a different
	 * criteria, like a different plugin setting.
	 *
	 * @since 		1.0.0
	 * @param 		string 		$current_post_type 		The current post type.
	 * @return 		bool 								TRUE if autoposting, FALSE if not
	 */
	protected function check_auto_post( $current_post_type ) {

		$autopost = FALSE;

		if ( isset( $this->settings['auto-post'] ) && 1 === $this->settings['auto-post'] ) {

			$autopost = TRUE;

		}

		/**
		 * The tout_social_buttons_check_auto_post filter.
		 *
		 * Allows for changing this check based on criteria besides
		 * the auto-post plugin setting.
		 *
		 * @var 		bool 		$autopost 				Whether auto-post setting is on or off.
		 * @var 		string 		$current_post_type 		The current post type.
		 */
		return apply_filters( 'tout_social_buttons_check_auto_post', $autopost, $current_post_type );

	} // check_auto_post()

	/**
	 * Returns whether the buttons should automatically appear at the bottom
	 * of the current post type or not.
	 *
	 * The filter allows for checking other post types.
	 *
	 * @since 		1.0.0
	 * @param 		string 		$current_post_type 		The current post type.
	 * @return 		bool 								TRUE if post type is selected, FALSE if not
	 */
	protected function check_post_type( $current_post_type ) {

		$post_types[] = 'post';

		/**
		 * The tout_social_buttons_check_post_types filter.
		 *
		 * Allows for chceking other post types.
		 *
		 * @var 		array 		$post_type
		 */
		$post_types = apply_filters( 'tout_social_buttons_check_post_types', $post_types );

		return in_array( $current_post_type, $post_types );

	} // check_post_type()

	/**
	 * Includes the tout buttons partial file inside an output buffer.
	 *
	 * @exits 		If no buttons are selected.
	 * @exits 		If not on the selected post type.
	 * @hooked 		toutsocialbuttons
	 * @since 		1.0.0
	 * @return 		mixed 						The tout buttons partial file.
	 */
	public function display_buttons() {

		$buttons = $this->get_buttons();

		if ( empty( $buttons ) ) { return; }

		ob_start();

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'frontend/partials/button-set.php' );

		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	} // display_buttons()

	/**
	 * Includes the single Tout.Social button file.
	 *
	 * @since 		1.0.0
	 * @param 		string 		$button 		The button slug.
	 */
	public function display_button( $button ) {

		$class_name = '\ToutSocialButtons\Buttons\\' . $button;
		$instance 	= new $class_name;

		include( plugin_dir_path( __FILE__ ) . 'partials/button.php' );

	} // display_button()

	/**
	 * Register the stylesheets for the frontend of the site.
	 *
	 * @hooked 		wp_enqueue_scripts
	 * @since 		1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( TOUT_SOCIAL_BUTTONS_SLUG, plugin_dir_url( __FILE__ ) . 'css/tout-social-buttons.css', array(), TOUT_SOCIAL_BUTTONS_VERSION, 'all' );

	} // enqueue_styles()

	/**
	 * Register the JavaScript for the frontend of the site.
	 *
	 * @hooked 		wp_enqueue_scripts
	 * @since 		1.0.0
	 */
	public function enqueue_scripts() {

		if ( 'popup' !== $this->settings['button-behavior'] ) { return; }

		wp_enqueue_script( TOUT_SOCIAL_BUTTONS_SLUG, plugin_dir_url( __FILE__ ) . 'js/tout-social-buttons-frontend.min.js', array( 'jquery' ), TOUT_SOCIAL_BUTTONS_VERSION, true );

	} // enqueue_scripts()

	/**
	 * Returns the classes for the button icon wrap.
	 *
	 * @since 		1.0.0
	 * @param 		object 		$instance 		The button instance object.
	 * @return 		string 						The classes for the button icon wrap.
	 */
	protected function get_button_icon_wrap_classes( $instance ) {

		$return 	= '';
		$classes[] 	= 'tout-social-button-icon-wrap';

		if ( 'icon' !== $instance->get_type() ) {

			$classes[] = 'hidden';

		}

		/**
		 * The tout_social_buttons_button_icon_wrap_classes filter.
		 *
		 * Allows for changing classes on the button icon wrap.
		 *
		 * @var 		array 		$classes
		 */
		$classes 	= apply_filters( 'tout_social_buttons_button_icon_wrap_classes', $classes );
		$return 	= implode( ' ', $classes );

		return $return;

	} // get_button_icon_wrap_classes()

	/**
	 * Returns the classes for the button link.
	 *
	 * @since 		1.0.0
	 * @param 		string 		$button 		The button.
	 * @return 		string 						The classes for the button link.
	 */
	protected function get_button_link_classes( $button ) {

		$return 	= '';
		$classes[] 	= 'tout-social-button-link';
		$classes[] 	= 'tout-social-button-link-' . $button;

		if ( 'popup' === $this->settings['button-behavior'] ) {

			$classes[] = 'tout-social-button-popup-link';

		}

		/**
		 * The tout_social_buttons_button_link_classes filter.
		 *
		 * Allows for changing classes on the button links.
		 *
		 * @var 		array 		$classes
		 * @var 		string 		$button
		 */
		$classes 	= apply_filters( 'tout_social_buttons_button_link_classes', $classes, $button );
		$return 	= implode( ' ', $classes );

		return $return;

	} // get_button_link_classes()

	/**
	 * Returns the classes for the button set.
	 *
	 * @since 		1.0.0
	 * @return 		string 			The classes for the button set.
	 */
	protected function get_button_set_classes() {

		$return 	= '';
		$classes[] 	= 'tout-social-buttons';
		$classes[] 	= 'icon-color-brand';
		$classes[] 	= 'bg-color-none';

		/**
		 * The tout_social_buttons_button_set_classes filter.
		 *
		 * Allows for changing classes on the button set.
		 *
		 * @var 		array 		$classes
		 */
		$classes 	= apply_filters( 'tout_social_buttons_button_set_classes', $classes );
		$return 	= implode( ' ', $classes );

		return $return;

	} // get_button_set_classes()

	/**
	 * Returns the classes for the button text span.
	 *
	 * @since 		1.0.0
	 * @param 		object 		$instance 		The button instance object.
	 * @return 		string 						The classes for the button text span.
	 */
	protected function get_button_text_classes( $instance ) {

		$return 	= '';
		$classes[] 	= 'tout-social-button-text';

		if ( 'icon' === $instance->get_type() ) :

			$classes[] = 'screen-reader-text';

		endif;

		/**
		 * The tout_social_buttons_button_text_classes filter.
		 *
		 * Allows for changing classes on the button text span.
		 *
		 * @var 		array 		$classes
		 */
		$classes 	= apply_filters( 'tout_social_buttons_button_text_classes', $classes );
		$return 	= implode( ' ', $classes );

		return $return;

	} // get_button_text_classes()

	/**
	 * Returns all the active buttons and an instance of each button class.
	 *
	 * Gets the button order. Explodes that string into an array.
	 * Loops through the button order and adds any active buttons
	 * to the $buttons class variable array.
	 *
	 * @since 		1.0.0
	 */
	protected function get_buttons() {

		//wp_die( print_r( $this->settings ) );

		$active = $this->settings['active-buttons'];

		if ( empty( $active ) ) { return; }

		/**
		 * The tout_social_buttons_frontend_buttons filter.
		 *
		 * @param 		array 		$buttons 		Array of button objects.
		 */
		$buttons 	= apply_filters( 'tout_social_buttons_frontend_buttons', array() );
		$active 	= explode( ',', $active );
		$ordered 	= array();

		// Put the active buttons in order.
		foreach ( $active as $button ) {

			$ordered[$button] = $buttons[$button];

		}

		/**
		 * The tout_social_buttons_frontend_active_buttons filter.
		 *
		 * Allows for adding active buttons via filter.
		 *
		 * @param 		array 		$buttons 		Button selected in the plugin settings.
		 */
		$this->buttons = apply_filters( 'tout_social_buttons_frontend_active_buttons', $buttons );

	} // get_buttons()

	/**
	 * Includes the pretext partial file.
	 *
	 * @hooked 		tout_social_buttons_button_set_wrap_begin
	 * @since 		1.0.0
	 */
	public function pretext() {

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'frontend/partials/pretext.php' );

	} // pretext()

	/**
	 * Sets the class variable $settings with the plugin settings.
	 *
	 * @since 		1.0.0
	 */
	public function set_settings() {

		$this->settings = get_option( TOUT_SOCIAL_BUTTONS_SETTINGS );

	} // set_settings()

	/**
	 * Handles the output of the shortcode.
	 *
	 * Does not currently use any shortcode attributes.
	 *
	 * @hooked 		toutsocialbuttons
	 * @since 		1.0.0
	 * @param 		array 		$atts 			The shortcode attributes.
	 * @param 		mixed 		$content 		Optional. The post content.
	 * @return 		mixed 						The shortcode output.
	 */
	public function shortcode( $atts = array(), $content = null ) {

		$defaults[''] 	= '';
		$args 			= shortcode_atts( $defaults, $atts, 'toutsocialbuttons' );

		return $this->display_buttons();

	} // shortcode()

} // class
