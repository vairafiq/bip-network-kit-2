
<?php
$site_title = get_bloginfo('name') ?? '';
$sub_title = sd_get_kit('archive_banner_subtitle') ?? '';
$bg_url = sd_get_kit('otherpage_banner_bg') ?? '';
echo do_shortcode('[sd_header 
    image="'.$bg_url.'"
    heading="'.$site_title.'" 
    sub-heading="'.$sub_title.'" 
    description=""]');
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
                    <div class="ad-archive-title">
                        <?php echo do_shortcode( '[sd_breadcrumb]' ); ?>
                    </div>
                    <div class="ad-archive-title">
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
                </div>
                
                <div style="padding: 20px 0;">
                        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4744853284629324"
                        crossorigin="anonymous"></script>
                    <!-- Network Arch -->
                    <ins class="adsbygoogle"
                        style="display:block"
                        data-ad-client="ca-pub-4744853284629324"
                        data-ad-slot="8123723940"
                        data-ad-format="auto"
                        data-full-width-responsive="true"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
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
