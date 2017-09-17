<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Plugin Name: LCDR Push Notification
 * Plugin URI: http://lecourrierderussie.com/
 * Description: Allow to ask user for notifications and hande sending to users
 * Version: 0.1
 * Author: Dammaretz Theo
 * Author URI: http://dammaretz.fr.
 */

// Defines
define('NOTIFICATIONS_PLUGIN_ROOT_DIR', plugin_dir_path(__FILE__));
define('NOTIFICATIONS_SUBSCRIBER_TABLE', 'notifications_subscribers');

// Include subclasses
require_once NOTIFICATIONS_PLUGIN_ROOT_DIR.'includes/api.php';
require_once NOTIFICATIONS_PLUGIN_ROOT_DIR.'includes/admin.php';
require_once NOTIFICATIONS_PLUGIN_ROOT_DIR.'includes/endpoint.php';

// Load the web push PHP API
require NOTIFICATIONS_PLUGIN_ROOT_DIR . 'vendor/autoload.php';
use Minishlink\WebPush\WebPush;

/**
 * Create DB tables on plugin activation
 * @return void
 */
function notifications_on_activate()
{
    $db = new Notifications_API_DB();
    $db->initDB();
}
register_activation_hook(__FILE__, 'notifications_on_activate');

/**
 * Intantiate all the different classes of the plugin
 * @return void 
 */
function notifications_init() {
    if (is_admin()) {
        new Notification_Admin_Class();
    }
    new Notifications_API_Emitter();
    new Notifications_API_Endpoint();
}
add_action('init', 'notifications_init');

/**
 * Register and localize the required scripts
 * @return void
 */
function notifications_register_scripts()
{
    wp_register_script('notifications_subscribe_script', plugin_dir_url(__FILE__) .'includes/js/notifications.js', array(), false, true );
    $options = get_option('notifications_admin');
    $options_array = array(vapid_public => $options['vapid_public']);
    wp_localize_script( 'notifications_subscribe_script', 'notifications_options', $options_array );
    wp_enqueue_script('notifications_subscribe_script');
}
add_action('wp_enqueue_scripts', 'notifications_register_scripts');
