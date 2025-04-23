<?php
function sd_page_banner_shortcode($atts) {
    $atts = shortcode_atts([
        'image'       => 'https://localnearmedirectory.com/wp-content/uploads/2025/04/banner-scaled.webp',
        'heading'     => '',
        'sub-heading' => '',
        'description' => '',
    ], $atts, 'sd_page_banner');

    ob_start();
    ?>
    <div class="sd-page-banner" style="background-image: url('<?php echo esc_url($atts['image']); ?>'); background-size: cover; background-position: center; background-attachment: scroll; color: white; text-align: center; position: relative;">
        <div class="sd-page-banner-container" style="background: rgba(0,0,0,0.6); display: inline-block; height: 100%; width: 100%;">
            <div class="sd-page-banner-inner">
                <?php if (!empty($atts['heading'])) : ?>
                    <h1 class="sd-banner-heading" style="margin-bottom: 10px;"><?php echo esc_html($atts['heading']); ?></h1>
                <?php endif; ?>
                <?php if (!empty($atts['sub-heading'])) : ?>
                    <span class="sd-banner-sub-heading" style="margin-bottom: 20px; display:inline-block;"><?php echo esc_html($atts['sub-heading']); ?></span>
                <?php endif; ?>
                <?php if (!empty($atts['description'])) : ?>
                    <p class="sd-banner-description"><?php echo esc_html($atts['description']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .sd-page-banner-container {
            padding: 158px 1rem 70px 1rem;
        }
        .sd-page-banner-inner {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        .sd-banner-heading {
            font-size: 4rem;
            font-weight: bold;
        }
        .sd-banner-sub-heading {
            font-size: 1.5rem;
            font-weight: 300;
        }
        .sd-banner-sub-heading {
            font-size: 1.5rem;
            font-weight: 300;
        }
        .sd-banner-description {
            font-size: 1rem;
            font-weight: 300;
        }
        @media (max-width: 768px) {
            .sd-page-banner-container {
                padding: 80px 1rem;
            }
            .sd-banner-heading {
                font-size: 3rem;
                font-weight: 600;
            }
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('sd_page_banner', 'sd_page_banner_shortcode');
