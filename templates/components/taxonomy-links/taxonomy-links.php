<?php
/**
 * Shortcode: [sd_taxonomy_links]
 *
 * Displays up to 6 links for Business Categories and Locations,
 * ordered by post count descending. If more than 6, shows a
 * “See All” link and makes the extra items collapsible.
 *
 * @return string HTML output of taxonomy link groups.
 */
function sd_taxonomy_links_shortcode() {
    // Fetch and sort terms by count desc
    $terms_data = [
        'Categories' => get_terms([
            'taxonomy'   => 'sd_business_category',
            'hide_empty' => false,
            'orderby'    => 'count',
            'order'      => 'DESC',
        ]),
        'Locations'  => get_terms([
            'taxonomy'   => 'sd_business_location',
            'hide_empty' => false,
            'orderby'    => 'count',
            'order'      => 'DESC',
        ]),
    ];

    // Bail if all empty or error
    $all_empty = true;
    foreach ($terms_data as $terms) {
        if (!is_wp_error($terms) && !empty($terms)) {
            $all_empty = false;
            break;
        }
    }
    if ($all_empty) {
        return '';
    }

    ob_start();
    ?>
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . 'taxonomy-links.css'; ?>">
    <!-- CSS -->

    <div class="sd-taxonomy-links">
        <h2 class="sd-taxonomy-links-title">Browse by</h2>
        <div class="sd-taxonomy-links-groups">

            <?php foreach ($terms_data as $title => $terms) : 
                if (is_wp_error($terms) || empty($terms)) {
                    continue;
                }
                // Limit to first 6 terms
                $primary_terms   = array_slice($terms, 0, 6);
                $overflow_terms  = array_slice($terms, 6);
                // Determine taxonomy slug for archive link
                $taxonomy = $title === 'Categories' ? 'sd_business_category' : 'sd_business_location';
                $archive_link = get_post_type_archive_link('sd_business');
                $archive_link = add_query_arg('taxonomy', str_replace(' ', '_', strtolower($title)), $archive_link);
                ?>
                <div class="sd-taxonomy-group sd-taxonomy-<?php echo strtolower($title); ?>">
                    <span class="sd-taxonomy-links-heading sd-desktop-only"><?php echo esc_html($title); ?></span>
                    <div class="sd-taxonomy-links-list sd-desktop-only">
                        <?php foreach ($primary_terms as $term) :
                            $term_link = esc_url(get_term_link($term));
                            $selected = (home_url(add_query_arg([], $_SERVER['REQUEST_URI'])) === $term_link) ? 'selected' : '';
                            ?>

                            <a href="<?php echo esc_url(get_term_link($term)); ?>" class="sd-taxonomy-link  <?php echo $selected; ?>">
                                <?php echo esc_html($term->name); ?>
                            </a>
                        <?php endforeach; ?>

                        <?php if (!empty($overflow_terms)) : ?>
                            <div class="sd-taxonomy-links-list sd-taxonomy-overflow" style="display:none;">
                                <?php foreach ($overflow_terms as $term) :
                                    $term_link = esc_url(get_term_link($term));
                                    $selected = (home_url(add_query_arg([], $_SERVER['REQUEST_URI'])) === $term_link) ? 'selected' : '';
                                    ?>

                                    <a href="<?php echo esc_url(get_term_link($term)); ?>" class="sd-taxonomy-link <?php echo $selected; ?>">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <a href="#" class="sd-taxonomy-toggle" data-target=".sd-taxonomy-<?php echo strtolower($title); ?> .sd-taxonomy-overflow">
                                See all <?php echo esc_html($title); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile Select Dropdown -->
                    <div class="sd-taxonomy-select-wrapper sd-mobile-only">
                        <select class="sd-taxonomy-select" onchange="if(this.value) window.location.href=this.value">
                            <option value=""><?php echo esc_html($title); ?></option>
                            <?php foreach ($terms as $term) : 
                                $term_link = esc_url(get_term_link($term));
                                $selected = (home_url(add_query_arg([], $_SERVER['REQUEST_URI'])) === $term_link) ? 'selected' : '';
                                ?>
                                <option value="<?php echo $term_link; ?>" <?php echo $selected; ?>>
                                    <?php echo esc_html($term->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                </div> <!-- sd-taxonomy-group -->
            <?php endforeach; ?>

        </div> <!-- sd-taxonomy-groups -->
    </div>  <!-- sd-taxonomy-link (main wrapper)-->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.sd-taxonomy-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const targetSelector = this.getAttribute('data-target');
                const overflow = document.querySelector(targetSelector);
                if (!overflow) return;
                const isHidden = overflow.style.display === 'none';
                overflow.style.display = isHidden ? 'block' : 'none';
                this.textContent = isHidden
                    ? 'Show less'
                    : 'See all ' + this.closest('.sd-taxonomy-group').querySelector('.sd-taxonomy-links-heading').textContent;
            });
        });
    });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('sd_taxonomy_links', 'sd_taxonomy_links_shortcode');
