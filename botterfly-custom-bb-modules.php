<?php
/**
 * Plugin Name: BotterFly Custom Modules for Beaver Builder
 * Description: A plugin that adds extra modules to Beaver Builder.
 * Version: 1.0
 * Author: Online With You
 */

// Define the plugin path and URL.
define( 'botterfly_custom_bb_modules_PATH', plugin_dir_path( __FILE__ ) );
define( 'botterfly_custom_bb_modules_URL', plugin_dir_url( __FILE__ ) );

// Load the hello world module.
function oa_load_modules() {
    if ( class_exists( 'FLBuilder' ) ) {
        require_once botterfly_custom_bb_modules_PATH . 'modules/botterfly-menu/botterfly-menu.php';
        require_once botterfly_custom_bb_modules_PATH . 'modules/letter-page/letter-page.php';
        require_once botterfly_custom_bb_modules_PATH . 'modules/ai-art-page/ai-art-page.php';
        require_once botterfly_custom_bb_modules_PATH . 'modules/templates-page/templates-page.php';
    }
}

add_action( 'init', 'oa_load_modules' );

?>
