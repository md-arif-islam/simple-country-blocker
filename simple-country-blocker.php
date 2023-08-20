<?php
/**
 * Plugin Name: Simple Country Blocker
 * Plugin URI:
 * Description: Restricts access to your website based on selected countries.
 * Version: 1.0.2
 * Author: MD Arif Islam
 * Author URI: https://github.com/md-arif-islam
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

// Add the top-level menu item before the "Settings" menu
function simple_country_blocker_add_admin_menu() {
    // Create a top-level menu item before the "Settings" menu
    $position = 79; // Position before "Settings" which is 80
    add_menu_page(
        'Simple Country Blocker Settings', // Page title
        'Simple Country Blocker', // Menu title
        'manage_options', // Capability
        'simple-country-blocker-settings', // Menu slug
        'simple_country_blocker_render_settings', // Callback function
        'dashicons-shield', // Icon URL (using a WordPress dashicon)
        $position // Position
    );
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
