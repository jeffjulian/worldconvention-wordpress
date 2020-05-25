<?php

namespace Etn\Core\Speaker;

defined('ABSPATH') || exit;

class Base extends \Etn\Base\Common
{

    use \Etn\Traits\Singleton;

    // $api veriable call for Cpt Class Instance 
    public $speaker;

    // $api veriable call for Api Class Instance 
    public $api;

    // set template type for template
    public $template_type = [];

    public function get_dir()
    {
        return dirname(__FILE__);
    }

    public function __construct()
    {
    }

    public function init()
    {
        // call custom post type
        $this->speaker = new Cpt();
        Hooks::instance()->Init();
    }
}
