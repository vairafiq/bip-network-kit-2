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
                'primary'           => '#333333',
                'primary_hover'     => '#000000',
                'secondary'         => '#EE0000',
                'secondary_hover'   => '#DB0000',

                'header_bg'   => '#333333',
                'footer_bg'   => '#333333',

                'accent'      => '#ff297e',
                'green'       => '#34a853',
                'red'         => '#EE0000',

                'white'       => '#FFFFFF',
                'lighter'     => '#f4f4f4',
                'light'       => '#e0e0e0',
                'gray'        => '#787878',
                'black'       => '#333333',

                'homepage_meta_title'           => '',
                'homepage_meta_description'     => '',
                'homepage_banner_bg'            => '',
                'homepage_banner_title'         => '',
                'homepage_banner_description'   => '',
                'about_content'                 => '',
                'about_image'                   => '',
                'cta_title'                     => '',
                'cta_content'                   => '',

                'archive_meta_title'            => '',
                'archive_meta_description'      => '',
                'archive_banner_bg'             => '',
                'archive_banner_title'          => '',
                'archive_banner_description'    => '',




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

    // Default values
    $default_values = [
        'primary'           => '#333333',
        'primary_hover'     => '#000000',
        'secondary'         => '#EE0000',
        'secondary_hover'   => '#DB0000',

        'header_bg'   => '#333333',
        'footer_bg'   => '#333333',

        'accent'      => '#ff297e',
    ];

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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll("#color-fields tr");

            rows.forEach(row => {
                const textInput = row.querySelector(".color-text");
                const colorInput = row.querySelector(".color-picker");

                if (textInput && colorInput) {
                    textInput.addEventListener("input", () => {
                        colorInput.value = textInput.value;
                    });

                    colorInput.addEventListener("input", () => {
                        textInput.value = colorInput.value;
                    });
                }
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
        'primary' => [
            'Primary Color',
            'Used for normal buttons, archive badges and pagination elements.'
        ],
        'primary_hover' => [
            'Primary Hover Color',
            'Hover over primary buttons.'
        ],
        'secondary' => [
            'Secondary Color',
            'Used for business buttons.'
        ],
        'secondary_hover' => [
            'Secondary Hover Color',
            'Hover over business buttons.'
        ],
        'header_bg' => [
            'Header Background',
            'Background color of the website header.'
        ],
        'footer_bg' => [
            'Footer Background',
            'Background color of the website footer.'
        ],
        'accent' => [
            'Accent Color',
            'Used for links, star icons, star progress bars and hover/active states of menu items.'
        ],
    ];


    $home = [
        'homepage_meta_title'         => ['Homepage Meta Title', 'SEO title for the homepage (shown in search results)'],
        'homepage_meta_description'   => ['Homepage Meta Description', 'SEO description for the homepage (shown in search results)'],
        'homepage_banner_bg'          => ['Homepage Banner Background Image', 'Background image displayed in the homepage banner section'],
        'homepage_banner_title'       => ['Homepage Banner Title', 'Main heading text shown in the homepage banner'],
        'homepage_banner_description' => ['Homepage Banner Description', 'Short description or tagline displayed under the homepage banner title'],
        'about_content'               => ['About Section Content', 'Formatted content shown in the About section on the homepage'],
        'about_image'                 => ['About Section Image', 'Image displayed alongside the About section content'],
        'cta_title'                   => ['CTA Title', 'Heading for the Call-to-Action section on the homepage'],
        'cta_content'                 => ['CTA Content', 'Descriptive content or list shown in the Call-to-Action section'],
    ];
    
    $archive = [
        'archive_meta_title'          => ['Archive Meta Title', 'SEO title for archive pages (e.g. category, tag)'],
        'archive_meta_description'    => ['Archive Meta Description', 'SEO description for archive pages'],
        'archive_banner_bg'           => ['Archive Banner Background Image', 'Background image used in the archive page banner'],
        'archive_banner_title'        => ['Archive Banner Title', 'Main heading text for archive page banners'],
        'archive_banner_description'  => ['Archive Banner Description', 'Short description shown beneath the archive banner title'],
    ];
    
    // Display the fields
    echo '<h2 style="font-size:20px;font-weight:600;padding:20px 0 10px 0;">Colors</h2><table class="form-table" id="color-fields">';
    foreach ($colors as $field => [$label, $description]) {
        $saved_value = get_post_meta($post->ID, $field, true);
        $value = !empty($saved_value) ? $saved_value : ($default_values[$field] ?? '#000000');

        echo '<tr>
            <th><label>' . esc_html($label) . '</label></th>
            <td>
                <input type="text" class="color-text" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" style="width: 300px; height: 40px;">
                <input type="color" class="color-picker" value="' . esc_attr($value) . '" style="width: 300px; height: 40px;">
                <p class="description">' . esc_html($description) . '</p>
            </td>
        </tr>';
    }
    echo '</table>';

    echo '<h2 style="font-size:20px;font-weight:600;padding:20px 0 10px 0;">Home</h2><table class="form-table">';
    foreach ($home as $field => [$label, $description]) {
        $value = get_post_meta($post->ID, $field, true);
        echo '<tr>
            <th><label for="' . esc_attr($field) . '">' . esc_html($label) . '</label></th>
            <td>';
        if(in_array($field, ['homepage_banner_title', 'cta_title'])) {
            echo '<input type="text" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat" />';
        } elseif (in_array($field, ['homepage_meta_title', 'homepage_meta_description', 'homepage_banner_description'])) {
            echo '<textarea id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" rows="4" class="widefat">' . esc_textarea($value) . '</textarea>';
        } elseif ($field === 'about_content' || $field === 'cta_content') {
            $content = get_post_meta($post->ID, $field, true);
            wp_editor($content, $field, ['textarea_rows' => 6]);
        } elseif (in_array($field, ['homepage_banner_bg', 'about_image'])) {
            $preview_id = $field . '_preview';
            echo '<input type="text" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat" />';
            echo '<button class="button image-upload-button" data-input="' . esc_attr($field) . '" data-preview="' . $preview_id . '">Upload Image</button>';
            echo '<img id="' . $preview_id . '" src="' . esc_url($value) . '" class="image-preview" style="' . ($value ? '' : 'display:none;') . '" />';
        } 
        echo '<p class="description">' . esc_html($description) . '</p></td></tr>';
    }
    echo '</table>';

    echo '<h2 style="font-size:20px;font-weight:600;padding:20px 0 10px 0;">Archive</h2><table class="form-table">';
    foreach ($archive as $field => [$label, $description]) {
        $value = get_post_meta($post->ID, $field, true);
        echo '<tr>
            <th><label for="' . esc_attr($field) . '">' . esc_html($label) . '</label></th>
            <td>';
        if($field === 'archive_banner_title') {
            echo '<input type="text" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat" />';
        } elseif (in_array($field, ['archive_meta_title', 'archive_meta_description', 'archive_banner_description'])) {
            echo '<textarea id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" rows="4" class="widefat">' . esc_textarea($value) . '</textarea>';
        } elseif($field === 'archive_banner_bg') {
            $preview_id = $field . '_preview';
            echo '<input type="text" id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" value="' . esc_attr($value) . '" class="widefat" />';
            echo '<button class="button image-upload-button" data-input="' . esc_attr($field) . '" data-preview="' . $preview_id . '">Upload Image</button>';
            echo '<img id="' . $preview_id . '" src="' . esc_url($value) . '" class="image-preview" style="' . ($value ? '' : 'display:none;') . '" />';
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
        'primary', 'primary_hover', 'secondary', 'secondary_hover',
        'header_bg', 'footer_bg',
        'accent', 'green', 'red', 'white',
        'lighter', 'light', 'gray', 'black',
        // Home
        'homepage_meta_title', 'homepage_meta_description',
        'homepage_banner_bg', 'homepage_banner_title', 'homepage_banner_description',
        'about_content', 'about_image',
        'cta_title', 'cta_content',
        // Archive
        'archive_meta_title', 'archive_meta_description',
        'archive_banner_bg', 'archive_banner_title', 'archive_banner_description',
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if (in_array($field, ['about_content', 'cta_content'])) {
                $value = wp_kses_post($_POST[$field]);
            } else {
                $value = sanitize_text_field($_POST[$field]);
            }
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post', 'sd_save_kit_settings_fields');
