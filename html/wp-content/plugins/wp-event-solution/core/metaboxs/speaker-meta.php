<?php

namespace Etn\Core\Metaboxs;

use Etn\Utils\Utilities as Utilities;

defined('ABSPATH') || exit;

class Speaker_meta extends Event_manager_metabox
{
   public $metabox_id = 'etn_speaker_settings';
   public $event_fields = [];
   public $cpt_id = 'etn-speaker';
   public function __construct()
   {
   }

   public function register_meta_boxes()
   {
      add_meta_box($this->metabox_id, __('Speaker info', 'eventin'), [$this, 'display_callback'], $this->cpt_id);
   }

   public function default_Fields()
   {
      $default_fields = [
         'etn_speaker_title' => [
            'label' => esc_html__('Name', 'eventin'),
            'type' => 'text',
            'default' => '',
            'value' => '',
            'desc' => '',
            'priority' => 1,
            'attr' => ['class' => 'etn-label-item'],
            'required' => true,
         ],
         'etn_speaker_designation' => [
            'label' => esc_html__('Designation', 'eventin'),
            'type' => 'text',
            'default' => '',
            'value' => '',
            'desc' => '',
            'priority' => 1,
            'attr' => ['class' => 'etn-label-item'],
            'required' => true,
         ],
         'etn_speaker_website_email' => [
            'label' => esc_html__('Email', 'eventin'),
            'type' => 'email',
            'default' => '',
            'value' => '',
            'desc' => '',
            'attr' => ['class' => 'etn-label-item'],
            'priority' => 1,
            'required' => true,
         ],

         'etn_speaker_company_logo' => [
            'label' => esc_html__('Company logo', 'eventin'),
            'type' => 'upload',
            'multiple' => true,
            'default' => '',
            'value' => '',
            'desc' => '',
            'priority' => 1,
            'required' => false,
            'attr' => ['class' => ' banner etn-label-item'],
         ],
         'etn_speaker_summery' => [
            'label' => esc_html__('Summary', 'eventin'),
            'type' => 'textarea',
            'default' => '',
            'value' => '',
            'desc' => '',
            'priority' => 1,
            'attr' => ['class' => 'etn-label-item'],
            'required' => true,
         ],
         'etn_speaker_socials' => [
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
            'desc' => '',
            'attr' => ['class' => 'etn-label-item'],
            'priority' => 1,
            'required' => true,
         ],

      ];
      $this->event_fields = apply_filters('etn_speaker_fields', $default_fields);

      return $this->event_fields;
   }

   function etn_set_speaker_title($data, $postarr)
   {
      if ('etn-speaker' == $data['post_type']) {

         if (isset($postarr['etn_speaker_title'])) {
            $speaker_title = sanitize_text_field( $postarr['etn_speaker_title'] );
         } else {
            $speaker_title = get_post_meta($postarr['ID'], 'etn_speaker_title', true);
         }
         $post_slug = sanitize_title_with_dashes($speaker_title, '', 'save');
         $speaker_slug = sanitize_title($post_slug);

         $data['post_title'] = $speaker_title;
         $data['post_name'] = $speaker_slug;
      }
      return $data;
   }
}
