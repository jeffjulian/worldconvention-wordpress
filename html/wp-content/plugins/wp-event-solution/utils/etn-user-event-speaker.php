<?php

namespace Etn\Utils;

use Etn\Core\Metaboxs\Speaker_meta;

defined('ABSPATH') || exit;
class User_event_speaker
{
    public $user_id = null;
    public $etn_event_data = [];
    public $status = true;

    public function get_speakers($id = null)
    {
        if (is_null($id)) {
            $this->user_id = get_current_user_id();
        } else {
            $this->user_id = $id;
        }
        return $this->get_speaker_from_cpt();
    }

    public function default_speaker_cpt($organizer_event_speaker_fields = [])
    {
        if (is_array($organizer_event_speaker_fields)) {

            $organizer_event_speaker_fields['post_id']['value']  = '';
            $organizer_event_speaker_fields['post_title']['value'] = '';
            $organizer_event_speaker_fields['post_title']['label'] = esc_html__('Speaker Title', 'eventin');

            $organizer_event_speaker_fields['post_content']['value']  = '';
            $organizer_event_speaker_fields['post_content']['label']  = esc_html__('Speaker description', 'eventin');

            $organizer_event_speaker_fields['post_date']['value']  = '';
            $organizer_event_speaker_fields['post_date']['label']  = esc_html__('Date', 'eventin');

            $organizer_event_speaker_fields['post_category']['value']  = [];
            $organizer_event_speaker_fields['post_category']['label']  = esc_html__('Category', 'eventin');
            $organizer_event_speaker_fields['post_thumbnail_id']['value']  = '';
            $organizer_event_speaker_fields['post_thumbnail_id']['label']  = esc_html__('Feature image', 'eventin');
            $organizer_event_speaker_fields['post_status']['value']  = '';

            return $organizer_event_speaker_fields;
        } else {
            return $organizer_event_speaker_fields;
        }
    }

    public function get_user_speaker_data($user_id)
    {
        $args = array(
            'author' => $user_id,
            'post_type' => 'etn-speaker',
            'posts_per_page' => -1,
        );
        $results = get_posts($args);

        return $results;
    }

    public function get_single_speaker($speaker_id)
    {
        $org_events = new Speaker_meta();
        $event_fields = $org_events->default_Fields();
        $return_organizeetn_fields = $this->default_speaker_cpt($event_fields);
        $single_speaker = get_post($speaker_id);

        if ($single_speaker) {

            $return_organizer_etn_fields['post_id']['value']  = $single_speaker->ID;

            $return_organizer_etn_fields['post_title']['value'] = $single_speaker->post_title;
            $return_organizer_etn_fields['post_title']['label'] = esc_html__('Organizer Title', 'eventin');

            $return_organizer_etn_fields['post_content']['value']  = $single_speaker->post_content;
            $return_organizer_etn_fields['post_content']['label']  = esc_html__('Organizer description', 'eventin');

            $return_organizer_etn_fields['post_date']['value']  = $single_speaker->post_date;
            $return_organizer_etn_fields['post_date']['label']  = esc_html__('Date', 'eventin');

            $return_organizer_etn_fields['post_thumbnail_id']['value']  = get_post_thumbnail_id($single_speaker->ID);

            $return_organizer_etn_fields['post_status']['value']  = $single_speaker->post_status;

            if ($single_speaker->post_status != 'publish') {
                $this->status = false;
            }

            foreach ($event_fields as $orgeventkey => $meta_feild) {

                $return_organizer_etn_fields[$orgeventkey]['value'] = get_post_meta($speaker_id, $orgeventkey, true);
            }
        }

        return $return_organizer_etn_fields;
    }

    public function get_speaker_from_cpt()
    {

        $etn_data = [];
        $org_etn_speaker = new Speaker_meta();
        $event_fields = $org_etn_speaker->default_Fields();
        $return_organizer_fields = $this->default_speaker_cpt($event_fields);
        $speakers =  $this->get_user_speaker_data($this->user_id);

        foreach ($speakers as $speaker_key => $speaker) :

            $etn_data[$speaker_key]['post_id']['value']  = $speaker->ID;

            $etn_data[$speaker_key]['post_title']['value'] = $speaker->post_title;
            $etn_data[$speaker_key]['post_title']['label'] = esc_html__('Event Title', 'eventin');

            $etn_data[$speaker_key]['post_content']['value']  = $speaker->post_content;
            $etn_data[$speaker_key]['post_content']['label']  = esc_html__('Event description', 'eventin');

            $etn_data[$speaker_key]['post_date']['value']  = $speaker->post_date;
            $etn_data[$speaker_key]['post_date']['label']  = esc_html__('Date', 'eventin');

            $etn_data[$speaker_key]['post_thumbnail_id']['value']  = get_post_thumbnail_id($speaker->ID);
            $etn_data[$speaker_key]['post_status']['value']  = $speaker->post_status;

            foreach ($event_fields as $orgkey => $meta_feild) :

                $etn_data[$speaker_key][$orgkey]['value'] = get_post_meta($speaker->ID, $orgkey, true);

                if (isset($meta_feild['label'])) {
                    $etn_data[$speaker_key][$orgkey]['label'] = $meta_feild['label'];
                }
                if (isset($meta_feild['type'])) {
                    $etn_data[$speaker_key][$orgkey]['type'] = $meta_feild['type'];
                }
                if (isset($meta_feild['default'])) {
                    $etn_data[$speaker_key][$orgkey]['default'] = $meta_feild['default'];
                }
                if (isset($meta_feild['desc'])) {
                    $etn_data[$speaker_key][$orgkey]['desc'] = $meta_feild['desc'];
                }
                if (isset($meta_feild['attr'])) {
                    $etn_data[$speaker_key][$orgkey]['attr'] = $meta_feild['attr'];
                }

            endforeach;
        endforeach;

        $this->etn_event_data = $etn_data;
        return $etn_data;
    }
    public function get_null_speaker_field()
    {

        $event  = $this->get_single_speaker(0);
        return $event;
    }
}
