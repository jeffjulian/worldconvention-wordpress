<?php

namespace Etn\Core\Shortcodes;

use \Etn\Utils\Utilities as Utilities;
use \Etn\Utils\Helper as Helper;

defined('ABSPATH') || exit;
class Hooks
{

  use \Etn\Traits\Singleton;

  public function Init()
  {
    //[events limit='1'/]
    add_shortcode("events", [$this, "etn_events_widget"]);

    //[speakers cat_id='19' limit='3'/]
    add_shortcode("speakers", [$this, "etn_speakers_widget"]);

    //[schedules ids ='18,19'/]
    add_shortcode("schedules", [$this, "etn_schedules_widget"]);
  }

  public function etn_events_widget($attributes)
  {

    $etn_event_args = array(
      'posts_per_page'   => isset($attributes["limit"]) ?  $attributes["limit"] : 3,
      'orderby'          => 'post_date',
      'order'            => 'ASC',
      'post_type'        => 'etn',
      'post_status'      => 'publish',
      'suppress_filters' => false,

    );
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
    $event_query = new \WP_Query($etn_event_args);
    $div_inner_html = "";
    if ($event_query->have_posts()) {
      while ($event_query->have_posts()) {
        $event_query->the_post();
        $etn_event_location = get_post_meta(get_the_ID(), 'etn_event_location', true);
        
        $etn_start_date = get_post_meta(get_the_ID(), 'etn_start_date', true);
        $event_start_date = isset($event_options["date_format"]) ? date($date_options[$event_options["date_format"]], strtotime($etn_start_date)) : date('d/m/Y', strtotime($etn_start_date) );

        $category =  Utilities::etn_cate_with_link(get_the_ID(), 'etn_category');

        $div_inner_html .= "<div class='etn-col-lg-4 etn-col-md-6'>
                              <div class='etn-event-item'>";
        if (has_post_thumbnail()) {
          $event_permalink = esc_url(get_the_permalink());
          $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID(), 'full' ), 'full');
          $div_inner_html .= "  <div class='etn-event-thumb'>
                                  <a href='". esc_url($event_permalink) ."'>
                                    <img src='" . esc_url($image_url[0]). "' />
                                  </a>
                                  <div class='etn-event-category'>
                                  ". Helper::kses($category) ."
                                  </div>
                                </div>";
        }
        $div_inner_html .=  "<div class='etn-event-content'>";
        if (isset($etn_event_location) && $etn_event_location != '') {
          $div_inner_html .= "<div class='etn-event-location'>
                                <i class='fas fa-map-marker-alt'></i>" .   esc_html($etn_event_location) . 
                              "</div>";
        }
        $div_inner_html .= "        <h3 class='etn-title etn-event-title'>
                                      <a href='" . esc_url(get_the_permalink()) . "'>" . esc_html(get_the_title()) . "</a> 
                                    </h3>
                                    <p>" . esc_html(Helper::trim_words(get_the_content(), 15)) . "</p>
                                    <div class='etn-event-footer'>
                                      <div class='etn-event-date'>
                                        <i class='far fa-calendar-alt'></i> ".  esc_html($event_start_date) ."
                                      </div> 
                                      <div class='etn-atend-btn'>
                                        <a href='" . esc_url(get_the_permalink()) . "' class='etn-btn etn-btn-border'>" . esc_html__('attend', 'eventin') . " 
                                          <i class='fas fa-arrow-right'></i>
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>";
      }
    }
    wp_reset_query();
    $etn_event_final_markup = sprintf("<div class='etn-row'>
                                        %s
                                      </div>", $div_inner_html);
    return $etn_event_final_markup;
  }

  public function etn_speakers_widget($attributes)
  {

    $args = array(
      'posts_per_page'      =>  isset($attributes["limit"]) ?  $attributes["limit"] : 3,
      'orderby'          => 'post_date',
      'order' => 'ASC',
      'post_type'        => 'etn-speaker',
      'post_status'      => 'publish',
      'suppress_filters' => false,
    );
    $args['tax_query'] = array(
      array(
        'taxonomy' => 'etn_speaker_category',
        'terms'    => $attributes["cat_id"],
        'field' => 'id',
        'include_children' => true,
        'operator' => 'IN'
      ),
    );
    $speakers_query = new \WP_Query($args);
    $div_inner_html = "";
    if ($speakers_query->have_posts()) {
      while ($speakers_query->have_posts()) {
        $speakers_query->the_post();
        $etn_speaker_designation = get_post_meta(get_the_ID(), 'etn_speaker_designation', true);
        $social = get_post_meta(get_the_ID(), 'etn_speaker_socials', true);

        $div_inner_html .= "<div class='etn-col-sm-4'>
                              <div class='etn-speaker-item'>
                                  <div class='etn-speaker-thumb'>";
        if (has_post_thumbnail()) {
          $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID(), 'full' ), 'full');
        
          $div_inner_html .= "<a href='" . esc_url(get_the_permalink()) . "'><img src='" . esc_url($image_url[0]). "' /></a>";
        }
        $div_inner_html .= "<div class='etn-speakers-social'>";
        if (isset($social)) {
          foreach ($social as $social_value) {
            $social_url = $social_value["etn_social_url"];
            $social_title = $social_value["etn_social_title"];
            $social_icon = $social_value["icon"];
            $div_inner_html .= "<a href='" . esc_url($social_url) . "' title='" . esc_attr($social_title) . "'><i class='" . esc_attr($social_icon) . "'></i></a>";
          }
        }
        $div_inner_html .= "        </div>
                                  </div>
                                  <div class='etn-speaker-content'>
                                  <h3 class='etn-title etn-speaker-title'>
                                    <a href='" . esc_url(get_the_permalink()) . "'>" . esc_html(get_the_title()) . "</a> 
                                  </h3>
                                  <p>" . esc_html($etn_speaker_designation) . "</p>
                                </div>
                              </div>
                            </div>";
      }
    }
    wp_reset_query();
    $etn_speaker_final_markup = sprintf(
                            "<div class='etn-row etn-speaker-wrapper'>
                              %s
                            </div>", $div_inner_html);
    return $etn_speaker_final_markup;
  }



public function etn_schedules_widget($attributes)
{
  $etn_schedule_ids = explode(",", $attributes["ids"]);

  $args = array(
    'post__in' => $etn_schedule_ids,
    'orderby'          => 'post_date',
    'order' => isset($attributes["order"]) ? $attributes["order"] : 'asc',
    'post_type'        => 'etn-schedule',
    'post_status'      => 'publish',
    'suppress_filters' => false,
  );
  $schedule_query = new \WP_Query($args);
  global $post;
  $tab_titles = "";
  $tab_contents = "";
  $i = -1;
  foreach ($schedule_query->posts as $post) {
      $i++;
      $schedule_meta = get_post_meta($post->ID);
      $schedule_date = strtotime($schedule_meta['etn_schedule_date'][0]);
      $schedule_topics = unserialize($schedule_meta['etn_schedule_topics'][0]);
      $schedule_date = date("d M", $schedule_date);
      $active_class = (($i == 0) ? 'etn-active' : ' ');
      $active_tab = (($i == 0) ? 'tab-active' : ' ');
      $tab_titles .= " <li>
                      <a href='#' data-id='tab{$i}' class='etn-tab-a " . esc_attr($active_class) . "'>
                      <span class='etn-date'>" . esc_html($schedule_date) . "</span>
                        <span class=etn-day>" . esc_html($post->post_title) . "</span>
                      </a>
                      </li>";
      $tab_contents .= "<!-- start repeatable item -->
      <div class='etn-tab " . esc_attr($active_tab) .
      "' data-id='tab{$i}'>";
      foreach ($schedule_topics as $topic) {
      $etn_schedule_topic = (isset($topic['etn_schedule_topic']) ? $topic['etn_schedule_topic'] : '');
      $etn_schedule_start_time = date('h:i a', strtotime($topic['etn_shedule_start_time']));
      $etn_schedule_end_time = date('h:i a', strtotime($topic['etn_shedule_end_time']));
      $etn_schedule_room = (isset($topic['etn_shedule_room']) ? $topic['etn_shedule_room'] : '');
      $etn_schedule_speaker = (isset($topic['etn_shedule_speaker']) ? $topic['etn_shedule_speaker'] : []);
      $etn_schedule_objective = (isset($topic['etn_shedule_objective'])? $topic['etn_shedule_objective'] : '');
      $etn_speaker_block = '';
      if (count($etn_schedule_speaker)>0) {
        foreach ($etn_schedule_speaker as $key => $value) {
            $speaker_thumbnail = get_the_post_thumbnail_url($value);
            $etn_schedule_single_speaker = get_post($value);
            $etn_speaker_permalink = get_post_permalink($value);
            $speaker_title = $etn_schedule_single_speaker->post_title;
            
            $etn_speaker_block .= "<div class='etn-schedule-single-speaker'>";
            $etn_speaker_block .= "<a href='".esc_url($etn_speaker_permalink)."'>";
            $etn_speaker_block .= "<img src='".esc_url( $speaker_thumbnail)."' alt=''>";
            $etn_speaker_block .= "</a>";
            $etn_speaker_block .= "<span class='etn-schedule-speaker-title'>".$speaker_title."</span>";  
            $etn_speaker_block .= "</div>";        
        }
    }
      $tab_contents .= sprintf("<div class='etn-single-schedule-item etn-row'>
          <div class='etn-schedule-info etn-col-lg-3 etn-col-sm-12'>
          <span class='etn-schedule-time'> %s - %s</span>
          <span class='etn-schedule-location'><i class='fas fa-map-marker-alt'></i> %s </span>
          </div>
          <div class='etn-schedule-content etn-col-lg-6 etn-col-sm-6'>
          <h4 class='etn-title'>%s</h4>
          <p> %s </p>
          </div>
          <div class='etn-col-lg-3 etn-col-sm-3'>
          <div class='etn-schedule-right-content'>
          <div class='etn-schedule-speaker'>
            $etn_speaker_block
          </div>
          </div>
          </div>
          </div>", esc_html($etn_schedule_start_time),  esc_html($etn_schedule_end_time),
                    esc_html($etn_schedule_topic), esc_html($etn_schedule_objective), 
                    esc_html($etn_schedule_room));
      }
      $tab_contents .= "</div><!-- end repeatable item -->";
  }

  $final_markup = sprintf( "
  <!-- schedule tab start -->
  <div class='schedule-tab-wrapper'>
      <ul class='etn-nav'>
        %s
      </ul>
      <div class='etn-tab-content clearfix etn-schedule-wrap'>
          %s
      </div>
  </div>
  <!-- schedule tab end -->
  ", $tab_titles, $tab_contents);
  return  $final_markup;
}
}
?>