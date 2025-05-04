<?php
/**
 * Shortcode to generate FontAwesome star icons based on rating.
 *
 * Displays full stars, optional half star, and empty stars up to 5 total.
 *
 * @param float A rating number between 0 and 5.
 *
 * @usage
 * [sd_rating_stars rating="4.3"]
 */
function sd_rating_stars_shortcode($atts) {

    $rating = $atts['rating'] ? $atts['rating'] : 0;

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
                color: var(--accent); /* Accent for filled stars */
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
add_shortcode('sd_rating_stars', 'sd_rating_stars_shortcode');