<?php
/**
 * Logging class
 * 
 * @package WP_To_Social_Pro
 * @author  Tim Carr
 * @version 3.0.0
 */
class WP_To_Social_Pro_Log {

    /**
     * Holds the base class object.
     *
     * @since   3.2.0
     *
     * @var     object
     */
    public $base;

    /**
     * Holds the meta key
     *
     * @since   3.4.7
     *
     * @var     string
     */
    private $meta_key = '';

    /**
     * Constructor
     *
     * @since   3.0.0
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;

        // Define post meta key for storing logging
        $this->meta_key = '_' . str_replace( '-', '_', $this->base->plugin->settingsName ) . '_log';

        // Actions
        add_action( 'admin_menu', array( $this, 'admin_meta_boxes' ) );
        add_action( 'wp_loaded', array( $this, 'export_log' ) );

    }

    /**
     * Adds Metaboxes to Post Edit Screens
     *
     * @since   3.0.0
     */
    public function admin_meta_boxes() {

        // Only load if Logging is enabled
        if ( ! $this->base->get_class( 'settings' )->get_option( 'log' ) ) {
            return;
        }

        // Get Post Types
        $post_types = $this->base->get_class( 'common' )->get_post_types();
        
        // Add meta boxes for each Post Type
        foreach ( $post_types as $post_type => $post_type_obj ) {
            add_meta_box( $this->base->plugin->name . '-log', sprintf( __( '%s Log', $this->base->plugin->name ), $this->base->plugin->displayName ), array( $this, 'meta_log' ), $post_type, 'normal', 'low' );   
        }

    }

    /**
     * Outputs the plugin's log of existing status update calls made to the API
     *
     * @since   3.0.0
     *
     * @param   WP_Post     $post   Post
     */
    public function meta_log( $post ) {

        // Setup API
        $this->base->get_class( 'api' )->set_tokens( 
            $this->base->get_class( 'settings' )->get_access_token(),
            $this->base->get_class( 'settings' )->get_refresh_token(),
            $this->base->get_class( 'settings' )->get_token_expires()
        );

        // Get log and profiles
        $log = $this->get_log( $post->ID );
        $profiles = $this->base->get_class( 'api' )->profiles( false, $this->base->get_class( 'common' )->get_transient_expiration_time() );

        // Load View
        include_once( $this->base->plugin->folder . 'vendor/views/log.php' ); 

    }

    /**
     * Retrieves the log for the given Post ID
     *
     * @since   3.0.0
     *
     * @param   int     $post_id    Post ID
     * @return  array               Log
     */
    public function get_log( $post_id ) {

        // Get log
        $log = get_post_meta( $post_id, $this->meta_key, true );

        /**
         * Filters the log entries before output.
         *
         * @since   3.0.0
         *
         * @param   array   $log        Post Log
         * @param   int     $post_id    Post ID
         */
        $log = apply_filters( $this->base->plugin->filter_name . '_get_log', $log, $post_id );

        // Return
        return $log;

    }

    /**
     * Stores the log results against the given Post ID
     *
     * @since   3.0.0
     *
     * @param   int    $post_id     Post ID
     * @param   array  $log         Log Entry / Log Entries
     * @return  bool                Success
     */
    public function update_log( $post_id, $log ) {

        // Get current log
        $old_log = $this->get_log( $post_id );

        // If log exist, merge it with the new log
        if ( $old_log !== false && is_array( $old_log ) ) {
            $log = array_merge( $old_log, $log );
        }

        /**
         * Filters the log entries before saving.
         *
         * @since   3.0.0
         *
         * @param   array   $log        Post Log
         * @param   int     $post_id    Post ID
         */
        $log = apply_filters( $this->base->plugin->filter_name . '_update_log', $log, $post_id );

        // update_option will return false if no changes were made, so we can't rely on this
        update_post_meta( $post_id, $this->meta_key, $log );
        
        return true;
    }

    /**
     * Exports a Post's API log file in JSON format
     *
     * @since   3.0.0
     */
    public function export_log() {

        // Check the user requested a log
        if ( ! isset( $_GET[ $this->base->plugin->name . '-export-log' ] ) ) {
            return;
        }

        // Get log
        $log = $this->get_log( absint( $_GET['post'] ) );

        // Build JSON
        $json = json_encode( $log );
        
        // Export
        header( "Content-type: application/x-msdownload" );
        header( "Content-Disposition: attachment; filename=log.json" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        echo $json;
        exit();
                
    }

    /**
     * Clears a Post's API log
     *
     * @since   3.0.0
     *
     * @param   int     $post_id    Post ID
     */
    public function clear_log( $post_id = 0 ) {

        // If no Post ID has been specified, check the request
        if ( $post_id == 0 && isset( $_REQUEST['post'] ) ) {
            $post_id = absint( $_REQUEST['post'] );
        }

        // Bail if no Post ID
        if ( ! $post_id ) {
            return false;
        }

        // Delete log
        return delete_post_meta( $post_id, $this->meta_key );

    }

    /**
     * Clears a Post's API log of pending (cron) entries
     *
     * @since   3.7.9
     *
     * @param   int     $post_id    Post ID
     */
    public function clear_pending_log( $post_id = 0 ) {

        // Bail if no Post ID
        if ( ! $post_id ) {
            return false;
        }

        // Bail if no log exists
        $log = $this->get_log( $post_id );
        if ( ! $log || empty( $log ) || ! is_array( $log ) ) {
            return false;
        }

        // Bail if the log isn't an array
        if ( count( $log ) == 0 ) {
            return false;
        }

        // Iterate through the log, removing entries that are cron related
        foreach ( $log as $key => $result ) {
            // Skip if no success set
            if ( ! isset( $result['success'] ) ) {
                continue;
            }

            // Skip if success is false
            if ( ! $result['success'] ) {
                continue;
            }

            // Skip if status_created_at is defined, as this means the result relates
            // to a request made to the API
            if ( isset( $result['status_created_at'] ) ) {
                continue;
            }

            // Remove this request
            unset( $log[ $key ] );
        }

        // Rekey
        $log = array_values( $log );

        /**
         * Filters the log entries before saving.
         *
         * @since   3.7.9
         *
         * @param   array   $log        Post Log
         * @param   int     $post_id    Post ID
         */
        $log = apply_filters( $this->base->plugin->filter_name . '_clear_pending_log', $log, $post_id );

        // update_option will return false if no changes were made, so we can't rely on this
        update_post_meta( $post_id, $this->meta_key, $log );
        
        return true;

    }

    /**
     * Wrapper for PHP's error_log() function, which will only write
     * to the error log if:
     * - WP_DEBUG = true
     * - WP_DEBUG_DISPLAY = false
     * - WP_DEBUG_LOG = true
     *
     * This will ensure that the output goes to wp-content/debug.log
     *
     * @since   3.6.8
     *
     * @param   mixed   $data          Data to log
     * @param   array   $backtrace     Backtrace data from debug_backtrace()
     */
    public function add_to_debug_log( $data = '', $backtrace = false ) {

        // Bail if no WP_DEBUG, or it's false
        if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
            return;
        }

        // Bail if no WP_DEBUG_DISPLAY, or it's true
        if ( ! defined( 'WP_DEBUG_DISPLAY' ) || WP_DEBUG_DISPLAY ) {
            return;
        }

        // Bail if no WP_DEBUG_LOG, or it's false
        if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
            return;
        }

        // If we need to fetch the class and function name to prefix to the log entry, do so now
        $prefix_data = '';
        if ( $backtrace != false ) {
            if ( isset( $backtrace[0] ) ) {
                if ( isset( $backtrace[0]['class'] ) ) {
                    $prefix_data .= $backtrace[0]['class'];
                }
                if ( isset( $backtrace[0]['function'] ) ) {
                    $prefix_data .= '::' . $backtrace[0]['function'] . '()';
                }
            }
        }

        // If the data is empty, change it to 'called'
        if ( empty( $data ) ) {
            $data = 'Called';
        }

        // If the data is an array or object, convert it to a string
        if ( is_array( $data ) || is_object( $data ) ) {
            $data = print_r( $data, true );
        }

        // If we're prefixing the log entry, do so now
        if ( ! empty( $prefix_data ) ) {
            $data = $prefix_data . ': ' . $data;
        }

        // Add the data to the error log, which will appear in wp-content/debug.log
        error_log( $data );
        
    }

    /**
     * Takes a given array of log results, and builds HTML table row output
     * that can be used by:
     * - Posts > Log Meta Box
     * - Bulk Publish > Results Screen
     *
     * @since   3.7.9
     *
     * @param   array   $log        Log Results
     * @param   mixed   $profiles   Profiles
     * @return  string              Table Rows HTML
     */
    public function build_log_table_output( $log, $profiles = false ) {

        $html = '';

        // If no results, return a single row
        if ( ! is_array( $log ) ) {
            $html = '
                    <tr>
                        <td colspan="6">' . sprintf( __( 'No status updates have been sent to %s.', $this->base->plugin->name ), $this->base->plugin->account ) . '</td>
                    </tr>';

            return $html;
        }

        // Build standardised array
        $output = $this->build_log_output_array( $log, $profiles );

        foreach ( $output as $count => $result ) {
            $html .= '
            <tr class="' . $result['status_created'] . ( ( $count % 2 > 0 ) ? ' alternate' : '' ) . '">
                <td>' . $result['request_sent'] . '</td>';

            // Output
            switch ( $result['status_created'] ) {
                /**
                 * Error
                 */
                case 'error':
                    // Status
                    $html .= '<td>' . __( 'No', $this->base->plugin->name ) . '</td>';

                    // Profile
                    $html .= '<td>' . $result['profile'] . '</td>';

                    // Status Text
                    $html .= '<td colspan="3">' . $result['status_text'] . '</td>';
                    break;

                /**
                 * Pending
                 */
                case 'pending':
                    // Status
                    $html .= '<td>' . __( 'Pending', $this->base->plugin->name ) . '</td>';

                    // Profile
                    $html .= '<td>' . $result['profile'] . '</td>';

                    // Status Text
                    $html .= '<td>' . $result['status_text'] . '</td>';

                    // Status Created At
                    $html .= '<td colspan="2">' . $result['status_created_at'] . '</td>';
                    break;

                /**
                 * Success
                 */
                case 'success':
                    // Status
                    $html .= '<td>' . __( 'Yes', $this->base->plugin->name ) . '</td>';

                    // Profile
                    $html .= '<td>' . $result['profile'] . '</td>';

                    // Status Text
                    $html .= '<td>' . $result['status_text'] . '</td>';

                    // Status Created At
                    $html .= '<td>' . $result['status_created_at'] . '</td>';

                    // Status Due At
                    $html .= '<td>' . $result['status_due_at'] . '</td>';
                    break;
            }
          
            $html .='</td>
                </tr>';
        }

        // Return
        return $html;

    }

    /**
     * Takes a given array of log results, and builds a standardised array
     * that can be used by:
     * - Posts > Log Meta Box
     * - Bulk Publish > Results Screen
     * - WP CLI > Bulk Publish
     *
     * @since   3.8.1
     *
     * @param   array   $log        Log Results
     * @param   mixed   $profiles   Profiles
     * @return  array               Results
     */
    public function build_log_output_array( $log, $profiles = false ) {

        $output = array();

        foreach ( $log as $count => $result ) {
            $output[ $count ] = array(
                'request_sent'      => date_i18n( get_option( 'date_format' ) . ' H:i:s', $result['date'] ),
                'status_created'    => 'error',
                'profile'           => false,
                'status_text'       => false,
                'status_created_at' => false,
                'status_due_at'     => false,
            );

            // Determine status: success, pending (cron), error
            if ( $result['success'] && isset( $result['status_created_at'] ) ) {
                $output[ $count ]['status_created'] = 'success';
            } elseif ( $result['success'] ) {
                $output[ $count ]['status_created'] = 'pending';
            }

            // Determine Profile
            if ( isset( $result['profile_name'] ) ) {
                $output[ $count ]['profile'] = $result['profile_name'];
            } elseif ( isset( $result['status'] ) ) {
                // Try to get Profile Name
                if ( is_array( $profiles ) && isset( $profiles[ $result['status']['profile_ids'][0] ] ) ) {
                    $output[ $count ]['profile'] = $profiles[ $result['status']['profile_ids'][0] ]['formatted_service'] . ': ' . $profiles[ $result['status']['profile_ids'][0] ]['formatted_username'];
                } else {
                    // Output Profile ID
                    $output[ $count ]['profile'] = $result['status']['profile_ids'][0];
                }
            } else {
                $output[ $count ]['profile'] = __( 'N/A', $this->base->plugin->name );
            }

            // Output
            switch ( $output[ $count ]['status_created'] ) {

                /**
                 * Error
                 */
                case 'error':
                    // Status Text
                    $output[ $count ]['status_text'] = $result['message'];
                    break;

                /**
                 * Pending
                 */
                case 'pending':
                    // Status Text
                    $output[ $count ]['status_text'] = ( isset( $result['status_text'] ) ? $result['status_text'] : $result['status']['text'] );
                    
                    // Status Created At
                    $output[ $count ]['status_created_at'] = $result['message'];
                    break;

                /**
                 * Success
                 */
                case 'success':
                // Status Text
                    $output[ $count ]['status_text'] = ( isset( $result['status_text'] ) ? $result['status_text'] : $result['status']['text'] );
                    
                    // Status Created At
                    $output[ $count ]['status_created_at'] = date_i18n( get_option( 'date_format' ) . ' H:i:s', $result['status_created_at'] );

                    // Status Publication Due At
                    $output[ $count ]['status_due_at'] = date_i18n( get_option( 'date_format' ) . ' H:i:s', $result['status_due_at'] );
                    break;
            }
        }

        // Return
        return $output;

    }

}