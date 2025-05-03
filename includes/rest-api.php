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
    register_rest_route('bip/create', '/listing/', array(
        'methods' => 'POST',
        'callback' => 'bip_get_add_response',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('bip/update', '/listing/', array(
        'methods' => 'POST',
        'callback' => 'bip_get_update_response',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('bip/unlist', '/listing/', array(
        'methods' => 'POST',
        'callback' => 'bip_get_unlist_response',
        'permission_callback' => '__return_true',
    ));
}


function bip_get_unlist_response( $request ) {
    
    $params = $request->get_params();

    $network_id = $params['network_id'];
    
    $args = [
        'post_type'      => 'sd_business',
        'posts_per_page' => 1,
        'meta_query'     => [
            [
                'key'     => 'network_id',
                'value'   => $network_id, // Replace with the actual value
                'compare' => '='
            ]
        ]
    ];
    
    $query = new WP_Query($args);

    if( ! $query->have_posts() ) {
        return;
    }
    
    $post = $query->posts[0];

    $post_id = $post->ID;

    $post = array(
        'ID'           => $post_id,
        'post_status'  => 'draft',
    );
    
    wp_update_post( $post );
}

function bip_get_update_response( $request ) {
    
    $params = $request->get_params();

    $network_id = $params['network_id'];
    
    $args = [
        'post_type'      => 'sd_business',
        'posts_per_page' => 1,
        'meta_query'     => [
            [
                'key'     => 'network_id',
                'value'   => $network_id, // Replace with the actual value
                'compare' => '='
            ]
        ]
    ];
    
    $query = new WP_Query($args);

    if( ! $query->have_posts() ) {
        return;
    }
    
    $post = $query->posts[0];

    $post_id = $post->ID;

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
            'error' => true,
        ]);
    }
}


function bip_get_add_response( $request ) {
    $params = $request->get_params();

    $description = bip_get_listing_content( $params['name'][0], $params['address'][0], $params['category'][0] );

    $post_data = [
        'post_title'   => sanitize_text_field($params['name'][0] ?? 'Untitled'),
        'post_type'    => 'sd_business',
        'post_status'  => 'publish',
        'post_content' => $description['summary'],
    ];

    $post_id = wp_insert_post($post_data);

    update_post_meta( $post_id, 'review_summary', $description['review'] );
    update_post_meta( $post_id, 'faqs', $description['faqs'] );

    if (!is_wp_error($post_id)) {
        foreach ( $params as $key => $value ) {
            if ( $key === 'name' ) continue;

            // Handle 'category' taxonomy
            if ( $key === 'category' ) {
                $term_name = is_array($value) ? $value[0] : $value;
                $term_name = sanitize_text_field($term_name);

                $term = term_exists($term_name, 'sd_business_category');
                if (!$term) {
                    $term = wp_insert_term($term_name, 'sd_business_category');
                }

                if (!is_wp_error($term)) {
                    wp_set_object_terms($post_id, intval($term['term_id']), 'sd_business_category');
                }
                continue;
            }

            // Handle 'city' taxonomy
            if ( $key === 'city' ) {
                $term_name = is_array($value) ? $value[0] : $value;
                $term_name = sanitize_text_field($term_name);

                $term = term_exists($term_name, 'sd_business_location');
                if (!$term) {
                    $term = wp_insert_term($term_name, 'sd_business_location');
                }

                if (!is_wp_error($term)) {
                    wp_set_object_terms($post_id, intval($term['term_id']), 'sd_business_location');
                }
                continue;
            }

            $value = isset( $value[0] ) ? $value[0] : '';
            // Store other meta fields
            if ( is_array($value) || is_object($value) ) {
                $value = wp_json_encode($value);
            }

            update_post_meta($post_id, $key, $value);
        }

        return rest_ensure_response([
            'link'     => get_permalink($post_id),
            'post_id'  => $post_id,
        ]);

    } else {
        return rest_ensure_response([
            'error' => $post_id->get_error_message(),
        ]);
    }
}
