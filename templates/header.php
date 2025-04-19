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

    <?php wp_head(); // This is important to include all the necessary styles and scripts ?>
</head>
<body <?php body_class(); ?>>

<header class="sd-header">
    <div class="sd-header-inner">
        <!-- Site Logo -->
        <div class="sd-logo">
            <a href="<?php echo home_url(); ?>" rel="home">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <h1><?php bloginfo('name'); ?></h1>
                <?php endif; ?>
            </a>
        </div>

        <!-- Main Navigation -->
        <nav class="sd-navigation">
            <?php 
            wp_nav_menu( array(
                'theme_location' => 'primary', // Ensure you've registered this menu in functions.php
                'menu_class' => 'sd-menu',
                'container' => false,
            ) ); 
            ?>
        </nav>
    </div>
</header>
