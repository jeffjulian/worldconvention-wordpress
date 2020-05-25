<?php

namespace Etn\Utils;

use Etn\Core\Metaboxs\Schedule_meta;

defined('ABSPATH') || exit;
class User_event_schedule
{
    public $user_id = null;
    public $etn_event_data = [];
    public $status = true;

    public function get_schedules($id = null)
    {
        if (is_null($id)) {
            $this->user_id = get_current_user_id();
        } else {
            $this->user_id = $id;
        }
        return $this->get_schedule_from_cpt();
    }

    public function default_schedule_cpt($organizer_event_schedule_fields = [])
    {
        if (is_array($organizer_event_schedule_fields)) {

            $organizer_event_schedule_fields['post_id']['value']  = '';
            $organizer_event_schedule_fields['post_title']['value'] = '';
            $organizer_event_schedule_fields['post_title']['label'] = esc_html__('Schedule Title', 'eventin');

            $organizer_event_schedule_fields['post_content']['value']  = '';
            $organizer_event_schedule_fields['post_content']['label']  = esc_html__('Schedule description', 'eventin');

            $organizer_event_schedule_fields['post_date']['value']  = '';
            $organizer_event_schedule_fields['post_date']['label']  = esc_html__('Date', 'eventin');

            $organizer_event_schedule_fields['post_category']['value']  = [];
            $organizer_event_schedule_fields['post_category']['label']  = esc_html__('Category', 'eventin');
            $organizer_event_schedule_fields['post_thumbnail_id']['value']  = '';
            $organizer_event_schedule_fields['post_thumbnail_id']['label']  = esc_html__('Feature image', 'eventin');
            $organizer_event_schedule_fields['post_status']['value']  = '';

            return $organizer_event_schedule_fields;
        } else {
            return $organizer_event_schedule_fields;
        }
    }

    public function get_user_schedule_data($id)
    {
        $args = array(
            'author' => $id,
            'post_type' => 'etn-schedule',
            'posts_per_page' => -1,
        );
        $results = get_posts($args);

        return $results;
    }

    public function get_single_schedule($schedule_id)
    {
        $org_events = new Schedule_meta();
        $event_fields = $org_events->default_Fields();
        $return_organizer_etn_fields = $this->default_schedule_cpt($event_fields);
        $single_schedule = get_post($schedule_id);

        if ($single_schedule) {

            $return_organizer_etn_fields['post_id']['value']  = $single_schedule->ID;

            $return_organizer_etn_fields['post_title']['value'] = $single_schedule->post_title;
            $return_organizer_etn_fields['post_title']['label'] = esc_html__('Organizer Title', 'eventin');

            $return_organizer_etn_fields['post_content']['value']  = $single_schedule->post_content;
            $return_organizer_etn_fields['post_content']['label']  = esc_html__('Organizer description', 'eventin');

            $return_organizer_etn_fields['post_date']['value']  = $single_schedule->post_date;
            $return_organizer_etn_fields['post_date']['label']  = esc_html__('Date', 'eventin');

            $return_organizer_etn_fields['post_thumbnail_id']['value']  = get_post_thumbnail_id($single_schedule->ID);

            $return_organizer_etn_fields['post_status']['value']  = $single_schedule->post_status;

            if ($single_schedule->post_status != 'publish') {
                $this->status = false;
            }

            foreach ($event_fields as $orgeventkey => $meta_feild) {

                $return_organizer_etn_fields[$orgeventkey]['value'] = get_post_meta($schedule_id, $orgeventkey, true);
            }
        }

        return $return_organizer_etn_fields;
    }

    public function get_schedule_from_cpt()
    {
        $etn_data = [];
        $org_etn_schedule = new Schedule_meta();
        $event_fields = $org_etn_schedule->default_Fields();
        $return_organizer_fields = $this->default_schedule_cpt($event_fields);
        $schedules =  $this->get_user_schedule_data($this->user_id);

        foreach ($schedules as $schedule_key => $schedule) :

            $etn_data[$schedule_key]['post_id']['value']  = $schedule->ID;

            $etn_data[$schedule_key]['post_title']['value'] = $schedule->post_title;
            $etn_data[$schedule_key]['post_title']['label'] = esc_html__('Event Title', 'eventin');

            $etn_data[$schedule_key]['post_content']['value']  = $schedule->post_content;
            $etn_data[$schedule_key]['post_content']['label']  = esc_html__('Event description', 'eventin');

            $etn_data[$schedule_key]['post_date']['value']  = $schedule->post_date;
            $etn_data[$schedule_key]['post_date']['label']  = esc_html__('Date', 'eventin');

            $etn_data[$schedule_key]['post_thumbnail_id']['value']  = get_post_thumbnail_id($schedule->ID);
            $etn_data[$schedule_key]['post_status']['value']  = $schedule->post_status;

            foreach ($event_fields as $orgkey => $meta_feild) :

                $etn_data[$schedule_key][$orgkey]['value'] = get_post_meta($schedule->ID, $orgkey, true);

                if (isset($meta_feild['label'])) {
                    $etn_data[$schedule_key][$orgkey]['label'] = $meta_feild['label'];
                }
                if (isset($meta_feild['type'])) {
                    $etn_data[$schedule_key][$orgkey]['type'] = $meta_feild['type'];
                }
                if (isset($meta_feild['default'])) {
                    $etn_data[$schedule_key][$orgkey]['default'] = $meta_feild['default'];
                }
                if (isset($meta_feild['desc'])) {
                    $etn_data[$schedule_key][$orgkey]['desc'] = $meta_feild['desc'];
                }
                if (isset($meta_feild['attr'])) {
                    $etn_data[$schedule_key][$orgkey]['attr'] = $meta_feild['attr'];
                }

            endforeach;
        endforeach;

        $this->etn_event_data = $etn_data;
        return $etn_data;
    }
    public function get_null_schedule_field()
    {

        $event  = $this->get_single_schedule(0);
        return $event;
    }
}
