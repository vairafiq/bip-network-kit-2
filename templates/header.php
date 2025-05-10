<?php
/**
 * Plugin Header Template
 */
function sd_header_shortcode($atts) {

    $atts = shortcode_atts([
        'image' => '',
        'heading' => '',
        'sub-heading' => '',
        'meta-title' => '',
        'meta-description' => '',
    ], $atts, 'sd_header');



    ob_start(); ?>

    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $atts['meta-title']; ?></title>
        <meta name="description" content="<?php echo $atts['meta-description']; ?>"/>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
        <?php wp_head(); // This is important to include all the necessary styles and scripts ?>
    </head>
    <body <?php body_class(); ?>>

    <header class="sd-header">
        <div class="sd-header-container">
            <div class="sd-header-inner">
                <!-- Site Logo -->
                <div class="sd-logo">
                    <a href="<?php echo home_url(); ?>" rel="home" aria-label="Go homepage">
                        <span class="site-name"><?php bloginfo('name'); ?></span>
                    </a>
                </div>

                <!-- Main Navigation -->
                <nav class="sd-navigation">

                    <button class="sd-menu-toggle" aria-label="Mobile menu open">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="sd-menu">
                        <button class="sd-menu-toggle-close" aria-label="Mobile menu close">
                            <i class="fa fa-times"></i>
                        </button>

                        <li class="sd-menu-item-wrapper">
                            <span class="sd-menu-item">
                                <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="Go homepage">
                                    Home
                                </a>
                                
                            <!-- <span class="sd-submenu-toggle" aria-label="Toggle submenu">
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            </span> -->
                        
                            </span>
                            
                            <!-- submenu -->
                            <!-- <ul class="sd-submenu">
                                <li class="sd-submenu-item">
                                    <a href="#">
                                        submenu item
                                    </a>
                                </li>
                            </ul> -->
                
                        </li>
                        <li class="sd-menu-item-wrapper">
                            <span class="sd-menu-item">
                                <a href="<?php echo esc_url(home_url('/biz/')); ?>" aria-label="All businesses">
                                    Businesses
                                </a>
                                
                            <!-- <span class="sd-submenu-toggle" aria-label="Toggle submenu">
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            </span> -->
                        
                            </span>
                            
                            <!-- submenu -->
                            <!-- <ul class="sd-submenu">
                                <li class="sd-submenu-item">
                                    <a href="#">
                                        submenu item
                                    </a>
                                </li>
                            </ul> -->
                
                        </li>
                        <li class="sd-menu-item-wrapper">
                            <span class="sd-menu-item">
                                <a href="https://bippermedia.com/local-citations/" class="sd-btn-secondary" aria-label="Add your business">
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Add Your Business
                                </a>
                                
                            <!-- <span class="sd-submenu-toggle" aria-label="Toggle submenu">
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            </span> -->
                        
                            </span>
                            
                            <!-- submenu -->
                            <!-- <ul class="sd-submenu">
                                <li class="sd-submenu-item">
                                    <a href="#">
                                        submenu item
                                    </a>
                                </li>
                            </ul> -->
                
                        </li>

                    </ul>
                </nav>
                <!-- Main Navigation -->

            </div> <!-- sd-header-inner -->
        </div> <!-- sd-header-container -->

        <!-- Page Banner -->
        <?php
            $image = $atts['image'];
            $heading = $atts['heading'];
            $sub_heading = $atts['sub-heading'];
            
            echo do_shortcode('[sd_page_banner 
            image="'.$image.'"
            heading="'.$heading.'" 
            sub-heading="'.$sub_heading.'"]');
        ?>
    </header>

    <?php return ob_get_clean();
}
add_shortcode('sd_header', 'sd_header_shortcode');