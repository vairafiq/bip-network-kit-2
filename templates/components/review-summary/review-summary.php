<?php
function sd_render_review_summary() {
    $review_summary = get_post_meta(get_the_ID(), 'review_summary', true);
    
    // If it's an array, get the first element
    if (is_array($review_summary)) {
        $review_string = $review_summary[0] ?? '';
    } else {
        $review_string = $review_summary;
    }

    // Default string if empty
    if (empty($review_string)) {
        return;
    }

    // Match lines with **Source**: **Rating** from **Count**
    preg_match_all('/\*\*(.*?)\*\*: \*\*(.*?)\*\* from \*\*(.*?)\*\*/', $review_string, $matches, PREG_SET_ORDER);

    ob_start();
    ?>

    <table class="sd-review-summary-table">
        <thead>
            <tr>
                <th>Source</th>
                <th>Rating</th>
                <th>Reviews</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matches as $match): ?>
                <tr>
                    <td><?php echo esc_html($match[1]); ?></td>
                    <td><?php echo esc_html($match[2]); ?></td>
                    <td><?php echo esc_html($match[3]); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <style>
      .sd-review-summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sd-review-summary-table th,
        .sd-review-summary-table td {
            border: 1px solid var(--light);
            padding: 8px;
        }
    </style>

    <?php
    return ob_get_clean();
}
add_shortcode('sd_review_summary', 'sd_render_review_summary');
