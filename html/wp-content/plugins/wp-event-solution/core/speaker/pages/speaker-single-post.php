<?php

namespace Etn\Core\Speaker\Pages;

defined('ABSPATH') || exit;
class Speaker_single_post
{
    use \Etn\Traits\Singleton;

    function __construct()
    {
        add_action('single_template', array($this, 'speaker'));
    }

    function speaker($single)
    {

        global $post;

        if ($post->post_type == 'etn-speaker') {

            if (file_exists(ETN_DIR . '/core/speaker/views/single/speaker-single-page.php')) {
                return ETN_DIR . '/core/speaker/views/single/speaker-single-page.php';
            }
        }

        return $single;
    }
}
