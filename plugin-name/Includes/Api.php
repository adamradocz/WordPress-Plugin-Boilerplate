<?php

declare(strict_types=1);

namespace PluginName\Includes;



// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * The plugin REST API.
 *
 * The WordPress REST API provides REST endpoints (URLs) representing the posts, pages, taxonomies, and other built-in WordPress data types. 
 * Your application can send and receive JSON data to these endpoints to query, modify and create content on your site.
 * 
 * Below an example of how to send a post request to custom wordpress REST endpoint
 * 
 * ```
 * $.ajax( {
 *  url: 'http://example.com/wp-json/pluginslug/v1/endpoint',
 *  method: 'POST',
 *  beforeSend: function ( xhr ) {
 *      xhr.setRequestHeader( 'X-WP-Nonce', nonce ); // a nonce is required. check https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
 *  },
 *  data:{
 *      'arg' : 'test'
 *  }
 * } ).done( function ( response ) {
 *  console.log( response );
 * } );
 * ```
 *
 * @link       https://developer.wordpress.org/rest-api/
 * @since      1.0.0
 * @package    PluginName
 * @subpackage PluginName/Includes
 * @author     Your Name <email@example.com>
 */
class Api
{

    /**
     * The namespace for api url.
     * 
     * Namespaces are the first part of the URL for the endpoint. They should be used as a vendor/package prefix to prevent clashes between custom routes. 
     * Namespaces allows for two plugins to add a route of the same name, with different functionality.
     * Namespaces in general should follow the pattern of vendor/v1, where vendor is typically your plugin or theme slug, 
     * and v1 represents the first version of the API.
     * 
     * @since   1.0.0
     */
    private string $namespace;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     */
    protected string $pluginSlug;


    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     * @param   $pluginSlug     The name of the plugin.
     * 
     */
    public function __construct(string $pluginSlug)
    {
        $this->pluginSlug = $pluginSlug;
        $this->namespace = $pluginSlug . '/v1';
    }

    /**
     * Register all the hooks of this class.
     *
     * @since    1.0.0
     * 
     */
    public function initializeHooks(): void
    {
        // Register the routes (endpoints) of plugin API 
        add_action( 'rest_api_init', array($this, 'registerRoutes') );
    }

    /**
     * Inizialize all the endpoints in the resource name
     * 
     * @since   1.0.0
     */
    public function registerRoutes() {
        register_rest_route( $this->namespace, '/endpoint', // This is the name of the endpoint that will receive the requests (http://example.com/wp-json/pluginslug/v1/endpoint)
            array (
                array(
                    'methods'   => 'GET',                                          /* GET should be used for retrieving data from the API.
                                                                                    POST should be used for creating new resources (i.e users, posts, taxonomies).
                                                                                    PUT should be used for updating resources.
                                                                                    DELETE should be used for deleting resources.
                                                                                    OPTIONS should be used to provide context about our resources.*/

                    'callback'  => array( $this, 'endpointCallback' ),              // The main callback should handle the interaction with the resource.
                    'permission_callback' => array( $this, 'permissionCallback' ),  // (required) The permissions callback should handle what users have access to the endpoint.
                    'args' => $this->endpointArgs()                                 // The function that handles the single arguments passed to the endpoint, useful to validate and sanitize    
                ),
            )
        );
    }

    /**
     * This is the callback function that embeds the resource in a WP_REST_Response.
     *
     * The parameters are already sanitized by the endpointArgs function so we can use them without any worries.
     * 
     * @since   1.0.0
     */
    public function endpointCallback( $request )
    {
        /**
         * Here it is possible to access the arguments passed to the enpoint.
         * 
         * For example if this function is the main callback of an endpoint 
         * with GET method http://example.com/wp-json/pluginslug/v1/endpoint?arg=test
         * it is possible to retrive the "test" value from $request['arg'].
         * 
         * Here the main function ot the endpoint will be executed (retrieve data, or update something, or create new post, page, user ....)
         */

        return rest_ensure_response( new \WP_REST_Response(array(
            'success' => true, 
            'data' => array()
        ), 200) );
    }

    /**
     * Check permissions for the endpoint.
     * 
     * @since   1.0.0
     */
    public function permissionCallback( $request )
    {
        /**
         * Here it is possible to check if the user can access the endpoint.
         * 
         * Different checks can be used for example current_user_can() - is_admin() - is_user_logged_in()
         */

        // Just for example
        if ( ! current_user_can( 'read' ) ) {
            return new \WP_Error( 'rest_forbidden', esc_html__( 'You cannot view the resource.', 'plugin-name' ), array( 'status' => $this->authStatusCode() ) );
        }

        return true;
    }

    /**
     * Validate and sanitize the single arguments of the endpoint
     * 
     * @since   1.0.0
     */
    public function endpointArgs() {
        /**
         * The single arguments are the values of the array $args.
         * 
         * For example from the endpoint http://example.com/wp-json/pluginslug/v1/endpoint?arg=test
         * it is possible to retrive the argument "arg".
         */
        $args['arg'] = array(
            'description' => esc_html__( 'The description of the argument', 'plugin-name' ),
            'type'        => 'string',      // Type specifies the type of data that the argument should be.
            'required' => true,             // Set the argument to be required for the endpoint.
            'validate_callback' => array($this, 'validateCallback'),      // Register a validation callback for the data argument.
            'sanitize_callback' => array($this, 'sanitizeCallback')       // Register a sanitize callback for the data argument.
        );

        return $args;
    }

    /**
     * Validate the argument of the endpoint
     * 
     * @since   1.0.0
     * @param  mixed            $value   Value of the 'arg' argument.
     * @param  WP_REST_Request  $request The current request object.
     * @param  string           $param   Key of the parameter. In this case it is 'arg'.
     */
    public function validateCallback($value, $request, $param)
    {
        // Just for example
        if ( ! is_string( $value ) || empty( $value ) ) {
            return new \WP_Error( 'rest_invalid_param', esc_html__( 'The filter argument must be a string.', 'plugin-name' ), array( 'status' => 400 ) );
        }
    }

    /**
     * Sanitize the argument of the endpoint
     * 
     * @since   1.0.0
     * @param  mixed            $value   Value of the 'arg' argument.
     * @param  WP_REST_Request  $request The current request object.
     * @param  string           $param   Key of the parameter. In this case it is 'arg'.
     */
    public function sanitizeCallback($value, $request, $param)
    {
        // Just for example
        return sanitize_text_field($value);
    }

    /** 
     * Sets up the proper HTTP status code for authorization.
     * 
     * @since   1.0.0
     */
    public function authStatusCode() {
 
        $status = 401;
 
        if ( is_user_logged_in() ) {
            $status = 403;
        }
 
        return $status;
    }
}