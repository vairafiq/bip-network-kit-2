<?php
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
    $latitude  = sd_get_post_data('latitude');
    $longitude = sd_get_post_data('longitude');
    $address   = sd_get_post_data('address');

    // Exit if no address and no coordinates
    if (empty($address) && (empty($latitude) || empty($longitude))) {
        return '';
    }

    ob_start();
    ?>

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
    <style>
        #sd-business-map {
            width: 100%;
            height: 300px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>

    <div id="sd-business-map"></div>

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
    <script>
        mapboxgl.accessToken = '<?php echo esc_js($mapbox_token); ?>'; // Mapbox token

        const map = new mapboxgl.Map({
            container: 'sd-business-map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [-74.5, 40], // temporary default
            zoom: 14
        });

        <?php if (!empty($address)) : ?>
            // Try to geocode the address first
            fetch(`https://api.mapbox.com/geocoding/v5/mapbox.places/<?php echo urlencode($address); ?>.json?access_token=${mapboxgl.accessToken}`)
                .then(response => response.json())
                .then(data => {
                    if (data.features.length > 0) {
                        const [lng, lat] = data.features[0].geometry.coordinates;
                        map.setCenter([lng, lat]);
                        new mapboxgl.Marker().setLngLat([lng, lat]).addTo(map);
                    } else {
                        fallbackToLatLng();
                    }
                })
                .catch(() => {
                    fallbackToLatLng();
                });

            function fallbackToLatLng() {
                <?php if (!empty($latitude) && !empty($longitude)) : ?>
                    map.setCenter([<?php echo esc_js($longitude); ?>, <?php echo esc_js($latitude); ?>]);
                    new mapboxgl.Marker()
                        .setLngLat([<?php echo esc_js($longitude); ?>, <?php echo esc_js($latitude); ?>])
                        .addTo(map);
                <?php endif; ?>
            }
        <?php else: ?>
            // No address, fallback to lat/lng
            map.setCenter([<?php echo esc_js($longitude); ?>, <?php echo esc_js($latitude); ?>]);
            new mapboxgl.Marker()
                .setLngLat([<?php echo esc_js($longitude); ?>, <?php echo esc_js($latitude); ?>])
                .addTo(map);
        <?php endif; ?>
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('sd_single_map', 'sd_single_map_shortcode');
