<?php echo do_shortcode( '[sd_single_banner]' ); ?>

<section>

    <div><?php echo do_shortcode( '[sd_single_features]' ); ?></div>
    
</section>


<section class="sdl-address-section">

    <div>
        <?php echo do_shortcode( '[sd_business_map_link]' ); ?>
        <?php echo do_shortcode( '[sd_single_map]' ); ?>
    </div>
    <div style="border:1px solid lightgray;text-align:center;padding-top:50px;">Business Hours</div>
    
</section>


<section class="sdl-review-section">

    <div><?php echo do_shortcode( '[sd_rating_progress]' ); ?></div>
    <div><?php echo do_shortcode( '[sd_google_reviews]' ); ?></div>
    
</section>


<section>

    <?php echo do_shortcode('[sd_businesses query="related" title="More from this category" description="Discover more businesses from this category to connect with trusted service providers and explore your options easily."]'); ?>

</section>