<?php
/*
Plugin Name: Bip Directory Kit
Description: A simple kit to override theme templates and includes custom post types, styles, and scripts for a simple directory listing.
Version: 0.2
Author: Bip Dev Team
Author URI: https://bippermedia.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Include custom post types
require_once plugin_dir_path(__FILE__) . 'includes/cpt-business.php';
require_once plugin_dir_path(__FILE__) . 'includes/rest-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/helper.php';


// Include components
$components = plugin_dir_path(__FILE__) . 'templates/components/*/*.php';
foreach (glob($components) as $component) {
    include_once $component;
}



// Hooks
add_filter('template_include', 'sd_override_templates');
add_action('wp_enqueue_scripts', 'sd_enqueue_custom_assets');
add_action('wp_enqueue_scripts', 'sd_enqueue_cdn');

flush_rewrite_rules();


// Override theme templates
function sd_override_templates($template) {
    if (is_singular('sd_business')) {
        return plugin_dir_path(__FILE__) . 'templates/single.php';
    } elseif (is_post_type_archive('sd_business')) {
        return plugin_dir_path(__FILE__) . 'templates/archive.php';
    } elseif (is_tax(['sd_business_category', 'sd_business_location'])) {
        return plugin_dir_path(__FILE__) . 'templates/archive.php';
    } elseif (is_front_page()) {
        return plugin_dir_path(__FILE__) . 'templates/homepage.php';
    } elseif (is_404()) {
        return plugin_dir_path(__FILE__) . 'templates/404.php';
    }
    return $template;
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


// Enqueue custom styles and scripts
function sd_enqueue_custom_assets() {
    // Main (global)
    wp_enqueue_style('sd-main-css', plugin_dir_url(__FILE__) . 'assets/css/main.css');
    wp_enqueue_script('sd-main-js', plugin_dir_url(__FILE__) . 'assets/js/main.js', ['jquery'], time(), true);

    // Header/Footer (global)
    wp_enqueue_style('sd-header-css', plugin_dir_url(__FILE__) . 'assets/css/header.css');
    wp_enqueue_script('sd-header-js', plugin_dir_url(__FILE__) . 'assets/js/header.js', ['jquery'], time(), true);

    wp_enqueue_style('sd-footer-css', plugin_dir_url(__FILE__) . 'assets/css/footer.css');
    wp_enqueue_script('sd-footer-js', plugin_dir_url(__FILE__) . 'assets/js/footer.js', ['jquery'], time(), true);

    // Archive
    if (is_archive()) {
        wp_enqueue_style('sd-archive-css', plugin_dir_url(__FILE__) . 'assets/css/archive.css');
        wp_enqueue_script('sd-archive-js', plugin_dir_url(__FILE__) . 'assets/js/archive.js', ['jquery'], time(), true);

        wp_enqueue_style('sd-content-archive-css', plugin_dir_url(__FILE__) . 'assets/css/content-archive.css');
        wp_enqueue_script('sd-content-archive-js', plugin_dir_url(__FILE__) . 'assets/js/content-archive.js', ['jquery'], time(), true);
    }

    // Single
    if (is_single()) {
        wp_enqueue_style('sd-single-css', plugin_dir_url(__FILE__) . 'assets/css/single.css');
        wp_enqueue_script('sd-single-js', plugin_dir_url(__FILE__) . 'assets/js/single.js', ['jquery'], time(), true);
    }
}




// Enqueue packages from CDN
function sd_enqueue_cdn() {
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], '6.5.0');
}







/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

/**
 * Get custom post meta fields.
 *
 * @param string|null $field   Optional. Field name. If null, returns object of all fields.
 * @param int|null    $post_id Optional. Post ID. Defaults to current post.
 *
 * @return mixed
 */
function sd_get_post_data($field = null, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Return single field
    if ($field) {
        return get_post_meta($post_id, $field, true);
    }

    // Return all meta fields with first values
    $raw_meta = get_post_meta($post_id);
    $data = [];

    foreach ($raw_meta as $key => $value) {
        $data[$key] = maybe_unserialize($value[0]); // Get just the actual value
    }

    return $data;
}