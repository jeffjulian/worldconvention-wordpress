<?php

namespace Etn\Core\Metaboxs;

use Etn\Utils\Utilities as Utilities;

defined('ABSPATH') || exit;

class Event_meta extends Event_manager_metabox
{
   public $metabox_id = 'etn_event_settings';
   public $event_fields = [];
   public $cpt_id = 'etn';
   public function __construct()
   {
   }
   public function register_meta_boxes()
   {
      add_meta_box($this->metabox_id, __('Eventin Event Settings', 'eventin'), [$this, 'display_callback'], $this->cpt_id);
   }

   public function default_Fields()
   {
      $this->event_fields = [
         'etn_event_location' => [
            'label' => esc_html__('Event Location', 'eventin'),
            'desc' => esc_html__('Place event location', 'eventin'),
            'type' => 'text',
            'default' => '',
            'value' => '',
            'priority' => 1,
            'required' => true,
            'attr' => ['class' => 'etn-label-item'],
         ],

         'etn_event_schedule' => [
            'label' => esc_html__('Event Schedule', 'eventin'),
            'type' => 'select2',
            'options' => Utilities::get_schedules(),
            'priority' => 1,
            'required' => true,
            'attr' => ['class' => 'etn-label-item'],
         ],
         'etn_event_organizer' => [
            'label'   => esc_html__('Organizers', 'eventin'),
            'desc' => esc_html__('Select organizer','eventin'),
            'type' => 'select_single',
            'options' => Utilities::get_orgs(),
            'priority' => 1,
            'required' => true,
            'attr' => ['class' => 'etn-label-item'],
         ],

         'etn_start_date' => [
            'label' => esc_html__('Start date', 'eventin'),
            'desc' => esc_html__('Select start date','eventin'),
            'type' => 'text',
            'default' => '',
            'value' => '',
            'priority' => 1,
            'required' => false,
            'attr' => ['class' => 'etn-label-item'],
         ],

         'etn_start_time' => [
            'label' => esc_html__('Start time', 'eventin'),
            'desc' => esc_html__('Select start time','eventin'),
            'type' => 'time',
            'default' => '',
            'value' => '',
            'priority' => 1,
            'required' => false,
            'attr' => ['class' => 'etn-label-item'],
         ],

         'etn_end_date' => [
            'label' => esc_html__('End date', 'eventin'),
            'type' => 'text',
            'default' => '',
            'value' => '',
            'desc' => esc_html__('Select end date','eventin'),
            'priority' => 1,
            'required' => false,
            'attr' => ['class' => 'etn-label-item'],
         ],

         'etn_end_time' => [
            'label' => esc_html__('End time', 'eventin'),
            'type' => 'time',
            'default' => '',
            'desc' => esc_html__('Select end time','eventin'),
            'value' => '',
            'priority' => 1,
            'required' => false,
            'attr' => ['class' => 'etn-label-item'],
         ],

         'etn_registration_deadline' => [
            'label' => esc_html__('Registration deadline', 'eventin'),
            'type' => 'text',
            'default' => '',
            'desc' => esc_html__('Select registration deadline','eventin'),
            'value' => '',
            'priority' => 1,
            'required' => false,
            'attr' => ['class' => 'etn-label-item'],
         ],
         'etn_avaiilable_tickets' => [
            'label' => esc_html__('Available Tickets', 'eventin'),
            'type' => 'number',
            'default' => '',
            'value' => '',
            'desc' => esc_html__('Total no of ticket','eventin'),
            'priority' => 1,
            'required' => true,
            'attr' => ['class' => 'etn-label-item'],
         ],

         'etn_ticket_price' => [
            'label' => esc_html__('Ticket Price', 'eventin'),
            'type' => 'number',
            'default' => '',
            'value' => '',
            'desc' => esc_html__('Par ticket price','eventin'),
            'priority' => 1,
            'step' => 0.01,
            'required' => true,
            'attr' => ['class' => 'etn-label-item'],
         ],


         'etn_event_socials' => [
            'label' => esc_html__('Social', 'eventin'),
            'type' => 'social_reapeater',
            'default' => '',
            'value' => '',
            'options' => [
               'facebook' => [
                  'label' => esc_html__('Facebook', 'eventin'),
                  'icon_class' => ''
               ],
               'twitter'  => [
                  'label' => esc_html__('Twitter', 'eventin'),
                  'icon_class' => ''
               ]
            ],
            'desc' => esc_html__('Add multiple social icon','eventin'),
            'attr' => ['class' => ''],
            'priority' => 1,
            'required' => true,
         ]
      ];

      return $this->event_fields;
   }
}
