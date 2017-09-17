<?php

//Web Push API class
use Minishlink\WebPush\WebPush;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class responsible for emitting the notifications
 */
class Notifications_API_Emitter
{

    private $auth = array(); // Parameter for the Web API containing the required auth parameters
    private $options; // Options from Admin panel
    private $db; // Database handling class

    /**
     * Notification class constructor initializing private variables
     */
    public function __construct()
    {
        $this->options = get_option('notifications_admin');
        $this->auth = array(
            'GCM' => $this->options['gcm_api_key'], // deprecated and optional, it's here only for compatibility reasons
            'VAPID' => array(
                'subject' => 'https://www.lecourrierderussie.com', // can be a mailto: or your website address
                'publicKey' => $this->options['vapid_public'], // (recommended) uncompressed public key P-256 encoded in Base64-URL
                'privateKey' => $this->options['vapid_private'], // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
            ),
        );


        $this->db = new Notifications_API_DB();

        add_action('draft_to_publish', array($this, 'on_publish_post'), 10, 1);
        add_action('pending_to_publish', array($this, 'on_publish_post'), 10, 1);
        add_action('admin_post_notification_send_single', array($this,'send_single_notification'));
    }

    /**
     * Send a single notifications through a POST request containing the
     * parameters
     * @return void
     */
    public function send_single_notification()
    {
        status_header(200);
        $subscribers = $this->db->getSubscribers();
        foreach ($subscribers as $sub) {
            $this->sendNotification(
            $_REQUEST['title'],
            $_REQUEST['tag'],
            $_REQUEST['content'],
            $_REQUEST['url'],
            $sub->endpoint,
            $sub->key_p256,
            $sub->key_auth);
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Method to handle post Notifications
     * Called everytime a post go from draft or pending to publish
     * @param  WPPost $post Newly publish POST
     * @return void
     */
    public function on_publish_post($post)
    {
      if (isset($this->options['notify_posts'])
        && get_post_type($post) == 'post'
        && !get_post_meta( $post_id, 'notifications-disable', true )) {
        $tag = 'post';
      }
      else if (isset($this->options['notify_pages'])
        && get_post_type($post) == 'page'
        && !get_post_meta( $post_id, 'notifications-disable', true )) {
        $tag = 'page';
      }
      else if (isset($this->options['notify_products'])
        && get_post_type($post) == 'product'
        && !get_post_meta( $post_id, 'notifications-disable', true )) {
        $tag = 'product';
      }
      else {
        return;
      }
        $subscribers = $this->db->getSubscribers();
        foreach ($subscribers as $sub) {
            $this->sendNotification(
            $post->post_title,
            $tag,
            $post->post_excerpt,
            get_permalink($post->ID),
            $sub->endpoint,
            $sub->key_p256,
            $sub->key_auth);
        }
    }

    /**
     * Function to actually send the notification through the API
     * @param  string $title         Title of the notification
     * @param  string $tag           Tag of the notification
     * @param  string $message       Content of the notification
     * @param  string $url           relative URL of the target for the notification
     * @param  string $endpoint      Endpoint of a single user
     * @param  string $userPublicKey User Public p256 key
     * @param  string $userAuthToken User Public dh key
     * @return void
     */
    public function sendNotification($title, $tag, $message, $url, $endpoint, $userPublicKey, $userAuthToken)
    {
        $payload = json_encode(
          array(
            'title' => $title,
            'tag' => $tag,
            'body' => $message,
            'data' => array('url' => $url)
        ));
        $webPush = new WebPush($this->auth);
        $res = $webPush->sendNotification(
            $endpoint,
            $payload,
            $userPublicKey,
            $userAuthToken,
            true // optional (defaults false)
        );
    }
}

/**
 * Class for managing the database for the plugin
 */
class Notifications_API_DB
{
    protected $table_name; // Name of the target table containing data

    /**
     * Constructor of the class initiqlizing the class variables
     */
    public function __construct()
    {
        global $wpdb;
        if (is_multisite()) {
          $this->table_name = $wpdb->prefix . get_current_blog_id() . '_' . NOTIFICATIONS_SUBSCRIBER_TABLE;
        } else {
          $this->table_name = $wpdb->prefix . NOTIFICATIONS_SUBSCRIBER_TABLE;
        }
    }

    /**
     * Create the DB tables if not existing
     * @return void
     */
    public function initDB()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      user_id mediumint(9) default NULL,
      endpoint text NOT NULL,
      key_p256 text NOT NULL,
      key_auth text NOT NULL,
      date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Get the list of current subscribers
     * @return array Array of subscribers
     */
    public function getSubscribers()
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM $this->table_name;");
    }

    /**
     * Add a subscriber to the database. Protected against duplicates
     * @param  string $endpoint Endpoint of the new user
     * @param  string $key      p256 public key of the user
     * @param  string $auth     dh pubic key of the user
     * @return void
     */
    public function insertSubscriber($endpoint, $key, $auth)
    {
        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM $this->table_name WHERE endpoint = \"$endpoint\";");

        if (!count($results)) {
            $wpdb->insert(
            $this->table_name,
            array(
              'endpoint' => $endpoint,
              'key_p256' => $key,
              'key_auth' => $auth,
              'date' => current_time('mysql'),
              )
            );
        }
    }

    /**
     * Delete all subscrbers entries with the provided endpoint
     * @param  string $endpoint target endpoint to delete
     * @return void
     */
    public function removeSubscriber($endpoint)
    {
        global $wpdb;

        $wpdb->query(
          $wpdb->prepare(
              "DELETE FROM $this->table_name
              WHERE endpoint = %s",
              $endpoint
          )
      );
    }
}
