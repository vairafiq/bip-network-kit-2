<?php
/**
 * Shortcode: FAQ Accordion
 * 
 * Displays an accordion of FAQs from post meta key 'faq_data'.
 * Falls back to a default set of FAQs when meta is empty.
 *
 * Usage: [faq_accordion]
 */

function sd_faq_accordion_shortcode($atts) {
    $faqs_raw = get_post_meta(get_the_ID(), 'faqs', true);
    $faqs = $faqs_raw;

    // Fallback default FAQs if empty or invalid
    if (!is_array($faqs) || empty($faqs)) {
        return;
    }

    ob_start();
    ?>

    <div class="sd-faq-accordion">
        <?php foreach ($faqs as $faq): ?>
            <div class="sd-faq-item">
                <div class="sd-faq-question"><?php echo esc_html($faq['question']); ?></div>
                <div class="sd-faq-answer"><?php echo wp_kses_post($faq['answer']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>



    <style>
    .sd-faq-accordion {
        margin: 20px 0;
        font-family: Arial, sans-serif;
    }
    .sd-faq-item {
        margin-bottom: 12px;
    }
    .sd-faq-question {
        width: 100%;
        padding: 10px;
        text-align: left;
        background: var(--lighter);
        cursor: pointer;
        font-size: 16px;
        border-radius: 4px;
        transition: background 0.3s;
        font-weight: 500;
    }
    .sd-faq-question:hover {
        background: var(--light);
    }
    .sd-faq-answer {
        padding: 10px;
        background: var(--lighter);
        border-left: 3px solid #ddd;
        margin-top: 5px;
        font-size: 14px;
        display: none;
    }
    .sd-faq-answer p {
        margin: 0;
    }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    jQuery(document).ready(function($){
        $('.sd-faq-question').on('click', function(){
            var ans = $(this).next('.sd-faq-answer');
            if(ans.is(':visible')){
                ans.slideUp();
            } else {
                $('.sd-faq-answer').slideUp();
                ans.slideDown();
            }
        });
    });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('sd_faq_accordion', 'sd_faq_accordion_shortcode');
