<?php
/**
 * Shortcode to display a single business banner
 */

function sd_single_banner_shortcode() {
    $business = sd_get_post_data();
    
    $content = get_the_content();
    $categories         = get_the_terms(get_the_ID(), 'sd_business_category');
    $category           = $business['category'] ?? '';
    $phone              = $business['phone'] ?? '';
    $email              = $business['email'] ?? '';
    $website            = $business['website'] ?? '';
    $address            = $business['address'] ?? '';
    $latitude           = $business['latitude'] ?? '';
    $longitude          = $business['longitude'] ?? '';
    $price_range        = $business['price_range'] ?? '';
    $main_image         = $business['main_image'] ?? '';
    $overall_rating     = $business['overall_rating'] ?? '';
    $review_count       = $business['review_count'] ?? '';
    $review_details     = $business['review_details'] ?? '';
    $features           = $business['features'] ?? '';
    $business_hours     = $business['business_hours'] ?? '';
    $google_images      = $business['google_images'] ?? '';
    $facebook           = $business['facebook'] ?? '';
    $x                  = $business['x'] ?? '';
    $linkedin           = $business['linkedin'] ?? '';
    $youtube            = $business['youtube'] ?? '';


    // Replace dimensions in URL
    $image_width = wp_is_mobile() ? 360 : 800;
    $image_height = wp_is_mobile() ? 360 : 600;
    $main_image = preg_replace('/w\d+-h\d+/', "w{$image_width}-h{$image_height}", $main_image);

    $pin_icon = '<svg fill="#000000" width="15px" height="15px" viewBox="-0.96 -0.96 33.92 33.92" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="1.184"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.114-0.011c-6.559 0-12.114 5.587-12.114 12.204 0 6.93 6.439 14.017 10.77 18.998 0.017 0.020 0.717 0.797 1.579 0.797h0.076c0.863 0 1.558-0.777 1.575-0.797 4.064-4.672 10-12.377 10-18.998 0-6.618-4.333-12.204-11.886-12.204zM16.515 29.849c-0.035 0.035-0.086 0.074-0.131 0.107-0.046-0.032-0.096-0.072-0.133-0.107l-0.523-0.602c-4.106-4.71-9.729-11.161-9.729-17.055 0-5.532 4.632-10.205 10.114-10.205 6.829 0 9.886 5.125 9.886 10.205 0 4.474-3.192 10.416-9.485 17.657zM16.035 6.044c-3.313 0-6 2.686-6 6s2.687 6 6 6 6-2.687 6-6-2.686-6-6-6zM16.035 16.044c-2.206 0-4.046-1.838-4.046-4.044s1.794-4 4-4c2.207 0 4 1.794 4 4 0.001 2.206-1.747 4.044-3.954 4.044z"></path> </g></svg>';
    $email_icon = '<svg height="15px" width="15px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-20.48 -20.48 552.96 552.96" xml:space="preserve" fill="#000000" stroke="#000000" stroke-width="18.432"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:#000000;} </style> <g> <path class="st0" d="M510.746,110.361c-2.128-10.754-6.926-20.918-13.926-29.463c-1.422-1.794-2.909-3.39-4.535-5.009 c-12.454-12.52-29.778-19.701-47.531-19.701H67.244c-17.951,0-34.834,7-47.539,19.708c-1.608,1.604-3.099,3.216-4.575,5.067 c-6.97,8.509-11.747,18.659-13.824,29.428C0.438,114.62,0,119.002,0,123.435v265.137c0,9.224,1.874,18.206,5.589,26.745 c3.215,7.583,8.093,14.772,14.112,20.788c1.516,1.509,3.022,2.901,4.63,4.258c12.034,9.966,27.272,15.45,42.913,15.45h377.51 c15.742,0,30.965-5.505,42.967-15.56c1.604-1.298,3.091-2.661,4.578-4.148c5.818-5.812,10.442-12.49,13.766-19.854l0.438-1.05 c3.646-8.377,5.497-17.33,5.497-26.628V123.435C512,119.06,511.578,114.649,510.746,110.361z M34.823,99.104 c0.951-1.392,2.165-2.821,3.714-4.382c7.689-7.685,17.886-11.914,28.706-11.914h377.51c10.915,0,21.115,4.236,28.719,11.929 c1.313,1.327,2.567,2.8,3.661,4.272l2.887,3.88l-201.5,175.616c-6.212,5.446-14.21,8.443-22.523,8.443 c-8.231,0-16.222-2.99-22.508-8.436L32.19,102.939L34.823,99.104z M26.755,390.913c-0.109-0.722-0.134-1.524-0.134-2.341V128.925 l156.37,136.411L28.199,400.297L26.755,390.913z M464.899,423.84c-6.052,3.492-13.022,5.344-20.145,5.344H67.244 c-7.127,0-14.094-1.852-20.142-5.344l-6.328-3.668l159.936-139.379l17.528,15.246c10.514,9.128,23.922,14.16,37.761,14.16 c13.89,0,27.32-5.032,37.827-14.16l17.521-15.253L471.228,420.18L464.899,423.84z M485.372,388.572 c0,0.803-0.015,1.597-0.116,2.304l-1.386,9.472L329.012,265.409l156.36-136.418V388.572z"></path> </g> </g></svg>';
    $link_icon = '<svg width="15px" height="15px" viewBox="0 0 24.00 24.00" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>External-Link</title> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="External-Link"> <rect id="Rectangle" fill-rule="nonzero" x="0" y="0" width="24" height="24"> </rect> <path d="M20,12 L20,18 C20,19.1046 19.1046,20 18,20 L6,20 C4.89543,20 4,19.1046 4,18 L4,6 C4,4.89543 4.89543,4 6,4 L12,4" id="Path" stroke="#0C0310" stroke-width="2" stroke-linecap="round"> </path> <path d="M16,4 L19,4 C19.5523,4 20,4.44772 20,5 L20,8" id="Path" stroke="#0C0310" stroke-width="2" stroke-linecap="round"> </path> <line x1="11" y1="13" x2="19" y2="5" id="Path" stroke="#0C0310" stroke-width="2" stroke-linecap="round"> </line> </g> </g> </g></svg>';
    $phone_icon = '<svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M14.05 6C15.0268 6.19057 15.9244 6.66826 16.6281 7.37194C17.3318 8.07561 17.8095 8.97326 18 9.95M14.05 2C16.0793 2.22544 17.9716 3.13417 19.4163 4.57701C20.8609 6.01984 21.7721 7.91101 22 9.94M18.5 21C9.93959 21 3 14.0604 3 5.5C3 5.11378 3.01413 4.73086 3.04189 4.35173C3.07375 3.91662 3.08968 3.69907 3.2037 3.50103C3.29814 3.33701 3.4655 3.18146 3.63598 3.09925C3.84181 3 4.08188 3 4.56201 3H7.37932C7.78308 3 7.98496 3 8.15802 3.06645C8.31089 3.12515 8.44701 3.22049 8.55442 3.3441C8.67601 3.48403 8.745 3.67376 8.88299 4.05321L10.0491 7.26005C10.2096 7.70153 10.2899 7.92227 10.2763 8.1317C10.2643 8.31637 10.2012 8.49408 10.0942 8.64506C9.97286 8.81628 9.77145 8.93713 9.36863 9.17882L8 10C9.2019 12.6489 11.3501 14.7999 14 16L14.8212 14.6314C15.0629 14.2285 15.1837 14.0271 15.3549 13.9058C15.5059 13.7988 15.6836 13.7357 15.8683 13.7237C16.0777 13.7101 16.2985 13.7904 16.74 13.9509L19.9468 15.117C20.3262 15.255 20.516 15.324 20.6559 15.4456C20.7795 15.553 20.8749 15.6891 20.9335 15.842C21 16.015 21 16.2169 21 16.6207V19.438C21 19.9181 21 20.1582 20.9007 20.364C20.8185 20.5345 20.663 20.7019 20.499 20.7963C20.3009 20.9103 20.0834 20.9262 19.6483 20.9581C19.2691 20.9859 18.8862 21 18.5 21Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>';

    ob_start();
    ?>
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . 'single-banner.css'; ?>">
    <!-- CSS -->
    

    <div class="sd-single-banner">
        <div class="sd-single-banner-container">
            <div class="sd-single-banner-inner">

                <!-- banner head -->
                <div class="sd-single-banner-head">
                    <div class="sd-single-banner-head-inner">

                        <!-- title -->
                        <h1 class="sd-single-banner-title">
                            <?php the_title(); ?>
                        </h1>

                        <!-- head info container -->
                        <div class="sd-single-banner-head-info-container">
                            
                            <!-- head infos -->
                            <div class="sd-single-banner-head-infos">
                                <!-- overall rating -->
                                <!-- <span class="sd-overall-rating"> -->
                                <?php if( $overall_rating) {
                                    echo do_shortcode('[sd_overall_rating]');
                                } ?>
                                <!-- </span> -->

                                <!-- price range -->
                                <?php if( $price_range) { ?>
                                    <span class="sd-single-banner-price-range">
                                        <span class="sd-divider-dot"></span>
                                        <?php echo $price_range; ?>
                                    </span>
                                <?php } ?>
                                

                                <!-- categories -->
                                <?php if ( !empty( $categories ) ) : ?>
                                <div class="sd-single-banner-categories">
                                    <span class="sd-divider-dot"></span>
                                    <?php foreach ( $categories as $category ) : ?>
                                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="sd-single-banner-category-label"><?php echo esc_html( $category->name ); ?></a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>

                                <!-- social icons -->
                                <div class="sd-single-banner-head-social">
                                    <?php if ( $facebook ) : ?>
                                        <a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener" aira-label="Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( $x ) : ?>
                                        <a href="<?php echo esc_url( $x ); ?>" target="_blank" rel="noopener" aira-label="X - Twitter">
                                            <i class="fab fa-x"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( $linkedin ) : ?>
                                        <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener" aira-label="Linkdin">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ( $youtube ) : ?>
                                        <a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener" aira-label="Youtube">
                                            <i class="fab fa-youtube"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- head infos -->

                            <!-- head social -->
                            
                            <!-- head social -->

                        </div> <!-- head info container -->

                    </div> <!-- sd-single-banner-head-inner -->
                </div>
                <!-- banner head -->

                <!-- banner content -->
                <div class="sd-single-banner-content">

                    <!-- banner details -->
                    <div class="sd-single-banner-details">
                        <div class="sd-single-banner-details-inner">
                            
                        <?php
                        if ( ! empty( $content ) ) :
                            $content = wp_strip_all_tags( $content ); // Remove HTML tags
                            $content = preg_replace( '/\s+/', ' ', $content ); // Remove extra spaces
                            $words = explode( ' ',  $content );
                            $short_content = implode( ' ', array_slice( $words, 0, 45 ) );
                            ?>
                            <div class="sd-single-banner-details-content">
                                <h2 class="sd-single-banner-details-title">About</h2>
                                <div class="sd-single-banner-details-text">
                                    <p class="short-content"><?php echo wp_kses_post($short_content) . '...'; ?></p>
                                    <p class="full-content" style="display:none;"><?php echo wp_kses_post($content); ?></p>
                                    <a href="#" class="read-more-toggle" style="color:#0073aa;">Read More</a>
        
                                    <span class="sd-devider-line"></span>

                                </div>
                            </div>

                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                            jQuery(document).ready(function($) {
                                $('.read-more-toggle').on('click', function(e) {
                                    e.preventDefault();
                                    var container = $(this).closest('.sd-single-banner-details-text');
                                    container.find('.short-content, .full-content').toggle();
                                    var isExpanded = container.find('.full-content').is(':visible');
                                    $(this).text(isExpanded ? 'Read Less' : 'Read More');
                                });
                            });
                            </script>
                        <?php endif; ?>
                            
                            <!-- sd-single-banner-details-contact -->
                            <div class="sd-single-banner-details-contact">
                                <?php if ( false ) : ?>
                                <div class="sd-single-banner-address">
                                    <?php echo $pin_icon; ?>
                                    <span><?php echo esc_html( $address ); ?></span>
                                </div>
                                <?php endif; ?>

                                <?php if ( $phone ) : ?>
                                <div class="sd-single-banner-phone">
                                    <?php echo $phone_icon; ?>
                                    <a href="tel:<?php echo esc_html( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                                </div>
                                <?php endif; ?>

                                <?php if ( false ) : ?>
                                <div class="sd-single-banner-email" style="display:none !important;">
                                    <?php echo $email_icon; ?>
                                    <a href="mailto:<?php echo esc_html( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                </div>
                                <?php endif; ?>

                                <?php if ( $website ) : ?>
                                <div class="sd-single-banner-website">
                                    <?php echo $link_icon; ?>
                                    <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $website ); ?></a>
                                </div>
                                <?php endif; ?>

                            </div>
                            <!-- sd-single-banner-details-contact -->

                        </div>
                    </div>

                    <!-- banner image -->
                    <div class="sd-single-banner-image">
                        <?php if (has_post_thumbnail()) { ?>
                            <?php the_post_thumbnail('medium'); ?>
                        <?php } else { ?>
                            <img src="<?php echo esc_url($main_image); ?>" alt="<?php the_title(); ?>" />
                        <?php } ?>
                    </div>
                    <!-- banner image -->
                    
                </div> 
                <!-- banner content -->


            
                
            </div> <!-- sd-single-banner-inner -->
        </div> <!-- sd-single-banner-container -->
    </div> <!-- sd-single-banner -->


    <!-- JS -->
    <script src="<?php echo plugin_dir_url( __FILE__ ) . 'single-banner.js'; ?>"></script>
    <!-- JS -->

    <?php
    return ob_get_clean();
}
add_shortcode( 'sd_single_banner', 'sd_single_banner_shortcode' );