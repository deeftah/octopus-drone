<?php
/**
 * Arcade_Customizer Class
 *
 * @author   WooThemes
 * @package  Arcade
 * @since    2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Arcade_Customizer' ) ) :

	/**
	 * Arcade Customizer Class
	 */
	class Arcade_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$theme 					= wp_get_theme( 'storefront' );
			$storefront_version 	= $theme['Version'];

			add_action( 'wp_enqueue_scripts',                array( $this, 'add_customizer_css' ), 	1000 );
			add_filter( 'storefront_custom_background_args', array( $this, 'arcade_background' ) );
			add_filter( 'storefront_setting_default_values', array( $this, 'arcade_defaults' ) );

			/**
			 * The following can be removed when Storefront 2.1 lands
			 */
			add_action( 'customize_register',                               array( $this, 'edit_defaults' ), 		99 );
			add_action( 'init',												array( $this, 'default_theme_mod_values' ) );
			if ( version_compare( $storefront_version, '2.0.0', '<' ) ) {
				add_action( 'init',											array( $this, 'default_theme_settings' ) );
			}
		}

		/**
		 * Returns an array with default storefront and extension options
		 *
		 * @return array
		 */
		public function arcade_defaults() {
			return array(
				'storefront_heading_color'					=> '#333333',
				'storefront_footer_heading_color'			=> '#333333',
				'storefront_header_background_color'		=> '#333333',
				'storefront_header_link_color'				=> '#aaaaaa',
				'storefront_header_text_color'				=> '#878787',
				'storefront_footer_link_color'				=> '#666666',
				'storefront_text_color'						=> '#666666',
				'storefront_footer_text_color'				=> '#666666',
				'storefront_accent_color'					=> '#F34418',
				'storefront_button_background_color'		=> '#333333',
				'storefront_button_text_color'				=> '#ffffff',
				'storefront_button_alt_background_color'	=> '#F34418',
				'storefront_button_alt_text_color'			=> '#ffffff',
				'background_color'							=> 'ededed',
			);
		}

		/**
		 * Remove / Set Customizer settings (including extensions).
		 *
		 * @return void
		 * @param array $wp_customize the Customize object.
		 */
		public function edit_defaults( $wp_customize ) {
			$wp_customize->get_setting( 'storefront_header_link_color' )->transport 	= 'refresh';
			$wp_customize->get_setting( 'storefront_header_text_color' )->transport 	= 'refresh';

			// Set default values for settings in customizer.
			foreach ( Arcade_Customizer::arcade_defaults() as $mod => $val ) {
				$setting = $wp_customize->get_setting( $mod );

				if ( is_object( $setting ) ) {
					$setting->default = $val;
				}
			}
		}

		/**
		 * Returns a default theme_mod value if there is none set.
		 *
		 * @uses arcade_defaults()
		 * @return void
		 */
		public function default_theme_mod_values() {
			foreach ( Arcade_Customizer::arcade_defaults() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, function( $setting ) use ( $val ) {
					return $setting ? $setting : $val;
				});
			}
		}

		/**
		 * Sets default theme color filters for storefront color values.
		 * This function is required for Storefront < 2.0.0 support
		 *
		 * @uses arcade_defaults()
		 * @return void
		 */
		public function default_theme_settings() {
			$prefix_regex = '/^storefront_/';
			foreach ( self::arcade_defaults() as $mod => $val ) {
				if ( preg_match( $prefix_regex, $mod ) ) {
					$filter = preg_replace( $prefix_regex, 'storefront_default_', $mod );
					add_filter( $filter, function( $setting ) use ( $val ) {
						return $val;
					}, 99 );
				}
			}
		}

		/**
		 * Add inline css
		 *
		 * @return void
		 */
		public function add_customizer_css() {
			$header_text_color 				= get_theme_mod( 'storefront_header_text_color', 			'#878787' );
			$accent_color 					= get_theme_mod( 'storefront_accent_color', 				'#F34418' );
			$header_link_color 				= get_theme_mod( 'storefront_header_link_color', 			'#aaaaaa' );
			$text_color 					= get_theme_mod( 'storefront_text_color', 					'#666666' );
			$heading_color 					= get_theme_mod( 'storefront_heading_color', 				'#333333' );
			$button_alt_background_color 	= get_theme_mod( 'storefront_button_alt_background_color', 	'#773CDB' );

			$style = '
				.main-navigation ul li.smm-active li ul.products li.product h3 {
					color: ' . $header_text_color . ';
				}

				.single-product div.product .onsale + .product_title + .woocommerce-product-rating + div[itemprop="offers"] .price {
					border-color: ' . $accent_color . ';
				}

				.sd-header-sticky .secondary-navigation {
					background-color: ' . storefront_get_content_background_color() . ';
				}

				.page-template-template-homepage .site-main ul.tabs li a,
				.woocommerce-breadcrumb a {
					color: ' . $text_color . ';
				}

				.pagination .page-numbers li .page-numbers.current,
				.woocommerce-pagination .page-numbers li .page-numbers.current {
					background-color: transparent;
					border-color: ' . $button_alt_background_color . ';
					color: ' . $button_alt_background_color . ';
				}

				.woocommerce-breadcrumb {
					color: ' . storefront_adjust_color_brightness( $text_color, 75 ) . ';
				}

				.page-template-template-homepage .site-main ul.tabs li a.active,
				.page-template-template-homepage .site-main ul.tabs li a:hover,
				.single-product div.product .woocommerce-product-rating a,
				.woocommerce-breadcrumb a:hover {
					color: ' . storefront_adjust_color_brightness( $text_color, -50 ) . ';
				}

				ul.products li.product .star-rating span:before,
				.page-template-template-homepage .site-main .storefront-product-section ~ .storefront-product-section:not(.last) ul.products li.product.product-category h3,
				.page-template-template-homepage .site-main .storefront-product-section ~ .storefront-product-section:not(.last) ul.products li.product.product-category h3 mark {
					color: ' . $heading_color . ';
				}

				ul.products li.product a:hover h3,
				ul.products li.product .price,
				.single-product div.product .summary .price,
				.page-template-template-homepage .site-main .storefront-product-section ~ .storefront-product-section ul.products li.product.product-category a:hover h3,
				.page-template-template-homepage .site-main .storefront-product-section ~ .storefront-product-section ul.products li.product.product-category a:hover h3 mark {
					color: ' . $accent_color . ';
				}

				.single-product div.product .onsale,
				ul.products li.product.product-category a:hover h3,
				ul.products li.product a .onsale {
					background-color: ' . $accent_color . ';
				}

				.a-primary-navigation {
					border-top-color: ' . $accent_color . ';
				}

				.site-header-cart .widget_shopping_cart a {
					color: ' . $header_link_color . ';
				}

				.main-navigation ul li a:hover,
				.main-navigation ul li:hover > a,
				.main-navigation ul.menu li.current-menu-item > a,
				.main-navigation ul.nav-menu li.current-menu-item > a,
				.site-header-cart .widget_shopping_cart a:hover,
				.main-navigation ul li.smm-active .widget ul:not(.products) li a:hover {
					color: ' . storefront_adjust_color_brightness( $header_link_color, 50 ) . ' !important;
				}

				.site-title a,
				.site-branding h1 a,
				ul.menu li a.cart-contents {
					color: ' . storefront_adjust_color_brightness( $header_link_color, -100 ) . ';
				}

				.site-title a:hover,
				.site-branding h1 a:hover,
				ul.menu li a.cart-contents:hover {
					color: ' . storefront_adjust_color_brightness( $header_link_color, -150 ) . ';
				}

				@media screen and (min-width: 768px) {
					.site-header-cart .cart-contents span.count:before {
						border-color: ' . $accent_color . ';
					}

					.site-header-cart .widget_shopping_cart,
					.main-navigation > li > a:before, .main-navigation ul.menu > li > a:before, .main-navigation ul.nav-menu > li > a:before {
						border-top-color: ' . $accent_color . ';
					}

					.site-header-cart .cart-contents span.count:after {
						border-top-color: ' . $accent_color . ';
						border-left-color: ' . $accent_color . ';
						border-right-color: ' . $accent_color . ';
					}
				}';

			wp_add_inline_style( 'storefront-child-style', $style );
		}

		/**
		 * Arcade background settings
		 *
		 * @param array $args the background arguments.
		 * @return array $args the modified args.
		 */
		public function arcade_background( $args ) {
			$args['default-image'] 		= get_stylesheet_directory_uri() . '/assets/images/texture.jpg';
			$args['default-attachment'] = 'fixed';

			return $args;
		}
	}
endif;

return new Arcade_Customizer();
