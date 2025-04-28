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

// Include files
include plugin_dir_path(__FILE__) . 'templates/header.php';
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







/**
 * Get US state abbreviation or full name.
 *
 * If you pass a full state name (any case), it returns the abbreviation.
 * If you pass a state abbreviation (any case), it returns the full state name.
 *
 * @param string $state_input State full name or abbreviation.
 * @return string|null Returns abbreviation or full name, or null if not found.
 */
function sd_flip_state( $state_input ) {
    $states = [
        'Alabama' => 'AL', 'Alaska' => 'AK', 'Arizona' => 'AZ', 'Arkansas' => 'AR', 'California' => 'CA',
        'Colorado' => 'CO', 'Connecticut' => 'CT', 'Delaware' => 'DE', 'Florida' => 'FL', 'Georgia' => 'GA',
        'Hawaii' => 'HI', 'Idaho' => 'ID', 'Illinois' => 'IL', 'Indiana' => 'IN', 'Iowa' => 'IA',
        'Kansas' => 'KS', 'Kentucky' => 'KY', 'Louisiana' => 'LA', 'Maine' => 'ME', 'Maryland' => 'MD',
        'Massachusetts' => 'MA', 'Michigan' => 'MI', 'Minnesota' => 'MN', 'Mississippi' => 'MS', 'Missouri' => 'MO',
        'Montana' => 'MT', 'Nebraska' => 'NE', 'Nevada' => 'NV', 'New Hampshire' => 'NH', 'New Jersey' => 'NJ',
        'New Mexico' => 'NM', 'New York' => 'NY', 'North Carolina' => 'NC', 'North Dakota' => 'ND', 'Ohio' => 'OH',
        'Oklahoma' => 'OK', 'Oregon' => 'OR', 'Pennsylvania' => 'PA', 'Rhode Island' => 'RI', 'South Carolina' => 'SC',
        'South Dakota' => 'SD', 'Tennessee' => 'TN', 'Texas' => 'TX', 'Utah' => 'UT', 'Vermont' => 'VT',
        'Virginia' => 'VA', 'Washington' => 'WA', 'West Virginia' => 'WV', 'Wisconsin' => 'WI', 'Wyoming' => 'WY'
    ];

    $state_input = trim($state_input);

    if ( empty($state_input) ) {
        return null;
    }

    // Prepare flipped array for abbreviation => full name lookup
    $abbreviations_to_states = array_flip($states);

    // Check if input is abbreviation
    $upper_input = strtoupper($state_input);
    if ( isset( $abbreviations_to_states[$upper_input] ) ) {
        return $abbreviations_to_states[$upper_input]; // Return full name
    }

    // Normalize full name input (capitalize each word)
    $formatted_input = ucwords(strtolower($state_input));
    if ( isset( $states[$formatted_input] ) ) {
        return $states[$formatted_input]; // Return abbreviation
    }

    // Not found
    return null;
}









/**
 * Generate a Google Maps direction link for a business listing.
 *
 * Fetches the address fields from post meta using post ID.
 * First uses 'business_address' field if available.
 * If not, it builds the address from street, city, state (converted to abbreviation), and zip.
 * Cleans the address and returns a Google Maps search link.
 *
 * @param int $post_id The ID of the business post.
 * @return string Google Maps URL for directions.
 */
function sd_business_map_direction_link() {

    // Get meta fields
    $business_address = sd_get_post_data('business_address');
    $street           = sd_get_post_data('street');
    $city             = sd_get_post_data('city');
    $state            = sd_get_post_data('state');
    $zip              = sd_get_post_data('zip');
    $name             = get_the_title( get_the_ID() );

    // Step 1: Prepare full address
    if ( ! empty( $business_address ) ) {
        $full_address = $business_address;
    } else {
        $state_value = '';
        if ( ! empty( $state ) ) {
            $state_value = sd_flip_state( $state );
            if ( ! $state_value ) {
                $state_value = $state; // fallback: use whatever is saved
            }
        }

        $address_parts = array_filter( [ $street, $city, $state_value, $zip ] );
        $full_address  = implode( ', ', $address_parts );
    }

    // Step 2: Remove suite/unit numbers
    $clean_address = preg_replace( '/\b(Suite|Ste|Unit|Apt|Apartment|#)\s*\d+\b/i', '', $full_address );

    // Step 3: Remove special characters except commas and spaces
    $clean_address = preg_replace( '/[^a-zA-Z0-9,\s]/', '', $clean_address );

    // Step 4: Remove standalone trailing number
    $clean_address = preg_replace( '/\s\d+$/', '', $clean_address );

    // Step 5: Remove extra spaces
    $clean_address = trim( preg_replace( '/\s+/', ' ', $clean_address ) );

    // Step 6: Generate Google Maps link
    $map_link = ! empty( $clean_address )
        ? "https://www.google.com/maps?q=" . urlencode( $name . ' ' . $clean_address )
        : '';

    return $map_link;
}
