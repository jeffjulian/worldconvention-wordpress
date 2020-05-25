<?php

namespace Etn\Utils;

defined('ABSPATH') || exit;
class Utilities
{

   public static function etn_event_attachment_type_is_image($attachment_id = null)
   {
      if (is_null($attachment_id) || $attachment_id == '') {
         return false;
      }

      $path = wp_get_attachment_url($attachment_id);
      if ($path == '') {
         return false;
      }

      $image = getimagesize($path);
      $image_type = $image[2];

      if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
         return true;
      }
      return false;
   }

   public static function sanitize(string $data)
   {
      return strip_tags(
         stripslashes(
            sanitize_text_field(
               filter_input(INPUT_POST, $data)
            )
         )
      );
   }

   public static function get_speakers($id = null)
   {
      $return_organizers = [];
      try {
         if (is_null($id)) {
            $args = [
               'post_type' => 'etn-speaker',
               'post_status'    => 'publish',
               'posts_per_page' => -1
            ];
            $organizers = get_posts($args);
            foreach ($organizers as $value) {
               $return_organizers[$value->ID] = $value->post_title;
            }
            return $return_organizers;
         } else {
            // return single speaker
            return get_post($id);
         }
      } catch (\Exception $es) {
         return [];
      }
   }

   public static function get_speakers_category($id = null)
   {
      $speaker_category = [];
      try {
         if (is_null($id)) {
            $terms = get_terms(array(
               'taxonomy' => 'etn_speaker_category',
               'hide_empty' => false,
            ));

            foreach ($terms as $speakers) {
               $speaker_category[$speakers->term_id] = $speakers->name;
            }
            return $speaker_category;
         } else {
            // return single speaker
            return get_post($id);
         }
      } catch (\Exception $es) {
         return [];
      }
   }
   public static function get_event_category($id = null)
   {
      $event_category = [];
      try {
         if (is_null($id)) {
            $terms = get_terms(array(
               'taxonomy' => 'etn_category',
               'hide_empty' => false,
            ));

            foreach ($terms as $event) {
               $event_category[$event->term_id] = $event->name;
            }
            return $event_category;
         } else {
            // return single speaker
            return get_post($id);
         }
      } catch (\Exception $es) {
         return [];
      }
   }


   public static function get_schedules($id = null)
   {
      $return_schedules = [];
      try {
         if (is_null($id)) {
            $args = [
               'post_type' => 'etn-schedule',
               'post_status'    => 'publish',
               'posts_per_page' => -1
            ];
            $schedules = get_posts($args);
            foreach ($schedules as $value) {
               $schedule_date = get_post_meta($value->ID, 'etn_schedule_date', true);
               $return_schedules[$value->ID] = $value->post_title . " ($schedule_date)";
            }
            return $return_schedules;
         } else {
            // return single speaker
            return get_post($id);
         }
      } catch (\Exception $es) {
         return [];
      }
   }

   public static function get_events($id = null)
   {
      $return_events = [];
      try {
         if (is_null($id)) {
            $args = [
               'post_type' => 'etn',
               'post_status'    => 'publish',
               'posts_per_page' => -1
            ];
            $events = get_posts($args);
            foreach ($events as $value) {
               $return_events[$value->ID] = $value->post_title;
            }
            return $return_events;
         } else {
            // return single speaker
            return get_post($id);
         }
      } catch (\Exception $es) {
         return [];
      }
   }

   public static function get_users($id = null)
   {

      $return_organizers = ['' => esc_html__('select organizer', 'eventin')];
      try {
         $blogusers = get_users(
            [
               'order' => 'DESC',
               'role__in' => ['etn_organizer', 'administrator']
            ]
         );
         foreach ($blogusers as $user) {

            $name = isset($user->display_name) ? $user->display_name : $user->user_nicename;
            $return_organizers[$user->ID] = $name . ' - ' . $user->user_email;
         }
         return $return_organizers;
      } catch (\Exception $es) {
         return [];
      }
   }

   public static function user_can_access($cap = null)
   {
      include_once(ABSPATH . 'wp-includes/pluggable.php');
      if (current_user_can($cap)) {
         return true;
      }
      return false;
   }


   public static function etn_event_manager_fontawesome_icons($prefix = 'fab')
   {
      $prefix = apply_filters('etn_event_social_icons_prefix', $prefix);
      $social_icons = array(
         "$prefix fa-facebook" => esc_html__('facebook', 'eventin'),
         "$prefix fa-facebook-f" => esc_html__('facebook-f', 'eventin'),
         "$prefix fa-facebook-messenger" => esc_html__('facebook-messenger', 'eventin'),
         "$prefix fa-facebook-square" => esc_html__('facebook-square', 'eventin'),
         "$prefix fa-linkedin" => esc_html__('linkedin', 'eventin'),
         "$prefix fa-linkedin-in" => esc_html__('linkedin-in', 'eventin'),
         "$prefix fa-twitter" => esc_html__('twitter', 'eventin'),
         "$prefix fa-twitter-square" => esc_html__('twitter-square', 'eventin'),
         "$prefix fa-uber" => esc_html__('uber', 'eventin'),
         "$prefix fa-google" => esc_html__('google', 'eventin'),
         "$prefix fa-google-drive" => esc_html__('google-drive', 'eventin'),
         "$prefix fa-google-play" => esc_html__('google-play', 'eventin'),
         "$prefix fa-google-wallet" => esc_html__('google-wallet', 'eventin'),
         "$prefix fa-linkedin" => esc_html__('linkedin', 'eventin'),
         "$prefix fa-linkedin-in" => esc_html__('linkedin-in', 'eventin'),
         "$prefix fa-whatsapp" => esc_html__('whatsapp', 'eventin'),
         "$prefix fa-whatsapp-square" => esc_html__('whatsapp-square', 'eventin'),
         "$prefix fa-wordpress" => esc_html__('wordpress', 'eventin'),
         "$prefix fa-wordpress-simple" => esc_html__('wordpress-simple', 'eventin'),
         "$prefix fa-youtube" => esc_html__('youtube', 'eventin'),
         "$prefix fa-youtube-square" => esc_html__('youtube-square', 'eventin'),
         "$prefix fa-xbox" => esc_html__('xbox', 'eventin'),
         "$prefix fa-vk" => esc_html__('vk', 'eventin'),
         "$prefix fa-vnv" => esc_html__('vnv', 'eventin'),
         "$prefix fa-instagram" => esc_html__('instagram', 'eventin'),
         "$prefix fa-reddit" => esc_html__('reddit', 'eventin'),
         "$prefix fa-reddit-alien" => esc_html__('reddit-alien', 'eventin'),
         "$prefix fa-reddit-square" => esc_html__('reddit-square', 'eventin'),
         "$prefix fa-pinterest" => esc_html__('pinterest', 'eventin'),
         "$prefix fa-pinterest-p" => esc_html__('pinterest-p', 'eventin'),
         "$prefix fa-pinterest-square" => esc_html__('pinterest-square', 'eventin'),
         "$prefix fa-tumblr" => esc_html__('tumblr', 'eventin'),
         "$prefix fa-tumblr-square" => esc_html__('tumblr-square', 'eventin'),
         "$prefix fa-flickr" => esc_html__('flickr', 'eventin'),
         "$prefix fa-meetup" => esc_html__('meetup', 'eventin'),
         "$prefix fa-share" => esc_html__('share', 'eventin'),
         "$prefix fa-vimeo-v" => esc_html__('vimeo', 'eventin'),
         "$prefix fa-weixin" => esc_html__('Wechat', 'eventin')
      );

      return apply_filters('etn_social_icons', $social_icons);
   }


   public static function get_organizers($title = null)
   {
      global $wpdb;
      $return_organizers = [];
      try {
         if (is_null($title)) {
            $orgs = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'etn_event_organizer'", ARRAY_A);
            foreach ($orgs as $org) {
               $org_data = unserialize($org['meta_value']);
               $return_organizers[$org_data[0]["etn_organizer_title"]] = $org_data[0]["etn_organizer_title"];
            }
            return $return_organizers;
         } else {
            // return single organizer
            $org = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'etn_event_organizer' AND  meta_value LIKE '%$title%' LIMIT 1", ARRAY_A);
            $org_data = unserialize($org[0]['meta_value']);
            return $org_data[0];
         }
      } catch (\Exception $es) {
         return [];
      }
   }

   public static function get_orgs()
   {
      $return_organizers = [];
      try {
         $terms = get_terms(array(
            'taxonomy' => 'etn_speaker_category',
            'orderby'    => 'count',
            'hide_empty' => false,
            'fields'     => 'all'
         ));
         foreach ($terms as $term) {
            $return_organizers[$term->slug] = $term->name;
         }
         return $return_organizers;
      } catch (\Exception $es) {
         return [];
      }
   }


   public static function etn_cate_with_link($post_id = null, $category, $single = true)
   {
      $terms = get_the_terms($post_id,  $category);
      $category_name = '';

      if (is_array($terms)) :

         foreach ($terms as $tkey => $term) :

            $cat = $term->name;

            $category_name .= sprintf("<span>%s</span>",  $cat);

            if ($single) {
               break;
            }

            if ($tkey == 1) {
               break;
            }

         endforeach;

      endif;
      return $category_name;
   }





   public static function post_kses($raw)
   {

      $allowed_tags = array(
         'a'                         => array(
            'class'    => array(),
            'id' => [],
            'href'    => array(),
            'rel'    => array(),
            'title'    => array(),
         ),
         'input' => [
            'value' => [],
            'type' => [],
            'size' => [],
            'name' => [],
            'checked' => [],
            'placeholder' => [],
            'id' => [],
            'class' => []
         ],

         'select' => [
            'value' => [],
            'type' => [],
            'size' => [],
            'name' => [],
            'placeholder' => [],
            'id' => [],
            'class' => [],
            'option' => [
               'value' => [],
               'checked' => [],
            ]
         ],

         'textarea' => [
            'value' => [],
            'type' => [],
            'size' => [],
            'name' => [],
            'rows' => [],
            'cols' => [],

            'placeholder' => [],
            'id' => [],
            'class' => []
         ],
         'abbr'                      => array(
            'title' => array(),
         ),
         'b'                         => array(),
         'blockquote'                => array(
            'cite' => array(),
         ),
         'cite'                      => array(
            'title' => array(),
         ),
         'code'                      => array(),
         'del'                      => array(
            'datetime'    => array(),
            'title'       => array(),
         ),
         'dd'                      => array(),
         'div'                      => array(
            'class'    => array(),
            'title'    => array(),
            'style'    => array(),
         ),
         'dl'                      => array(),
         'dt'                      => array(),
         'em'                      => array(),
         'h1'                      => array(),
         'h2'                      => array(),
         'h3'                      => array(),
         'h4'                      => array(),
         'h5'                      => array(),
         'h6'                      => array(),
         'i'                         => array(
            'class' => array(),
         ),
         'img'                      => array(
            'alt'    => array(),
            'class'    => array(),
            'height' => array(),
            'src'    => array(),
            'width'    => array(),
         ),
         'li'                      => array(
            'class' => array(),
         ),
         'ol'                      => array(
            'class' => array(),
         ),
         'p'                         => array(
            'class' => array(),
         ),
         'q'                         => array(
            'cite'    => array(),
            'title'    => array(),
         ),
         'span'                      => array(
            'class'    => array(),
            'title'    => array(),
            'style'    => array(),
         ),
         'iframe'                   => array(
            'width'          => array(),
            'height'       => array(),
            'scrolling'       => array(),
            'frameborder'    => array(),
            'allow'          => array(),
            'src'          => array(),
         ),
         'strike'                   => array(),
         'label'                   => array(),
         'br'                      => array(),
         'strong'                   => array(),
         'data-wow-duration'             => array(),
         'data-wow-delay'             => array(),
         'data-wallpaper-options'       => array(),
         'data-stellar-background-ratio'    => array(),
         'ul'                      => array(
            'class' => array(),
         ),
      );

      if (function_exists('wp_kses')) { // WP is here
         $allowed = wp_kses($raw, $allowed_tags);
      } else {
         $allowed = $raw;
      }

      return $allowed;
   }
}
