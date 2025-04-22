<?php
/*
Plugin Name: Bip Directory Kit
Description: A simple kit to override theme templates and includes custom post types, styles, and scripts for a simple directory listing.
Version: 0.1
Author: Bip Dev Team
Author URI: https://bippermedia.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

// Include custom post types
require_once plugin_dir_path(__FILE__) . 'includes/cpt-business.php';


// Include components
$components = plugin_dir_path(__FILE__) . 'templates/components/*.php';
foreach (glob($components) as $component) {
    include_once $component;
}



// Hooks
add_filter('template_include', 'sd_override_templates');
add_action('wp_enqueue_scripts', 'sd_enqueue_custom_assets');
add_action('wp_enqueue_scripts', 'sd_enqueue_cdn');




// Override theme templates
function sd_override_templates($template) {
    if (is_singular('businesses')) {
        return plugin_dir_path(__FILE__) . 'templates/single.php';
    } elseif (is_post_type_archive('businesses')) {
        return plugin_dir_path(__FILE__) . 'templates/archive.php';
    } elseif (is_tax(['business_category', 'business_tag'])) {
        return plugin_dir_path(__FILE__) . 'templates/archive.php';
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
    wp_enqueue_script('sd-main-js', plugin_dir_url(__FILE__) . 'assets/js/main.js', ['jquery'], false, true);

    // Header/Footer (global)
    wp_enqueue_style('sd-header-css', plugin_dir_url(__FILE__) . 'assets/css/header.css');
    wp_enqueue_script('sd-header-js', plugin_dir_url(__FILE__) . 'assets/js/header.js', ['jquery'], false, true);

    wp_enqueue_style('sd-footer-css', plugin_dir_url(__FILE__) . 'assets/css/footer.css');
    wp_enqueue_script('sd-footer-js', plugin_dir_url(__FILE__) . 'assets/js/footer.js', ['jquery'], false, true);

    // Archive
    if (is_archive()) {
        wp_enqueue_style('sd-archive-css', plugin_dir_url(__FILE__) . 'assets/css/archive.css');
        wp_enqueue_script('sd-archive-js', plugin_dir_url(__FILE__) . 'assets/js/archive.js', ['jquery'], false, true);

        wp_enqueue_style('sd-content-archive-css', plugin_dir_url(__FILE__) . 'assets/css/content-archive.css');
        wp_enqueue_script('sd-content-archive-js', plugin_dir_url(__FILE__) . 'assets/js/content-archive.js', ['jquery'], false, true);
    }

    // Single
    if (is_single()) {
        wp_enqueue_style('sd-single-css', plugin_dir_url(__FILE__) . 'assets/css/single.css');
        wp_enqueue_script('sd-single-js', plugin_dir_url(__FILE__) . 'assets/js/single.js', ['jquery'], false, true);
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
|
| These utility functions are used throughout the theme/plugin to simplify
| common operations such as fetching business meta fields and rendering
| star ratings.
|
*/

/**
 * Get all custom business fields for a post.
 *
 * @param int|null $post_id Optional. The post ID. Defaults to current post.
 * @return array An associative array of business meta field values.
 *
 * @usage
 * $fields = sd_get_business_fields(123);
 * $fields = sd_get_business_fields(get_the_ID());
 * echo $fields['sd_phone'];
 */
function sd_get_business_fields($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $fields = [
        'sd_phone',
        'sd_email',
        'sd_website',
        'sd_address',
        'sd_latitude',
        'sd_longitude',
        'sd_google_id',
        'sd_overall_rating',
        'sd_total_reviews',
        'sd_google_reviews',
        'sd_review_summary',
        'sd_review_details',
        'sd_google_images',
        'sd_features',
        'sd_business_hours',
        'sd_listed_date',
        'sd_expire_date',
    ];

    $data = [];

    foreach ($fields as $field) {
        $value = get_post_meta($post_id, $field, true);
        $data[$field] = $value;
    }

    return $data;
}


/**
 * Generate FontAwesome star icons based on rating.
 *
 * Displays full stars, optional half star, and empty stars up to 5 total with overall count, review count.
 *
 * @param float $rating A rating number between 0 and 5.
 * @param int $total_review The total number of reviews.
 * @return string HTML string of star icons and counts.
 *
 * @usage
 * echo sd_get_overall_rating(4.3, 30);
 */
function sd_get_overall_rating($rating, $total_review) {
    $rating = floatval($rating);
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

    $stars_html = '';

    $stars_html .= '<span class="sd-overall-rating">';

    $stars_html .= '<span class="sd-star-rating">';
    for ($i = 1; $i <= $full_stars; $i++) { // full stars
        $stars_html .= '<i class="fas fa-star sd-star-filled"></i>';
    }

    if ($half_star) { // half star
        $stars_html .= '<i class="fas fa-star-half-alt sd-star-filled"></i>';
    }

    for ($i = 1; $i <= $empty_stars; $i++) { // empty stars
        $stars_html .= '<i class="far fa-star"></i>';
    }
    $stars_html .= '</span>';


    $stars_html .= '<span class="sd-overall-count">';
    $stars_html .= $rating; // rating number
    $stars_html .= '</span>';

    $stars_html .= '<span class="sd-review-count">';
    $stars_html .= '('.$total_review.' reviews)'; // review count
    $stars_html .= '</span>';

    $stars_html .= '</span>';


    return $stars_html;
}




