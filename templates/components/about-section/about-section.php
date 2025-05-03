<?php


function sd_about_section_shorcode() {
    $about_content = '
    <p>We are a team of passionate individuals dedicated to providing the best service possible. Our mission is to deliver high-quality products and services that exceed our customers\' expectations.</p>
    <p>With years of experience in the industry, we have honed our skills and expertise to ensure that we can meet the diverse needs of our clients. We believe in building long-lasting relationships with our customers based on trust, transparency, and mutual respect.</p>
    <p>Our team is committed to continuous improvement and innovation, always striving to stay ahead of the curve and provide the latest solutions to our clients. We take pride in our work and are dedicated to delivering exceptional results.</p>
    ';
    $about_content = !empty(sd_get_kit('about_content')) ? sd_get_kit('about_content') : $about_content;
    $about_image = !empty(sd_get_kit('about_image')) ? sd_get_kit('about_image') : SD_PLUGIN_URL . 'assets/images/about.webp';
    ob_start();
    ?>

    <div class="sd-about-container">
        <div class="sd-about-inner">
            <div class="sd-about-details">
                <h2 class="sd-about-title">Why Choose Us?</h2>
                <div class="sd-about-text">
                    <?php echo apply_filters('the_content', $about_content); ?>
                </div>
            </div>
            <div class="sd-about-image">
                <img src="<?php echo esc_url($about_image); ?>" alt="About us banner">
            </div>
        </div>
    </div>
    <style>
        
        .sd-about-inner {
            display: grid;
            grid-template-columns: 50% calc(50% - 2rem);
            gap: 2rem;
            justify-content: space-between;
        }
        .sd-about-text:not(strong, h2, h3, h4, h5) {
            font-size: var(--p);
            font-weight: var(--p-weight);
        }
        .sd-about-image img{
            border-radius: 10px;
            width: 100%;
            max-height: 400px;
            object-fit: cover;
        }
        @media (max-width: 768px) {
            .sd-about-inner {
                grid-template-columns: auto;
                gap: 1rem;
            }
        }
    </style>
    <?php return ob_get_clean();
}
add_shortcode('sd_about_section', 'sd_about_section_shorcode');