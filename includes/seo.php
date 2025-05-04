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

    // === Category to Schema Type Mapping ===
    $schema_type_map = [
        'restaurant'     => 'Restaurant',
        'steak house'    => 'SteakHouse',
        'bar'            => 'BarOrPub',
        'cafe'           => 'CafeOrCoffeeShop',
        'bakery'         => 'Bakery',
        'fast food'      => 'FastFoodRestaurant',
        'ice cream'      => 'IceCreamShop',
        'hotel'          => 'Hotel',
        'salon'          => 'HairSalon',
        'barbershop'     => 'Barbershop',
        'spa'            => 'DaySpa',
        'gym'            => 'HealthClub',
        'doctor'         => 'MedicalClinic',
        'dentist'        => 'Dentist',
        'pharmacy'       => 'Pharmacy',
        'vet'            => 'VeterinaryCare',
        'lawyer'         => 'LegalService',
        'accountant'     => 'AccountingService',
        'auto repair'    => 'AutoRepair',
        'car dealer'     => 'AutoDealer',
        'real estate'    => 'RealEstateAgent',
        'plumber'        => 'Plumber',
        'electrician'    => 'Electrician',
        'locksmith'      => 'Locksmith',
        'pet store'      => 'PetStore',
        'clothing'       => 'ClothingStore',
        'grocery'        => 'GroceryStore',
        'bookstore'      => 'BookStore',
        'furniture'      => 'FurnitureStore',
        'jewelry'        => 'JewelryStore',
        'electronics'    => 'ElectronicsStore',
        'toys'           => 'ToyStore',
    ];

    // === Get primary category from taxonomy and determine @type
    $schema_type = 'LocalBusiness';
    $terms = get_the_terms($post_id, 'sd_business_category');
    if (!empty($terms) && !is_wp_error($terms)) {
        $primary_term_name = strtolower($terms[0]->name); // assuming first term is primary
        foreach ($schema_type_map as $key => $mapped_type) {
            if (strpos($primary_term_name, $key) !== false) {
                $schema_type = $mapped_type;
                break;
            }
        }
    }

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
                "datePublished" => date('Y-m-d'),
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
    $faq_array = is_string($faq_meta) ? @unserialize($faq_meta) : $faq_meta;

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
            $faq_schema = null;
        }
    }

    // === Business Schema ===
    $schema = [
        "@context" => "https://schema.org",
        "@type" => $schema_type,
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

    // Add reviews
    if (!empty($structured_reviews)) {
        $schema["aggregateRating"] = [
            "@type" => "AggregateRating",
            "ratingValue" => get_post_meta($post_id, 'overall_rating', true),
            "reviewCount" => get_post_meta($post_id, 'review_count', true)
        ];
        $schema["review"] = $structured_reviews;
    }

    // === Menu Schema (if menu meta exists) ===
    $menu_meta = get_post_meta($post_id, 'menu', true);
    $menu_items = is_string($menu_meta) ? @unserialize($menu_meta) : $menu_meta;

    if (!empty($menu_items) && is_array($menu_items)) {
        $structured_menu_items = array_map(function($item) {
            return [
                "@type" => "MenuItem",
                "name" => $item['name'] ?? '',
                "description" => $item['description'] ?? '',
                "offers" => [
                    "@type" => "Offer",
                    "price" => $item['price'] ?? '',
                    "priceCurrency" => "USD"
                ]
            ];
        }, $menu_items);

        $schema["hasMenu"] = [
            "@type" => "Menu",
            "name" => "Main Menu",
            "hasMenuSection" => [
                [
                    "@type" => "MenuSection",
                    "name" => "Featured Items",
                    "hasMenuItem" => $structured_menu_items
                ]
            ]
        ];
    }

    // === Output JSON-LD ===
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';

    if (!empty($faq_schema)) {
        echo '<script type="application/ld+json">' . json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}

