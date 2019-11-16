<?php
/**
 * Provides several validation functions which the Plugin can run
 * to ensure features work as expected.
 * 
 * @package WP_To_Social_Pro
 * @author  Tim Carr
 * @version 3.8.1
 */
class WP_To_Social_Pro_Validation {

    /**
     * Holds the base class object.
     *
     * @since   3.8.1
     *
     * @var     object
     */
    public $base;

    /**
     * Constructor
     *
     * @since   3.8.1
     *
     * @param   object $base    Base Plugin Class
     */
    public function __construct( $base ) {

        // Store base class
        $this->base = $base;
        
    }

    /**
     * Checks if an Access Token exists, meaning that the API service is connected
     * to the Plugin.
     *
     * @since   3.8.1
     *
     * @return  bool    API Connected
     */
    public function api_connected() {

        $access_token = $this->base->get_class( 'settings' )->get_access_token();
        if ( empty( $access_token ) ) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the WordPress timezone matches the given API Timezone,
     * which could be a global API timezone or a profile-specific timezone.
     *
     * @since   3.8.1
     *
     * @param   string  $api_profile_timezone               API Timezone
     * @param   string  $api_profile_name                   API Profile Name (e.g. @n7TestAcct)
     * @param   string  $api_profile_change_timezone_url    URL to API service where the user can change the timezone
     * @return  mixed   WP_Error | true
     */
    public function timezones_match( $api_profile_timezone = false, $api_profile_name = '', $api_profile_change_timezone_url = '#' ) {

        // Pass test if we don't have API access
        $api_connected = $this->api_connected();
        if ( ! $api_connected ) {
            return true;
        }

        // Fetch timezones for WordPress, Server and API
        $this->base->get_class( 'api' )->set_tokens( 
            $this->base->get_class( 'settings' )->get_access_token(),
            $this->base->get_class( 'settings' )->get_refresh_token(),
            $this->base->get_class( 'settings' )->get_token_expires()
        );
        $wordpress_timezone = $this->base->get_class( 'common' )->convert_wordpress_gmt_offset_to_offset_value( get_option( 'gmt_offset' ) );

        // Pass test if the API date couldn't be fetched
        if ( ! $api_profile_timezone ) {
            return true;
        }

        // Fetch the current date and time, to the minute, for each of the timezones
        try {
            $wordpress_date = new DateTime( 'now', new DateTimeZone( $wordpress_timezone ) );
            $api_date = new DateTime( 'now', new DateTimeZone( $api_profile_timezone ) );
        } catch ( Exception $e ) {
            return new WP_Error( $this->base->plugin->filter_name . '_date_time_zone_error', $e->getMessage() );
        }

        // If the three dates don't match, scheduling won't work as expected
        $wordpress_date = $wordpress_date->format( 'Y-m-d H:i' );
        $api_date = $api_date->format( 'Y-m-d H:i' );

        if ( $api_date != $wordpress_date ) {
            return new WP_Error( 
                $this->base->plugin->filter_name . '_timezones_invalid',
                sprintf( 
                    __( 'This Profile\'s Timezone does not match your WordPress timezone.  They must be the same, to ensure that statuses 
                        can be scheduled, and are scheduled at the correct time.<br />
                        <br />
                        Right now, your timezones are configured as:<br />
                        WordPress Timezone: %s (%s) [<a href="%s" target="_blank">Fix</a>]<br />
                        %s Profile Timezone: %s (%s) [<a href="%s" target="_blank">Fix</a>]', $this->base->plugin->name ),
                    
                    $wordpress_timezone,
                    $wordpress_date,
                    admin_url( 'options-general.php#timezone_string' ),
                    
                    $api_profile_name,
                    $api_profile_timezone,
                    $api_date,
                    $api_profile_change_timezone_url
                )
            );
        }

    }

    /**
     * Checks if the PHP max_query_vars setting will be sufficient for the minimum 
     * number of setting fields we have for the given Profiles for any Post Type.
     *
     * @since   3.8.1
     */
    public function max_query_vars_valid( $post_type, $profiles, $post_actions ) {

        // Define the number of fields per status
        $settings_per_status = 20;

        // Calculate the total number of fields that might be sent, as a minimum
        $total_fields = ( count( $profiles ) * count( $post_actions ) * $settings_per_status );

        // Get the maximum input vars
        $max_input_vars = (int) ini_get( 'max_input_vars' );

        // Return true if no limit found
        if ( ! $max_input_vars ) {
            return true;
        }

        // Return false if we have more fields than will be saved by PHP
        if ( $total_fields > $max_input_vars ) {
            return new WP_Error( $this->base->plugin->filter_name . '_max_query_vars_valid', sprintf( 
                __( '%s: Your web hosting\'s PHP max_input_vars setting of %s may be too low, resulting in any Plugin changes not saving.<br />
                    We recommend increasing this value to at least %s.', $this->base->plugin->name ),
                $this->base->plugin->displayName,
                $max_input_vars,
                ( $total_fields * 1.2 )
            ) );
        }

        // OK
        return true;

    }

    /**
     * Iterates through all associative statuses for a given Post Type,
     * checking whether a profile and action combination have two or more statuses
     * that are the same.
     *
     * @since   3.1.1
     *
     * @param   array   $settings   Settings
     * @return  bool                Duplicates
     */
    public function check_for_duplicates( $settings ) {

        // Define the status keys to compare
        $status_keys_to_compare  = array(
            'message',
        );

        /**
         * Defines the key values to compare across all statuses for a Post Type and Social Profile
         * combination, to ensure no duplicate statuses have been defined.
         *
         * @since   3.1.1
         *
         * @param   array   $status_keys_to_compare     Status Key Values to Compare
         */
        $status_keys_to_compare = apply_filters( $this->base->plugin->filter_name . '_validate_check_for_duplicates_status_keys', $status_keys_to_compare );

        // Iterate through each profile
        foreach ( $settings as $profile_id => $actions ) {
            // Iterate through each action for this profile
            foreach ( $actions as $action => $statuses ) {
                // Check if this action is enabled
                if ( ! isset( $statuses['enabled'] ) || ! $statuses['enabled'] ) {
                    continue;
                }

                // Build serialized strings for each status, so we can compare them
                $statuses_serialized = array();
                foreach ( $statuses['status'] as $status ) {
                    // Build status comprising of just the keys we want to compare with other statuses
                    $status_compare = array();
                    foreach ( $status_keys_to_compare as $status_key_to_compare ) {
                        $status_compare[ $status_key_to_compare ] = ( isset( $status[ $status_key_to_compare ] ) ? $status[ $status_key_to_compare ] : '' );
                    }

                    // Add the status compare to the serialized array
                    $statuses_serialized[] = serialize( $status_compare );
                }

                // Check if any two values in our array are the same
                // If so, this means the user is using the same status message twice, which may cause an issue
                $counts = array_count_values( $statuses_serialized );
                foreach ( $counts as $count ) {
                    if ( $count > 1 ) {
                        return true;
                    }
                }
            }
        }

        // No duplicates found
        return false;

    }

}