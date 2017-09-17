<?php
/**
 * Arcade_Structure Class
 *
 * @author   WooThemes
 * @package  Arcade
 * @since    2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Arcade_Structure' ) ) :

	/**
	 * Arcade structure class
	 */
	class Arcade_Structure {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'storefront_header', array( $this, 'a_primary_navigation_wrapper' ), 45 );
			add_action( 'storefront_header', array( $this, 'a_primary_navigation_wrapper_close' ), 65 );

			add_action( 'init', array( $this, 'arcade_custom_storefront_markup' ) );

			add_filter( 'storefront_product_categories_args', array( $this, 'arcade_homepage_categories' ), 5 );
			add_filter( 'storefront_recent_products_args', array( $this, 'arcade_homepage_products' ), 5 );
			add_filter( 'storefront_featured_products_args', array( $this, 'arcade_homepage_products' ), 5 );
			add_filter( 'storefront_popular_products_args', array( $this, 'arcade_homepage_products' ), 5 );
			add_filter( 'storefront_on_sale_products_args', array( $this, 'arcade_homepage_products' ), 5 );
			add_filter( 'storefront_best_selling_products_args', array( $this, 'arcade_homepage_products' ), 5 );

			add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'arcade_change_breadcrumb_delimiter' ), 999 );

			add_filter( 'storefront_loop_columns', array( $this, 'return_4' ) );
			add_filter( 'storefront_related_products_args', array( $this, 'a_related_products_args' ) );

			add_filter( 'storefront_product_categories_args',    array( $this, 'homepage_section_title' ), 999 );
			add_filter( 'storefront_featured_products_args',     array( $this, 'homepage_section_title' ), 999 );
			add_filter( 'storefront_recent_products_args',       array( $this, 'homepage_section_title' ), 999 );
			add_filter( 'storefront_popular_products_args',      array( $this, 'homepage_section_title' ), 999 );
			add_filter( 'storefront_on_sale_products_args',      array( $this, 'homepage_section_title' ), 999 );
			add_filter( 'storefront_best_selling_products_args', array( $this, 'homepage_section_title' ), 999 );
		}

		/**
		 * Primary navigation wrapper
		 *
		 * @return void
		 */
		public function a_primary_navigation_wrapper() {
			echo '<section class="a-primary-navigation">';
		}

		/**
		 * Primary navigation wrapper close
		 *
		 * @return void
		 */
		public function a_primary_navigation_wrapper_close() {
			echo '</section>';
		}

		/**
		 * Custom markup tweaks
		 *
		 * @return void
		 */
		public function arcade_custom_storefront_markup() {
			remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
			add_action( 'storefront_header', 'storefront_secondary_navigation', 5 );
			add_action( 'storefront_header', array( $this, 'a_secondary_navigation_wrapper' ), 4 );
			add_action( 'storefront_header', array( $this, 'a_secondary_navigation_wrapper_close' ), 6 );

			add_action( 'storefront_header', array( $this, 'a_header_content_wrapper' ), 7 );
			add_action( 'storefront_header', array( $this, 'a_header_content_wrapper_close' ), 41 );

			if ( class_exists( 'WooCommerce' ) ) {
				remove_action( 'storefront_header', 'storefront_header_cart', 60 );
				add_action( 'storefront_header', 'storefront_header_cart', 40 );

				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 	10 );
				add_action( 'woocommerce_single_product_summary', 			'woocommerce_show_product_sale_flash', 	3 );
			}
		}

		/**
		 * Header content wrapper
		 *
		 * @return void
		 */
		public function a_header_content_wrapper() {
			echo '<section class="arcade-header-content">';
		}
		/**
		 * Header content wrapper close
		 *
		 * @return void
		 */
		public function a_header_content_wrapper_close() {
			echo '</section>';
		}

		/**
		 * Secondary navigation wrapper
		 *
		 * @return void
		 */
		public function a_secondary_navigation_wrapper() {
			echo '<section class="a-secondary-navigation">';
		}
		/**
		 * Secondary navigation wrapper close
		 *
		 * @return void
		 */
		public function a_secondary_navigation_wrapper_close() {
			echo '</section>';
		}

		/**
		 * Specified how many categories to display on the homepage
		 *
		 * @param array $args The arguments used to control the layout of the homepage product sections.
		 */
		public function arcade_homepage_categories( $args ) {
			$args['limit'] 		= 5;
			$args['columns'] 	= 5;

			return $args;
		}

		/**
		 * Specified how many products to display on the homepage
		 *
		 * @param array $args The arguments used to control the layout of the homepage product sections.
		 */
		public function arcade_homepage_products( $args ) {
			$args['limit'] 		= 5;
			$args['columns'] 	= 5;

			return $args;
		}

		/**
		 * Change the WooCommerce breadcrumb delimiter
		 *
		 * @param array $defaults the breadcrumb defaults.
		 */
		public function arcade_change_breadcrumb_delimiter( $defaults ) {
			$defaults['delimiter'] = '';
			return $defaults;
		}

		/**
		 * Set the number of related products to match the product archive columns
		 *
		 * @param  array $args related products args.
		 */
		public function a_related_products_args( $args ) {
			$args['posts_per_page'] = 4;
			$args['columns'] 		= 4;

			return $args;
		}

		/**
		 * Wrap homepage section titles inside a `span`.
		 *
		 * @param  array $args homepage section arguments.
		 * @return array       homepage section arguments
		 */
		public function homepage_section_title( $args ) {
			$title          = $args['title'];
			$args['title']  = '<span>' . $title . '</span>';

			return $args;
		}

		/**
		 * Set the default column layout to 4 products
		 *
		 * @param  int $cols number of columns.
		 */
		public function return_4( $cols ) {
			$cols = 4;

			return $cols;
		}
	}
endif;

return new Arcade_Structure();
