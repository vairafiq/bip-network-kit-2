<?php
/** 
 * Shortcode: [sd_single_map]
 *
 * Outputs
 * - Mapbox map
 * - Business name and address, google map link for the current business post.
 *
 * Usage: (single business page only)
 *   echo do_shortcode('[sd_single_map]');
 *
 * @return string Map and HTML anchor linking to Google Maps directions.
 */
function sd_single_map_shortcode($atts) {
    if (!is_singular('sd_business')) return '';

    $env = include plugin_dir_path(__FILE__) . '../../../includes/env.php';
    $mapbox_token = $env['MAPBOX_ACCESS_TOKEN'] ?? '';

    if (empty($mapbox_token)) {
        // If no token, show an error in debug mode
        if (isset($_GET['debug'])) {
            echo 'Mapbox access token is missing.';
        }
        return;
    }

    // Get meta fields
    $business_name  = get_the_title( get_the_ID() );
    $latitude       = sd_get_post_data('latitude');
    $longitude      = sd_get_post_data('longitude');
    $address        = sd_get_post_data('address');
    $map_url        = sd_business_map_direction_link() ? sd_business_map_direction_link() : '';

    $pin_icon = '<svg fill="#000000" width="20px" height="20px" viewBox="-0.96 -0.96 33.92 33.92" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="1.184"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.114-0.011c-6.559 0-12.114 5.587-12.114 12.204 0 6.93 6.439 14.017 10.77 18.998 0.017 0.020 0.717 0.797 1.579 0.797h0.076c0.863 0 1.558-0.777 1.575-0.797 4.064-4.672 10-12.377 10-18.998 0-6.618-4.333-12.204-11.886-12.204zM16.515 29.849c-0.035 0.035-0.086 0.074-0.131 0.107-0.046-0.032-0.096-0.072-0.133-0.107l-0.523-0.602c-4.106-4.71-9.729-11.161-9.729-17.055 0-5.532 4.632-10.205 10.114-10.205 6.829 0 9.886 5.125 9.886 10.205 0 4.474-3.192 10.416-9.485 17.657zM16.035 6.044c-3.313 0-6 2.686-6 6s2.687 6 6 6 6-2.687 6-6-2.686-6-6-6zM16.035 16.044c-2.206 0-4.046-1.838-4.046-4.044s1.794-4 4-4c2.207 0 4 1.794 4 4 0.001 2.206-1.747 4.044-3.954 4.044z"></path> </g></svg>';
    $building_icon = '<svg width="20px" height="20px" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.5 22.0001H4.07997C2.91997 22.0001 1.96997 21.0701 1.96997 19.9301V5.09011C1.96997 2.47011 3.91997 1.2801 6.30997 2.4501L10.75 4.63011C11.71 5.10011 12.5 6.3501 12.5 7.4101V22.0001Z" stroke="#000000" stroke-width="2.136" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M21.97 15.0602V18.8402C21.97 21.0002 20.97 22.0002 18.81 22.0002H12.5V10.4202L12.97 10.5202L17.47 11.5302L19.5 11.9802C20.82 12.2702 21.9 12.9502 21.96 14.8702C21.97 14.9302 21.97 14.9902 21.97 15.0602Z" stroke="#000000" stroke-width="2.136" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M5.5 9H8.97" stroke="#000000" stroke-width="2.136" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M5.5 13H8.97" stroke="#000000" stroke-width="2.136" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.47 11.53V14.75C17.47 15.99 16.46 17 15.22 17C13.98 17 12.97 15.99 12.97 14.75V10.52L17.47 11.53Z" stroke="#000000" stroke-width="2.136" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M21.96 14.87C21.9 16.05 20.92 17 19.72 17C18.48 17 17.47 15.99 17.47 14.75V11.53L19.5 11.98C20.82 12.27 21.9 12.95 21.96 14.87Z" stroke="#000000" stroke-width="2.136" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>';

    // Exit if no address and no coordinates
    if (empty($address) && (empty($latitude) || empty($longitude))) {
        return '';
    }

    ob_start();
    ?>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">



    
    
    <!-- map and address markup -->
    <div class="sd-single-map">
        <div class="sd-single-map-container">
            <div id="sd-business-map"></div>
      
            <div class="sd-map-link-container">
                <a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer" class="sd-map-link">
                    <!-- <span class="sd-map-link-text">View on map</span> -->
                    <p class="sd-map-business-name"><span><?php echo $building_icon ?></span></span><span><?php echo esc_html( $business_name ); ?></span></p>
                    <span class="sd-map-address"><?php echo $pin_icon ?><span><?php echo esc_html( $address ); ?></span></span>
                </a>
            </div>
        </div>
    </div>
    <!-- map and address markup -->





    <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
    <script>
        mapboxgl.accessToken = '<?php echo esc_js($mapbox_token); ?>';

        const map = new mapboxgl.Map({
            container: 'sd-business-map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [-34.5, 40], // temp default
            zoom: 16
        });

        const setMapCenter = (lng, lat) => {
            map.resize(); // Ensure map container size is correct
            map.easeTo({ center: [lng, lat], zoom: 16 });
            new mapboxgl.Marker().setLngLat([lng, lat]).addTo(map);
        };

        map.on('load', function () {
            <?php if (!empty($address)) : ?>
                fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/<?php echo urlencode($address); ?>.json?access_token=${mapboxgl.accessToken}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.features.length > 0) {
                            const [lng, lat] = data.features[0].geometry.coordinates;
                            setMapCenter(lng, lat);
                        } else {
                            fallbackToLatLng();
                        }
                    })
                    .catch(() => {
                        fallbackToLatLng();
                    });

                function fallbackToLatLng() {
                    <?php if (!empty($latitude) && !empty($longitude)) : ?>
                        setMapCenter(<?php echo esc_js($longitude); ?>, <?php echo esc_js($latitude); ?>);
                    <?php endif; ?>
                }
            <?php else: ?>
                setMapCenter(<?php echo esc_js($longitude); ?>, <?php echo esc_js($latitude); ?>);
            <?php endif; ?>
        });
    </script>




    <style>
        .sd-single-map-container {
            display: grid;
            gap: 1rem;
            grid-template-columns: 320px 1fr;
            align-items: center;
        }
        #sd-business-map {
            width: 320px;
            height: 200px;
            border-radius: 10px;
        }

        .sd-map-link{
            text-decoration: none !important;
        }

        .sd-map-link-text {
            color: var(--accent) !important;
            font-weight: 500;
        }
        .sd-map-business-name {
            color: var(--black) !important;
            font-weight: 500 !important;
        }
        .sd-map-address {
            color: var(--black) !important;
            font-weight: 500 !important;
            margin-top: 1rem;
        }
        .sd-map-address svg {
            min-width: 20px;
        }
        .sd-map-link:hover .sd-map-address {
            text-decoration: underline !important;
        }

        .sd-map-business-name,
        .sd-map-address {
            display: flex;
            align-items: start;
            gap: 0.5rem;
            line-height: 1 !important;
        }
        @media (max-width:768px) {
            .sd-single-map-container {
                gap: 1rem;
                grid-template-columns: 1fr;
            }
            #sd-business-map {
                width: 100%;
                height: 300px;
            }
        }
    </style>


    <?php
    return ob_get_clean();
}
add_shortcode('sd_single_map', 'sd_single_map_shortcode');
