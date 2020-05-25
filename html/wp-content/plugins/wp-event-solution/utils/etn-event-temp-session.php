<?php

namespace Etn\Utils;

defined('ABSPATH') || exit;
class Temp_session
{

    public function start()
    {
        if (!session_id())
            session_start();
    }
}
