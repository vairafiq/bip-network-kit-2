<?php
    
    $business = sd_get_business_fields( get_the_ID() );

    $categories         = get_the_terms(get_the_ID(), 'business_category');
    $category           = $business['category'] ?? '';
    $phone              = $business['phone'] ?? '';
    $email              = $business['email'] ?? '';
    $website            = $business['website'] ?? '';
    $zip                = $business['zip'] ?? '';
    $city               = $business['city'] ?? '';
    $state              = $business['state'] ?? '';
    $country            = $business['country'] ?? '';
    $street             = $business['street'] ?? '';
    $address            = $business['address'] ?? '';
    $business_address   = $business['business_address'] ?? '';
    $latitude           = $business['latitude'] ?? '';
    $longitude          = $business['longitude'] ?? '';
    $price_range        = $business['price_range'] ?? '';
    $main_image         = $business['main_image'] ?? '';
    $overall_rating     = $business['overall_rating'] ?? '';
    $review_count       = $business['review_count'] ?? '';
    $review_details     = $business['review_details'] ?? '';
    $features           = $business['features'] ?? '';
    $business_hours     = $business['business_hours'] ?? '';
    $review_summary     = $business['review_summary'] ?? '';
    $google_id          = $business['google_id'] ?? '';
    $google_reviews     = $business['google_reviews'] ?? '';
    $google_images      = $business['google_images'] ?? '';
    $facebook           = $business['facebook'] ?? '';
    $x                  = $business['x'] ?? '';
    $linkedin           = $business['linkedin'] ?? '';
    $youtube            = $business['youtube'] ?? '';



    // prepare google images
    $google_images = json_decode( $google_images );
    $image_urls = [];

    if ( ! empty( $google_images ) ) {
        foreach ($google_images as $subArray) {
            if ( is_string( $subArray ) ) {
                $image_urls[] = $subArray;
            } else {
                foreach ($subArray as $singleImageURL) {
                    $image_urls[] = is_string( $singleImageURL ) ? $singleImageURL : $singleImageURL->url;
                }
            }
        }
    }

    // Validate and set main_image
    if ( ! empty( $main_image ) && @fopen( $main_image, 'r' ) ) {
        $image_urls[] = $main_image;
    } elseif ( ! empty( $image_urls[0] ) && @fopen( $image_urls[0], 'r' ) ) {
        $main_image = $image_urls[0];
        update_post_meta( $post_id, 'main_image', $main_image );
    } elseif ( ! empty( $image_urls[1] ) && @fopen( $image_urls[1], 'r' ) ) {
        $main_image = $image_urls[1];
        update_post_meta( $post_id, 'main_image', $main_image );
    } elseif ( ! empty( $image_urls[2] ) && @fopen( $image_urls[2], 'r' ) ) {
        $main_image = $image_urls[2];
        update_post_meta( $post_id, 'main_image', $main_image );
    } else {
        $main_image = 'https://bippermedia.com/wp-content/uploads/2024/04/working-2023-11-27-04-57-54-utc-scaled.jpg';
    }


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
