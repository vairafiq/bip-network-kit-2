<?php
function sd_render_review_summary() {
    $review_summary = get_post_meta(get_the_ID(), 'review_summary', true);
    
    if (is_array($review_summary)) {
        $review_string = $review_summary[0] ?? '';
    } else {
        $review_string = $review_summary;
    }

    if (empty($review_string)) {
        return;
    }

    // Match both bold and normal styles
    preg_match_all(
        '/\*\*(.*?)\*\*: ?(?:(?:\*\*(.*?)\*\* from \*\*(.*?)\*\*)|([\d.]+) stars from (\d+) reviews)/',
        $review_string,
        $matches,
        PREG_SET_ORDER
    );

    if (empty($matches)) {
        return;
    }

    ob_start();
    ?>

    <table class="sd-review-summary-table">
        <thead>
            <tr>
                <th>Source</th>
                <th>Rating</th>
                <th>Based on</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matches as $match): ?>
                <tr>
                    <td><?php echo esc_html($match[1]); ?></td>
                    <td>
                        <?php
                        echo esc_html(!empty($match[2]) ? $match[2] : $match[4]);
                        ?>
                    </td>
                    <td>
                        <?php
                        echo esc_html(!empty($match[3]) ? $match[3] : $match[5]) . ' reviews';
                        ?>
                    </td>
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
