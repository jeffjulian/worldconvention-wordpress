<?php

use \Etn\Utils\Utilities as Utilities;
use \Etn\Utils\Helper as Helper;

$etn_event_cat      = $settings["etn_event_cat"];
$etn_event_count      = $settings["etn_event_count"];
$etn_event_col      = $settings["etn_event_col"];
$etn_desc_limit      = $settings["etn_desc_limit"];

$args = array(
    'posts_per_page'   => $etn_event_count,
    'orderby'          => 'post_date',
    'order'            => 'DESC',
    'post_type'        => 'etn',
    'post_status'      => 'publish',
    'suppress_filters' => false,

);

if (is_array($settings['etn_event_cat']) && count($settings['etn_event_cat'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'etn_category',
            'terms'    => $settings['etn_event_cat'],
            'field' => 'id',
            'include_children' => true,
            'operator' => 'IN'
        ),
    );
};

$date_options= [
    '0' => 'Y-m-d',
    '1' => 'n/j/Y',
    '2' => 'm/d/Y',
    '3' => 'j/n/Y',
    '4' => 'd/m/Y',
    '5' => 'n-j-Y',
    '6' => 'm-d-Y',
    '7' => 'j-n-Y',
    '8' => 'd-m-Y',
    '9' => 'Y.m.d',
    '10' => 'm.d.Y',
    '11' => 'd.m.Y',
];
$event_options = get_option("etn_event_options");

$event_query = new \WP_Query($args);
?>
<div class='etn-row etn-event-wrapper'>
    <?php
    if ($event_query->have_posts()) {
        while ($event_query->have_posts()) {

            $event_query->the_post();
            $social           = get_post_meta(get_the_ID(), 'etn_event_socials', true);
            $etn_event_location = get_post_meta(get_the_ID(), 'etn_event_location', true);
            $etn_start_date = get_post_meta(get_the_ID(), 'etn_start_date', true);
            $event_start_date = isset($event_options["date_format"]) ? date($date_options[$event_options["date_format"]], strtotime($etn_start_date)) : date('d/m/Y', strtotime($etn_start_date) );
            $category =  Utilities::etn_cate_with_link(get_the_ID(), 'etn_category');

    ?>
            <div class="etn-col-md-6 etn-col-lg-<?php echo esc_attr($etn_event_col); ?>">
                <div class="etn-event-item">
                    <!-- thumbnail -->
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="etn-event-thumb">
                            <a href="<?php echo esc_url(get_the_permalink()); ?>">
                                <?php the_post_thumbnail(); ?>
                            </a>
                            <div class="etn-event-category">
                                <?php echo  Helper::kses($category); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- thumbnail start-->

                    <!-- content start-->
                    <div class="etn-event-content">
                        <?php if (isset($etn_event_location) && $etn_event_location != '') { ?>
                            <div class="etn-event-location"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($etn_event_location); ?></div>
                        <?php } ?>
                        <h3 class="etn-title etn-event-title"><a href="<?php echo esc_url(get_the_permalink()); ?>"> <?php echo esc_html(get_the_title()); ?></a> </h3>
                        <p><?php echo esc_html(Helper::trim_words(get_the_content(), $etn_desc_limit)); ?></p>
                        <div class="etn-event-footer">
                            <div class="etn-event-date">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo esc_html($event_start_date); ?>
                            </div>
                            <div class="etn-atend-btn">
                                <a href="<?php echo esc_url(get_the_permalink()); ?>" class="etn-btn etn-btn-border"><?php echo esc_html__('attend', 'eventin') ?> <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- content end-->
                </div>
                <!-- etn event item end-->
            </div>

    <?php
        }
    }
    wp_reset_query();
    ?>

</div>