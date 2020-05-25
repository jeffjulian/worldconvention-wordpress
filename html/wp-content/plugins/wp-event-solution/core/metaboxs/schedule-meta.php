<?php

namespace Etn\Core\Metaboxs;

use Etn\Utils\Utilities as Utilities;

defined('ABSPATH') || exit;

class Schedule_meta extends Event_manager_metabox
{
   public $metabox_id = 'etn_schedule_settings';
   public $schedule_fields = [];
   public $cpt_id = 'etn-schedule';
   public function __construct()
   {
   }

   public function register_meta_boxes()
   {
      add_meta_box($this->metabox_id, __('Schedule info', 'eventin'), [$this, 'display_callback'], $this->cpt_id);
   }

   public function default_Fields()
   {
      $this->schedule_fields = [
         'etn_schedule_title' => [
            'label' => esc_html__('Title', 'eventin'),
            'type' => 'text',
            'default' => '',
            'value' => '',
            'desc' => esc_html__('Place schedule title','eventin'),
            'priority' => 1,
            'attr' => ['class' => 'etn-label-item'],
            'required' => true,
         ],
         'etn_schedule_date' => [
            'label' => esc_html__('Date', 'eventin'),
            'desc' => esc_html__('Select schedule date','eventin'),
            'type'       => 'date',
            'inline' => false,
            'timestamp' => false,
            'priority' => 1,
            'attr' => ['class' => 'etn-label-item'],
            'required' => true,
         ],
         'etn_schedule_day' => [
            'label' => esc_html__('Day', 'eventin'),
            'type' => 'text',
            'default' => '',
            'value' => '',
            'desc' => esc_html__('Select schedule day','eventin'),
            'priority' => 1,
            'attr' => ['class' => 'etn-label-item'],
            'required' => true,
         ],
         'etn_schedule_topics' => [
            'label'   => esc_html__('Schedule List', 'eventin'),
            'type'    => 'repeater',
            'default' => '',
            'value'   => '',
            'options' => [

               'etn_schedule_topic' => [
                  'label' => esc_html__('Topic', 'eventin'),
                  'type' => 'text',
                  'default' => '',
                  'value' => '',
                  'desc' => esc_html__('Place schedule topic','eventin'),
                  'priority' => 1,
                  'attr' => ['class' => ''],
                  'required' => true,
               ],
               'etn_shedule_start_time' => [
                  'label' => esc_html__('Start Time', 'eventin'),
                  'type' => 'time',
                  'default' => '',
                  'value' => '',
                  'desc' => esc_html__('Select start time ','eventin'),
                  'priority' => 1,
                  'attr' => ['class' => ''],
                  'required' => true,
               ],
               'etn_shedule_end_time' => [
                  'label' => esc_html__('End Time', 'eventin'),
                  'type' => 'time',
                  'default' => '',
                  'value' => '',
                  'desc' => esc_html__('Select end time ','eventin'),
                  'priority' => 1,
                  'attr' => ['class' => ''],
                  'required' => true,
               ],
               'etn_shedule_room' => [
                  'label' => esc_html__('Location', 'eventin'),
                  'type' => 'text',
                  'default' => '',
                  'value' => '',
                  'desc' => esc_html__('Place location here ','eventin'),
                  'priority' => 1,
                  'attr' => ['class' => ''],
                  'required' => true,
               ],
               'etn_shedule_speaker' => [
                  'label' => esc_html__('Speaker', 'eventin'),
                  'type' => 'select2',
                  'multiple' => true,
                  'default' => '',
                  'value' => '',
                  'options' => Utilities::get_speakers(),
                  'priority' => 1,
                  'desc' => esc_html__('Select speaker ','eventin'),
                  'required' => true,
                  'attr' => ['class' => 'etn-event-speakers-section'],
               ],
               'etn_shedule_objective' => [
                  'label' => esc_html__('Objective', 'eventin'),
                  'type' => 'textarea',
                  'default' => '',
                  'value' => '',
                  'desc' => esc_html__('Place your objective','eventin'),
                  'attr' => [
                     'class' => 'schedule',
                     'row' => 14,
                     'col' => 50
                  ],
                  'settings' => [],
                  'priority' => 1,
                  'required' => true,
               ],
            ],
            'desc' => '',
            'attr' => ['class' => ''],
            'priority' => 1,
            'required' => true,
         ],
      ];

      return $this->schedule_fields;
   }

   function etn_set_schedule_title($data, $postarr)
   {
      if ('etn-schedule' == $data['post_type']) {
         if (isset($postarr['etn_schedule_title'])) {
            $schedule_title = sanitize_text_field( $postarr['etn_schedule_title'] );
         } else {
            $schedule_title = get_post_meta($postarr['ID'], 'etn_schedule_title', true);
         }

         $post_slug = sanitize_title_with_dashes($schedule_title, '', 'save');
         $schedule_slug = sanitize_title($post_slug);

         $data['post_title'] = $schedule_title;
         $data['post_name'] = $schedule_slug;
      }
      return $data;
   }
}
