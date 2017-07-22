<?php

/**
 * The ajax-specific functionality of the plugin.
 *
 * @link       https://www.slushman.com
 * @since      1.0.0
 *
 * @package    Tout_Social_Buttons
 * @subpackage Tout_Social_Buttons/admin
 */

/**
 * The ajax-specific functionality of the plugin.
 *
 * Handles the AJAX request for saving the button order in the admin.
 *
 * @package    Tout_Social_Buttons
 * @subpackage Tout_Social_Buttons/admin
 * @author     Slushman <chris@slushman.com>
 */
class Tout_Social_Buttons_AJAX_Save_Buttons {

	/**
	 * The plugin settings.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$settings 		The plugin settings.
	 */
	private $settings;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_settings();

	} // __construct()

	/**
	 * Saves the button order when buttons are sorted.
	 * Request comes through AJAX.
	 *
	 * @since 		1.0.0
	 */
	public function save_button_order() {

		check_ajax_referer( 'tout-social-buttons-order-ajax-nonce', 'tbOrderNonce' );

		$new_order 						= $_POST['order'];
		$this->settings['button-order']	= $new_order;
		$update 						= update_option( TOUT_SOCIAL_BUTTONS_SETTINGS, $this->settings );

		if ( ! $update ) {

			echo esc_html__( 'There was a problem saving the button order.', 'tout-social-buttons' );

		} else {

			echo esc_html__( 'Button order saved.', 'tout-social-buttons' );

		}

		wp_die();

	} // save_button_order()

	/**
	 * Saves button selection.
	 * Request comes through AJAX.
	 *
	 * @since 		1.0.0
	 */
	public function save_button_selection() {

		check_ajax_referer( 'tout-social-buttons-selection-ajax-nonce', 'tbSelectionNonce' );

		$selection 								= $_POST['selection'];
		$this->settings['button-' . $selection]	= $_POST['checked'];
		$update 								= update_option( TOUT_SOCIAL_BUTTONS_SETTINGS, $this->settings );

		if ( ! $update ) {

			echo esc_html__( 'Could not save the selected button.', 'tout-social-buttons' );

		} elseif ( ! $_POST['checked'] ) {

			echo esc_html__( 'Button deselected.', 'tout-social-buttons' );

		} else {

			echo esc_html__( 'Button selection saved.', 'tout-social-buttons' );

		}

		wp_die();

	} // save_button_selection()

	/**
	 * Sets the class variable $settings.
	 *
	 * @since 		1.0.0
	 */
	private function set_settings() {

		$this->settings = get_option( TOUT_SOCIAL_BUTTONS_SETTINGS );

	} // set_settings()

} // class
