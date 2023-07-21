<?php
/**
 * Plugin Name: Simple Country Blocker
 * Plugin URI:
 * Description: Restricts access to your website based on selected countries.
 * Version: 1.0.1
 * Author: MD Arif Islam
 * Author URI: https://arifislam.techviewing.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple_country_blocker
 * Domain Path: /languages
 */

// Enqueue the admin scripts and styles
function simple_country_blocker_enqueue_admin_scripts() {
    wp_enqueue_script( 'country-blocker-admin-script', plugins_url( '/assets/js/admin-script.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
}

add_action( 'admin_enqueue_scripts', 'simple_country_blocker_enqueue_admin_scripts' );

// Add the menu item and settings page
function simple_country_blocker_add_admin_menu() {
    add_options_page( 'Simple Country Blocker Settings', 'Simple Country Blocker', 'manage_options', 'simple-country-blocker-settings', 'simple_country_blocker_render_settings' );
}

add_action( 'admin_menu', 'simple_country_blocker_add_admin_menu' );

// Register settings
function simple_country_blocker_register_settings() {
    register_setting( 'simple_country_blocker_settings_group', 'simple_country_blocker_blocked_countries' );
}

add_action( 'admin_init', 'simple_country_blocker_register_settings' );

// Render the settings page
function simple_country_blocker_render_settings() {
    include_once plugin_dir_path( __FILE__ ) . 'admin/settings.php';
}

// Restrict access to the website based on selected countries
function simple_country_blocker_restrict_countries() {
    // Check if the current user is an admin
    if ( current_user_can( 'manage_options' ) ) {
        return; // Allow admin users to access the site
    }

    // Check if we are on the WordPress dashboard
    if ( is_admin() ) {
        return; // Allow access to the WordPress dashboard
    }

    $blocked_countries = get_option( 'country_blocker_blocked_countries', array() );
    $user_country = get_user_country();

    if ( isset( $blocked_countries, $user_country ) ) {
        if ( !empty( $blocked_countries ) && in_array( $user_country, $blocked_countries ) ) {
            // Load the custom block page template
            status_header( 403 );
            nocache_headers();
            include plugin_dir_path( __FILE__ ) . 'block-page-template.php';
            exit;
        }
    }
}

add_action( 'template_redirect', 'simple_country_blocker_restrict_countries' );

// Function to get the user's country code using ipinfo.io
function get_user_country() {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $details = json_decode( file_get_contents( "http://ipinfo.io/{$ip_address}/json" ) );
    $country = isset( $details->country ) ? $details->country : '';

    return $country;
}

?>