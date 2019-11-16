<?php
/**
* Runs any steps required on plugin activation and upgrade.
* 
* @package  WP_To_Social_Pro
* @author   Tim Carr
* @version  3.2.5
*/
class WP_To_Social_Pro_Install {

    /**
     * Holds the base class object.
     *
     * @since   3.2.5
     *
     * @var     object
     */
    public $base;

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
     * Runs installation routines for first time users
     *
     * @since   3.4.0
     */
    public function install() {

        // Enable logging by default
        $this->base->get_class( 'settings' )->update_option( 'log', 1 );

        // Bail if settings already exist
        $settings = $this->base->get_class( 'settings' )->get_settings( 'post' );
        if ( $settings != false ) {
            return;
        }

        // Get default installation settings
        $settings = $this->base->get_class( 'settings' )->default_installation_settings();
        $this->base->get_class( 'settings' )->update_settings( 'post', $settings );

    }

    /**
     * Runs uninstallation routines
     *
     * @since   3.7.2
     */
    public function uninstall() {

    }

}