
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

            <div class="sd-sidebar" style="border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;">
                Filter
            </div>

            <div class="sd-archive-wrapper">

                <!-- Archive header -->
                <div class="sd-archive-header">

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

            </div> <!-- sd-archive-wrapper -->
        </div> <!-- sd-archive-page-container -->
    
    </section>
</main>

<?php include plugin_dir_path(__FILE__) . '/footer.php'; ?>
