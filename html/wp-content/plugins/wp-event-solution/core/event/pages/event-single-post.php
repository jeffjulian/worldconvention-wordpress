<?php

namespace Etn\Core\Event\Pages;

defined('ABSPATH') || exit;
class Event_single_post
{
    use \Etn\Traits\Singleton;

    function __construct()
    {
        add_action('single_template', array($this, 'event'));
    }
    function event($single)
    {

        global $post;

        if ($post->post_type == 'etn') {

            if (file_exists(ETN_DIR . '/core/event/views/single/event-single-page.php')) {
                return ETN_DIR . '/core/event/views/single/event-single-page.php';
            }
        }

        return $single;
    }

    function speaker($single)
    {

        global $post;

        if ($post->post_type == 'etn-speaker') {

            if (file_exists(ETN_DIR . '/views/template/speaker-single-page.php')) {
                return ETN_DIR . '/views/template/speaker-single-page.php';
            }
        }

        return $single;
    }
}
