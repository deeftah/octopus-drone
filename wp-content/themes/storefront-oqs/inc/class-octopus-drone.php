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
            add_filter('the_generator', array( $this, 'remove_version_info'));
            add_action('get_header',array( $this, 'remove_woocommerce_generator_tag'));
        }

        /**
         * Enqueue Storefront Styles
         *
         * @return void
         */
        public function enqueue_styles()
        {
            global $storefront_version;

            wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css', $storefront_version);
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

        //Remove WordPress Generator Tag
        public function remove_version_info() {
          return '';
        }

        //Remove WooCommerce Generator Tag
        public function remove_woocommerce_generator_tag()
        {
          remove_action('wp_head','wc_generator_tag');
        }

    }
endif;

return new Octopus_Drone();
