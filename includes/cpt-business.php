<?php
/**
 * Register Custom Post Type: Business
 */

function sd_register_business_cpt() {
    $labels = array(
        'name'               => 'Businesses',
        'singular_name'      => 'Business',
        'menu_name'          => 'Businesses',
        'name_admin_bar'     => 'Business',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Business',
        'new_item'           => 'New Business',
        'edit_item'          => 'Edit Business',
        'view_item'          => 'View Business',
        'all_items'          => 'All Businesses',
        'search_items'       => 'Search Businesses',
        'not_found'          => 'No businesses found.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-building',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'rewrite'            => array('slug' => 'businesses'),
        'show_in_rest'       => true,
    );

    register_post_type('businesses', $args);
}
add_action('init', 'sd_register_business_cpt');





/**
 * Add meta box for Business Details
 */
function sd_add_business_meta_boxes() {
    add_meta_box(
        'sd_business_details',
        'Business Details',
        'sd_business_fields_callback',
        'businesses',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'sd_add_business_meta_boxes');






/**
 * Render the business details fields
 */
function sd_business_fields_callback($post) {
    $fields = [
        'phone'           => 'Business contact phone number.',
        'email'           => 'Business contact email address.',
        'website'         => 'Official website URL.',
        'address'         => 'Full physical address.',
        'latitude'        => 'Latitude coordinate for map location.',
        'longitude'       => 'Longitude coordinate for map location.',
        'google_id'       => 'Google Business ID.',
        'overall_rating'  => 'Average rating.',
        'total_reviews'   => 'Total number of reviews.',
        'google_reviews'  => 'Raw Google review data (HTML or text).',
        'review_summary'  => 'Short summary of reviews (HTML allowed).',
        'review_details'  => 'Detailed review content (HTML allowed).',
        'google_images'   => 'HTML image tags or comma-separated URLs.',
        'features'        => 'Key features or services (use HTML if needed).',
        'business_hours'  => 'Opening and closing times.',
        'listed_date'     => 'Date when the business was listed.',
        'expire_date'     => 'Date when the listing expires.'
    ];

    // Add nonce field for security
    wp_nonce_field('sd_save_business_fields', 'sd_business_nonce');

    echo '<table class="form-table">';
    foreach ($fields as $field => $description) {
        $value = get_post_meta($post->ID, 'sd_' . $field, true);
        $label = ucwords(str_replace('_', ' ', $field));
        $type = in_array($field, ['listed_date', 'expire_date']) ? 'date' : 'text';
        $is_textarea = in_array($field, ['google_reviews', 'review_summary', 'review_details', 'google_images', 'features']);

        echo "<tr>
            <th><label for='sd_{$field}'>{$label}</label></th>
            <td>";

        if ($is_textarea) {
            echo "<textarea id='sd_{$field}' name='sd_{$field}' rows='4' class='large-text'>" . esc_textarea($value) . "</textarea>";
        } else {
            echo "<input type='{$type}' id='sd_{$field}' name='sd_{$field}' value='" . esc_attr($value) . "' class='large-text' />";
        }

        echo "<p class='description'>{$description}</p>
            </td>
        </tr>";
    }
    echo '</table>';
}






/**
 * Save custom field values
 */
function sd_save_business_fields($post_id) {
    if (!isset($_POST['sd_business_nonce']) || !wp_verify_nonce($_POST['sd_business_nonce'], 'sd_save_business_fields')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        'phone', 'email', 'website', 'address', 'latitude', 'longitude',
        'google_id', 'overall_rating', 'total_reviews', 'google_reviews',
        'review_summary', 'review_details', 'google_images', 'features',
        'business_hours', 'listed_date', 'expire_date'
    ];

    foreach ($fields as $field) {
        if (isset($_POST['sd_' . $field])) {
            update_post_meta($post_id, 'sd_' . $field, sanitize_text_field($_POST['sd_' . $field]));
        }
    }
}
add_action('save_post', 'sd_save_business_fields');
