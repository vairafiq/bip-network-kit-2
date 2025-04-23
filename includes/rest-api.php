<?php
/**
 * REST API Routes
 *
 * @author   Bipper Media
 * @category API
 * @since    0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'rest_api_init', 'register_api_endpoints' );


function register_api_endpoints(){
    register_rest_route('bip/add', '/listing/', array(
        'methods' => 'POST',
        'callback' => 'bip_get_add_response',
        'permission_callback' => '__return_true',
    ));
}

function bip_get_add_response( $request ) {
    
    $params = $request->get_params();
    
    // file_put_contents( dirname(__FILE__) . '/'.$params['name'][0].'.txt', print_r( $params, true), FILE_APPEND);   

    $description = bip_get_listing_content( $params['name'][0], $params['address'][0] );
    // Create post
    $post_data = [
        'post_title'   => sanitize_text_field($params['name'][0] ?? 'Untitled'),
        'post_type'    => 'sd_business', // or your CPT like 'gd_restaurants'
        'post_status'  => 'publish',
        'post_content' => $description,
    ];

    $post_id = wp_insert_post($post_data);

    if (!is_wp_error($post_id)) {

        // Loop through all keys and save as post meta
        foreach ( $params as $key => $value ) {

            // Skip post fields
            if ( in_array( $key, ['name'] ) ) {
                continue;
            }

            // If value is array, store first item or serialize
            if ( is_array($value) ) {
                $value = count($value) === 1 ? $value[0] : maybe_serialize($value);
            }

            update_post_meta($post_id, $key, $value);
        }

        $post_link = get_permalink($post_id);

        return rest_ensure_response([
            'link' => $post_link,
            'post_id' => $post_id,
        ]);

    } else {
        return rest_ensure_response([
            'error' => $post_id->get_error_message(),
        ]);
    }
}