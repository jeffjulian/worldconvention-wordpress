<?php

namespace Etn\Core\Metaboxs;

use Etn\Utils\Utilities as Utilities;

defined('ABSPATH') || exit;
abstract class Event_manager_repeater_metabox
{

    protected function getRepeaterMarkup($item = null, $key = '', $data = [], $rep_key = '')
    {

        if (is_null($item)) {
            return;
        }

        if (isset($item['type'])) {

            switch ($item['type']) {
                case "text":
                    return $this->getrepeaterTextinput($item, $key, $data);
                    break;
                case "date":
                    return $this->getrepeaterTextinput($item, $key, $data);
                    break;
                case "email":
                    return $this->getrepeaterTextinput($item, $key, $data);
                    break;
                case "time":
                    return $this->getrepeaterTextinput($item, $key, $data);
                    break;
                case "url":
                    return $this->getrepeaterTextinput($item, $key, $data);
                    break;
                case "textarea":
                    return $this->getrepeaterTextarea($item, $key, $data);
                    break;
                case "select2":
                    return $this->getrepeaterselect2($item, $key, $data);
                    break;
                case "radio":
                    return $this->getrepeaterradio($item, $key, $data);
                    break;
                case "upload":
                    return $this->getrepeaterUpload($item, $key, $data);
                    break;
                case "heading":
                    return $this->getHeading($item, $key);
                    break;
                case "separator":
                    return $this->getSeparator($item, $key);
                    break;
                case "select_single":
                    return $this->getRepeaterSelectSingle($item, $key);
                    break;
                default:
                    return;
            }
        }

        return;
    }


    public function getRepeaterSelectSingle($item, $key)
    {
        $value = '';
        $class = $key;
        $input = '';
        $value = get_post_meta(get_the_ID(), $key, true);
        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
        }
        if (!isset($item['options']) || !count($item['options'])) {
            $html = sprintf('<div class="%s form-group"> 
         <div class="etn-label"> <label for="%s"> %s : </label></div>
        </div>', $class, $key, $item['label']);
            echo   Helper::kses($html);
            return;
        } elseif (isset($item['options']) && count($item['options'])) {
            $options = $item['options'];
            $input .= sprintf('<select  name="%s" class="etn_es_event_select2 %s">', $key, $key, $class);
            foreach ($options as $option_key => $option) {
                if ($option_key == $value) {
                    $input .= sprintf(' <option selected value="%s"> %s </option>',  $option_key, $option);
                } else {
                    $input .= sprintf(' <option value="%s"> %s </option>',  $option_key, $option);
                }
            }
            $input .= sprintf('</select>');
        }


        $html = sprintf('
      <div class="%s"> 
         <div class="etn-label"> 
            <label> %s  </label>
         </div>
          %s
     </div>', $class, $item['label'], $input);

        echo ($html);
    }

    public function getrepeaterUpload($item, $key, $data)
    {

        $class = $key;
        $value  = null;
        if (is_array($data) && count($data)) {
            $value = isset($data[$key]) ? $data[$key] : '';
        }

        $image = ' button">Upload image';
        $image_size = 'full';
        $display = 'none';
        $multiple = 0;

        if (isset($item['multiple']) && $item['multiple']) {
            $multiple = true;
        }

        if (isset($item['attr'])) {

            if (isset($item['attr']['class']) && $item['attr']['class'] != '') {
                $class = 'attr-form-control etn_event_meta_field ' . $class . ' ' . $item['attr']['class'];
            } else {
                $class = 'etn_event_meta_field attr-form-control';
            }
        }

        if ($image_attributes = wp_get_attachment_image_src($value, $image_size)) {

            $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
            $display = 'inline-block';
        }

        echo "<div class='{$class} form-group'>";
        echo '<label>' . $item['label'] . '</label>
           <a data-multiple="' . $multiple . '" class="etn_event_upload_image_button' . $image . '</a>
                   <input type="hidden" name="' . $key . '" id="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />
             <a href="#" class="essential_event_remove_image_button" style="display:inline-block;display:' . $display . '">' . esc_html__('Remove image', 'eventin') . '</a>
        </div>';
    }

    public function getrepeaterTextinput($item, $key, $data)
    {
        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $class = $key;

        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
        }

        $html = "<div class='etn-label-item'>";
        $html .= sprintf('<div class="etn-label"><label for="%s"> %s : </label><div class="etn-desc">  %s  </div></div><div class="etn-meta"><input autocomplete="off" class="etn-form-control %s" type="%s" name="%s"  value="%s" />', $key, $item['label'], $item['desc'] ,$class, $item['type'], $key, $value);
        $html .= "</div></div>";

        return $html;
    }

    public function getrepeaterTextarea($item, $key, $data)
    {

        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $class = $key;
        $rows = 14;
        $cols = 50;

        if (isset($item['attr'])) {
            $rows = isset($item['attr']['row']) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
            $cols = isset($item['attr']['col']) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field etn-form-control' : 'etn_event_meta_field form-control';
        }

        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
        }

        $html = sprintf('<div class="%s etn-label-item"> <div class="etn-label"><label for="%s"> %s : </label><div class="etn-desc">  %s  </div></div><div class="etn-meta"> <textarea id="%s" rows="%s" cols="%s" class="etn-form-control msg-control-box" name="%s"> %s  </textarea> </div></div>', $class, $key, $item['label'],$item['desc'], $key, $rows, $cols, $key, $value);

        return $html;
    }

    public function getrepeaterradio($item, $key, $data)
    {

        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $class = $key;
        $input = '';

        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field attr-form-control' : 'etn_event_meta_field attr-form-control';
        }

        if (!isset($item['options']) || !count($item['options'])) {

            $html = sprintf('<div class="attr-form-control %s"> 
       <label for="%s"> %s : </label>
       </div>', $class, $key, $item['label']);
            return $html;
        } elseif (isset($item['options']) && count($item['options'])) {

            $options = $item['options'];

            foreach ($options as $option_key => $option) {
                $checked =  $option_key == $value ? 'checked' : '';
                $input .= sprintf(' <input  %s type="%s" name="%s" value="%s"/><span> %s  </span> ', $checked, $item['type'], $key, $option_key, $option);
            }
        }

        $html = sprintf('<div class="%s form-group"> <label> %s  </label> %s </div>', $class, $item['label'], $input);

        return $html;
    }

    public function getrepeaterselect2($item, $key, $data)
    {
        $input = '';
        $class = $key;
        $value = $data;
        $value = isset($value[$key]) ? $value[$key] : '';
        $multiple = isset($item['multiple']) ? 'multiple' : '' ;
        if (isset($item['attr'])) {
            $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field ' : 'etn_event_meta_field form-control';
        }

        if (!isset($item['options']) || !count($item['options'])) {

            $html = sprintf('<div class="%s form-group"> 
          <label for="%s"> %s : </label><div class="etn-desc">  %s  </div>
      </div>', $class, $key, $item['label'],$item['desc']);

            echo  Utilities::post_kses($html);
            return;
        } elseif (isset($item['options']) && count($item['options'])) {

            $options = $item['options'];
            $input .= sprintf('<div class="etn-meta"><select '.$multiple.' name="%s" class="etn_es_event_repeater_select2 etn-form-control">', $key, $key);
            foreach ($options as $option_key => $option) {
                if ($multiple) {
                    $etn_shedule_speaker_arr = isset( $data['etn_shedule_speaker'] ) ? $data['etn_shedule_speaker'] : [];
                    $selected =in_array($option_key , $etn_shedule_speaker_arr ) ? "selected " : '';
                    $input .= sprintf(' <option %s '.$selected.' value="%s"> %s </option>', $class, $option_key, $option);
                }
                else {
                    if ($option_key == $value) {
                        $input .= sprintf(' <option %s selected value="%s"> %s </option>', $class, $option_key, $option);
    
                    } else {
                        $input .= sprintf(' <option %s value="%s"> %s </option>', $class, $option_key, $option);
                    }
                } 
                
            }
            $input .= sprintf('</select></div>');
        }

        $html = sprintf('<div class="%s etn-label-item"> <div class="etn-label"> <label> %s  </label><div class="etn-desc">  %s  </div> </div> %s </div>', $class, $item['label'],$item['desc'],$input);
        return $html;
    }
}
