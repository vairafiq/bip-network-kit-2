<?php
function sd_add_business_cta_shortcode() {
    ob_start(); ?>
    
    <div class="sd-cta-wrapper">
        <div class="sd-cta-container">
            <div class="sd-cta-head">
                <h2 class="sd-cta-title">Get Your Business Found Online</h2>
            </div>
            <div class="sd-cta-features">
                <div class="sd-cta-feature"><span class="sd-checkmark">✔</span> Show up in local searches</div>
                <div class="sd-cta-feature"><span class="sd-checkmark">✔</span> Share business hours, address, and services</div>
                <div class="sd-cta-feature"><span class="sd-checkmark">✔</span> Boost visibility and gain more customers</div>
                <div class="sd-cta-feature"><span class="sd-checkmark">✔</span> Listing with instant approval</div>
            </div>
            <div class="sd-cta-button">
                <a href="https://bippermedia.com/add-network-business/" class="sd-btn-primary">Add Your Business</a>
            </div>
        </div>
    </div>

    <style>
        .sd-cta-wrapper {
            background-color: var(--lighter);
            padding: 80px 80px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            max-width: 1100px;
            margin: 60px auto;
        }

        .sd-cta-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-areas:
                "head features"
                "button features";
            align-items: start;
            gap: 30px;
        }

        .sd-cta-head {
            grid-area: head;
        }

        .sd-cta-features {
            grid-area: features;
        }

        .sd-cta-button {
            grid-area: button;
        }

        .sd-cta-title {
            text-align: left;
            font-size: var(--h2);
            font-weight: 600;
            color: var(--black);
            margin-bottom: 20px;
        }

        .sd-cta-feature {
            display: flex;
            align-items: start;
            gap: 10px;
            font-size: 16px;
            font-weight: 500;
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 10px;
            text-align: left;
        }

        .sd-checkmark {
            color: var(--green);
            font-weight: bold;
            font-size: 18px;
            line-height: 1;
        }

        @media (max-width: 768px) {
            .sd-cta-wrapper {
                padding: 1rem;
                margin-bottom: 0;
            }

            .sd-cta-container {
                grid-template-columns: 1fr;
                grid-template-areas:
                "head"
                "features"
                "button";
            }

            .sd-cta-button {
                margin-top: 20px;
            }

        }
    </style>

    <?php
    return ob_get_clean();
}
add_shortcode('sd_add_business_cta', 'sd_add_business_cta_shortcode');
