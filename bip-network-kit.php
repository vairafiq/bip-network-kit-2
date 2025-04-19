<?php
/*
Plugin Name: Simple Directory Plugin
Description: Overrides theme templates and includes custom post types, styles, and scripts for a simple directory listing.
Version: 1.0
Author: Bipper Media
Author URI: https://bippermedia.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Include custom post types
require_once plugin_dir_path(__FILE__) . 'includes/cpt-business.php';


add_filter('template_include', 'sd_override_templates');
add_action('wp_enqueue_scripts', 'sd_enqueue_custom_assets');




// Override theme templates
function sd_override_templates($template) {
    if (is_single()) {
        return plugin_dir_path(__FILE__) . 'templates/single.php';
    } elseif (is_archive()) {
        return plugin_dir_path(__FILE__) . 'templates/archive.php';
    }
    return $template;
}



// Enqueue custom styles and scripts
function sd_enqueue_custom_assets() {
    // Main (global)
    wp_enqueue_style('sd-main-css', plugin_dir_url(__FILE__) . 'assets/css/main.css');
    wp_enqueue_script('sd-main-js', plugin_dir_url(__FILE__) . 'assets/js/main.js', [], false, true);

    // Header/Footer (global)
    wp_enqueue_style('sd-header-css', plugin_dir_url(__FILE__) . 'assets/css/header.css');
    wp_enqueue_script('sd-header-js', plugin_dir_url(__FILE__) . 'assets/js/header.js', [], false, true);

    wp_enqueue_style('sd-footer-css', plugin_dir_url(__FILE__) . 'assets/css/footer.css');
    wp_enqueue_script('sd-footer-js', plugin_dir_url(__FILE__) . 'assets/js/footer.js', [], false, true);

    // Archive
    if (is_archive()) {
        // Archive page styles/scripts
        wp_enqueue_style('sd-archive-css', plugin_dir_url(__FILE__) . 'assets/css/archive.css');
        wp_enqueue_script('sd-archive-js', plugin_dir_url(__FILE__) . 'assets/js/archive.js', [], false, true);

        // Archive content block (loop items) styles/scripts
        wp_enqueue_style('sd-content-archive-css', plugin_dir_url(__FILE__) . 'assets/css/content-archive.css');
        wp_enqueue_script('sd-content-archive-js', plugin_dir_url(__FILE__) . 'assets/js/content-archive.js', [], false, true);
    }

    // Single
    if (is_single()) {
        wp_enqueue_style('sd-single-css', plugin_dir_url(__FILE__) . 'assets/css/single.css');
        wp_enqueue_script('sd-single-js', plugin_dir_url(__FILE__) . 'assets/js/single.js', [], false, true);
    }
}


// Register custom menus
function register_my_menus() {
    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu' ),
            'footer'  => __( 'Footer Menu' )
        )
    );
}
add_action( 'after_setup_theme', 'register_my_menus' );