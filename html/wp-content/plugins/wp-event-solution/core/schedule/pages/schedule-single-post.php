<?php

namespace Etn\Core\Schedule\Pages;

defined('ABSPATH') || exit;
class Schedule_single_post
{
    use \Etn\Traits\Singleton;

    function __construct()
    {
        add_action('single_template', array($this, 'schedule'));
    }

    function schedule($single)
    {

        global $post;

        if ($post->post_type == 'etn-schedule') {

            if (file_exists(ETN_DIR . '/core/schedule/views/single/schedule-single-page.php')) {
                return ETN_DIR . '/core/schedule/views/single/schedule-single-page.php';
            }
        }

        return $single;
    }
}
