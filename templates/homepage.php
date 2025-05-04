
<?php
$title = sd_get_kit('homepage_banner_title') ?? '';
$title = !empty($title) ? $title : get_bloginfo('name');
$description = sd_get_kit('homepage_banner_description') ?? '';
$bg_url = sd_get_kit('homepage_banner_bg') ?? '';

$meta_title = sd_get_kit('homepage_meta_title') ?? '';
$meta_description = sd_get_kit('homepage_meta_description') ?? '';

echo do_shortcode('[sd_header 
    image="'.$bg_url.'"
    heading="'.$title.'" 
    sub-heading="'.$description.'"
    meta-title="'.$meta_title.'"
    meta-description="'.$meta_description.'"]');
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
