<?php
function sd_rating_progress_shortcode() {
    $post_id = get_the_ID();
    $review_details = get_post_meta($post_id, 'review_details', true);

    if (empty($review_details)) {
        return;
    }

    // Extract star ratings
    preg_match_all('/(\d) Star Reviews: (\d+)/', $review_details, $matches, PREG_SET_ORDER);

    if (empty($matches)) {
        return '<p>Invalid review format.</p>';
    }

    $review_data = [];
    $total_reviews = 0;

    foreach ($matches as $match) {
        $star = (int) $match[1];
        $count = (int) $match[2];
        $review_data[$star] = $count;
        $total_reviews += $count;
    }

    krsort($review_data); // Sort from 5 to 1 stars

    ob_start();
    ?>
    
    <div class="sd-rating-progress" style="width: 100%;">

        <?php echo do_shortcode('[sd_overall_rating]'); ?>
        <br>
        <br>

        <div class="sd-rating-progress-container">
            <?php foreach ($review_data as $star => $count):
                $width = $total_reviews > 0 ? ($count / $total_reviews) * 100 : 0;
            ?>
                <div class="sd-rating-progress-item" style="display: flex; align-items: center; margin-bottom: 8px;">
                    <div class="sd-rating-star-label" style="text-align: right; margin-right: 8px;"><?php echo $star; ?></div>
                    <div class="sd-rating-progress-bar-bg" style="flex-grow: 1; background: #eee; height: 10px; border-radius: 0.5rem; overflow: hidden;">
                        <div class="sd-rating-progress-bar-fill" style="width: <?php echo $width; ?>%; height: 100%;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        .sd-rating-progress {
            text-align: center;
        }
        .sd-rating-progress-bar-fill {
            background: var(--primary-400);
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('sd_rating_progress', 'sd_rating_progress_shortcode');
