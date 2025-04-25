<?php

    $categories         = get_the_terms(get_the_ID(), 'sd_business_category');
    $category           = sd_get_post_data('category') ?? '';
    $address            = sd_get_post_data('address') ?? '';
    $main_image         = sd_get_post_data('main_image') ?? '';
    $overall_rating     = sd_get_post_data('overall_rating') ?? '';
    $review_count       = sd_get_post_data('review_count') ?? '';

    // Replace dimensions in URL
    $image_width = wp_is_mobile() ? 360 : 360;
    $image_height = wp_is_mobile() ? 360 : 360;
    $main_image = preg_replace('/w\d+-h\d+/', "w{$image_width}-h{$image_height}", $main_image);

    $pin_icon = '<svg fill="#000000" width="15px" height="15px" viewBox="-0.96 -0.96 33.92 33.92" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="1.184"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.114-0.011c-6.559 0-12.114 5.587-12.114 12.204 0 6.93 6.439 14.017 10.77 18.998 0.017 0.020 0.717 0.797 1.579 0.797h0.076c0.863 0 1.558-0.777 1.575-0.797 4.064-4.672 10-12.377 10-18.998 0-6.618-4.333-12.204-11.886-12.204zM16.515 29.849c-0.035 0.035-0.086 0.074-0.131 0.107-0.046-0.032-0.096-0.072-0.133-0.107l-0.523-0.602c-4.106-4.71-9.729-11.161-9.729-17.055 0-5.532 4.632-10.205 10.114-10.205 6.829 0 9.886 5.125 9.886 10.205 0 4.474-3.192 10.416-9.485 17.657zM16.035 6.044c-3.313 0-6 2.686-6 6s2.687 6 6 6 6-2.687 6-6-2.686-6-6-6zM16.035 16.044c-2.206 0-4.046-1.838-4.046-4.044s1.794-4 4-4c2.207 0 4 1.794 4 4 0.001 2.206-1.747 4.044-3.954 4.044z"></path> </g></svg>';

?>


<article id="post-<?php the_ID(); ?>" <?php post_class('sd-archive-item'); ?>>

<div class="sd-archive-item-container">
    <div class="sd-archive-item-inner">
    
        <!-- thumbnail -->
        <div class="sd-archive-item-thumbnail">
            <?php if (has_post_thumbnail()) { ?>
            <a href="<?php the_permalink(); ?>" rel="noopener">
                <?php the_post_thumbnail('medium'); ?>
            </a>
            <?php } else { ?>
            <a href="<?php the_permalink(); ?>" rel="noopener">
                <img src="<?php echo esc_url($main_image); ?>" alt="<?php the_title(); ?>" />
            </a>
            <?php } ?>
            <?php if ($categories) : ?>
                <div class="sd-archive-item-category">
                    <a href="<?php echo esc_url(get_term_link($categories[0])); ?>" class="sd-archive-item-category-label"><?php echo esc_html($categories[0]->name); ?></a>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="sd-archive-item-content">
            <header class="sd-archive-item-header">

                <!-- title -->
                <h3 class="sd-archive-item-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                </h3>

                <!-- overall rating -->
                <!-- <span class="sd-overall-rating"> -->
                <?php if( $overall_rating) {
                    echo do_shortcode('[sd_overall_rating]');
                } ?>
                <!-- </span> -->

                <!-- address -->
                <?php if( $address) { ?>
                    <div class="sd-archive-item-address">
                    <?php
                        echo $pin_icon;
                        echo '<span>'.$address.'</span>';
                    ?>
                    </div>
                <?php } ?>

            </header>
            
        </div> <!-- sd-archive-item-content -->

    </div> <!-- sd-archive-item-inner -->

</div> <!-- sd-archive-item-container -->
</article>
