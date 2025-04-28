<?php
/**
 * Shortcode: [sd_business_map_link]
 *
 * Outputs a “View on map” link for the current business post,
 * including the business name and address, each wrapped in spans
 * with their own classes.
 *
 * Usage:
 *   echo do_shortcode('[sd_business_map_link]');
 *
 * @return string HTML anchor linking to Google Maps directions.
 */
function sd_business_map_link_shortcode() {
    if ( ! is_singular( 'sd_business' ) ) {
        return '';
    }

    $business_name  = get_the_title( get_the_ID() );
    $address        = sd_get_post_data('address');
    $map_url        = sd_business_map_direction_link();

    if ( empty( $map_url ) ) {
        return '';
    }

    ob_start();
    ?>
    <a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer" class="sd-map-link">
        <p class="sd-map-link-text">View on map</p>
        <p class="sd-map-business-name"><?php echo esc_html( $business_name ); ?></p>
        <p class="sd-map-address"><?php echo esc_html( $address ); ?></p>
    </a>

    <style>
        .sd-map-link p,
        .sd-map-link{
            text-decoration: none !important;
            margin: 0;
            
        }
        .sd-map-link:hover .sd-map-business-name{
            text-decoration: underline !important;
        }
        .sd-map-link-text {
            color: var(--accent) !important;
            font-weight: 500;
        }
        .sd-map-business-name {
            color: var(--black) !important;
            font-weight: 500;
        }
        .sd-map-address {
            color: var(--black) !important;
            font-weight: 300 !important;
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode( 'sd_business_map_link', 'sd_business_map_link_shortcode' );
