<?php

namespace Etn\Core\Event\Settings;

defined('ABSPATH') || exit;

class Settings
{
   use \Etn\Traits\Singleton;
   private $key_settings_option;
   public function __construct()
   {
      $this->key_settings_option = 'etn_event_options';
   }

   public function get_settings_option($key = null, $default = null)
   {
      if ($key != null) {
         $this->key_settings_option = $key;
      }
      return get_option($this->key_settings_option);
   }

   public function add_setting_menu()
   {

      add_submenu_page(
         'etn-events-manager',
         esc_html__('Settings', 'eventin'),
         esc_html__('Settings', 'eventin'),
         'manage_options',
         'etn-event-settings',
         [$this, 'etn_settings_page']
      );
   }

   public function etn_settings_page()
   {
      include('etn-settings.php');
   }

   public function set_option($key, $default = null)
   {
   }

   public function register_actions()
   {
      if (isset($_POST['etn_settings_page_action'])) {
         if (!check_admin_referer('etn-settings-page', 'etn-settings-page')) {
            return;
         }
         $post_arr = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
         $status = \Etn\Base\Action::instance()->store(-1, $post_arr);
         return $status;
      }
      return false;
   }
}
