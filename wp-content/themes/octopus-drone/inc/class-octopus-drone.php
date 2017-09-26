<?php
/**
 * Octopus_Drone Class
 *
 * @author   Marc Dobler
 * @package  Octopus Drone
 * @since    1.0
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('Octopus_Drone')) :

  /**
     * The main OctopusDrone class.
     */
    class Octopus_Drone
    {

        /**
         * Setup class.
         *
         * @since 1.0
         */
        public function __construct()
        {
            add_action('wp_enqueue_scripts', array( $this, 'enqueue_styles' ));
            add_action('wp_enqueue_scripts', array( $this, 'enqueue_child_script' ), 99);
            add_filter('navigation_markup_template', array( $this, 'navigation_markup_template' ) );
            add_filter('the_generator', array( $this, 'remove_version_info'));
            add_action('get_header',array( $this, 'remove_woocommerce_generator_tag'));
            add_filter('style_loader_src',  array( $this, 'removeVersion'));
            add_filter('script_loader_src',  array( $this, 'removeVersion'));
            add_filter( 'wp_default_scripts', array( $this, 'remove_jquery_migrate') );
        }

        /**
         * Enqueue Storefront Styles
         *
         * @return void
         */
        public function enqueue_styles()
        {
            global $octopus_drone_version;

            wp_enqueue_style('octopus-drone-style', get_stylesheet_directory_uri() . '/app.min.css', $octopus_drone_version);
        }

        /**
         * Enqueue Storechild Script
         *
         * @return void
         */
        public function enqueue_child_script()
        {
            global $storefront_version, $octopus_drone_version;

            wp_register_script('pace_lib', get_stylesheet_directory_uri() . '/assets/js/pace.min.js', [], $octopus_drone_version, true);
            wp_enqueue_script('pace_lib');
            wp_register_script('pace_script', get_stylesheet_directory_uri() . '/assets/js/pace-lib.min.js', ['pace_lib'], $octopus_drone_version, true);
            wp_enqueue_script('pace_script');
        }

        /**
    		 * Custom navigation markup template hooked into `navigation_markup_template` filter hook.
    		 */
    		public function navigation_markup_template() {
    			$template  = '<nav id="post-navigation" class="navigation %1$s" aria-label="' . esc_html__( 'Post Navigation', 'octopus-drone' ) . '">';
    			$template .= '<span class="screen-reader-text">%2$s</span>';
    			$template .= '<div class="nav-links">%3$s</div>';
    			$template .= '</nav>';

    			return apply_filters( 'storefront_navigation_markup_template', $template );
    		}

        //Remove WordPress Generator Tag
        public function remove_version_info() {
          return '';
        }

        //Remove WooCommerce Generator Tag
        public function remove_woocommerce_generator_tag()
        {
          remove_action('wp_head','wc_generator_tag');
        }

        /**
* Dequeue jQuery Migrate script in WordPress.
*/
function remove_jquery_migrate( &$scripts) {
    if(!is_admin()) {
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
    }
}

        public function removeVersion($url) {
            $clean = preg_replace_callback('/ver=[^&]*/', function ($matches) {
                return '';
            }, $url);

            return $clean;
        }

    }
endif;

return new Octopus_Drone();
