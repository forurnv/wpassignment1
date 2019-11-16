<?php
/**
 * Buffer API class
 * 
 * @package WP_To_Social_Pro
 * @author  Tim Carr
 * @version 3.0.0
 */
class WP_To_Social_Pro_Buffer_API {

    /**
     * Holds the base class object.
     *
     * @since   3.4.7
     *
     * @var     object
     */
    public $base;

    /**
     * Holds the Buffer Application's Client ID
     *
     * @since   3.3.3
     *
     * @var     string
     */
    private $client_id = '592d41d14d97ab7e4e571edb';

    /**
     * Holds the oAuth Gateway endpoint, used to exchange a code for an access token
     *
     * @since   3.3.3
     *
     * @var     string
     */
    private $oauth_gateway_endpoint = 'https://www.wpzinc.com/?oauth=buffer';

    /**
     * Holds the API endpoint
     *
     * @since   3.4.7
     *
     * @var     string
     */
    private $api_endpoint = 'https://api.bufferapp.com/';

    /**
     * Holds the API version
     *
     * @since   3.4.7
     *
     * @var     int
     */
    private $api_version = '1';
    
    /**
     * Access Token
     *
     * @since   3.0.0
     *
     * @var     string
     */
    public $access_token = '';

    /**
     * Refresh Token
     *
     * @since   3.4.7
     *
     * @var     string
     */
    public $refresh_token = '';

    /**
     * Token Expiry Timestamp
     *
     * @since   3.5.0
     *
     * @var     int
     */
    public $token_expires = false;

    /**
     * Constructor
     *
     * @since   3.4.7
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

    }

    /**
     * Returns the oAuth 2 URL used to begin the oAuth process
     *
     * @since   3.3.3
     *
     * @return  string  oAuth URL
     */
    public function get_oauth_url() {

        // Return oAuth URL
        return 'https://bufferapp.com/oauth2/authorize?client_id=' . $this->client_id . '&redirect_uri=' . urlencode( $this->oauth_gateway_endpoint ) . '&response_type=code&state=' . urlencode( admin_url( 'admin.php?page=' . $this->base->plugin->name . '-settings' ) );

    }

    /**
     * Returns the Buffer URL where the user can connect their social media accounts
     * to Buffer
     *
     * @since   3.8.4
     *
     * @return  string  URL
     */
    public function get_connect_profiles_url() {

        // Return Connect Profiles URL
        return 'https://buffer.com/manage/';

    }

    /**
     * Returns the Buffer URL where the user can change the timezone for the
     * given profile ID.
     *
     * @since   3.8.1
     *
     * @param   string  $profile_id     Profile ID
     * @return  string                  Timezone Settings URL
     */
    public function get_timezone_settings_url( $profile_id ) {

        return 'https://buffer.com/app/profile/' . $profile_id . '/schedule';

    }

    /**
     * Sets this class' access and refresh tokens
     *
     * @since   3.4.0
     *
     * @param   string  $access_token    Access Token
     * @param   string  $refresh_token   Refresh Token
     * @param   mixed   $token_expires   Token Expires (false | timestamp)
     */
    public function set_tokens( $access_token = '', $refresh_token = '', $token_expires = false ) {

        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
        $this->token_expires = $token_expires;

    }

    /**
     * Checks if an access token was set.  Called by any function which 
     * performs a call to the API
     *
     * @since   3.5.0
     *
     * @return  bool    Token Exists
     */
    private function check_access_token_exists() {

        if ( empty( $this->access_token ) ) {
            return false;
        }

        return true;

    }

    /**
     * Checks if a refresh token was set.  Called by any function which 
     * performs a call to the API
     *
     * @since   3.5.0
     *
     * @return  bool    Token Exists
     */
    private function check_refresh_token_exists() {

        if ( empty( $this->refresh_token ) ) {
            return false;
        }

        return true;

    }

    /**
     * Returns the User object
     *
     * @since   3.0.0
     *
     * @return  mixed   WP_Error | User object
     */
    public function user() {

        // Check access token
        if ( ! $this->check_access_token_exists() ) {
            return false;
        }

        return $this->get( 'user.json' );

    }

    /**
     * Returns a list of Social Media Profiles attached to the Buffer Account.
     *
     * @since   3.0.0
     *
     * @param   bool    $force                      Force API call (false = use WordPress transient)
     * @param   int     $transient_expiration_time  Transient Expiration Time
     * @return  mixed                               WP_Error | Profiles object
     */
    public function profiles( $force = false, $transient_expiration_time ) {

        // Check access token
        if ( ! $this->check_access_token_exists() ) {
            return false;
        }

        // Setup profiles array
        $profiles = array();

        // Check if our WordPress transient already has this data.
        // This reduces the number of times we query the API
        if ( $force || false === ( $profiles = get_transient( $this->base->plugin->name . '_buffer_api_profiles' ) ) ) {
            // Get profiles
            $results = $this->get( 'profiles.json?subprofiles=1' );

            // Check for errors
            if ( is_wp_error( $results ) ) {
                return $results;
            }

            // Check data is valid
            foreach ( $results as $result ) {
                // We don't support Instagram or Pinterest in the Free version
                if ( class_exists( 'WP_To_Buffer' ) ) {
                    if ( $result->service == 'instagram' || $result->service == 'pinterest' ) {
                        continue;
                    }
                }

                // Add profile to array
                $profiles[ $result->id ] = array(
                    'id'                => $result->id, // Buffer ID
                    'default'           => $result->default, // If enabled as a default on Buffer
                    'social_network_id' => $result->service_id, // Social Network (e.g. FB, Twitter) ID
                    'formatted_service' => $result->formatted_service,
                    'formatted_username'=> ( $result->service != 'twitter' ? $result->formatted_username : '' ),
                    'service'           => $result->service,
                    'timezone'          => $result->timezone,
                );

                // Twitter's 2019 Developer Policies mean that the formatted username and profile image are no longer returned.
                // In turn, Buffer cannot provide this information, so we must directly query for it through the Twitter API.
                if ( $result->service == 'twitter' && empty( $profiles[ $result->id ]['formatted_username'] ) ) {
                    // Fetch Twitter username from the API
                    // The API class will check the transient first and use cached results if available
                    $twitter_username = $this->base->get_class( 'twitter_api' )->get_username_by_id( $profiles[ $result->id ]['social_network_id'], $transient_expiration_time );
                    if ( is_wp_error( $twitter_username ) ) {
                        continue;
                    }

                    // Add username to results
                    $profiles[ $result->id ]['formatted_username'] = $twitter_username;
                }

                // Pinterest: add subprofiles
                if ( isset( $result->subprofiles ) && count( $result->subprofiles ) > 0 ) {
                    $profiles[ $result->id ]['subprofiles'] = array();
                    foreach ( $result->subprofiles as $sub_profile ) {
                        $profiles[ $result->id ]['subprofiles'][ $sub_profile->id ] = array(
                            'id'        => $sub_profile->id,
                            'name'      => $sub_profile->name,
                            'service'   => $sub_profile->service,
                        );
                    }
                }
            }
            
            // Store profiles in transient
            set_transient( $this->base->plugin->name . '_buffer_api_profiles', $profiles, $transient_expiration_time );
        }

        // Return results
        return $profiles;

    }

    /**
     * Creates an Update
     *
     * @since   3.0.0
     *
     * @return  mixed   WP_Error | Update object
     */
    public function updates_create( $params ) {

        // Check access token
        if ( ! $this->check_access_token_exists() ) {
            return false;
        }

        // Send request
        $result = $this->post( 'updates/create.json', $params );

        // Bail if the result is an error
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // Return array of just the data we need to send to the Plugin
        return array(
            'profile_id'        => $result->updates[0]->profile_id,
            'message'           => $result->message,
            'status_text'       => $result->updates[0]->text,
            'status_created_at' => $result->updates[0]->created_at,
            'due_at'            => $result->updates[0]->due_at,
        );

    }

    /**
     * Private function to perform a GET request
     *
     * @since  3.0.0
     *
     * @param  string  $cmd        Command (required)
     * @param  array   $params     Params (optional)
     * @return mixed               WP_Error | object
     */
    private function get( $cmd, $params = array() ) {

        return $this->request( $cmd, 'get', $params );

    }

    /**
     * Private function to perform a POST request
     *
     * @since  3.0.0
     *
     * @param  string  $cmd        Command (required)
     * @param  array   $params     Params (optional)
     * @return mixed               WP_Error | object
     */
    private function post( $cmd, $params = array() ) {

        return $this->request( $cmd, 'post', $params );

    }

    /**
     * Main function which handles sending requests to the Buffer API
     *
     * @since   3.0.0
     *
     * @param   string  $cmd        Command
     * @param   string  $method     Method (get|post)
     * @param   array   $params     Parameters (optional)
     * @return  mixed               WP_Error | object
     */
    private function request( $cmd, $method = 'get', $params = array() ) {

        // Check required parameters exist
        if ( empty( $this->access_token ) ) {
            return new WP_Error( 'missing_access_token', __( 'No access token was specified' ) );
        }

        // Add access token to command, depending on the command's format
        if ( strpos ( $cmd, '?' ) !== false ) {
            $cmd .= '&access_token=' . $this->access_token;
        } else {
            $cmd .= '?access_token=' . $this->access_token;
        }

        // Build endpoint URL
        $url = $this->api_endpoint . $this->api_version . '/' . $cmd;

        // Define the timeout
        $timeout = 20;

        /**
         * Defines the number of seconds before timing out a request to the Buffer API.
         *
         * @since   3.0.0
         *
         * @param   int     $timeout    Timeout, in seconds
         */
        $timeout = apply_filters( $this->base->plugin->name . '_buffer_api_request', $timeout );

        // Request via WordPress functions
        $result = $this->request_wordpress( $url, $method, $params, $timeout );

        // Request via cURL if WordPress functions failed
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
            if ( is_wp_error( $result ) ) {
                $result = $this->request_curl( $url, $method, $params, $timeout );
            }
        }

        // Result will be WP_Error or the data we expect
        return $result;

    }

    /**
     * Performs POST and GET requests through WordPress wp_remote_post() and
     * wp_remote_get() functions
     *
     * @since   3.2.6
     *
     * @param   string  $url        URL
     * @param   string  $method     Method (post|get)
     * @param   array   $params     Parameters
     * @param   int     $timeout    Timeout, in seconds (default: 10)
     * @return  mixed               WP_Error | object
     */
    private function request_wordpress( $url, $method, $params, $timeout = 20 ) {

        // Send request
        switch ( $method ) {
            /**
             * GET
             */
            case 'get':
                $result = wp_remote_get( $url, array(
                    'body'      => $params,
                    'timeout'   => $timeout,
                ) );
                break;
            
            /**
             * POST
             */
            case 'post':
                $result = wp_remote_post( $url, array(
                    'body'      => $params,
                    'timeout'   => $timeout,
                ) );
                break;
        }

        // If an error occured, return it now
        if ( is_wp_error( $result ) ) {
            return $result;
        }

        // If the HTTP code isn't 200, something went wrong
        if ( $result['response']['code'] != 200 ) {
            // Decode error message
            $body = json_decode( $result['body'] );

            // Define the error message
            $message = array();
            if ( isset( $body->error ) ) {
                $message[] = $body->error;
            }
            if ( isset( $body->message ) ) {
                $message[] = $body->message;
            }

            // Return WP_Error
            return new WP_Error( 
                $result['response']['code'], 
                'Buffer API Error: HTTP Code ' . $result['response']['code'] . '. #' . $body->code . ' - ' . implode( "\n", $message ) 
            );
        }

        // All OK, return response
        return json_decode( $result['body'] );

    }

    /**
     * Performs POST and GET requests through PHP's curl_exec() function.
     *
     * If this function is called, request_wordpress() failed, most likely
     * due to a DNS lookup failure or CloudFlare failing to respond.
     *
     * We therefore use CURLOPT_RESOLVE, to tell cURL the IP address for the domain.
     *
     * @since   3.2.6
     *
     * @param   string  $url        URL
     * @param   string  $method     Method (post|get)
     * @param   array   $params     Parameters
     * @param   int     $timeout    Timeout, in seconds (default: 20)
     * @return  mixed               WP_Error | object
     */
    private function request_curl( $url, $method, $params, $timeout = 20 ) {

        // Init
        $ch = curl_init();

        // Set request specific options
        switch ( $method ) {
            /**
             * GET
             */
            case 'get':
                curl_setopt_array( $ch, array(
                    CURLOPT_URL             => $url . '&' . http_build_query( $params ),
                    CURLOPT_RESOLVE         => array( 
                        str_replace( 'https://', '', $this->api_endpoint ) . ':443:104.16.97.40',
                        str_replace( 'https://', '', $this->api_endpoint ) . ':443:104.16.98.40',
                    ),
                ) );
                break;

            /**
             * POST
             */
            case 'post':
                curl_setopt_array( $ch, array(
                    CURLOPT_URL             => $url,
                    CURLOPT_POST            => true,
                    CURLOPT_POSTFIELDS      => http_build_query( $params ),
                    CURLOPT_RESOLVE         => array( 
                        str_replace( 'https://', '', $this->api_endpoint ) . ':443:104.16.97.40',
                        str_replace( 'https://', '', $this->api_endpoint ) . ':443:104.16.98.40',
                    ),
                ) );
                break;
        }

        // Set shared options
        curl_setopt_array( $ch, array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_CONNECTTIMEOUT  => $timeout,
            CURLOPT_TIMEOUT         => $timeout,
        ) );

        // Execute
        $result     = curl_exec( $ch );
        $http_code  = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        $error      = curl_error( $ch );
        curl_close( $ch );

        // If our error string isn't empty, something went wrong
        if ( ! empty( $error ) ) {
            return new WP_Error( $this->base->plugin->name . '_api_request_curl', $error );
        }

        // If HTTP code isn't 200, something went wrong
        if ( $http_code != 200 ) {
            // Decode error message
            $result = json_decode( $result );

            // Return basic WP_Error if we don't have any more information
            if ( is_null( $result ) ) {
                return new WP_Error(
                    $http_code,
                    'Buffer API Error: HTTP Code ' . $http_code . '. Sorry, we don\'t have any more information about this error. Please try again.'
                );
            }

            // Define the error message
            $message = array();
            if ( isset( $result->error ) ) {
                $message[] = $result->error;
            }
            if ( isset( $result->message ) ) {
                $message[] = $result->message;
            }

            // Return WP_Error
            return new WP_Error( $http_code, 'Buffer API Error: HTTP Code ' . $http_code . '. #' . $result->code. ' - ' . implode( "\n", $message )  );
        }
        
        // All OK, return response
        return json_decode( $result );

    }

}