<?php
/**
 * Plugin Footer Template
 */
?>
<footer class="sd-footer">
    <div class="sd-footer-inner">
        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
        
        <!-- Footer Navigation -->
        <nav class="sd-footer-navigation">
            <?php 
            wp_nav_menu( array(
                'theme_location' => 'footer', // Ensure you've registered this menu in functions.php
                'menu_class' => 'sd-footer-menu',
                'container' => false,
            ) ); 
            ?>
        </nav>
    </div>
</footer>

<?php wp_footer(); // This is important to include any necessary scripts or resources ?>
</body>
</html>
