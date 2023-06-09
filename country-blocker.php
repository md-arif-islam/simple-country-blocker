<?php
/**
 * Plugin Name: Simple Country Blocker
 * Plugin URI:
 * Description: Restricts access to your website based on selected countries.
 * Version:     1.0.0
 * Author:      MD Arif Islam
 * Author URI:  https://arifislam.techviewing.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Enqueue the admin scripts and styles
function country_blocker_enqueue_admin_scripts() {
    wp_enqueue_script( 'country-blocker-admin-script', plugins_url( '/assets/js/admin-script.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
}

add_action( 'admin_enqueue_scripts', 'country_blocker_enqueue_admin_scripts' );

// Add the menu item and settings page
function country_blocker_add_admin_menu() {
    add_options_page( 'Country Blocker Settings', 'Country Blocker', 'manage_options', 'country-blocker-settings', 'country_blocker_render_settings' );
}

add_action( 'admin_menu', 'country_blocker_add_admin_menu' );

// Register settings
function country_blocker_register_settings() {
    register_setting( 'country_blocker_settings_group', 'country_blocker_blocked_countries' );
    register_setting( 'country_blocker_settings_group', 'country_blocker_block_page_id' );
}

add_action( 'admin_init', 'country_blocker_register_settings' );

// Render the settings page
function country_blocker_render_settings() {
    include_once plugin_dir_path( __FILE__ ) . 'admin/settings.php';
}

// Restrict access to the website based on selected countries
function country_blocker_restrict_countries() {
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
    $block_page_id = get_option( 'country_blocker_block_page_id', 0 );

    if ( isset( $blocked_countries, $user_country ) ) {
        if ( !empty( $blocked_countries ) && in_array( $user_country, $blocked_countries ) ) {
            // Check if block page ID is set
            if ( $block_page_id ) {
                // Get the block page content
                $block_page_content = get_post_field( 'post_content', $block_page_id );

                if ( $block_page_content ) {
                    echo $block_page_content;
                    exit;
                }
            }

            // If the block page ID is not set or the block page content is empty,
            // display a default message
            echo '<h1>Access Restricted</h1>';
            echo '<p>Access to this website is restricted from your country.</p>';

            exit;
        }
    }
}

add_action( 'template_redirect', 'country_blocker_restrict_countries' );

// Function to get the user's country code using ipinfo.io
function get_user_country() {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $details = json_decode( file_get_contents( "http://ipinfo.io/{$ip_address}/json" ) );
    $country = isset( $details->country ) ? $details->country : '';

    return $country;
}
