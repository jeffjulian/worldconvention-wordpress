<?php

namespace Etn\Utils;

use Etn\Core\Metaboxs\Event_meta as Event_meta;

defined('ABSPATH') || exit;
class User_events
{

    public $user_id = null;
    public $etn_event_data = [];
    public $status = true;

    public function search($data)
    {
        return $data;
    }

    public function get_events($id = null)
    {

        if (is_null($id)) {

            $this->user_id = get_current_user_id();
        } else {

            $this->user_id = $id;
        }

        return $this->get_event_from_cpt();
    }

    public function default_event_cpt($organizer_event_fields = [])
    {

        if (is_array($organizer_event_fields)) {

            $organizer_event_fields['post_id']['value']  = '';
            $organizer_event_fields['post_title']['value'] = '';
            $organizer_event_fields['post_title']['label'] = esc_html__('Event Title', 'eventin');

            $organizer_event_fields['post_content']['value']  = '';
            $organizer_event_fields['post_content']['label']  = esc_html__('Event description', 'eventin');

            $organizer_event_fields['post_date']['value']  = '';
            $organizer_event_fields['post_date']['label']  = esc_html__('Date', 'eventin');

            $organizer_event_fields['post_thumbnail_id']['value']  = '';
            $organizer_event_fields['post_thumbnail_id']['label']  = esc_html__('Feature image', 'eventin');
            $organizer_event_fields['post_status']['value']  = '';

            return $organizer_event_fields;
        } else {
            return $organizer_event_fields;
        }
    }


    public function get_single_event($event_id)
    {

        $org_events = new Event_meta();
        $event_fields = $org_events->default_Fields();
        $return_organizer_etn_fields = $this->default_event_cpt($event_fields);
        $single_event = get_post($event_id);

        if ($single_event) {

            $return_organizer_etn_fields['post_id']['value']  = $single_event->ID;

            $return_organizer_etn_fields['post_title']['value'] = $single_event->post_title;
            $return_organizer_etn_fields['post_title']['label'] = esc_html__('Title', 'eventin');

            $return_organizer_etn_fields['post_content']['value']  = $single_event->post_content;
            $return_organizer_etn_fields['post_content']['label']  = esc_html__('Description', 'eventin');

            $return_organizer_etn_fields['post_date']['value']  = $single_event->post_date;
            $return_organizer_etn_fields['post_date']['label']  = esc_html__('Date', 'eventin');

            $return_organizer_etn_fields['post_thumbnail_id']['value']  = get_post_thumbnail_id($single_event->ID);

            $return_organizer_etn_fields['post_status']['value']  = $single_event->post_status;

            if ($single_event->post_status != 'publish') {
                $this->status = false;
            }

            foreach ($event_fields as $orgeventkey => $meta_feild) {

                $return_organizer_etn_fields[$orgeventkey]['value'] = get_post_meta($event_id, $orgeventkey, true);
            }
        }

        return $return_organizer_etn_fields;
    }

    public function get_event_from_cpt()
    {

        $etn_data = [];
        $org_events = new Event_meta();
        $event_fields = $org_events->default_Fields();
        $return_organizer_fields = $this->default_event_cpt($event_fields);

        $events =  $this->get_user_event_data($this->user_id);

        foreach ($events as $event_key => $event) :

            $etn_data[$event_key]['post_id']['value']  = $event->ID;

            $etn_data[$event_key]['post_title']['value'] = $event->post_title;
            $etn_data[$event_key]['post_title']['label'] = esc_html__('Event Title', 'eventin');

            $etn_data[$event_key]['post_content']['value']  = $event->post_content;
            $etn_data[$event_key]['post_content']['label']  = esc_html__('Event description', 'eventin');

            $etn_data[$event_key]['post_date']['value']  = $event->post_date;
            $etn_data[$event_key]['post_date']['label']  = esc_html__('Date', 'eventin');
            $etn_data[$event_key]['post_thumbnail_id']['value']  = get_post_thumbnail_id($event->ID);

            $etn_data[$event_key]['post_status']['value']  = $event->post_status;

            foreach ($event_fields as $orgkey => $meta_feild) :

                $etn_data[$event_key][$orgkey]['value'] = get_post_meta($event->ID, $orgkey, true);

                if (isset($meta_feild['label'])) {
                    $etn_data[$event_key][$orgkey]['label'] = $meta_feild['label'];
                }
                if (isset($meta_feild['type'])) {
                    $etn_data[$event_key][$orgkey]['type'] = $meta_feild['type'];
                }
                if (isset($meta_feild['default'])) {
                    $etn_data[$event_key][$orgkey]['default'] = $meta_feild['default'];
                }
                if (isset($meta_feild['desc'])) {
                    $etn_data[$event_key][$orgkey]['desc'] = $meta_feild['desc'];
                }
                if (isset($meta_feild['attr'])) {
                    $etn_data[$event_key][$orgkey]['attr'] = $meta_feild['attr'];
                }

            endforeach;
        endforeach;

        $this->etn_event_data = $etn_data;
        return $etn_data;
    }

    public function get_null_event_field()
    {

        return $this->get_single_event(0);
    }
}
