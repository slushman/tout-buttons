<?php

/**
 * Defines the Stumbleupon tout button.
 *
 * @link 			https://www.slushman.com
 * @since 			1.0.0
 *
 * @package 		Tout_Social_Buttons
 * @subpackage 		Tout_Social_Buttons/buttons
 * @author 			Slushman <chris@slushman.com>
 */
class Tout_Button_Stumbleupon extends Tout_Button {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.0.0
	 */
	public function __construct() {

		$this->set_settings();

		$this->icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" class="tout-social-button-icon tout-social-button-icon-stumbleupon"><path d="M11.1 6.9c0-.6-.5-1.1-1.1-1.1s-1.1.5-1.1 1.1v6.3c0 2.4-2 4.4-4.4 4.4-2.4 0-4.4-2-4.4-4.4v-2.7h3.4v2.7c0 .6.5 1.1 1.1 1.1.6 0 1.1-.5 1.1-1.1V6.7c0-2.4 2-4.3 4.4-4.3 2.4 0 4.4 1.9 4.4 4.3v1.4l-2 .6-1.4-.6V6.9zm8.8 3.5v2.7c0 2.4-2 4.4-4.4 4.4-2.4 0-4.4-2-4.4-4.4v-2.8l1.4.6 2-.6v2.8c0 .6.5 1 1.1 1 .6 0 1.1-.5 1.1-1v-2.8h3.2z"/></svg>';
		$this->id 	= 'stumbleupon';
		$this->name = esc_html__( 'Stumbleupon', 'tout-social-buttons' );

		$this->set_a11y_text();
		$this->set_url();

	} // __construct()

	/**
	 * Sets the url class variable.
	 *
	 * @since 		1.0.0
	 */
	protected function set_url() {

		$link 		= urlencode( get_permalink() );
		$title 		= urlencode( get_the_title() );

		$this->url['args']['url'] 		= $link;
		$this->url['args']['title'] 	= $title;
		$this->url['base_url'] 			= 'http://www.stumbleupon.com/submit';

	} // set_url()

} // class
