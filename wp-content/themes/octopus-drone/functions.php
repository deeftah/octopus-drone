<?php
/**
 * OctopusDrone engine room
 *
 * @author   Marc Dobler
 * @package  Octopus Drone
 * @since    1.0
 */

 $theme = wp_get_theme('octopus-drone');
 $octopus_drone_version = $theme['Version'];

 /**
  * Set the content width based on the theme's design and stylesheet.
  */
 if ( ! isset( $content_width ) ) {
 	$content_width = 980; /* pixels */
 }

 $octopus_drone = (object) array(
 	'version' =>  $octopus_drone_version,

 	/**
 	 * Initialize all the things.
 	 */
 	'main'       => require 'inc/class-octopus-drone.php',
 	'customizer' => require 'inc/customizer/class-octopus-drone-customizer.php',
 );

 // require 'inc/octopus-drone-functions.php';
require 'inc/octopus-drone-template-hooks.php';
require 'inc/octopus-drone-template-functions.php';

 /**
  * Load the individual classes required by this theme
  */
// include_once('inc/class-octopus-drone.php');
// include_once( 'inc/class-octopus-drone-customizer.php' );

// include_once( 'inc/class-arcade-structure.php' );
// include_once( 'inc/class-arcade-integrations.php' );
// include_once( 'inc/plugged.php' );

// if ( storefront_is_woocommerce_activated() ) {
	// $storefront->woocommerce = require 'inc/woocommerce/class-storefront-woocommerce.php';

	// require 'inc/woocommerce/octopus-drone-woocommerce-template-hooks.php';
	// require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
// }

/**
 * Do not add custom code / snippets here.
 * While Child Themes are generally recommended for customisations, in this case it is not
 * wise. Modifying this file means that your changes will be lost when an automatic update
 * of this theme is performed. Instead, add your customisations to a plugin such as
 * https://github.com/woothemes/theme-customisations
 */
