<?php
function sd_add_business_cta_shortcode() {
    ob_start(); ?>
    
    <div class="sd-cta-wrapper">
        <div class="sd-cta-flex">
            <div class="sd-cta-left">
                <h2 class="sd-cta-title">Get Your Business Found Online</h2>
                <a href="https://bippermedia.com/add-network-business/" class="sd-btn-primary">Add Your Business</a>
            </div>
            <div class="sd-cta-right">
                <div class="sd-cta-feature"><span class="sd-checkmark">✔</span> Show up in local searches</div>
                <div class="sd-cta-feature"><span class="sd-checkmark">✔</span> Share business hours, address, and services</div>
                <div class="sd-cta-feature"><span class="sd-checkmark">✔</span> Boost visibility and gain more customers</div>
                <div class="sd-cta-feature"><span class="sd-checkmark">✔</span> Listing with instant approval</div>
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

        .sd-cta-flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
        }

        .sd-cta-left {
            flex: 1 1 300px;
        }

        .sd-cta-right {
            flex: 1 1 300px;
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
            .sd-cta-flex {
                flex-direction: column;
                text-align: center;
            }

            .sd-cta-left, .sd-cta-right {
                flex: 1 1 100%;
            }

            .sd-cta-button {
                margin-top: 10px;
            }
            .sd-cta-wrapper {
                padding: 1rem;
                margin-bottom: 0;
            }
        }
    </style>

    <?php
    return ob_get_clean();
}
add_shortcode('sd_add_business_cta', 'sd_add_business_cta_shortcode');
