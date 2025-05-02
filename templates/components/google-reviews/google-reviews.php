<?php

function sd_google_reviews_shortcode() {
    $post_id          = get_the_ID();
    $google_reviews   = get_post_meta($post_id, 'google_reviews', true) ?? '';
    
    if(empty($google_reviews)) {
        return 'Reviews not found!';
    }
    
    $google_reviews   = unserialize($google_reviews);
    $google_reviews   = $google_reviews['reviews'];

    ob_start();
    ?>
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . 'google-reviews.css'; ?>">
    <!-- CSS -->

    <div class="sd-google-reviews">
        <div class="sd-google-reviews-container">
            <div class="sd-google-review-list">

                <?php foreach( $google_reviews as $review ) :
                    
                    $name        = !empty($review['name']) ? $review['name'] : 'Unknown';
                    $date        = !empty($review['date']) ? $review['date'] : '';
                    $text        = !empty($review['text']) ? nl2br($review['text']) : '';
                    $rating_text = !empty($review['review_count']) ? $review['review_count'] : '';
                    $avatar_url  = !empty($review['avatar_url']) ? $review['avatar_url'] : 'https://images.unsplash.com/photo-1740252117012-bb53ad05e370?q=80&w=2500&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
                    $photos      = !empty($review['photos']) ? $review['photos'] : [];
                    $tag         = !empty($review['tag']) ? $review['tag'] : 'Google user';
                    $meta        = !empty($review['meta']) ? $review['meta'] : [];

                    preg_match('/Rated ([0-5](?:\.\d+)?) stars out of 5/', $rating_text, $matches);
                    $rating = !empty($matches[1]) ? (float) $matches[1] : 0;
                
                    ?>               
                    <div class="sd-google-review-item">
                        <div class="sd-google-review-head">
                            <div class="sd-google-review-avatar"><img src="<?php echo esc_url($avatar_url); ?>" alt="Review owner's avatar"></div>
                            <div class="sd-google-review-infos">
                                <div>      
                                    <span class="user-name"><?php echo esc_html($name); ?></span>                  
                                    <span class="user-tag"> - <?php echo esc_html($tag); ?></span>
                                </div>
                                <div>
                                    <span class="rating-star">
                                        <?php echo do_shortcode('[sd_rating_stars rating="'.$rating.'"]'); ?>
                                    </span>
                                    <span class="review-time"><?php echo esc_html($date); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="sd-google-review-text">
                            <?php echo esc_html($text); ?>
                        </div>
                        <div class="sd-google-review-meta">

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="<?php echo plugin_dir_url( __FILE__ ) . 'google-reviews.js'; ?>"></script>
    <!-- JS -->
    <?php
    return ob_get_clean();
}
add_shortcode( 'sd_google_reviews', 'sd_google_reviews_shortcode' );