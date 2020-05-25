<?php
$etn_schedule_order = $settings["etn_schedule_order"];
$etn_schedule_ids = $settings["schedule_id"];

$args = array(
    'post__in' => $etn_schedule_ids,
    'orderby'          => 'post_date',
    'order' => isset($etn_schedule_order) ? $etn_schedule_order : 'asc',
    'post_type'        => 'etn-schedule',
    'post_status'      => 'publish',
    'suppress_filters' => false,
    'posts_per_page' => 3

);
$schedule_query = new \WP_Query($args);
global $post;
$i = -1;
?>

<!-- schedule tab start -->
<div class="schedule-tab-wrapper">
    <ul class='etn-nav'>
        <?php
        $i = -1;
        foreach ($schedule_query->posts as $post) :
         
            $i++;
            $schedule_meta = get_post_meta($post->ID);
            $schedule_date = strtotime($schedule_meta['etn_schedule_date'][0]);
            $schedule_date = date("d M", $schedule_date);
            $active_class = (($i == 0) ? 'etn-active' : ' ');
        ?>
            <li>
                <a href='#' class='etn-tab-a <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($this->get_id()) . "-" . $i; ?>'>
                    <span class='etn-date'><?php echo esc_html($schedule_date); ?></span>
                    <span class=etn-day><?php echo esc_html($post->post_title); ?></span>
                </a>
               
            </li>
        <?php endforeach; ?>
    </ul>
    <div class='etn-tab-content clearfix etn-schedule-wrap'>
        <?php
            $event_options = get_option("etn_event_options");
            $event_options["time_format"] == '' ?  $event_options["time_format"] = '12' : $event_options["time_format"];
            $etn_sched_time_format = $event_options["time_format"] == '24' ? "H:i":"h:i a"; 

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
            <div class='etn-tab <?php echo esc_attr($active_class); ?>' data-id='tab<?php echo esc_attr($this->get_id()) . "-" . $j; ?>'>
                <?php
                foreach ($schedule_topics as $topic) :
                    $etn_schedule_topic = (isset($topic['etn_schedule_topic']) ? $topic['etn_schedule_topic'] : '');
                    $etn_schedule_start_time = date($etn_sched_time_format, strtotime($topic['etn_shedule_start_time']));
                    $etn_schedule_end_time = date($etn_sched_time_format, strtotime($topic['etn_shedule_end_time']));
                    $etn_schedule_room = (isset($topic['etn_shedule_room']) ? $topic['etn_shedule_room'] : '');
                    $etn_schedule_objective = (isset($topic['etn_shedule_objective']) ? $topic['etn_shedule_objective'] : '');
                    $etn_schedule_speaker = (isset($topic['etn_shedule_speaker']) ? $topic['etn_shedule_speaker'] : []);
                ?>
                    <div class='etn-single-schedule-item etn-row'>
                        <div class='etn-schedule-info etn-col-lg-3 etn-col-sm-3'>
                            <span class='etn-schedule-time'><?php echo esc_html($etn_schedule_start_time) . " - " . esc_html($etn_schedule_end_time); ?></span>
                            <span class='etn-schedule-location'>
                            <i class='fas fa-map-marker-alt'></i>
                            <?php echo esc_html($etn_schedule_room);?>
                        </span>
                        </div>
                        <div class='etn-schedule-content etn-col-lg-6 etn-col-sm-6'>
                            <h4 class='etn-title'><?php echo esc_html($etn_schedule_topic); ?></h4>
                            <p><?php echo esc_html($etn_schedule_objective); ?></p>
                        </div>
                        <div class='etn-col-lg-3 etn-col-sm-3'>
                            <div class='etn-schedule-right-content'>

                                    <div class='etn-schedule-single-speaker'>
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
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- end repeatable item -->
        <?php endforeach;
        wp_reset_postdata(); ?>
    </div>
</div>
<!-- schedule tab end -->