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
        'immigration attorney' => 'LegalService',
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
    $category_name = ''; // Initialize category name

    if (!empty($terms) && !is_wp_error($terms)) {
        $primary_term_name = strtolower($terms[0]->name); // assuming first term is primary
        $category_name = $terms[0]->name; // Store the category name for cuisine
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
        $reviews = array_slice($unwrapped['reviews'], 0, 10);
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

    // === Business Hours (openingHours) ===
    $business_hours_meta = get_post_meta($post_id, 'business_hours', true);
    $opening_hours = [];

    // Unserialize the business hours meta data
    $business_hours = is_string($business_hours_meta) ? @unserialize($business_hours_meta) : [];

    // Check if business hours data exists and format accordingly
    if (!empty($business_hours) && is_array($business_hours)) {
        foreach ($business_hours as $hours) {
            $opening_hours[] = $hours['day'] . ' ' . $hours['hours'];
        }
    }

    // === Geo Schema (latitude & longitude) ===
    $latitude = get_post_meta($post_id, 'latitude', true);
    $longitude = get_post_meta($post_id, 'longitude', true);

    $geo = [];
    if ($latitude && $longitude) {
        $geo = [
            "@type" => "GeoCoordinates",
            "latitude" => $latitude,
            "longitude" => $longitude
        ];
    }

    // === Address Schema ===
    $address_meta = get_post_meta($post_id, 'address', true);
    $street_meta = get_post_meta($post_id, 'street', true);
    $zip_meta = get_post_meta($post_id, 'zip', true);
    $state_meta = get_post_meta($post_id, 'state', true);
    $country_meta = get_post_meta($post_id, 'country', true);

    $address = '';
    if (!empty($street_meta) && !empty($zip_meta) && !empty($state_meta) && !empty($country_meta)) {
        $address = $street_meta[0] . ', ' . $state_meta[0] . ' ' . $zip_meta[0] . ', ' . $country_meta[0];
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
        "address" => [
            "@type" => "PostalAddress",
            "streetAddress" => $street_meta[0] ?? '',
            "addressLocality" => $state_meta[0] ?? '',
            "postalCode" => $zip_meta[0] ?? '',
            "addressCountry" => $country_meta[0] ?? ''
        ],
        "geo" => $geo, // Add geo coordinates
        "telephone" => get_post_meta($post_id, 'phone', true),
        "priceRange" => "$$",
        "url" => get_permalink($post_id),
        "openingHours" => $opening_hours, // Use the dynamic opening hours
    ];
    
    if ($schema_type === 'Restaurant') {
        $schema["servesCuisine"] = $category_name;
    }

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
    
    
    // === VideoObject Schema ===
    $videos = ! empty( $unwrapped['media']['videoLinks'] ) ? $unwrapped['media']['videoLinks'] : [];

    if (!empty($videos) && is_array($videos)) {
        $video_schema = [];

        foreach ($videos as $index => $v) {
            
            if( 1 == $index ) {
                break;
            }
            
            if (!empty($v['video'])) {
                $video_schema[] = [
                    "@type" => "VideoObject",
                    "name" => "Restaurant Video " . ($index + 1),
                    "thumbnailUrl" => $v["poster"] ?? '',
                    "contentUrl" => $v["video"],
                    "uploadDate" => date("c"),
                    "description" => get_the_content( get_the_ID() ),
                    "duration" => isset($v['duration']) ? "PT" . str_replace(":", "M", $v["duration"]) . "S" : null
                ];
            }
        }

        if (!empty($video_schema)) {
            echo '<script type="application/ld+json">' . json_encode($video_schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
        }
    }
    
        // Organization
    $organization = [
        '@type' => 'Organization',
        '@id'   => home_url() . '#organization',
        'name'  => get_bloginfo( 'name' ),
        'url'   => home_url(),
        'logo'  => [
            '@type' => 'ImageObject',
            'url'   => get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
        ],
        'sameAs' => [
            'https://www.facebook.com/bippermedia/',
            'https://x.com/bippermedia/',
            'https://www.linkedin.com/company/bipper-media/',
            'https://www.youtube.com/channel/UCSp_-mTLSATD8kFxZFs-fKw'
        ],
    ];
    
    echo '<script type="application/ld+json">' . json_encode($organization, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';

    // WebSite
    $website = [
        '@type' => 'WebSite',
        '@id'   => home_url() . '#website',
        'url'   => home_url(),
        'name'  => get_bloginfo( 'name' ),
        'publisher' => [
            '@id' => home_url() . '#organization'
        ],
    ];

    echo '<script type="application/ld+json">' . json_encode($website, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';


    // === Output JSON-LD ===
    echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';

    if (!empty($faq_schema)) {
        echo '<script type="application/ld+json">' . json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}