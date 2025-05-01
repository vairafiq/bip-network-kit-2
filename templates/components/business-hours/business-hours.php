<?php
function sd_business_hours_shortcode() {
    $business_hours = sd_get_post_data('business_hours');
    
    if ( empty( $business_hours ) ) {
        return 'Business hours not found!';
    }

    $hours = json_decode( $business_hours, true );
    if ( ! is_array( $hours ) ) {
        return 'There is a problem with business hours!';
    }

    ob_start(); ?>
    <div class="sd-business-hours">
        <ul class="sd-business-hours-list">
            <?php foreach ( $hours as $day => $time ) :
                $is_closed = stripos($time, 'close') !== false; // case-insensitive check
                $class = $is_closed ? ' sd-is-closed' : '';
            ?>
                <li class="sd-business-hours-item<?php echo esc_attr($class); ?>">
                    <span class="sd-business-day"><?php echo esc_html( ucfirst($day) ); ?></span>
                    <span class="sd-business-time"><?php echo esc_html( $time ); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <style>
        .sd-business-hours {
            padding: 10px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.4;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .sd-business-hours-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sd-business-hours-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px dashed var(--light);
        }

        .sd-business-hours-item:last-child {
            border-bottom: none;
        }
        .sd-business-day {
            font-weight: 500;
        }
        .sd-business-day,
        .sd-business-time {
            color: var(--black);
        }

        .sd-business-hours-item.sd-is-closed .sd-business-day,
        .sd-business-hours-item.sd-is-closed .sd-business-time {
            color: var(--red);
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('sd_business_hours', 'sd_business_hours_shortcode');
?>
