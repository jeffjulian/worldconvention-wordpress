<?php

namespace Etn\Core\Metaboxs;

use Etn\Utils\Utilities as Utilities;

defined('ABSPATH') || exit;

class Product_meta extends Event_manager_metabox
{
   public $metabox_id = 'etn_products_settings';
   public $event_fields = [];
   public $cpt_id = 'product';

   public function register_meta_boxes()
   {
      add_meta_box($this->metabox_id, __('Event Settings', 'eventin'), [$this, 'display_callback'], $this->cpt_id);
   }

   public function default_Fields()
   {
      $this->event_fields = [

         'etn_es_product_event_id' => [
            'label' => esc_html__('Event ', 'eventin'),
            'type' => 'select2',
            'default' => '',
            'value' => '',
            'options' => Utilities::get_events(),
            'priority' => 1,
            'required' => true,
            'attr' => ['class' => ''],
         ],

         'etn_es_product_is_event' => [

            'label' => esc_html__('Product as event', 'eventin'),
            'type' => 'radio',
            'value' => '',
            'default' => 'yes',

            'options' => [
               'yes' => esc_html__('yes', 'eventin'),
               'no'  => esc_html__('no', 'eventin')
            ],

            'desc' => '',
            'priority' => 1,
            'required' => true,
            'attr' => ['class' => ''],
         ],
      ];

      return $this->event_fields;
   }
}
