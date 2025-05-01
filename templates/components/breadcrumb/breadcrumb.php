<?php
function sd_business_breadcrumb() {
    if (is_front_page()) return;

    ob_start(); ?>
    <nav class="sd-breadcrumb">
        <a href="<?php echo home_url(); ?>">Home</a>
        <span class="sep">/</span>

        <?php if (is_post_type_archive('sd_business')) : ?>
            <span class="current">Businesses</span>

        <?php elseif (is_singular('sd_business')) : ?>
            <a href="<?php echo get_post_type_archive_link('sd_business'); ?>">Businesses</a>
            <span class="sep">/</span>
            <span class="current"><?php the_title(); ?></span>

        <?php elseif (is_tax()) : ?>
            <?php
            $term = get_queried_object();
            $post_type = 'sd_business';
            ?>
            <a href="<?php echo get_post_type_archive_link($post_type); ?>">Businesses</a>
            <span class="sep">/</span>
            <span class="current"><?php echo esc_html($term->name); ?></span>
        <?php endif; ?>
    </nav>

    <style>
        .sd-breadcrumb {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .sd-breadcrumb a {
            color: var(--gray) !important;
            text-decoration: none;
        }

        .sd-breadcrumb .sep {
            margin: 0 5px;
            color: var(--gray);
        }

        .sd-breadcrumb .current {
            color: var(--black);
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('sd_breadcrumb', 'sd_business_breadcrumb');
