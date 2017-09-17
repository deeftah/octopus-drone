<?php
/**
 * Arcade_Integrations Class
 *
 * @author   WooThemes
 * @package  Arcade
 * @since    2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Arcade_Integrations' ) ) :

	/**
	 * Arcade Integrations class
	 */
	class Arcade_Integrations {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'after_switch_theme', array( $this, 'edit_theme_mods' ) );
			add_action( 'customize_register', array( $this, 'edit_controls' ), 99 );
		}

		/**
		 * Remove unused/incompatible controls
		 *
		 * @param array $wp_customize the Customizer object.
		 * @return void
		 */
		public function edit_controls( $wp_customize ) {
			$wp_customize->remove_control( 'storefront_header_background_color' );
			$wp_customize->remove_control( 'storefront_footer_background_color' );
		}

		/**
		 * Set / remove theme mods that are incompatible with this theme
		 *
		 * @return void
		 */
		public function edit_theme_mods() {
			remove_theme_mod( 'storefront_header_background_color' );
			remove_theme_mod( 'storefront_footer_background_color' );
		}
	}
endif;

return new Arcade_Integrations();
