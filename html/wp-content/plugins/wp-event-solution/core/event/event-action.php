<?php

namespace Etn\Core\Event;

use Etn\Utils\Helper;

defined('ABSPATH') || exit;
/**
 * Action Class.
 * for post insert, update and get data.
 *
 * @since 1.0.0
 */
class Event_Action
{


   public function __construct()
   {
      // custom post meta
      $event_metabox = new  \Etn\Core\Metaboxs\Event_meta();
      add_action('add_meta_boxes', [$event_metabox, 'register_meta_boxes']);
      add_action('save_post', [$event_metabox, 'save_meta_box_data']);
   }
}
