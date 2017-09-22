<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

define( 'WP_ROCKET_ADVANCED_CACHE', true );
$rocket_cache_path = '/home/marc/www-dev/octopus-drone.com/wp-content/cache/wp-rocket/';
$rocket_config_path = '/home/marc/www-dev/octopus-drone.com/wp-content/wp-rocket-config/';

if ( file_exists( '/home/marc/www-dev/octopus-drone.com/wp-content/plugins/wp-rocket/inc/vendors/Mobile_Detect.php' ) ) {
	include( '/home/marc/www-dev/octopus-drone.com/wp-content/plugins/wp-rocket/inc/vendors/Mobile_Detect.php' );
}
if ( file_exists( '/home/marc/www-dev/octopus-drone.com/wp-content/plugins/wp-rocket/inc/front/process.php' ) ) {
	include( '/home/marc/www-dev/octopus-drone.com/wp-content/plugins/wp-rocket/inc/front/process.php' );
} else {
	define( 'WP_ROCKET_ADVANCED_CACHE_PROBLEM', true );
}