<?php
/**
 * Arcade Class
 *
 * @author   WooThemes
 * @package  Arcade
 * @since    2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Arcade' ) ) :

	/**
	 * The main Arcade class.
	 */
	class Arcade {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_child_styles' ), 99 );
		}

		/**
		 * Enqueue Storefront Styles
		 *
		 * @return void
		 */
		public function enqueue_styles() {
			global $storefront_version;

			wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css', $storefront_version );
		}

		/**
		 * Enqueue Storechild Styles
		 *
		 * @return void
		 */
		public function enqueue_child_styles() {
			global $storefront_version, $arcade_version;

			wp_style_add_data( 'storefront-child-style', 'rtl', 'replace' );

			wp_enqueue_style( 'montserrat', '//fonts.googleapis.com/css?family=Montserrat:400,700', array( 'a-style' ) );
			wp_enqueue_style( 'arimo', '//fonts.googleapis.com/css?family=Arimo:400,400italic,700', array( 'a-style' ) );

			if ( is_page_template( 'template-homepage.php' ) ) {
				wp_enqueue_script( 'arcade', get_stylesheet_directory_uri() . '/assets/js/arcade.min.js', array( 'jquery' ), $arcade_version );
			}
		}
	}
endif;

return new Arcade();
