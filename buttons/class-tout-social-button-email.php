<?php

/**
 * Defines the Email tout button.
 *
 * @link 			https://www.slushman.com
 * @since 			1.0.0
 *
 * @package 		Tout_Social_Buttons
 * @subpackage 		Tout_Social_Buttons/buttons
 * @author 			Slushman <chris@slushman.com>
 */
class Tout_Button_Email extends Tout_Button {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.0.0
	 */
	public function __construct() {

		$this->set_settings();

		$this->icon = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" class="tout-social-button-icon tout-social-button-icon-email"><path d="M19 14.5v-9q0-.62-.44-1.06T17.5 4H3.49q-.62 0-1.06.44T1.99 5.5v9q0 .62.44 1.06t1.06.44H17.5q.62 0 1.06-.44T19 14.5zm-1.31-9.11q.15.15.175.325t-.04.295-.165.22L13.6 9.95l3.9 4.06q.26.3.06.51-.09.11-.28.12t-.28-.07l-4.37-3.73-2.14 1.95-2.13-1.95-4.37 3.73q-.09.08-.28.07t-.28-.12q-.2-.21.06-.51l3.9-4.06-4.06-3.72q-.1-.1-.165-.22t-.04-.295.175-.325q.4-.4.95.07l6.24 5.04 6.25-5.04q.55-.47.95-.07z"/></svg>';
		$this->id 	= 'email';
		$this->name = esc_html__( 'Email', 'tout-social-buttons' );

		$this->set_a11y_text();
		$this->set_url();

	} // __construct()

	/**
	 * Sets the a11y_text class variable.
	 *
	 * @since 		1.0.0
	 */
	public function set_a11y_text() {

		if ( is_admin() ) {

			$this->a11y_text = esc_html__( 'Share content by ' . $this->name, 'tout-social-buttons' );

		} elseif ( 'icon' === $this->settings['button-type'] ) {

			$this->a11y_text = esc_html__( 'Share by ' . $this->name, 'tout-social-buttons' );

		} elseif ( 'text' === $this->settings['button-type'] ) {

			$this->a11y_text = esc_html__( 'Share by ', 'tout-social-buttons' );

		}

	} // set_a11y_text()

	/**
	 * Sets the url class variable.
	 *
	 * @since 		1.0.0
	 */
	protected function set_url() {

		$this->url['args']['subject'] 			= rawurlencode( get_the_title() );
		$this->url['args']['body'] 				= wp_strip_all_tags( get_the_excerpt() ) . '%0A%0A' . get_permalink();
		$this->url['base_url'] 					= 'mailto:';

	} // set_url()

} // class
