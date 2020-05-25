<?php

namespace Etn\Core\Speaker;

use \Etn\Core\Speaker\Pages\Speaker_single_post;

defined('ABSPATH') || exit;
class Hooks
{

  use \Etn\Traits\Singleton;

  public $cpt;
  public $action;
  public $base;
  public $speaker;
  public $category;
  public $settings;
  public $spaeker_action;

  public $actionPost_type = ['etn-speaker'];

  public function Init()
  {

    $this->cpt      = new Cpt();
    $this->action   = new Action();
    $this->base     = new Base();
    $this->settings = new Settings('etn', '1.0');
    $this->category = new Category();
    // custom post meta

    $_metabox = new  \Etn\Core\Metaboxs\Speaker_meta();

    add_action('add_meta_boxes', [$_metabox, 'register_meta_boxes']);
    add_action('save_post', [$_metabox, 'save_meta_box_data']);

    $this->add_menu();
    $this->add_single_page_template();
    add_action('init', [$this,  'add_default_speaker_categories'], 99999);
  }

  function add_menu()
  {
    $this->category->menu();
  }

  function add_default_speaker_categories()
  {

    $org_term = term_exists('Organizer', 'etn_speaker_category');
    if ($org_term === null) {
      wp_insert_term(
        'Organizer',
        'etn_speaker_category',
        array(
          'description' => 'Organizer of event',
          'slug'        => 'organizer',
          'parent'      => 0
        )
      );
    }

    $speaker_term = term_exists('Speaker', 'etn_speaker_category');
    if ($speaker_term === null) {
      wp_insert_term(
        'Speaker',
        'etn_speaker_category',
        array(
          'description' => 'Speaker of schedule',
          'slug'        => 'speaker',
          'parent'      => 0
        )
      );
    }
  }

  function add_single_page_template()
  {
    $page = new Speaker_single_post();
  }
}
