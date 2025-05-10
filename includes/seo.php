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


function bip_schema_types() {
    return [
        // Food & Drink
        'restaurant'         => 'Restaurant',
        'steak house'        => 'SteakHouse',
        'bar'                => 'BarOrPub',
        'cafe'               => 'CafeOrCoffeeShop',
        'bakery'             => 'Bakery',
        'fast food'          => 'FastFoodRestaurant',
        'ice cream'          => 'IceCreamShop',
        'pizza'              => 'PizzaRestaurant',
        'sushi'              => 'SushiRestaurant',
        'burger'             => 'FastFoodRestaurant',
        'buffet'             => 'Restaurant',
        'fine dining'        => 'Restaurant',
    
        // Health & Wellness
        'doctor'             => 'MedicalClinic',
        'dentist'            => 'Dentist',
        'pharmacy'           => 'Pharmacy',
        'hospital'           => 'Hospital',
        'vet'                => 'VeterinaryCare',
        'spa'                => 'DaySpa',
        'salon'              => 'HairSalon',
        'barbershop'         => 'Barbershop',
        'gym'                => 'HealthClub',
        'chiropractor'       => 'Chiropractic',
        'therapist'          => 'Physician',
        'optometrist'        => 'Optician',
    
        // Legal & Professional
        'lawyer'             => 'LegalService',
        'accountant'         => 'AccountingService',
        'notary'             => 'Notary',
        'consulting'         => 'ProfessionalService',
        'real estate'        => 'RealEstateAgent',
        'insurance'          => 'InsuranceAgency',
        'tax'                => 'AccountingService',
    
        // Auto & Transportation
        'auto repair'        => 'AutoRepair',
        'car dealer'         => 'AutoDealer',
        'car rental'         => 'CarRental',
        'taxi'               => 'TaxiService',
        'limousine'          => 'TaxiService',
        'bus'                => 'BusStation',
        'train'              => 'TrainStation',
        'airport'            => 'Airport',
        'gas station'        => 'GasStation',
        'parking'            => 'ParkingFacility',
    
        // Stores & Shopping
        'clothing'           => 'ClothingStore',
        'grocery'            => 'GroceryStore',
        'bookstore'          => 'BookStore',
        'furniture'          => 'FurnitureStore',
        'jewelry'            => 'JewelryStore',
        'electronics'        => 'ElectronicsStore',
        'toys'               => 'ToyStore',
        'pet store'          => 'PetStore',
        'hardware'           => 'HardwareStore',
        'liquor'             => 'LiquorStore',
        'shoe'               => 'ShoeStore',
        'florist'            => 'Florist',
        'sporting goods'     => 'SportingGoodsStore',
        'beauty supply'      => 'HealthAndBeautyBusiness',
    
        // Home Services
        'plumber'            => 'Plumber',
        'electrician'        => 'Electrician',
        'locksmith'          => 'Locksmith',
        'cleaning'           => 'HomeAndConstructionBusiness',
        'pest control'       => 'PestControl',
        'landscaping'        => 'LandscapingBusiness',
        'contractor'         => 'HomeAndConstructionBusiness',
        'roofing'            => 'RoofingContractor',
        'hvac'               => 'HVACBusiness',
    
        // Hospitality & Tourism
        'hotel'              => 'Hotel',
        'motel'              => 'LodgingBusiness',
        'resort'             => 'Resort',
        'bed and breakfast'  => 'BedAndBreakfast',
        'guest house'        => 'LodgingBusiness',
        'travel agency'      => 'TravelAgency',
        'tour operator'      => 'TouristInformationCenter',
        'hostel'             => 'Hostel',
    
        // Entertainment & Attractions
        'museum'             => 'Museum',
        'zoo'                => 'Zoo',
        'aquarium'           => 'Aquarium',
        'park'               => 'Park',
        'movie theater'      => 'MovieTheater',
        'theater'            => 'PerformingArtsTheater',
        'amusement park'     => 'AmusementPark',
        'arcade'             => 'EntertainmentBusiness',
        'bowling'            => 'BowlingAlley',
        'nightclub'          => 'NightClub',
        'art gallery'        => 'ArtGallery',
        'casino'             => 'Casino',
    
        // Education & Childcare
        'school'             => 'EducationalOrganization',
        'university'         => 'CollegeOrUniversity',
        'daycare'            => 'ChildCare',
        'tutoring'           => 'EducationalOrganization',
        'music school'       => 'MusicSchool',
        'driving school'     => 'DrivingSchool',
    
        // Religious & Civic
        'church'             => 'PlaceOfWorship',
        'mosque'             => 'PlaceOfWorship',
        'temple'             => 'PlaceOfWorship',
        'synagogue'          => 'PlaceOfWorship',
        'courthouse'         => 'GovernmentBuilding',
        'police'             => 'PoliceStation',
        'fire station'       => 'FireStation',
        'post office'        => 'PostOffice',
        'library'            => 'Library',
    
        // Tech & Media
        'computer repair'    => 'ComputerStore',
        'web design'         => 'ProfessionalService',
        'print shop'         => 'Store',
        'photography'        => 'Photograph',
        'marketing'          => 'ProfessionalService',
    ];
    
}

add_action('wp_head', 'add_business_schema_to_head');
function add_business_schema_to_head() {
    if (!is_singular('sd_business')) return;

    $post_id = get_the_ID();

    // === Category to Schema Type Mapping ===
    $schema_type_map = bip_schema_types();
    
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

