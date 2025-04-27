<?php
/**
 * Shortcode to display business features
 */

 function sd_single_features_shortcode() {
    $features = sd_get_post_data('features');
    $features = json_decode($features, true);

    if (empty($features)) {
        return;
    }

    ob_start();
    ?>

    <div class="sd-single-features">
        <div class="sd-single-features-container">
            <h2 class="sd-single-features-title">Features</h2>
            <div class="sd-single-features-groups">
                
            <?php if (!empty($features) && is_array($features)) :

                // Sort groups by number of features (descending)
                uasort($features, function($a, $b) {
                    return count($b) <=> count($a);
                });

                foreach ($features as $group_title => $items) :
                    ?>
                    <div class="sd-single-features-group">
                        <span class="sd-single-features-group-title"><?php echo esc_html($group_title); ?></span>
                        <div class="sd-single-features-group-items">
                            <?php foreach ($items as $item_title => $value) : 
                                $icon = $value ? '✔' : '✘';
                                $class = $value ? 'feature-true' : 'feature-false';
                                ?>
                                <span class="sd-single-features-item <?php echo esc_attr($class); ?>">
                                    <span class="icon"><?php echo $icon; ?></span>
                                    <?php echo esc_html($item_title); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

            </div>
        </div>
    </div>



    <style>
    .sd-single-features-title {
        font-size: var(--h2);
        font-weight: var(--h2-weight);
        color: var(--black);
    }
    .sd-single-features-groups {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .sd-single-features-group-title {
        display: block;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .sd-single-features-group-items {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .sd-single-features-item {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        background-color: var(--lighter);
        font-size: var(--small);
    }

    .sd-single-features-item .icon {
        margin-right: 0.5rem;
        font-weight: bold;
    }

    .feature-true .icon {
        color: green;
    }

    .feature-false .icon {
        color: red;
        opacity: 0.6;
    }
    @media (max-width:1024px) {
        .sd-single-features-groups {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width:768px) {
        .sd-single-features-groups {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    </style>

    <?php return ob_get_clean();
 }
 add_shortcode('sd_single_features', 'sd_single_features_shortcode');