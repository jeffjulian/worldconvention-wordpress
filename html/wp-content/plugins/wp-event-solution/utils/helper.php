<?php

namespace Etn\Utils;

defined('ABSPATH') || exit;
/**
 * Global helper class.
 *
 * @since 1.0.0
 */

class Helper
{

	/**
	 * Get etn older version if has any.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function old_version()
	{
		$version = get_option('etn_version');
		return null == $version ? -1 : $version;
	}

	/**
	 * Set metform installed version as current version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function set_version()
	{
	}

	/**
	 * Auto generate classname from path.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function make_classname($dirname)
	{
		$dirname = pathinfo($dirname, PATHINFO_FILENAME);
		$class_name	 = explode('-', $dirname);
		$class_name	 = array_map('ucfirst', $class_name);
		$class_name	 = implode('_', $class_name);
		return $class_name;
	}

	public static function google_fonts($font_families = [])
	{
		$fonts_url         = '';
		if ($font_families) {
			$query_args = array(
				'family' => urlencode(implode('|', $font_families))
			);

			$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
		}

		return esc_url_raw($fonts_url);
	}

	public static function render($content)
	{
		return $content;
	}

	public static function kses($raw)
	{
		$allowed_tags = array(
			'a'								 => array(
				'class'	 => array(),
				'href'	 => array(),
				'rel'	 => array(),
				'title'	 => array(),
				'target' => array(),
			),
			'input' => [
				'value' => [],
				'type' => [],
				'size' => [],
				'name' => [],
				'checked' => [],
				'placeholder' => [],
				'id' => [],
				'class' => [],
				'min' =>[],
				'step' => []
			],

			'select' => [
				'value' => [],
				'type' => [],
				'size' => [],
				'name' => [],
				'placeholder' => [],
				'id' => [],
				'class' => [],
				'option' => [
					'value' => [],
					'checked' => [],
				]
			],

			'textarea' => [
				'value' => [],
				'type' => [],
				'size' => [],
				'name' => [],
				'rows' => [],
				'cols' => [],

				'placeholder' => [],
				'id' => [],
				'class' => []
			],
			'abbr'							 => array(
				'title' => array(),
			),
			'b'								 => array(),
			'blockquote'					 => array(
				'cite' => array(),
			),
			'cite'							 => array(
				'title' => array(),
			),
			'code'							 => array(),
			'del'							 => array(
				'datetime'	 => array(),
				'title'		 => array(),
			),
			'dd'							 => array(),
			'div'							 => array(
				'class'	 => array(),
				'title'	 => array(),
				'style'	 => array(),
			),
			'dl'							 => array(),
			'dt'							 => array(),
			'em'							 => array(),
			'h1'							 => array(
				'class' => array(),
			),
			'h2'							 => array(
				'class' => array(),
			),
			'h3'							 => array(
				'class' => array(),
			),
			'h4'							 => array(
				'class' => array(),
			),
			'h5'							 => array(
				'class' => array(),
			),
			'h6'							 => array(
				'class' => array(),
			),
			'i'								 => array(
				'class' => array(),
			),
			'img'							 => array(
				'alt'	 => array(),
				'class'	 => array(),
				'height' => array(),
				'src'	 => array(),
				'width'	 => array(),
			),
			'li'							 => array(
				'class' => array(),
			),
			'ol'							 => array(
				'class' => array(),
			),
			'p'								 => array(
				'class' => array(),
			),
			'q'								 => array(
				'cite'	 => array(),
				'title'	 => array(),
			),
			'span'							 => array(
				'class'	 => array(),
				'title'	 => array(),
				'style'	 => array(),
			),
			'iframe'						 => array(
				'width'			 => array(),
				'height'		 => array(),
				'scrolling'		 => array(),
				'frameborder'	 => array(),
				'allow'			 => array(),
				'src'			 => array(),
			),
			'strike'						 => array(),
			'br'							 => array(),
			'strong'						 => array(),
			'data-wow-duration'				 => array(),
			'data-wow-delay'				 => array(),
			'data-wallpaper-options'		 => array(),
			'data-stellar-background-ratio'	 => array(),
			'ul'							 => array(
				'class' => array(),
			),
			'label'			=> array(
				'class' => array(),
			)
		);

		if (function_exists('wp_kses')) { // WP is here
			return wp_kses($raw, $allowed_tags);
		} else {
			return $raw;
		}
	}

	public static function kspan($text)
	{
		return str_replace(['{', '}'], ['<span>', '</span>'], self::kses($text));
	}


	public static function trim_words($text, $num_words)
	{
		return wp_trim_words($text, $num_words, '');
	}

	public static function array_push_assoc($array, $key, $value)
	{
		$array[$key] = $value;
		return $array;
	}

	public static function img_meta($id)
	{
		$attachment = get_post($id);
		if ($attachment == null || $attachment->post_type != 'attachment') {
			return null;
		}
		return [
			'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
			'caption' => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href' => get_permalink($attachment->ID),
			'src' => $attachment->guid,
			'title' => $attachment->post_title
		];
	}

	public static function datepicker_formats($translate = null)
	{
		$formats = array(
			0     => 'Y-m-d',
			1     => 'n/j/Y',
			2     => 'm/d/Y',
			3     => 'j/n/Y',
			4     => 'd/m/Y',
			5     => 'n-j-Y',
			6     => 'm-d-Y',
			7     => 'j-n-Y',
			8     => 'd-m-Y',
			9     => 'Y.m.d',
			10    => 'm.d.Y',
			11    => 'd.m.Y',
			'm0'  => 'Y-m',
			'm1'  => 'n/Y',
			'm2'  => 'm/Y',
			'm3'  => 'n/Y',
			'm4'  => 'm/Y',
			'm5'  => 'n-Y',
			'm6'  => 'm-Y',
			'm7'  => 'n-Y',
			'm8'  => 'm-Y',
			'm9'  => 'Y.m',
			'm10' => 'm.Y',
			'm11' => 'm.Y',
		);

		if (is_null($translate)) {
			return $formats;
		}

		return isset($formats[$translate]) ? $formats[$translate] : $formats[1];
	}

	public static function datetime_from_format($format, $date)
	{
		$keys = array(
			// Year with 4 Digits
			'Y' => array('year', '\d{4}'),

			// Year with 2 Digits
			'y' => array('year', '\d{2}'),

			// Month with leading 0
			'm' => array('month', '\d{2}'),

			// Month without the leading 0
			'n' => array('month', '\d{1,2}'),

			// Month ABBR 3 letters
			'M' => array('month', '[A-Z][a-z]{2}'),

			// Month Name
			'F' => array('month', '[A-Z][a-z]{2,8}'),

			// Day with leading 0
			'd' => array('day', '\d{2}'),

			// Day without leading 0
			'j' => array('day', '\d{1,2}'),

			// Day ABBR 3 Letters
			'D' => array('day', '[A-Z][a-z]{2}'),

			// Day Name
			'l' => array('day', '[A-Z][a-z]{5,8}'),

			// Hour 12h formatted, with leading 0
			'h' => array('hour', '\d{2}'),

			// Hour 24h formatted, with leading 0
			'H' => array('hour', '\d{2}'),

			// Hour 12h formatted, without leading 0
			'g' => array('hour', '\d{1,2}'),

			// Hour 24h formatted, without leading 0
			'G' => array('hour', '\d{1,2}'),

			// Minutes with leading 0
			'i' => array('minute', '\d{2}'),

			// Seconds with leading 0
			's' => array('second', '\d{2}'),
		);

		$date_regex = "/{$keys['Y'][1]}-{$keys['m'][1]}-{$keys['d'][1]}( {$keys['H'][1]}:{$keys['i'][1]}:{$keys['s'][1]})?$/";

		// if the date is already in Y-m-d or Y-m-d H:i:s, just return it
		if (preg_match($date_regex, $date)) {
			return $date;
		}


		// Convert format string to regex
		$regex = '';
		$chars = str_split($format);
		foreach ($chars as $n => $char) {
			$last_char = isset($chars[$n - 1]) ? $chars[$n - 1] : '';
			$skip_current = '\\' == $last_char;
			if (!$skip_current && isset($keys[$char])) {
				$regex .= '(?P<' . $keys[$char][0] . '>' . $keys[$char][1] . ')';
			} elseif ('\\' == $char) {
				$regex .= $char;
			} else {
				$regex .= preg_quote($char);
			}
		}

		$dt = array();

		// Now try to match it
		if (preg_match('#^' . $regex . '$#', $date, $dt)) {
			// Remove unwanted Indexes
			foreach ($dt as $k => $v) {
				if (is_int($k)) {
					unset($dt[$k]);
				}
			}

			// We need at least Month + Day + Year to work with
			if (!checkdate($dt['month'], $dt['day'], $dt['year'])) {
				return false;
			}
		} else {
			return false;
		}

		$dt['month'] = str_pad($dt['month'], 2, '0', STR_PAD_LEFT);
		$dt['day'] = str_pad($dt['day'], 2, '0', STR_PAD_LEFT);

		$formatted = '{year}-{month}-{day}' . (isset($dt['hour'], $dt['minute'], $dt['second']) ? ' {hour}:{minute}:{second}' : '');
		foreach ($dt as $key => $value) {
			$formatted = str_replace('{' . $key . '}', $value, $formatted);
		}

		return $formatted;
	}
}
