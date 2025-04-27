<?php
/**
 * Shortcode: [sd_businesses]
 *
 * Outputs a grid of business listings from the "sd_business" post type.
 *
 * Options:
 * - query (string)   : "recent" (default) or "related"
 *     - "recent" shows the latest businesses from the passed category or any category
 *     - "related" shows businesses from the same category as the current post (used only on singular business pages)
 * - category (string): Category slug to filter by (only applicable if query = "related"). If empty(category), will use current post type category.
 * - count (int)      : Number of posts to display (default: 6)
 * - title (string)   : Heading to display
 * - description (string)   : Description bellow heading
 * 
 * Template:
 * - Uses plugin's /templates/content-archive.php for each item layout
 * - Uses plugin's /assets/css/archive.css
 * - Enqueues inline CSS grid layout 
 * 
 * Uses:
 * - [sd_businesses query="recent" count="9"] for recent posts in anywhere
 * - [sd_businesses query="related" count="9"] for single business page (category will be current post category).
 * - [sd_businesses query="related" category="service" count="9"] for specific type of businesses
 *
 * @return string HTML output for the business grid
 */
function sd_businesses_shortcode($atts) {

    $atts = shortcode_atts([
        'query' => 'recent',
        'category' => '', // slug, if related.
        'count' => 6,
        'title' => '',
        'description' => '',
    ], $atts, 'sd_businesses');


    if (!is_singular('sd_business') && !empty($atts['category']) && $atts['query'] !== 'related' ) return 'You can not retrive businesses by passing category without passing query="related"';

    $taxonomy = 'sd_business_category';
    $post_id = get_the_ID();

    // Prepare base query
    $args = [
        'post_type' => 'sd_business',
        'posts_per_page' => (int) $atts['count'],
        'post__not_in' => [$post_id],
    ];

    // If "related", limit by category
    $term = '';
    if ($atts['query'] == 'related') {
        if (!empty($atts['category'])) {
            $term = get_term_by('slug', $atts['category'], $taxonomy);
        } else {
            $terms = get_the_terms($post_id, $taxonomy);
            $term = (!empty($terms) && !is_wp_error($terms)) ? $terms[0] : null;
        }

        if ($term) {
            $args['tax_query'] = [[
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term->term_id,
            ]];

            // Build “Show All” link
            $term_name = esc_html( 'See All '. $term->name .'s');
            $term_slug = esc_attr( $term->slug );
            $term_link = esc_url( get_term_link( $term ) );
        }
    }

    // Add ordering for recent
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';

    $query = new WP_Query($args);

    ob_start(); ?>
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . '../../../assets/css/archive.css'; ?>">

    <?php
    if ($query->have_posts()) {
        echo '<div class="sd-businesses">';

        if( !empty($atts['title']) ) {
            echo '<h2 class="sd-businesses-title">'.$atts['title'].'</h2>';
            echo '<p class="sd-businesses-description">'.$atts['description'].'</p>';
        }

        echo '<div class="sd-queried-business-list">';
        while ($query->have_posts()) {
            $query->the_post();
            include plugin_dir_path(__FILE__) . '../../../templates/content-archive.php';
        }
        echo '</div>';
        
        if($term) {
            echo '<a class="sd-btn-primary sd-businesses-all-btn" href="'.$term_link.'">'.$term_name.'</a>';
        } else {
            echo '<a class="sd-btn-primary sd-businesses-all-btn" href="'.site_url('/biz/').'">See All Businesses</a>';
        }

        echo '</div>';
        wp_reset_postdata();
    } else {
        if(isset($_GET['debug'])) {
            echo 'No business found!';
        }
    }


    ?>
    <style>
        .sd-businesses-title {
            font-size: var(--h2);
            font-weight: var(--h2-weight);
            color: var(--black);
            text-align: center !important;
        }
        .sd-queried-business-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            justify-content: space-around;
            gap: 1.5rem;
        }
        @media (max-width: 1024px) {
            .sd-queried-business-list {
                grid-template-columns: repeat(2, 1fr);
                justify-content: space-around;
            }
        }
        @media (max-width: 768px) {
            .sd-queried-business-list {
                grid-template-columns: 1fr;
                justify-content: space-around;
            }
        }
        .sd-businesses-all-btn {
            display: block;
            width: max-content !important;
            margin: auto;
            margin-top: 2rem !important;
        }
        .sd-businesses-description {
            font-size: var(--p);
            font-weight: var(--p-weight);
            color: var(--black);
            margin: 1rem auto 3rem auto;
            max-width: 800px;
            text-align: center !important;
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('sd_businesses', 'sd_businesses_shortcode');
