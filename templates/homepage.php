
<?php
$site_title = get_bloginfo('name') ?? '';
$sub_title = sd_get_kit('homepage_banner_subtitle') ?? '';
$intro = sd_get_kit('homepage_banner_intro') ?? '';
$bg_url = sd_get_kit('homepage_banner_bg') ?? '';
echo do_shortcode('[sd_header 
    image="'.$bg_url.'"
    heading="'.$site_title.'" 
    sub-heading="'.$sub_title.'" 
    description="'.$intro.'"]');
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
