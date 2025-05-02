<?php
// Register Custom Post Type: Kit Settings
function sd_register_kit_settings_cpt() {
    $labels = [
        'name' => 'Kit Settings',
        'singular_name' => 'Kit Setting',
        'menu_name' => 'Kit Settings',
        'name_admin_bar' => 'Kit Setting',
    ];

    $args = [
        'label' => 'Kit Setting',
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 30,
        'menu_icon' => 'dashicons-admin-generic',
        'supports' => ['title'],
        'capability_type' => 'post',
        'capabilities' => [
            'create_posts' => 'do_not_allow',
        ],
        'map_meta_cap' => true,
    ];

    register_post_type('kit_settings', $args);
}
add_action('init', 'sd_register_kit_settings_cpt');

// Create a default Kit Settings post if none exists
function sd_create_default_kit_settings_post() {
    $existing = get_posts([
        'post_type' => 'kit_settings',
        'post_status' => 'any',
        'posts_per_page' => 1,
    ]);

    if (empty($existing)) {
        $post_id = wp_insert_post([
            'post_title' => 'Default Kit Settings',
            'post_type' => 'kit_settings',
            'post_status' => 'publish',
        ]);

        if ($post_id) {
            $default_values = [
                'primary_800' => '#003C43',
                'primary_600' => '#135D66',
                'primary_400' => '#77B0AA',
                'primary_200' => '#E3FEF7',
                'primary_100' => '#f6fffc',
                'header_bg'   => '#003C43',
                'footer_bg'   => '#003C43',
                'accent'      => '#ff9800',
                'green'       => '#34a853',
                'red'         => '#EE0000',
                'white'       => '#FFFFFF',
                'lighter'     => '#f4f4f4',
                'light'       => '#e0e0e0',
                'gray'        => '#787878',
                'black'       => '#333333',

                'homepage_banner_subtitle' => 'Explore the Best Local Businesses in your area',
                'archive_banner_subtitle'  => 'Explore the Best Local Businesses in your area',
                'homepage_banner_intro'    => 'Your trusted online guide to discovering the best local businesses around you. We help you find top-rated services, restaurants, shops, and more all in one place, tailored to your needs.',
                'homepage_banner_bg'       => '',
                'otherpage_banner_bg'      => '',

                'about_image'              => '',
                'about_content'            => '',

                'cta_title'                => '',
                'cta_features'             => '',
            ];

            foreach ($default_values as $key => $value) {
                update_post_meta($post_id, $key, $value);
            }
        }
    }
}
add_action('init', 'sd_create_default_kit_settings_post', 20);

// Add Meta Boxes
function sd_kit_settings_meta_boxes() {
    add_meta_box(
        'sd_kit_settings_fields',
        'Kit Settings Fields',
        'sd_render_kit_settings_fields',
        'kit_settings',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'sd_kit_settings_meta_boxes');

// Render Fields
function sd_render_kit_settings_fields($post) {
    wp_nonce_field('sd_save_kit_settings_fields', 'sd_kit_settings_nonce');

    // Enqueue media uploader scripts
    wp_enqueue_media();
    ?>
    <script>
        jQuery(document).ready(function ($) {
            function openMediaUploader(inputId, previewId) {
                const frame = wp.media({
                    title: 'Select Image',
                    multiple: false,
                    library: { type: 'image' },
                    button: { text: 'Use this image' }
                });

                frame.on('select', function () {
                    const attachment = frame.state().get('selection').first().toJSON();
                    $('#' + inputId).val(attachment.url);
                    $('#' + previewId).attr('src', attachment.url).show();
                });

                frame.open();
            }

            $('.image-upload-button').on('click', function (e) {
                e.preventDefault();
                const inputId = $(this).data('input');
                const previewId = $(this).data('preview');
                openMediaUploader(inputId, previewId);
            });
        });
    </script>
    <style>
        .image-preview {
            max-width: 200px;
            max-height: 150px;
            display: block;
            margin-top: 8px;
        }
    </style>

    <?php
    $colors = [
        'primary_800' => 'Primary 800 (#003C43)',
        'primary_600' => 'Primary 600 (#135D66)',
        'primary_400' => 'Primary 400 (#77B0AA)',
        'primary_200' => 'Primary 200 (#E3FEF7)',
        'primary_100' => 'Primary 100 (#f6fffc)',
        'header_bg'   => 'Header Background (#003C43)',
        'footer_bg'   => 'Footer Background (#003C43)',
        'accent'      => 'Accent (#ff9800)',
        'green'       => 'Green (#34a853)',
        'red'         => 'Red (#EE0000)',
        'white'       => 'White (#FFFFFF)',
        'lighter'     => 'Lighter (#f4f4f4)',
        'light'       => 'Light (#e0e0e0)',
        'gray'        => 'Gray (#787878)',
        'black'       => 'Black (#333333)',
    ];

    $banner = [
        'homepage_banner_subtitle' => ['Homepage Banner Sub-title', 'Text under the homepage banner title'],
        'archive_banner_subtitle'  => ['Archive Banner Sub-title', 'Text under the archive page banner title'],
        'homepage_banner_intro'    => ['Homepage Banner Intro', 'Short intro/description in the homepage banner'],
        'homepage_banner_bg'       => ['Homepage Banner Background Image', 'Background image for homepage banner'],
        'otherpage_banner_bg'      => ['Other Page Banner Background Image', 'Background image for inner page banners'],
    ];

    $about = [
        'about_image'   => ['About Section Image', 'Image used in the About section'],
        'about_content' => ['About Section Content', 'Content for the About section (supports formatting)'],
    ];

    $cta = [
        'cta_title'    => ['CTA Title', 'Main heading for the Call to Action section'],
        'cta_features' => ['CTA Features (list)', 'Features shown in CTA section, separate by camma'],
    ];

    echo '<h2 style="font-size:20px;font-weight:600;padding:20px 0 10px 0;">Colors</h2><table class="form-table">';
    foreach ($colors as $field => $label) {
        $value = get_post_meta($post->ID, $field, true);
        echo '<tr>
            <th><label for="' . esc_attr($field) . '">' . esc_html($label) . '</label></th>
            <td><input type="color" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" style="width: 300px; height: 40px;"></td>
        </tr>';
    }
    echo '</table>';

    echo '<h2 style="font-size:20px;font-weight:600;padding:20px 0 10px 0;">Banner / Hero</h2><table class="form-table">';
    foreach ($banner as $field => [$label, $description]) {
        $value = get_post_meta($post->ID, $field, true);
        echo '<tr>
            <th><label for="' . esc_attr($field) . '">' . esc_html($label) . '</label></th>
            <td>';
        if ($field === 'homepage_banner_intro') {
            echo '<textarea id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" rows="4" class="widefat">' . esc_textarea($value) . '</textarea>';
        } elseif (in_array($field, ['homepage_banner_bg', 'otherpage_banner_bg'])) {
            $preview_id = $field . '_preview';
            echo '<input type="text" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat" />';
            echo '<button class="button image-upload-button" data-input="' . esc_attr($field) . '" data-preview="' . $preview_id . '">Upload Image</button>';
            echo '<img id="' . $preview_id . '" src="' . esc_url($value) . '" class="image-preview" style="' . ($value ? '' : 'display:none;') . '" />';
        } else {
            echo '<input type="text" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat" />';
        }
        echo '<p class="description">' . esc_html($description) . '</p></td></tr>';
    }
    echo '</table>';

    echo '<h2 style="font-size:20px;font-weight:600;padding:20px 0 10px 0;">About Section</h2><table class="form-table">';
    foreach ($about as $field => [$label, $description]) {
        echo '<tr>
            <th><label for="' . esc_attr($field) . '">' . esc_html($label) . '</label></th>
            <td>';
        if ($field === 'about_content') {
            $content = get_post_meta($post->ID, $field, true);
            wp_editor($content, $field, ['textarea_rows' => 6]);
        } else {
            $value = get_post_meta($post->ID, $field, true);
            $preview_id = $field . '_preview';
            echo '<input type="text" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat" />';
            echo '<button class="button image-upload-button" data-input="' . esc_attr($field) . '" data-preview="' . $preview_id . '">Upload Image</button>';
            echo '<img id="' . $preview_id . '" src="' . esc_url($value) . '" class="image-preview" style="' . ($value ? '' : 'display:none;') . '" />';
        }
        echo '<p class="description">' . esc_html($description) . '</p></td></tr>';
    }
    echo '</table>';

    echo '<h2 style="font-size:20px;font-weight:600;padding:20px 0 10px 0;">CTA Section</h2><table class="form-table">';
    foreach ($cta as $field => [$label, $description]) {
        $value = get_post_meta($post->ID, $field, true);
        echo '<tr>
            <th><label for="' . esc_attr($field) . '">' . esc_html($label) . '</label></th>
            <td>';
        if ($field === 'cta_features') {
            echo '<textarea id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" rows="4" class="widefat">' . esc_textarea($value) . '</textarea>';
        } else {
            echo '<input type="text" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat" />';
        }
        echo '<p class="description">' . esc_html($description) . '</p></td></tr>';
    }
    echo '</table>';
}


// Save Fields
function sd_save_kit_settings_fields($post_id) {
    if (!isset($_POST['sd_kit_settings_nonce']) || !wp_verify_nonce($_POST['sd_kit_settings_nonce'], 'sd_save_kit_settings_fields')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        // Colors
        'primary_800', 'primary_600', 'primary_400', 'primary_200', 'primary_100',
        'header_bg', 'footer_bg',
        'accent', 'green', 'red', 'white', 'lighter', 'light', 'gray', 'black',
        // Banner
        'homepage_banner_subtitle', 'archive_banner_subtitle', 'homepage_banner_intro', 'homepage_banner_bg', 'otherpage_banner_bg',
        // About
        'about_image', 'about_content',
        // CTA
        'cta_title', 'cta_features'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = $field === 'about_content' ? wp_kses_post($_POST[$field]) : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post', 'sd_save_kit_settings_fields');
