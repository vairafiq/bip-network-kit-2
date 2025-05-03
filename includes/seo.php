<?php
/**
 * SEO and schema
 *
 * @author   Bipper Media
 * @category API
 * @since    0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('wp_head', 'add_business_schema_to_head');
function add_business_schema_to_head() {
    if (!is_singular('sd_business')) return;

    $post_id = get_the_ID();

    // === Reviews ===
    $structured_reviews = [];
    $reviews_meta = get_post_meta($post_id, 'google_reviews', true);
    $unwrapped = is_string($reviews_meta) ? @unserialize($reviews_meta) : [];

    if (!empty($unwrapped['reviews']) && is_array($unwrapped['reviews'])) {
        $reviews = array_slice($unwrapped['reviews'], 0, 2);

        $structured_reviews = array_map(function($review) {
            return [
                "@type" => "Review",
                "author" => $review['name'] ?? 'Anonymous',
                "datePublished" => date('Y-m-d'), // Replace with real date if available
                "reviewBody" => $review['text'] ?? '',
                "reviewRating" => [
                    "@type" => "Rating",
                    "ratingValue" => preg_replace('/[^0-9.]/', '', $review['review_count'] ?? '0')
                ]
            ];
        }, $reviews);
    }

    // === FAQ Schema ===
    $faq_schema = null;
    $faq_meta = get_post_meta($post_id, 'faqs', true);
    $faq_array = is_string($faq_meta) ? @unserialize($faq_meta) : [];

    if (!empty($faq_array) && is_array($faq_array)) {
        $faq_schema = [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => []
        ];

        foreach ($faq_array as $faq) {
            if (!empty($faq['question']) && !empty($faq['answer'])) {
                $faq_schema["mainEntity"][] = [
                    "@type" => "Question",
                    "name" => $faq["question"],
                    "acceptedAnswer" => [
                        "@type" => "Answer",
                        "text" => $faq["answer"]
                    ]
                ];
            }
        }

        if (empty($faq_schema['mainEntity'])) {
            $faq_schema = null; // Avoid outputting empty FAQ schema
        }
    }

    // === Business Schema ===
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => get_the_title($post_id),
        "image" => [
            "@type" => "ImageObject",
            "url" => get_post_meta($post_id, 'main_image', true)
        ],
        "address" => get_post_meta($post_id, 'business', true),
        "telephone" => get_post_meta($post_id, 'phone', true),
        "priceRange" => "$$",
        "servesCuisine" => get_post_meta($post_id, 'catgory', true),
        "url" => get_permalink($post_id),
        "openingHours" => "Mo-Su 12:00-18:00",
    ];

    // Add reviews only if available
    if (!empty($structured_reviews)) {
        $schema["aggregateRating"] = [
            "@type" => "AggregateRating",
            "ratingValue" => get_post_meta($post_id, 'overall_rating', true),
            "reviewCount" => get_post_meta($post_id, 'review_count', true)
        ];
        $schema["review"] = $structured_reviews;
    }

    // Output schemas
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';

    if (!empty($faq_schema)) {
        echo '<script type="application/ld+json">' . json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }
}