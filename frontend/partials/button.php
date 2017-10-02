<?php

/**
 * Provides a public-facing view for the plugin
 *
 * This file is used to markup each individual button within the set.
 *
 * @link       https://www.slushman.com
 * @since      1.0.0
 * @package    ToutSocialButtons\Frontend
 */

?><li class="tout-social-button tout-social-button-<?php echo esc_attr( $button ); ?>" data-id="<?php echo esc_attr( $button ); ?>" data-name="<?php echo esc_attr( $instance->get_name() ); ?>">
	<a class="<?php echo esc_attr( $this->get_button_link_classes( $button ) ); ?>" href="<?php echo esc_url( $instance->get_url(), $instance->get_protocols() ); ?>" rel="nofollow"<?php

		echo $instance->get_link_attributes( $button );

		if ( ! empty( $this->settings['button-behavior'] ) && 'email' !== $button ) {

			echo ' target="_blank"';

		}

		?> title="<?php echo esc_attr( $instance->get_title() ); ?>">
		<span class="<?php echo esc_attr( $this->get_button_icon_wrap_classes( $instance ) ); ?>"><?php

			echo $instance->get_icon();

		?></span>
		<span class="screen-reader-text"><?php

			echo $instance->get_a11y_text();

		?></span>
		<span class="<?php echo esc_attr( $this->get_button_text_classes( $instance ) ); ?>"><?php

			echo $instance->get_name();

		?></span>
	</a>
</li>
