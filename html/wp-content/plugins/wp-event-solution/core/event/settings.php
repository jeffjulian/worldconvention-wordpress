<?php

namespace Etn\Core\Event;

defined('ABSPATH') || exit;

class Settings
{
	private $plugin_name;

	private $version;

	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$subSettings = new \Etn\Core\Event\Settings\Settings();
		add_action('admin_menu', [$subSettings, 'add_setting_menu']);
		add_action('admin_init', [$subSettings, 'register_actions'], 999);
	}



	public function validate_input_text($input)
	{
		$output = array();
		foreach ($input as $key => $value) {
			if (isset($input[$key])) {
				$output[$key] = strip_tags(stripslashes($input[$key]));
			}
		}
		return apply_filters('etn_validate_input_text', $output, $input);
	}
}
