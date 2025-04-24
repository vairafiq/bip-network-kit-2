<?php
/**
 * Shortcode to generate FontAwesome star icons based on rating.
 *
 * Displays full stars, optional half star, and empty stars up to 5 total with overall count, review count.
 *
 * @param float $rating A rating number between 0 and 5.
 * @param int $review_count The total number of reviews.
 * @return string HTML string of star icons and counts.
 *
 * @usage
 * [sd_overall_rating rating="4.3" review_count="30"]
 */
function sd_overall_rating_shortcode() {

    $post_id        = get_the_ID();
    $rating         = get_post_meta($post_id, 'overall_rating', true);
    $review_count   = get_post_meta($post_id, 'review_count', true);

    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

    $stars_html = '';

    $stars_html .= '<span class="sd-overall-rating">';

    $stars_html .= '<span class="sd-star-rating">';
    for ($i = 1; $i <= $full_stars; $i++) { // full stars
        $stars_html .= '<i class="fas fa-star sd-star-filled"></i>';
    }

    if ($half_star) { // half star
        $stars_html .= '<i class="fas fa-star-half-alt sd-star-filled"></i>';
    }

    for ($i = 1; $i <= $empty_stars; $i++) { // empty stars
        $stars_html .= '<i class="far fa-star"></i>';
    }
    $stars_html .= '</span>';


    $stars_html .= '<span class="sd-overall-count">';
    $stars_html .= $rating; // rating number
    $stars_html .= '</span>';

    $stars_html .= '<span class="sd-review-count">';
    $stars_html .= '('.$review_count.' reviews)'; // review count
    $stars_html .= '</span>';

    $stars_html .= '</span>';

    $stars_html .= '
        <style>
            /* overall rating */
            .sd-overall-rating {
                display: inline-flex;
                align-items: center;
                gap: 0.3em;
                font-size: var(--small)
            }
            .sd-overall-rating .fa-star,
            .sd-overall-rating .fa-star-half-alt {
                color: var(--light); /* Default filled color */
            }

            .sd-overall-rating .sd-star-filled {
                color: var(--primary-600); /* Green for filled stars */
            }
            .sd-overall-count {
                font-weight: 500;
                font-size: var(--small);
            }
            .sd-review-count {
                color: var(--gray);
                font-size: var(--small);
            }
            /* overall rating */
        </style>
        ';
    return $stars_html;
}
add_shortcode('sd_overall_rating', 'sd_overall_rating_shortcode');