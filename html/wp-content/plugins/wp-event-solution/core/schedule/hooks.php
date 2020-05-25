<?php

namespace Etn\Core\Schedule;

use \Etn\Core\Schedule\Pages\Schedule_single_post;

defined('ABSPATH') || exit;

class Hooks
{

  use \Etn\Traits\Singleton;

  public $cpt;
  public $action;
  public $base;
  public $schedule;
  public $settings;
  public $schedule_action;

  public $actionPost_type = ['etn-schedule'];

  public function Init()
  {
    $this->cpt      = new Cpt();
    $this->action   = new Action();
    $this->base     = new Base();
    $this->settings = new Settings('etn', '1.0');

    // custom post meta
    $_metabox = new  \Etn\Core\Metaboxs\Schedule_meta();

    add_action('add_meta_boxes', [$_metabox, 'register_meta_boxes']);
    add_action('save_post', [$_metabox, 'save_meta_box_data']);

    $this->add_single_page_template();
  }
  function add_single_page_template()
  {
    $page = new Schedule_single_post();
  }
}
