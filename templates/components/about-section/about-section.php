<?php


function sd_about_section_shorcode() {
    ob_start();
    ?>

    <div class="sd-about-container">
        <div class="sd-about-inner">
            <div class="sd-about-details">
                <h2 class="sd-about-title">Why Choose Us?</h2>
                <span class="sd-about-subtitle">Local Expertise, Global Reach</span>
                <p class="sd-about-text">We specialize in understanding the unique dynamics of local business environments. While our focus is rooted in community-driven solutions, we extend our services to businesses worldwide, helping them thrive and make an impact in their respective regions.</p>
                <span class="sd-about-subtitle">Customized Solutions for Your Growth</span>
                <p class="sd-about-text">Every business has its own goals and challenges, which is why we offer personalized services tailored to your exact needs. Whether you're a neighborhood café or an international brand, our platform is here to enhance your visibility and success in your local market.</p>
            </div>
            <div class="sd-about-image">
                <img src="https://localnearmedirectory.com/wp-content/uploads/2025/04/map-with-markers.webp" alt="map with markers">
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
        .sd-about-subtitle {
            font-size: var(--h3);
            font-weight: var(--h2-weight);
        }
        .sd-about-text {
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