<?php
/**
 * Plugin Header Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title(); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <?php wp_head(); // This is important to include all the necessary styles and scripts ?>
</head>
<body <?php body_class(); ?>>

<header class="sd-header">
    <div class="sd-header-container header-single">
        <div class="sd-header-inner">
            <!-- Site Logo -->
            <div class="sd-logo">
                <a href="<?php echo home_url(); ?>" rel="home">
                    <span class="site-name"><?php bloginfo('name'); ?></span>
                </a>
            </div>

            <!-- Main Navigation -->
            <nav class="sd-navigation">

                <button class="sd-menu-toggle">
                    <i class="fa fa-bars"></i>
                </button>

                <ul class="sd-menu">
                    <button class="sd-menu-toggle-close">
                        <i class="fa fa-times"></i>
                    </button>

                    <li class="sd-menu-item-wrapper">
                        <span class="sd-menu-item">
                            <a href="<?php echo esc_url(home_url('/')); ?>">
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
                            <a href="<?php echo esc_url(home_url('/biz/')); ?>">
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

                </ul>
            </nav>
            <!-- Main Navigation -->

        </div> <!-- sd-header-inner -->
    </div> <!-- sd-header-container -->

</header>
