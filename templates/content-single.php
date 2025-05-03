<div><?php echo do_shortcode( '[sd_breadcrumb]' ); ?></div>
<?php echo do_shortcode( '[sd_single_banner]' ); ?>

<section>

    <div><?php echo do_shortcode( '[sd_single_features]' ); ?></div>
    
</section>


<section class="sdl-map-hours-section">

    <div><?php echo do_shortcode( '[sd_single_map]' ); ?></div>
    <div><?php echo do_shortcode( '[sd_business_hours]' ); ?></div>
    
</section>


<section class="sdl-review-section">

    <!-- left -->
    <div>
        <div class="sdl-review-sidebar">
            <div><?php echo do_shortcode( '[sd_rating_progress]' ); ?></div>
            <br>
            <br>
            <div><?php echo do_shortcode( '[sd_faq_accordion]' ); ?></div>
        </div>
    </div>

    <!-- right -->
    <div>
        <div><?php echo do_shortcode( '[sd_review_summary]' ); ?></div>
        <br>
        <br>
        <div><?php echo do_shortcode( '[sd_google_reviews]' ); ?></div>
    </div>
    
</section>


<section>

    <?php echo do_shortcode('[sd_businesses query="related" title="More from this category" description="Discover more businesses from this category to connect with trusted service providers and explore your options easily."]'); ?>

</section>