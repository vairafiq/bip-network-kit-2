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
        'rewrite'            => array('slug' => 'biz'),
        'query_var'          => 'business',
        'show_in_rest'       => true,
    );

    register_post_type('sd_business', $args);
}
add_action('init', 'sd_register_business_cpt');





/**
 * Register Taxonomies for Businesses
 */
function sd_register_business_taxonomies() {
    // Business Category (hierarchical)
    $category_labels = array(
        'name'              => 'Business Categories',
        'singular_name'     => 'Business Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'parent_item'       => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Categories',
    );

    register_taxonomy('business_category', 'sd_business', array(
        'hierarchical'      => true,
        'labels'            => $category_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'sd_business_category'),
        'show_in_rest'      => true,
    ));

    // Business Location (hierarchical)
    $location_labels = array(
        'name'              => 'Business Locations',
        'singular_name'     => 'Business Location',
        'search_items'      => 'Search Locations',
        'all_items'         => 'All Locations',
        'parent_item'       => 'Parent Location',
        'parent_item_colon' => 'Parent Location:',
        'edit_item'         => 'Edit Location',
        'update_item'       => 'Update Location',
        'add_new_item'      => 'Add New Location',
        'new_item_name'     => 'New Location Name',
        'menu_name'         => 'Locations',
    );

    register_taxonomy('business_location', 'sd_business', array(
        'hierarchical'      => true,
        'labels'            => $location_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'sd_business_location'),
        'show_in_rest'      => true,
    ));
    
}
add_action('init', 'sd_register_business_taxonomies');




/**
 * Add meta box for Business Details
 */
function sd_add_business_meta_boxes() {
    add_meta_box(
        'sd_business_details',
        'Business Details',
        'sd_business_fields_callback',
        'sd_business',
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
        'category'        => 'Business category',
        'phone'           => 'Business contact phone number.',
        'email'           => 'Business contact email address.',
        'website'         => 'Official website URL.',

        'zip'             => 'ZIP code of the business.',
        'city'            => 'City of the business.',
        'state'           => 'State of the business.',
        'country'         => 'Country of the business.',
        'street'          => 'Street address.',
        'address'         => 'Full physical address.',
        'business_address'=> 'Full business address.',
        'latitude'        => 'Latitude coordinate for map location.',
        'longitude'       => 'Longitude coordinate for map location.',

        'price_range'     => 'Price range (e.g., $ - $$$).',
        'main_image'      => 'Main image URL of the business.',
        'overall_rating'  => 'Average rating.',
        'review_count'    => 'Total number of reviews.',
        'review_details'  => 'Review star count details (HTML allowed).',
        'features'        => 'Key features or services (use HTML if needed).',
        'business_hours'  => 'Opening and closing times.',
        'review_summary'  => 'Short summary of reviews (HTML allowed).',

        'google_id'       => 'Business Google ID.',
        'google_reviews'  => 'Raw Google review data (HTML or text).',
        'google_images'   => 'HTML image tags or comma-separated URLs.',

        'facebook'        => 'Facebook page URL.',
        'x'               => 'X / Twitter profile URL.',
        'linkedin'        => 'LinkedIn profile URL.',
        'youtube'         => 'YouTube channel URL.',


    ];

    // Add nonce field for security
    wp_nonce_field('sd_save_business_fields', 'sd_business_nonce');

    echo '<table class="form-table">';
    foreach ($fields as $field => $description) {
        $value = get_post_meta($post->ID, $field, true);
        $label = ucwords(str_replace('_', ' ', $field));
        $type = in_array($field, ['listed_date', 'expire_date']) ? 'date' : 'text';
        $is_textarea = in_array($field, ['google_reviews', 'review_summary', 'review_details', 'google_images', 'features', 'business_hours']);

        echo "<tr>
            <th><label for='{$field}'>{$label}</label></th>
            <td>";

        if ($is_textarea) {
            echo "<textarea id='{$field}' name='{$field}' rows='4' class='large-text'>" . esc_textarea($value) . "</textarea>";
        } else {
            echo "<input type='{$type}' id='{$field}' name='{$field}' value='" . esc_attr($value) . "' class='large-text' />";
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
        'category',
        'phone',
        'email',
        'website',

        'zip',
        'city',
        'state',
        'country',
        'street',
        'address',
        'business_address',
        'latitude',
        'longitude',

        'price_range',
        'main_image',
        'overall_rating',
        'review_count',
        'review_details',
        'features',
        'business_hours',
        'review_summary',

        'google_id',
        'google_reviews',
        'google_images',
        
        'facebook',
        'x',
        'linkedin',
        'youtube',
        
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'sd_save_business_fields');
