    <?php

    use Etn\Utils\Helper as Helper;
    use \Etn\Utils\Utilities as Utilities;

    $date_options         = [
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
    $extra_service = [];

    $etn_start_date        = strtotime(get_post_meta(get_the_id(), 'etn_start_date', true));
    $etn_start_time        = strtotime(get_post_meta(get_the_id(), 'etn_start_time', true));
    $etn_event_location    = get_post_meta(get_the_id(), 'etn_event_location', true);
    $etn_event_tags        = get_post_meta(get_the_id(), 'etn_event_tags', true);
    $etn_event_description = get_post_meta(get_the_id(), 'etn_event_description', true);
    $etn_event_schedule    = get_post_meta(get_the_id(), 'etn_event_schedule', true);
    $etn_online_event      = get_post_meta(get_the_id(), 'etn_online_event', true);
    $etn_es_event_feature  = get_post_meta(get_the_id(), 'etn_es_event_feature', true);
    $etn_event_banner      = get_post_meta(get_the_id(), 'etn_event_banner', true);
    $etn_event_banner_url  = wp_get_attachment_image_src($etn_event_banner);
    $etn_organizer_banner  = get_post_meta(get_the_id(), 'etn_organizer_banner', true);
    $etn_organizer_banner_url = wp_get_attachment_image_src($etn_organizer_banner);
    $etn_end_date          = strtotime(get_post_meta(get_the_id(), 'etn_end_date', true));
    $etn_end_time          = strtotime(get_post_meta(get_the_id(), 'etn_end_time', true));
    $etn_event_socials     = get_post_meta(get_the_id(), 'etn_event_socials', true);
    $etn_event_page        = get_post_meta(get_the_id(), 'etn_event_page', true);
    $etn_organizer_events  = get_post_meta(get_the_id(), 'etn_event_organizer', true);
    $etn_avaiilable_tickets  = get_post_meta(get_the_id(), 'etn_avaiilable_tickets', true);
    $etn_avaiilable_tickets = isset($etn_avaiilable_tickets) ? (intval($etn_avaiilable_tickets)) : 0;

    $cart_product_id = get_post_meta(get_the_id(), 'link_wc_product', true) ? esc_attr(get_post_meta(get_the_id(), 'link_wc_product', true)) : esc_attr(get_the_id());

    $etn_sold_tickets  = get_post_meta(get_the_id(), 'etn_sold_tickets', true);
    if (!$etn_sold_tickets) {
        $etn_sold_tickets = 0;
    }
    $etn_ticket_price  = get_post_meta(get_the_id(), 'etn_ticket_price', true);
    $etn_ticket_price = isset($etn_ticket_price) ? (floatval($etn_ticket_price)) : 0;
    $etn_left_tickets = $etn_avaiilable_tickets - $etn_sold_tickets;
    $event_options = get_option("etn_event_options");
    $event_options["time_format"] == '' ?  $event_options["time_format"] = '12' : $event_options["time_format"];
    $event_start_time = ($event_options["time_format"] == "24") ? date('H:i', $etn_start_time) : date('h:i A', $etn_start_time);
    $event_end_time = ($event_options["time_format"] == "24") ? date('H:i', $etn_end_time) : date('h:i A', $etn_end_time);
    $event_options["date_format"] == '' ?  $event_options["date_format"] = '0' : $event_options["date_format"];
    $event_start_date = isset($event_options["date_format"]) ? date($date_options[$event_options["date_format"]], $etn_start_date) : date('d/m/Y', $etn_start_date);
    $event_end_date = isset($event_options["date_format"]) ? date($date_options[$event_options["date_format"]], $etn_end_date) : date('d/m/Y', $etn_end_date);
    $etn_deadline  =  strtotime(get_post_meta(get_the_id(), 'etn_registration_deadline', true));
    $etn_deadline =  isset($event_options["date_format"]) ? date($date_options[$event_options["date_format"]], $etn_deadline) : date('d/m/Y', $etn_deadline);
    $category =  Utilities::etn_cate_with_link(get_the_ID(), 'etn_category');

    ?>

    <div class="etn-event-single-wrap">
        <div class="etn-row">
            <div class="etn-col-lg-9">
                <div class="etn-event-single-content-wrap">
                    <div class="etn-event-entry-header">
                        <div class="etn-event-meta">
                            <div class="etn-event-category">
                                <?php echo  Helper::kses($category); ?>
                            </div>
                            <div class="etn-event-social-wrap">
                                <i class="fas fa-share-alt"></i>
                                <div class="etn-social">
                                    <?php if (is_array($etn_event_socials)) : ?>
                                        <?php foreach ($etn_event_socials as $social) : ?>
                                            <?php $etn_social_class = 'etn-' . str_replace('fab fa-', '', $social['icon']); ?>
                                            <a href="<?php echo esc_url($social['etn_social_url']); ?>" target="_blank" class="<?php echo esc_attr($etn_social_class); ?>"> <i class="<?php echo esc_attr($social['icon']); ?>"></i> </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <h2 class="etn-event-entry-title"> <?php echo esc_html(get_the_title()); ?> </h2>
                    </div>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="etn-single-event-media">
                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" />
                        </div>
                    <?php endif; ?>

                    <div class="etn-event-content-body">
                        <?php the_content(); ?>
                    </div>
                    <div class="etn-event-tag-list">
                        <?php
                        global $post;
                        $etn_terms = wp_get_post_terms(get_the_id(), 'etn_tags');
                        if ($etn_terms) {
                            echo '<h4 class="etn-tags-title">' . esc_html__('Tags', 'eventin') . '</h4>';

                            $output = array();
                            foreach ($etn_terms as $term) {
                                $term_link =  get_term_link($term->slug, 'etn_tags');
                                $output[] = '<a href="' . $term_link . '">' . $term->name . '</a>';
                            }
                            echo Helper::kses(join(' ', $output)) ;
                        }
                        ?>
                    </div>
                </div>

                <?php
                if (!isset($event_options["etn_hide_schedule_from_details"])) {
                 if (is_array($etn_event_schedule)) {
                        $args = array(
                            'post__in' => $etn_event_schedule,
                            'orderby' => 'post_date',
                            'order' => 'asc',
                            'post_type' => 'etn-schedule',
                            'post_status' => 'publish',
                            'suppress_filters' => false,
                        );
                        $schedule_query = new \WP_Query($args);
                        global $post;
                        ?>
                        <!-- schedule tab start -->
                        <div class="schedule-tab-wrapper">
                            <ul class='etn-nav'>
                                <?php
                                $i = -1;
                                foreach ($schedule_query->posts as $post):
                                    $i++;
                                    $schedule_meta = get_post_meta($post->ID);
                                    $schedule_date = strtotime($schedule_meta['etn_schedule_date'][0]);
                                    $schedule_date = date("d M", $schedule_date);
                                    $active_class = (($i == 0) ? 'etn-active' : ' ');
                                    ?> 
                                        <li>
                                            <a href='#' class='etn-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($i); ?>'>
                                                <span class='etn-date'><?php echo esc_html($schedule_date); ?></span>
                                                <span class=etn-day><?php echo esc_html($post->post_title); ?></span>
                                            </a>
                                        </li>
                                        
                                <?php endforeach; ?>
                            </ul>
                            <div class='etn-tab-content clearfix etn-schedule-wrap'>
                                <?php 
                                    $j = -1;
                                    foreach ($schedule_query->posts as $post) :
                                        $j++;
                                        $schedule_meta = get_post_meta($post->ID);
                                        $schedule_date = strtotime($schedule_meta['etn_schedule_date'][0]);
                                        $schedule_topics = unserialize($schedule_meta['etn_schedule_topics'][0]);
                                        $schedule_date = date("d M", $schedule_date);
                                        $active_class = (($j == 0) ? 'tab-active' : ' ');
                                        ?>
                                            <!-- start repeatable item -->
                                            <div class='etn-tab <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($j); ?>'>
                                        <?php
                                        $etn_tab_time_format = $event_options["time_format"] == '24' ? "H:i":"h:i a"; 
                                        foreach ($schedule_topics as $topic) :
                                        $etn_schedule_topic = (isset($topic['etn_schedule_topic']) ? $topic['etn_schedule_topic'] : '');
                                        $etn_schedule_start_time = date($etn_tab_time_format, strtotime($topic['etn_shedule_start_time']));
                                        $etn_schedule_end_time = date($etn_tab_time_format, strtotime($topic['etn_shedule_end_time']));
                                        $etn_schedule_room = (isset($topic['etn_shedule_room']) ? $topic['etn_shedule_room'] : '');
                                        $etn_schedule_objective = (isset($topic['etn_shedule_objective'])? $topic['etn_shedule_objective'] : '');
                                        $etn_schedule_speaker = (isset($topic['etn_shedule_speaker']) ? $topic['etn_shedule_speaker'] : []);
                          
                                        ?>
                                        <div class='etn-single-schedule-item etn-row'>
                                            <div class='etn-schedule-info etn-col-sm-4'>
                                            <span class='etn-schedule-time'><?php echo esc_html($etn_schedule_start_time) . " - " . esc_html($etn_schedule_end_time); ?></span>
                                             <span class='etn-schedule-location'>
                                                    <i class='fas fa-map-marker-alt'></i>
                                                    <?php echo esc_html($etn_schedule_room);?>
                                                </span>
                                        </div>
                                        <div class='etn-schedule-content etn-col-sm-8'>
                                            <h4 class='etn-title'><?php echo esc_html($etn_schedule_topic); ?></h4>
                                            <p><?php echo esc_html($etn_schedule_objective); ?></p>
                                            <div class='etn-schedule-content'>
                                            <div class='etn-schedule-speaker'>
                                            <?php
                                            if (count($etn_schedule_speaker)>0) {
                                                foreach ($etn_schedule_speaker as $key => $value) { 
                                                    $speaker_thumbnail = get_the_post_thumbnail_url($value);
                                                    $etn_schedule_single_speaker = get_post($value);
                                                    $etn_speaker_permalink = get_post_permalink($value);
                                                    $speaker_title = $etn_schedule_single_speaker->post_title;
                                                    ?>
                                                  
                                                        <div class='etn-schedule-single-speaker'>
                                                            <a href='<?php echo esc_url($etn_speaker_permalink); ?>'>
                                                                <img src='<?php echo esc_url( $speaker_thumbnail);?>' alt=''>
                                                            </a>
                                                            <span class='etn-schedule-speaker-title'><?php echo esc_html($speaker_title);?></span>
                                                        </div>
                                                   
                                                    <?php
                                                }
                                            }

                                            ?>
                                             </div>
                                        </div>
                                            
                                        </div>
                                     
                                        </div>
                                        <?php endforeach; ?>
                                        </div>
                                        <!-- end repeatable item -->
                                    <?php endforeach;
                                     wp_reset_postdata(); ?>
                            </div>
                        </div>
                        <!-- schedule tab end -->
                        <?php }
                } 
                ?>

            </div><!-- col end -->

            <div class="etn-col-lg-3">
                <div class="etn-sidebar">
                    <div class="etn-event-meta-info etn-widget">
                        <ul>
                            <li>
                                <span> <?php echo esc_html__('Date : ', 'eventin'); ?></span>
                                <?php echo esc_html($event_start_date . " - " . $event_end_date); ?>
                            </li>
                            <li>
                                <span><?php echo esc_html__('Time : ', 'eventin'); ?></span>
                                <?php echo esc_html($event_start_time . " - " . $event_end_time); ?>
                            </li>
                            <li>
                                <span><?php echo esc_html__('Deadline : ', 'eventin'); ?></span>
                                <?php echo esc_html($etn_deadline); ?>
                            </li>
                            <li>
                                <?php
                                if (
                                    !isset($event_options["etn_hide_location_from_details"])
                                ) {
                                ?>
                                    <span><?php echo esc_html__('Venue : ', 'eventin') ?></span>
                                    <?php echo esc_html($etn_event_location);  ?>
                                <?php } ?>
                            </li>
                        </ul>

                        <?php
                        ?>
                    </div> <!-- event schedule meta end -->
                        <?php
                            // if active woocmmerce and has ticket , show registation form 
                            if ( is_plugin_active('woocommerce/woocommerce.php') ) {
                                echo '<div class="etn-widget etn-ticket-widget">';
                                if ($etn_left_tickets > 0 ) {
                                        ?>
                                            <h4 class="etn-widget-title etn-title"> <?php echo esc_html__("Registration Form", 'eventin'); ?> </h4>
                                            <p>
                                                <?php
                                                if (
                                                    !isset($event_options["etn_hide_seats_from_details"])
                                                ) {
                                                ?>
                                                    <?php echo esc_html($etn_left_tickets); ?>
                                                    <?php echo esc_html__('seats remaining', 'eventin'); ?>
                                                <?php } ?>
                                            </p>
                                            <form action="" method="post">
                                            <input name="add-to-cart" type="hidden" value="<?php echo get_the_id(); ?>" />
                                                <div class="etn-row">
                                                    <div class="etn-qty-field etn-col-lg-6">
                                                        <label for="etn_product_qty">
                                                            <?php echo esc_html__('Quantity', 'eventin'); ?>
                                                        </label>
                                                        <input id="etn_product_qty" class="attr-form-control" name="quantity" type="number" value="1" min="1" data-left_ticket="<?php echo esc_html($etn_left_tickets); ?>" />
                                                    </div>
                                                    <div class="etn-price-field etn-col-lg-6">
                                                        <label for="etn_product_price">
                                                            <?php echo isset($event_options["etn_price_label"]) ? esc_html($event_options["etn_price_label"]) : esc_html__('Price','eventin'); ?>
                                                        </label>
                                                        <input id="etn_product_price" class="attr-form-control" readonly name="price" type="number" value="<?php echo esc_attr($etn_ticket_price); ?>" min="1" />
                                                    </div>
                                                </div>
                                                <div class="etn-total-price">
                                                    <?php echo esc_html__('Total price', 'eventin'); ?>
                                                    <?php echo esc_html(get_woocommerce_currency_symbol()); ?>
                                                    <span id="etn_form_price">
                                                        <?php echo esc_html($etn_ticket_price); ?>
                                                    </span>
                
                                                </div>
                
                                                <?php
                                                if (!isset($event_options["etn_purchase_login_required"])) {
                                                ?>
                                                    <input name="submit" class="etn-btn etn-primary" type="submit" value="Add to cart" />
                                                    <?php
                                                } else {
                                                    if (is_user_logged_in()) {
                                                    ?>
                                                        <input name="submit" class="etn-btn etn-primary" type="submit" value="Add to cart" />
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <small><?php echo esc_html__('Please login to buy ticket!', 'eventin'); ?></small>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </form>
                                        <?php
                                        } else { ?>
                                            <h6><?php echo esc_html__('No Tickets Available!!', 'eventin'); ?></h6>
                                        <?php
                                        }
                                    echo '</div>';
                                }
                        ?>
                    <!-- etn widget end -->

                    <div class="etn-widget etn-event-organizers">
                        <?php
                        if (!isset($event_options["etn_hide_organizers_from_details"])) {
                        ?>
                            <h4 class="etn-widget-title etn-title"><?php echo esc_html__("Organizers", 'eventin'); ?> </h4>
                            <?php
                            $term_details = get_term_by('slug',  $etn_organizer_events, 'etn_speaker_category');
                            $organizer_term_id = $term_details->term_id;

                            $organizer_query_args = array(
                                'posts_per_page'      => -1,
                                'orderby'          => 'post_date',
                                'order' => 'asc',
                                'post_type'        => 'etn-speaker',
                                'post_status'      => 'publish',
                                'suppress_filters' => false,
                            );
                            $organizer_query_args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'etn_speaker_category',
                                    'terms'    =>  $organizer_term_id,
                                    'field' => 'id',
                                    'include_children' => true,
                                    'operator' => 'IN'
                                ),
                            );
                            $etn_event_organizer_query = new \WP_Query($organizer_query_args);
                            if ($etn_event_organizer_query->have_posts()) {
                                while ($etn_event_organizer_query->have_posts()) {
                                    $etn_event_organizer_query->the_post();
                                    $social = get_post_meta(get_the_ID(), 'etn_speaker_socials', true);
                                    $email = get_post_meta(get_the_ID(), 'etn_speaker_website_email', true);
                                    $etn_speaker_company_logo = get_post_meta(get_the_id(), 'etn_speaker_company_logo', true);
                                    $logo = wp_get_attachment_image_src($etn_speaker_company_logo);
                            ?>
                                    <div class="etn-organaizer-item">
                                        <h4 class="etn-organizer-name">
                                            <?php echo esc_html(get_the_title()); ?>
                                        </h4>
                                        <?php if (isset($logo[0])) { ?>
                                            <div class="etn-organizer-logo">
                                                <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php the_title_attribute(); ?>">
                                            </div>
                                        <?php } ?>
                                        <?php if ($email) { ?>
                                            <div class="etn-organizer-email">
                                                <span class="etn-label-name"><?php echo esc_html__('Email :', 'eventin'); ?></span>
                                                <?php echo esc_html($email); ?>
                                            </div>
                                        <?php } ?>
                                        <?php if ($social) { ?>
                                            <div class="etn-social">
                                                <span class="etn-label-name"><?php echo esc_html__('Social :', 'eventin'); ?></span>
                                                <?php if ($social) { ?>
                                                    <?php foreach ($social as $social_value) {  ?>
                                                        <?php $etn_social_class = 'etn-' . str_replace('fab fa-', '', $social_value['icon']); ?>

                                                        <a href="<?php echo esc_url($social_value["etn_social_url"]); ?>" target="_blank" class="<?php echo esc_attr($etn_social_class); ?>" title="<?php echo esc_attr($social_value["etn_social_title"]); ?>"><i class="<?php echo esc_attr($social_value["icon"]); ?>"></i></a>
                                                    <?php  } ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        <?php } ?>
                    </div>
                    <!-- etn widget end -->

                </div>
                <!-- etn sidebar end -->
            </div>
            <!-- col end -->
        </div>
        <!-- Row end -->

        <!-- related post start -->
        <div class="etn-event-related-post">
            <?php
            $etn_term_ids = array();
            if ($etn_terms) {
                foreach ($etn_terms as $terms) {
                    array_push($etn_term_ids, $terms->term_id);
                }
            }
            $term_args = [
                'post__not_in'        => array(get_the_id()),
                'posts_per_page'      => 5,
                'orderby'             => 'asc',
                'post_type' => 'etn',
                'tax_query' => [
                    [
                        'taxonomy' => 'etn_tags',
                        'terms'    => $etn_term_ids
                    ]
                ]
            ];

            $similar_post_query = new \WP_Query($term_args);
            if ($similar_post_query->have_posts()) {
                ?>
                <h3 class="related-post-title"><?php  echo esc_html__('Related Events', 'eventin'); ?></h3> 
                    <div class="etn-row">
                <?php
                while ($similar_post_query->have_posts()) {
                    $similar_post_query->the_post(); ?>
                    <div class="etn-col-lg-4 etn-col-md-6">
                        <div class="etn-event-item">
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
                            <div class="etn-event-content">
                                <?php if (isset($etn_event_location) && $etn_event_location != '') { ?>
                                    <div class="etn-event-location"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($etn_event_location); ?></div>
                                <?php } ?>
                                <h3 class="etn-title etn-event-title"><a href="<?php echo esc_url(get_the_permalink()); ?>"> <?php echo esc_html(get_the_title()); ?></a> </h3>
                                <p><?php echo esc_html(Helper::trim_words(get_the_content(), 8)); ?></p>

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
                        </div>
                    </div>
            <?php } wp_reset_postdata(); ?>
                </div>
            <?php }
            ?>
        </div>
        <!-- related post end -->
    </div>