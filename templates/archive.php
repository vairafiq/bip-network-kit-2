
<?php
$site_title = get_bloginfo('name');
echo do_shortcode('[sd_header 
    image="https://localnearmedirectory.com/wp-content/uploads/2025/04/banner2-scaled.webp"
    heading="Best Local Businesses" 
    sub-heading="Explore the Best Local Businesses in your area" 
    description="Your trusted online guide to discovering the best local businesses around you. Whether you are in the heart of Austin or just exploring, we help you find top-rated services, restaurants, shops, and more all in one place, tailored to your needs."]');
?>


<main class="sd-archive">
    <section class="sd-container">

        <div class="sd-archive-page-container">

            <div class="sd-sidebar">
                <?php echo do_shortcode('[sd_taxonomy_links]'); ?>
            </div>

            <div class="sd-archive-wrapper">

                <!-- Archive header -->
                <div class="sd-archive-header">
                    <?php
                    if (is_tax('sd_business_category')) {
                        echo '<h2 class="sd-archive-title">' . single_term_title('', false) . 's</h2>';
                    } elseif (is_tax('sd_business_location')) {
                        echo '<h2 class="sd-archive-title">Businesses in ' . single_term_title('', false) . '</h2>';
                    } elseif (is_post_type_archive('sd_business')) {
                        echo '<h2 class="sd-archive-title">' . post_type_archive_title('', false) . '</h2>';
                    } elseif (is_search()) {
                        echo '<h2 class="sd-archive-title">Search results for: ' . get_search_query() . '</h2>';
                    } else {
                        echo '<h2 class="sd-archive-title">' . get_the_archive_title() . '</h2>';
                    }
                    ?>
                </div>

                <!-- Business list starts here -->
                <div class="sd-business-list">
                    <?php if (have_posts()) : ?>
                        <?php while (have_posts()) : the_post(); ?>
                            <?php include plugin_dir_path(__FILE__) . '/content-archive.php'; ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p>No Business found.</p>
                    <?php endif; ?>
                </div>

                <div class="sd-archive-footer">
                    <!-- Pagination -->
                    <div class="sd-archive-pagination">
                        <?php
                        the_posts_pagination([
                            'mid_size'  => 2,
                            'prev_text' => '<i class="fas fa-chevron-left"></i>',
                            'next_text' => '<i class="fas fa-chevron-right"></i>',
                            'before_page_number' => '<span class="sd-page-number">',
                            'after_page_number' => '</span>',
                        ]);
                        ?>
                    </div>
                </div> <!-- sd-archive-footer -->

            </div> <!-- sd-archive-wrapper -->
        </div> <!-- sd-archive-page-container -->
    
    </section>
</main>

<?php include plugin_dir_path(__FILE__) . '/footer.php'; ?>
