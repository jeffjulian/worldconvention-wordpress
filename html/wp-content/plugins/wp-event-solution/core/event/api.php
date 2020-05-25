<?php

namespace Etn\Core\Event;

defined('ABSPATH') || exit;

class Api extends \Etn\Base\Api
{

    public function config()
    {
        $this->prefix = 'etn';
        $this->param  = "/(?P<id>\w+)";
    }

    public function post_add()
    {

        $form_id = $this->request['id'];
        $form_setting = $this->request->get_params();
        return Action::instance()->store($form_id, $form_setting);
    }

    public function get_getdata()
    {
        $post_id = $this->request['id'];
        return Action::instance()->get_all_data($post_id);
    }
}
