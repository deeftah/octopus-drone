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
  * Load the individual classes required by this theme
  */
include_once('inc/class-octopus-drone.php');
include_once( 'inc/class-octopus-drone-customizer.php' );
// include_once( 'inc/class-arcade-structure.php' );
// include_once( 'inc/class-arcade-integrations.php' );
// include_once( 'inc/plugged.php' );

/**
 * Do not add custom code / snippets here.
 * While Child Themes are generally recommended for customisations, in this case it is not
 * wise. Modifying this file means that your changes will be lost when an automatic update
 * of this theme is performed. Instead, add your customisations to a plugin such as
 * https://github.com/woothemes/theme-customisations
 */
