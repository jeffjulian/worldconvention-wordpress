<?php

namespace Etn\Core\Event;

use WP_Query;
use \Etn\Core\Event\Pages\Event_single_post;
use \Etn\Utils\Helper as Helper;

defined('ABSPATH') || exit;
class Hooks
{

  use \Etn\Traits\Singleton;

  public $cpt;
  public $action;
  public $base;
  public $category;
  public $tags;
  public $event;
  public $settings;
  public $event_action;

  public $actionPost_type = ['etn'];

  public function Init()
  {

    $this->cpt      = new Cpt();
    $this->category = new Category();
    $this->tags     = new Tags();
    $this->action   = new Action();
    $this->base     = new Base();
    $this->event_action = new Event_Action();
    $this->settings     = new Settings('etn', '1.0');
    $this->add_menu();
    $this->add_single_page_template();
    $this->add_shortcodes();
    $this->add_event_search_hook();
  }


  function add_event_search_hook()
  {
    $event_options = get_option("etn_event_options");
    if (isset($event_options["etn_include_from_search"])) {
      add_filter('pre_get_posts', [$this, 'include_cpt_search']);
    }
  }

  function include_cpt_search($query)
  {

    if ($query->is_search) {
      $query->set('post_type', array('post', 'page', 'etn'));
    }
    return $query;
  }

  function add_shortcodes()
  {
    add_shortcode('organizer', [$this, "get_organizer"]);
  }

  function add_menu()
  {
    $this->category->menu();
    $this->tags->menu();
  }

  function add_single_page_template()
  {
    $page = new Event_single_post();
  }


  function get_organizer($args)
  {
    $defaults = array(
      'title' => ""
    );
    $attributes = shortcode_atts($defaults, $args);
    $meta_val = $attributes["title"];
    global $wpdb;
    $posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'etn_event_organizer' AND  meta_value LIKE '%$meta_val%' LIMIT 1", ARRAY_A);
    $organizer_data = unserialize($posts[0]['meta_value']);
    require ETN_DIR . '/core/organizer/views/etn-organizer-single-display.php';
  }
}
