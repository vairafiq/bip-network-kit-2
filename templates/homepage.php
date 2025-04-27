
<?php
$site_title = get_bloginfo('name');
echo do_shortcode('[sd_header 
    image="https://localnearmedirectory.com/wp-content/uploads/2025/04/banner2-scaled.webp"
    heading="'.$site_title.'" 
    sub-heading="Explore the Best Local Businesses in your area" 
    description="Your trusted online guide to discovering the best local businesses around you. Whether you are in the heart of Austin or just exploring, we help you find top-rated services, restaurants, shops, and more all in one place, tailored to your needs."]');
?>


<main class="sd-home">
    <section class="sd-container">

        <div class="sd-home-page-container">

            <div class="sd-home-wrapper">

                <?php include plugin_dir_path(__FILE__) . '/content-home.php'; ?>

            </div> <!-- sd-single-wrapper -->

        </div> <!-- sd-single-page-container -->

    </section>
</main>        

<?php include plugin_dir_path(__FILE__) . 'footer.php'; ?>
