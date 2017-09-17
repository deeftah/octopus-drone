<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Rest API Handling class
 */
class Notifications_API_Endpoint
{
    /**
     * Construtctor adding actions to the rest api hook
     */
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_rest_api'));
    }

    /**
     * Register different callbacks and route to the WP REST API
     * @return void
     */
    public function register_rest_api()
    {
        register_rest_route('notif/v1', '/subscribe', array(
          array(
            'methods' => WP_REST_Server::CREATABLE,
            'callback' => array($this, 'subscribe'),
            'args' => array(
                  'endpoint' => array(
                      'required' => true,
                      'validate_callback' => function ($param, $request, $key) {
                          return !preg_match('/\s/',$param);
                      },
                  ),
                  'key' => array(
                      'required' => true,
                      'validate_callback' => function ($param, $request, $key) {
                          return !preg_match('/\s/',$param);
                      },
                  ),
                  'auth' => array(
                      'required' => true,
                      'validate_callback' => function ($param, $request, $key) {
                          return !preg_match('/\s/',$param);
                      },
                  ),
              ),
            'permission_callback' => function () {
                return true;
            }
          ),
          array(
            'methods' => WP_REST_Server::DELETABLE,
            'callback' => array($this, 'unsubscribe'),
            'args' => array(
                  'endpoint' => array(
                      'required' => true,
                      'validate_callback' => function ($param, $request, $key) {
                          return !preg_match('/\s/',$param);
                      },
                  ),
                  'key' => array(
                      'required' => true,
                      'validate_callback' => function ($param, $request, $key) {
                          return !preg_match('/\s/',$param);
                      },
                  ),
                  'auth' => array(
                      'required' => true,
                      'validate_callback' => function ($param, $request, $key) {
                          return !preg_match('/\s/',$param);
                      },
                  ),
              ),
            'permission_callback' => function () {
                return true;
            }
          ),
    ));
    }

    /**
     * Endpoint for the POST request
     * @param  WP_REST_Request $request Incoming request
     * @return WP_REST_Reponse | WP_Error     Respomse to the API REST client
     */
    public function subscribe(WP_REST_Request $request)
    {
        $id = $request->get_param('endpoint');
        $key = $request->get_param('key');
        $auth = $request->get_param('auth');
        if ($id) {
          $data = 'ok';
          $db = new Notifications_API_DB();
          $db->insertSubscriber($id, $key, $auth);
          return new WP_REST_Response($data, 201);
        }

        return new WP_Error( 'empty_endpoint', 'Endpoint parameter is empty', array( 'status' => 400 ) );
    }

    /**
     * Endpoint for the DELETE request
     * @param  WP_REST_Request $request Incoming request
     * @return WP_REST_Reponse | WP_Error     Respomse to the API REST client
     */
    public function unsubscribe(WP_REST_Request $request)
    {
        $id = $request->get_param('endpoint');
        $key = $request->get_param('key');
        $auth = $request->get_param('auth');
        if ($id) {
          $data = 'ok';
          $db = new Notifications_API_DB();
          $db->removeSubscriber($id, $key, $auth);
          return new WP_REST_Response($data, 200);
        }

        return new WP_Error( 'empty_empty_endpoint', 'Endpoint parameter is empty', array( 'status' => 400 ) );
    }
}
