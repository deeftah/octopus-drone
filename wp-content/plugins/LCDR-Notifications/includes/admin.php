<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class to create and handle the admin panels for the plugins
 */
class Notification_Admin_Class
{
    private $options;
    private $subscribers;
    private $text_params;
    private $trigger_params;
    const OPTION_PAGE = 'notifications-admin';
    const OPTION_NAME = 'notifications_admin';

    /**
     * Contructor initializing the private variables and adding actions to admin hooks
     */
    public function __construct()
    {
        add_action('admin_menu', array( $this, 'add_plugin_page' ));
        add_action('admin_init', array( $this, 'page_init' ));
        add_action('add_meta_boxes', array( $this, 'notifications_register_meta_boxes') );
        add_action('save_post', array( $this, 'notifications_save_meta_box'), 5, 2 );

        $db = new Notifications_API_DB();
        $this->subscribers = $db->getSubscribers();

        $this->text_params = array(
          array(
            'nicename' => 'GCM API KEY',
            'handle' => 'gcm_key',
            'hidden' => true
          ),
          array(
            'nicename' => 'VAPID Private key',
            'handle' => 'vapid_private',
            'hidden' => true
          ),
          array(
            'nicename' => 'VAPID Public key',
            'handle' => 'vapid_public',
            'hidden' => true
          )
        );

        $this->trigger_params = array(
          array(
            'nicename' => 'Posts',
            'handle' => 'notify_posts',
            'hidden' => false
          ),
          array(
            'nicename' => 'Pages',
            'handle' => 'notify_pages',
            'hidden' => false
          ),
          array(
            'nicename' => 'Products',
            'handle' => 'notify_products',
            'hidden' => false
          ),
        );
    }

    /**
     * Add the page to the Settings menu of the admin panel
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Notifications Admin',
            'Notifications',
            'manage_options',
            self::OPTION_PAGE,
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Htlm description of the page
     * @return void
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option(self::OPTION_NAME); ?>
        <div class="wrap">
            <h1>My Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields('my_option_group');
        do_settings_sections(self::OPTION_PAGE);
        submit_button(); ?>
            </form>

            <form action="/wp-admin/admin-post.php" method="post">
              <h2>Send a signle message to all subscribers: </h2>
              <input type="hidden" name="action" value="notification_send_single">
              <table class="form-table">
                <tbody>
                  <tr>
                    <th><label for="title">Title</label></th>
                    <td><input type="text" name="title" value=""></td>
                  </tr>
                  <tr>
                    <th><label for="content">Content</label></th>
                    <td><input type="text" name="content" value=""></td>
                  </tr>
                  <tr>
                    <th><label for="tag">Tag</label></th>
                    <td><input type="text" name="tag" value=""></td>
                  </tr>
                  <tr>
                    <th><label for="url">URL (relative, <i>ex: '/'</i> )</label></th>
                    <td><input type="text" name="url" value=""></td>
                  </tr>
                </tbody>
              </table>
              <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Send"></p>
            </form>
        </div>
        <?php

    }

    /**
     * Init the page content
     * @return voir
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            self::OPTION_NAME, // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'infos', // ID
            'Status', // Title
            array( $this, 'print_info' ), // Callback
            self::OPTION_PAGE // Page
        );

        add_settings_section(
            'text_parameters', // ID
            'Required parameters', // Title
            array( $this, 'print_text_section_info' ), // Callback
            self::OPTION_PAGE // Page
        );

        foreach ($this->text_params as $param) {
            add_settings_field(
            $param['handle'], // ID
            $param['nicename'], // Title
            array( $this, 'text_parameter' ), // Callback
            self::OPTION_PAGE, // Page
            'text_parameters', // Section
            $param
          );
        }

        add_settings_section(
          'trigger_parameters', // ID
          'Type of content:', // Title
          array( $this, 'print_trigger_section_info' ), // Callback
          self::OPTION_PAGE // Page
        );

        foreach ($this->trigger_params as $param) {
            add_settings_field(
            $param['handle'], // ID
            $param['nicename'], // Title
            array( $this, 'trigger_parameter' ), // Callback
            self::OPTION_PAGE, // Page
            'trigger_parameters', // Section
            $param
          );
        }
    }

    /**
     * Sanitize the option return
     * @param  array $input values of form
     * @return array        sanitized input
     */
    public function sanitize($input)
    {
        $new_input = array();
        foreach ($this->text_params as $param) {
            if (isset($input[$param['handle']])) {
                $new_input[$param['handle']] = $input[$param['handle']];
            }
        }
        foreach ($this->trigger_params as $param) {
            if (isset($input[$param['handle']])) {
                $new_input[$param['handle']] = $input[$param['handle']];
            }
        }

        return $new_input;
    }

    /**
     * Print current subscribrs number
     * @return voir
     */
    public function print_info()
    {
        print '<h4>You currently have <big>'.count($this->subscribers).'</big> subscribers.</h4>';
    }

    /**
     * Print the section info for the parameters of the plugin
     * @return void
     */
    public function print_text_section_info()
    {
        print '<p>Enter the Notifications plugin required settings below:</p>';
    }

    /**
     * Print the section info for the send single message section
     * @return void
     */
    public function print_trigger_section_info()
    {
        print '<p>Choose the type of new content you want to notify to subscribers:</p>';
    }

    /**
     * Print the HTML for a text input
     * @param  array $opts Parameters of the input
     * @return void
     */
    public function text_parameter($opts)
    {
        printf(
            '<input type="%s" id="%s" name="%s[%s]" value="%s" />',
            ($opts['hidden']) ? 'password' : 'text',
            $opts['handle'],
            self::OPTION_NAME,
            $opts['handle'],
            isset($this->options[$opts['handle']]) ? esc_attr($this->options[$opts['handle']]) : ''
        );
    }

    /**
     * Print the HTML for a checkbox parameter
     * @param  array $opts Parameters of the input
     * @return void
     */
    public function trigger_parameter($opts)
    {
        printf(
            '<input type="checkbox" id="%s" name="%s[%s]" value="true" %s />',
            $opts['handle'],
            self::OPTION_NAME,
            $opts['handle'],
            isset($this->options[$opts['handle']]) ? 'checked' : ''
        );
    }

    /**
     * Add meta box to the edit post/product/page form
     * @return void
     */
    function notifications_register_meta_boxes() {
        add_meta_box( 'notifications-meta-box', 'Notifications', array( $this, 'notifications_box_display_callback'), array('post', 'page', 'product'), 'side', 'high' );
    }

    /**
     * Callback for the metabox printing the Content of it
     * @param  WPPost $post Post currently editinh
     * @return void
     */
    function notifications_box_display_callback( $post ) {
      $meta_value = get_post_meta( $post->ID, 'notifications-disable', true );
      wp_nonce_field( basename( __FILE__ ), 'notifications_nonce' ); ?>
      <p>
        <span style="padding-right: 3px;" class="dashicons dashicons-email"></span>
        <label for="notifications-meta-box">Désactiver notifications</label>
        <input type="checkbox" id="notifications-disable" value="true" name="notifications-disable" <?php if($meta_value) echo 'checked';?>/>
      </p>
      <?php
    }

    /**
     * Sanitiwe and save the parameter in the metabox
     * @param  integer $post_id ID of the post saved
     * @param  WPPost $post    Post saved
     * @return void
     */
    function notifications_save_meta_box( $post_id, $post ) {
      if ( !isset( $_POST['notifications_nonce'] ) || !wp_verify_nonce( $_POST['notifications_nonce'], basename( __FILE__ ) ) )
        return $post_id;
      $post_type = get_post_type_object( $post->post_type );
      if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

      $new_meta_value = ( isset( $_POST['notifications-disable'] ) ? sanitize_html_class( $_POST['notifications-disable'] ) : '' );
      $meta_key = 'notifications-disable';
      $meta_value = get_post_meta( $post_id, $meta_key, true );

      if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );
      elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );
      elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
    }
}
