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
    
    // dump data
    file_put_contents( dirname(__FILE__) . '/network_form_data.txt', print_r( [
        'request' => $request,
        'RRR' => $_REQUEST,
    ], true), FILE_APPEND);   

}