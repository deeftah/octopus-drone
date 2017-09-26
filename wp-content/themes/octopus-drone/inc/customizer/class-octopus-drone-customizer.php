<?php
/**
 * Octopus_Drone_Customizer Class
 *
 * @author   WooThemes
 * @package  Octopus Drone
 * @since    1.0
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('Octopus_Drone_Customizer')) :

    /**
     * Arcade Customizer Class
     */
    class Octopus_Drone_Customizer
    {

        /**
         * Setup class.
         *
         * @since 1.0
         */
        public function __construct()
        {
            $theme = wp_get_theme('storefront');
            $storefront_version = $theme['Version'];

            add_action('wp_enqueue_scripts', [$this, 'add_customizer_css'], 1000);
            // add_filter('storefront_custom_background_args', [$this, 'octopus_drone_background'] ));
            add_filter('storefront_setting_default_values', [$this, 'octopus_drone_defaults']);

            /**
             * The following can be removed when Storefront 2.1 lands
             */
            add_action('customize_register', [$this, 'edit_defaults'], 99);
            add_action('init', [$this, 'default_theme_mod_values']);
            if (version_compare($storefront_version, '2.0.0', '<')) {
                add_action('init', [$this, 'default_theme_settings']);
            }
        }

        /**
         * Returns an array with default storefront and extension options
         *
         * @return array
         */
        public function octopus_drone_defaults()
        {
            return [
            //     'storefront_heading_color'                 => '#333333',
            //     'storefront_footer_heading_color'          => '#333333',
            //     'storefront_header_background_color'       => '#333333',
            //     'storefront_header_link_color'             => '#aaaaaa',
            //     'storefront_header_text_color'             => '#878787',
            //     'storefront_footer_link_color'             => '#666666',
            //     'storefront_text_color'                    => '#666666',
            //     'storefront_footer_text_color'             => '#666666',
            'storefront_accent_color'                       => '#673ab7',
            'storefront_button_background_color'            => '#43454b',
            //     'storefront_button_text_color'             => '#ffffff',
            //     'storefront_button_alt_background_color'   => '#F34418',
            //     'storefront_button_alt_text_color'         => '#ffffff',
            'background_color'                              => '#cfd8dc'
            ];
        }

        /**
         * Remove / Set Customizer settings (including extensions).
         *
         * @return void
         * @param array $wp_customize the Customize object.
         */
        public function edit_defaults($wp_customize)
        {
            $wp_customize->get_setting('storefront_header_link_color')->transport    = 'refresh';
            $wp_customize->get_setting('storefront_header_text_color')->transport    = 'refresh';

            // Set default values for settings in customizer.
            foreach (Octopus_Drone_Customizer::octopus_drone_defaults() as $mod => $val) {
                $setting = $wp_customize->get_setting($mod);

                if (is_object($setting)) {
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
        public function default_theme_mod_values()
        {
            foreach (Octopus_Drone_Customizer::octopus_drone_defaults() as $mod => $val) {
                add_filter('theme_mod_' . $mod, function ($setting) use ($val) {
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
        public function default_theme_settings()
        {
            $prefix_regex = '/^storefront_/';
            foreach (self::octopus_drone_defaults() as $mod => $val) {
                if (preg_match($prefix_regex, $mod)) {
                    $filter = preg_replace($prefix_regex, 'storefront_default_', $mod);
                    add_filter($filter, function ($setting) use ($val) {
                        return $val;
                    }, 99);
                }
            }
        }

        /**
         * Add inline css
         *
         * @return void
         */
        public function add_customizer_css()
        {
            // $header_text_color 				= get_theme_mod( 'storefront_header_text_color', 			'#878787' );
            // $accent_color 					= get_theme_mod( 'storefront_accent_color', 				'#F34418' );
            // $header_link_color 				= get_theme_mod( 'storefront_header_link_color', 			'#aaaaaa' );
            // $text_color 					= get_theme_mod( 'storefront_text_color', 					'#666666' );
            // $heading_color 					= get_theme_mod( 'storefront_heading_color', 				'#333333' );
            // $button_alt_background_color 	= get_theme_mod( 'storefront_button_alt_background_color', 	'#773CDB' );

            // $style = '
						// 						input {
						// 	border-radius:3px;
						// }
            //
						// ul.products {
						// 	display: flex;
						//   	flex-wrap: wrap;
						// }
            //
						// .site-main ul.products li.product {
            //   box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            //   transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            //   display: flex;
            //   flex-wrap: wrap;
            //   overflow: hidden;
						// }
            //
            // .site-main ul.products li.product{margin-right:1.41575em;width:46.2%}.site-main ul.products li.product:nth-child(2n){margin-right:0}
            //
						// .site-main ul.products li.product .onsale {
						// 	position: absolute;
						//     right: .5em;
						//     top: .5em;
						// }
						// .site-main ul.products li.product:hover {
						// 	box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
						// }
            //
						// ul.products li.product .button {
						// 	align-self: flex-end;
						// 	margin:0 auto;
						// }
            //
						// ul.products li.product img {
						// 	border-radius:3px 3px 0 0;
						// }
            //
						// .site-header-cart.focus .widget_shopping_cart, .site-header-cart:hover .widget_shopping_cart {
						// 	width:25em;
						// 	right: 0em;
						//     left: auto;
						// }
            //
						// @media screen and (max-width: 768px) {
						// 	.main-navigation {
						// 		margin-bottom: 1em;
						// 	}
						// }
            //
						// .woocommerce-mini-cart__empty-message {
						//     margin: 0;
						//     padding: 1.41575em;
						// }
            //
						// @media screen and (min-width: 768px) {
						// 	.ssatc-sticky-add-to-cart {
						//     	padding: 2.618em;
						// 		box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
						// 	}
						// }
            //
						// .woocommerce-breadcrumb {
						// 	white-space: nowrap;
						//     overflow: hidden;
						//     text-overflow: ellipsis;
						// }
            //
						// .storefront-handheld-footer-bar ul li>a {
						// 	    height: 4em;
						// 		    font-size: 0.7em;
						// }
						// .product_list_widget li>a {
						//     white-space: nowrap;
						//     overflow: hidden;
						//     text-overflow: ellipsis;
						// }
            //
						// a.remove:before {
						// 	color: rgba(0, 0, 0, 0.5);
						// }
            //
						// .orderby {
						// 	    border: none;
						//     background-color: rgba(38, 50, 56, 0.2);
						//     padding: 1em;
						// }
            //
						// .woocommerce-result-count {
						// 	padding: 1em;
						// }
            //
						// ul.products li.product .woocommerce-LoopProduct-link {
						// 	overflow:hidden;
						// }
            //
						// ul.products li.product .woocommerce-loop-product__title, ul.products li.product h2, ul.products li.product h3 {
						// 	white-space: nowrap;
						//     overflow: hidden;
						//     text-overflow: ellipsis;
						// 	margin-left:1em;
						// 	margin-right:1em;
						// }
            //
						// .storefront-handheld-footer-bar ul li.cart .count {
						//     color: #ffffff;
						//     background: #7f2b11;
						// 	font-size: 1em;
						// 	font-weight: bold;
						// }
            // button.menu-toggle, button.menu-toggle span {
            //   font-weight:bold;
            //   background: #ff5722;
            // }
            // .show-for-sr {
            //   display:none
            // }
            // ';
            //
            // wp_add_inline_style('storefront-child-style', $style);
        }

        /**
         * Octopus Drone background settings
         *
         * @param array $args the background arguments.
         * @return array $args the modified args.
         */
        public function octopus_drone_background($args)
        {
            // $args['default-image']        = get_stylesheet_directory_uri() . '/assets/images/texture.jpg';
            // $args['default-attachment'] = 'fixed';

            return $args;
        }
    }
endif;

return new Octopus_Drone_Customizer();
