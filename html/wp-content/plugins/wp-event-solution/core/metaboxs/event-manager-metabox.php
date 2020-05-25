<?php

namespace Etn\Core\Metaboxs;
use Etn\Utils\Utilities as Utilities;
use Etn\Utils\Helper as Helper;
use Etn\Core\Metaboxs\Event_manager_repeater_metabox as Event_manager_repeater_metabox;

defined('ABSPATH') || exit;
abstract class Event_manager_metabox extends Event_manager_repeater_metabox
{

   protected function is_secured($nonce_field, $action, $post_id,  $post)
   {
      $nonce = isset($post[$nonce_field]) ? sanitize_text_field( $post[$nonce_field] ) : '';
      if ($nonce == '') {
         return false;
      }

      if (!current_user_can('edit_post', $post_id)) {
         return false;
      }

      if (wp_is_post_autosave($post_id)) {
         return false;
      }

      if (wp_is_post_revision($post_id)) {
         return false;
      }

      if (!wp_verify_nonce($nonce, $action)) {
         return false;
      }
      return true;
   }

   public function display_callback($post)
   {
      foreach ($this->default_Fields() as $key => $item) :
         $this->getMarkup($item, $key);
      endforeach;
      wp_nonce_field('etn_event_data', 'etn_event_n_fields');
   }

   function save_meta_box_data($post_id)
   {
      $post_arr = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      
      if (!$this->is_secured('etn_event_n_fields', 'etn_event_data', $post_id, $post_arr)) {
         return $post_id;
      }

      try {
         $this->update($this->default_Fields(), $post_arr);
      } catch (Exception $e) {

         $error = new WP_Error($e->getCode(), $e->getMessage());
      }
   }

   protected function update($fields = null, $post)
   {
      if (!is_array($fields) || !count($fields)) {
         throw new Exception(esc_html__("meta data field not found", 'eventin'));
      }
      foreach ($fields as $field_key => $field) {

         if ($field['type'] == 'radio' || $field['type'] == 'select2') {

            if (isset($post[$field_key])) {
               $upload_key  = isset($post[$field_key]) ? $post[$field_key] : '';
               $rv = $upload_key;

               update_post_meta(get_the_ID(), $field_key, $rv);
            } else {
               update_post_meta(get_the_ID(), $field_key, '');
            }
         } elseif ($field['type'] == 'upload') {

            if (isset($post[$field_key])) {
               $upload_key  = isset($post[$field_key]) ? sanitize_text_field($post[$field_key]) : '';
               update_post_meta(get_the_ID(), $field_key, $upload_key);
            }
         } elseif ($field['type'] == 'wp_editor') {
            if (isset($post[$field_key])) {
               $upload_key  = isset($post[$field_key]) ? sanitize_textarea_field( $post[$field_key] ) : '';
               update_post_meta(get_the_ID(), $field_key, $upload_key);
            }
         } elseif ($field['type'] == 'social_reapeater') {

            if (isset($post[$field_key])) {
               $social_key  = isset($post[$field_key]) ?  $post[$field_key]   : '';
               if (is_array($social_key)) {
                  if (count($social_key) == 1) {
                     if ($social_key[0]['icon'] == '') {
                        update_post_meta(get_the_ID(), $field_key, "");
                     } else {
                        update_post_meta(get_the_ID(), $field_key, $social_key);
                     }
                  } else {
                     update_post_meta(get_the_ID(), $field_key, $social_key);
                  }
               }
            }
         } elseif ($field['type'] == 'repeater') {

            if (isset($post[$field_key])) {
               $etn_rep_key  = isset($post[$field_key]) ? $post[$field_key] : '';
               if (is_array($etn_rep_key)) {
                  if (count($etn_rep_key) == 1) {
                     if (strlen(trim(implode($etn_rep_key[0]))) == 0) {
                        update_post_meta(get_the_ID(), $field_key, "");
                     } else {
                        update_post_meta(get_the_ID(), $field_key, $etn_rep_key);
                     }
                  } else {
                     update_post_meta(get_the_ID(), $field_key, $etn_rep_key);
                  }
               }
            }
         } elseif($field['type'] == 'email') {
            if (isset($post[$field_key])) {
               $email_value  = isset($post[$field_key]) ? sanitize_email( $post[$field_key]) : '';
               update_post_meta(get_the_ID(), $field_key, $email_value);
            }
         }else {
            if (isset($post[$field_key])) {
               $text_value  = isset($post[$field_key]) ? sanitize_text_field( $post[$field_key]) : '';
               update_post_meta(get_the_ID(), $field_key, $text_value);
            }
         }
      }
   }

   protected function getMarkup($item = null, $key = '')
   {

      if (is_null($item)) {
         return;
      }
      if (isset($item['type'])) {

         switch ($item['type']) {
            case "text":
               return $this->getTextinput($item, $key);
               break;
            case "number":
               return $this->getNumberInput($item, $key);
               break;
            case "date":
               return $this->getTextinput($item, $key);
               break;
            case "time":
               return $this->getTextinput($item, $key);
               break;
            case "textarea":
               return $this->getTextarea($item, $key);
               break;
            case "url":
               return $this->getUrlinput($item, $key);
               break;
            case "email":
               return $this->getEmailinput($item, $key);
               break;
            case "radio":
               return $this->getradioinput($item, $key);
               break;
            case "select2":
               return $this->getSelect2($item, $key);
               break;
            case "select_single":
               return $this->getSelectSingle($item, $key);
               break;
            case "upload":
               return $this->getUpload($item, $key);
               break;
            case "wp_editor":
               return $this->getWpEditor($item, $key);
               break;
            case "map":
               return $this->getWpMap($item, $key);
               break;
            case "social_reapeater":
               return $this->getWpSocialReapeater($item, $key);
               break;
            case "repeater":
               return $this->getWpRepeater($item, $key);
               break;
            case "heading":
               return $this->getHeading($item, $key);
               break;
            case "separator":
               return $this->getSeparator($item, $key);
               break;
            default:
               return;
         }
      }

      return;
   }

   public function getWpRepeater($item, $key)
   {

      $value = [];
      $class = $key;
      $options_fields = $item['options'];
      $repeater_arr = get_post_meta(get_the_ID(), $key, true);
      $count = is_array($repeater_arr) ? count($repeater_arr) : 1;

      echo "<div class='etn-event-repeater-clearfix etn-repeater-item'>";
      echo "<h3 class='etn-title'>" . esc_html($item['label']) . '</h3>';
      echo  sprintf("<div class='etn-event-manager-repeater-fld %s'>
            <div data-repeater-list='%s'>", $class, $key);
         for ($x = 0; $x < $count; $x++) {
         $label_no = $x; ?>
         <div data-repeater-list="etn-event-repeater-options" class="etn-repeater-item" data-repeater-item>
            <div class="form-group mb-3">
               <div class="etn-event-shedule-collapsible">
                  <span class="event-title"><?php echo esc_html($item['label'] . ' ' . ++$label_no); ?></span>
                  <i data-repeater-delete type="button" class="dashicons dashicons-no-alt" aria-hidden="true"></i>
               </div>

               <div class="etn-event-repeater-collapsible-content" style="display: none">
                  <?php $i = $x;
                  foreach ($options_fields as $op_fld_key => $options_field) : ?>
                     <?php

                     $nested_data = isset($repeater_arr[$i]) ? $repeater_arr[$i] : [];

                     ?>
                     <?php echo $this->getRepeaterMarkup($options_field, $op_fld_key, $nested_data); ?>
                  <?php endforeach;  ?>
               </div>
            </div>
         </div>
      <?php } 
       echo "</div> "; ?>
      <input data-repeater-create type='button' class='etn-btn attr-btn-primary mb-2 clearfix' value='Add' />

      <?php 
      echo "</div>
      </div>";
   }


   public function getWpRepeaterpublic($item, $key, $id)
   {

      $value = [];
      $class = $key;
      $options_fields = $item['options'];
      $repeater_arr = get_post_meta($id, $key, true);
      $count = is_array($repeater_arr) ? count($repeater_arr) : 1;

      echo "<div class='etn-event-repeater-clearfix'>";
      echo "<h3>" . esc_html($item['label']) . '</h3>';
      echo  sprintf("<div class='form-inline etn-event-repeater %s'>
         <div data-repeater-list='%s'>", $class, $key);
      echo "<input data-repeater-create type='button' class='etn-btn attr-btn-primary mb-2 clearfix' value='Add' />";
       for ($x = 0; $x < $count; $x++) {
         $label_no = $x; ?>
         <div data-repeater-list="etn-event-repeater-options" class="etn-repeater-item">
            <div class="form-group mb-3" data-repeater-item>

               <div onclick="etn_essential_event_reapeater_collapse_public(this)" class="etn-event-repeater-collapsible">
                  <?php echo esc_html($item['label'] . ' ' . ++$label_no); ?>
                  <i data-repeater-delete type="button" class="dashicons dashicons-no-alt" aria-hidden="true"></i>
               </div>

               <div class="etn-event-repeater-collapsible-content">
                  <?php $i = $x;
                  foreach ($options_fields as $op_fld_key => $options_field) : ?>
                     <?php
                     $nested_data = isset($repeater_arr[$i]) ? $repeater_arr[$i] : [];
                     ?>
                     <?php echo $this->getRepeaterMarkup($options_field, $op_fld_key, $nested_data); ?>
                  <?php endforeach;  ?>
               </div>
            </div>
         </div>
      <?php }
      echo "</div>
      </div>";
   }


   public function getWpRepeaterpublicnull($item, $key)
   {

      $value = [];
      $class = $key;
      $options_fields = $item['options'];

      $count = 1;

      echo "<div class='etn-event-repeater-clearfix'>";
      echo "<h3>" . esc_html($item['label']) . '</h3>';
      echo  sprintf("<div class='form-inline etn-event-repeater %s'>
         <div data-repeater-list='%s'>", $class, $key);
   ?>

      <input data-repeater-create type="button" class="etn-btn attr-btn-primary mb-2 clearfix" value="Add" />
      <?php for ($x = 0; $x < $count; $x++) {
         $label_no = $x; ?>
         <div data-repeater-list="etn-event-repeater-options" class="etn-repeater-item">
            <div class="form-group mb-3" data-repeater-item>

               <div onclick="etn_essential_event_repeater_collapse_publicnull(this)" class="etn-event-repeater-collapsible">
                  <?php echo esc_html($item['label'] . ' ' . ++$label_no); ?>
                  <i data-repeater-delete type="button" class="dashicons dashicons-no-alt" aria-hidden="true"></i>
               </div>

               <div class="etn-event-repeater-collapsible-content">
                  <?php $i = $x;
                  foreach ($options_fields as $op_fld_key => $options_field) : ?>
                     <?php

                     $nested_data = isset($repeater_arr[$i]) ? $repeater_arr[$i] : [];
                     echo $this->getRepeaterMarkup($options_field, $op_fld_key, $nested_data); ?>
                  <?php endforeach;  ?>
               </div>
            </div>
         </div>
      <?php } ?>


      <script>
         function etn_essential_event_repeater_collapse_publicnull(e) {

            e.classList.toggle("etn-repeater-fld-active");
            var content = e.nextElementSibling;
            if (content.style.display === "block") {
               content.style.display = "none";
            } else {
               content.style.display = "block";
            }
            jQuery('.etn_event_date').datepicker({
               dateFormat: "yy,MM,dd",
               onSelect: function() {
                  jQuery(this).val();
               }
            });
            jQuery('.etn_es_event_repeater_select2').select2();
            jQuery('.etn_es_event_repeater_select2').select2();
            if (jQuery(e).next().find('span.select2:eq(1)').length) {
               jQuery(e).next().find('span.select2:eq(1)').hide();
            }

         }
      </script>

      <?php

      echo "</div>
         </div>";
   }



   public function getWpSocialReapeater($item, $key)
   {
      $value = '';
      $class = $key;
      $social_items = $key;

      $dbvalue = get_post_meta(get_the_ID(), $key, true);

      require ETN_DIR . '/core/metaboxs/views/fields/icons.php';

      echo "<div class='etn-social-clearfix etn-label-item'>";
      echo "<div class='etn-label'>
               <label>" . esc_html($item['label']) . '</label> 
               <div class="etn-desc">'.esc_html($item['desc']).'</div>
            </div>';
      if (is_array($dbvalue)) {
         echo sprintf("<div class='form-inline etn-meta social-repeater %s'>
         <div class='etn-repeater-wrap' data-repeater-list='%s'>", $class, $social_items);
         foreach ($dbvalue as $db_socail) {
            echo sprintf("<div data-repeater-item> 
                          <div class='etn-form-group mb-2'>");
            echo sprintf("<i class='%s show-repeater-icon'></i><input  type='text' value='%s' name='icon' class='etn-social-icon etn-form-control'  data-toggle='modal' data-target='#etn-event-es-social-modal'/> <input type='text' class='etn-form-control' value='%s' name='etn_social_title' placeholder='title' /> <input type='text' class='etn-form-control' value='%s' name='etn_social_url' placeholder='url' /> <button data-repeater-delete type='button' class='etn-btn btn-danger'><span class='dashicons dashicons-no-alt'></span></button>", $db_socail['icon'], $db_socail['icon'], $db_socail['etn_social_title'], $db_socail['etn_social_url']);
            echo "</div>
                  </div>";
         }

         echo "</div>
         <div class='add-social'> 
            <input class='etn-btn attr-btn-primary' data-repeater-create type='button' value='Add Social'/>  
         </div>
         </div> ";
      } else {

         echo sprintf("<div class='form-inline etn-meta social-repeater %s'><div data-repeater-list='%s'>", $class, $social_items);
         echo sprintf("<div data-repeater-item> <div class='etn-form-group mb-2'> <i class=''></i><input  type='text' name='icon' class='etn-social-icon etn-form-control'  data-toggle='modal' data-target='#etn-event-es-social-modal'/> <input type='text' class='etn-form-control' name='etn_social_title' placeholder='title here' /> <input type='text' class='etn-form-control' name='etn_social_url' placeholder='url here' />  <button data-repeater-delete type='button' class='etn-btn btn-danger'><span class='dashicons dashicons-no-alt'></span></button></div></div>");
         echo "</div>
         <div class='add-social'> 
              <input class='etn-btn attr-btn-primary' data-repeater-create type='button' value='Add'/> 
         </div>
         </div>";
      }

      echo "</div>";
   }

   public function getSeparator($item, $key)
   {

      $class = $key;
      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
      }
      echo "<div class='{$class}'>";
      echo '<hr/>';
      echo "</div>";
   }

   public function getWpMap($item, $key)
   {
      $options = get_option('etn_event_general_options');
      $value = '';
      $class = $key;

      if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
      }
      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field ' : ' etn_event_meta_field';
      }
      require ETN_DIR . '/views/fields/map.php';
   }
   public function getHeading($item, $key)
   {

      if (!isset($item['label'])) {
         return;
      }

      $class = $key;
      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
      }

      $html = sprintf('<div class="%s"> 
      <h3 for="%s"> %s  </h3>
     
     </div>', $class, $key, $item['label']);

      echo  Helper::kses($html);
   }

   public function getTextinput($item, $key)
   {
      $value = '';
      $class = $key;

      if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
      }

      $value = get_post_meta(get_the_ID(), $key, true);


      if (isset($item['attr'])) {

         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
      }
      $html = sprintf('<div class="%s"> 
      <div class="etn-label"> <label for="%s"> %s : </label><div class="etn-desc">  %s  </div></div>
     <div class="etn-meta"> <input autocomplete="off" class="etn-form-control" type="%s" name="%s" id="%s" value="%s"/></div>
     </div>', $class, $key, $item['label'], $item['desc'] , $item['type'], $key, $key, $value);

      echo  Helper::kses($html);
   }

   public function getNumberInput($item, $key)
   {

      $value = '';
      $class = $key;

      if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
      }
      $value = get_post_meta(get_the_ID(), $key, true);

      $step = isset($item['step']) ? $item['step'] : "1";
      

      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field' : 'etn_event_meta_field';
      }

      $html = sprintf(
                     '<div class="%s"> 
                           <div class="etn-label"> <label for="%s"> %s : </label><div class="etn-desc">%s</div></div>
                           <div class="etn-meta"> 
                              <input autocomplete="off" class="etn-form-control" type="%s" name="%s" id="%s" value="%s" min="0" step="%s" />
                           </div>
                     </div>', $class, $key, $item['label'], $item['desc'] , $item['type'], $key, $key, $value, $step);

      echo  Helper::kses($html);
   }

   public function getEmailinput($item, $key)
   {

      $value = '';
      $class = $key;

      if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
      }


      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field ' : ' etn_event_meta_field';
      }

      $html = sprintf('<div class="%s"> 
      <div class="etn-label"> <label for="%s"> %s : </label></div>
      <div class="etn-meta">
      <input autocomplete="off" class="etn-form-control" type="%s" name="%s" id="%s" value="%s"/>
     </div></div>', $class, $key, $item['label'], $item['type'], $key, $key, $value);

      echo   Helper::kses($html);
   }

   public function getradioinput($item, $key)
   {

      $value = '';
      $class = $key;
      $input = '';

      $value = get_post_meta(get_the_ID(), $key, true);

      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field ' : 'etn_event_meta_field ';
      }


      if (!isset($item['options']) || !count($item['options'])) {
         $html = sprintf('<div class=" %s"> 
         <label for="%s"> %s : </label>
        
        </div>', $class, $key, $item['label']);

         echo   Helper::kses($html);
         return;
      } elseif (isset($item['options']) && count($item['options'])) {
         $options = $item['options'];

         foreach ($options as $option_key => $option) {
            $checked =  $option_key == $value ? 'checked' : '';

            $input .= sprintf(' <input  %s type="%s" name="%s" class="etn-form-control" value="%s"/><span> %s  </span> ', $checked, $item['type'], $key, $option_key, $option);
         }
      }


      $html = sprintf('<div class="%s form-group"> <label> %s  </label> 
          %s
     </div>', $class, $item['label'], $input);

      echo   Helper::kses($html);
   }

   public function getSelect2($item, $key)
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
        </div>', $class, $key, $item['label'] );
         echo   Helper::kses($html);
         return;
      } elseif (isset($item['options']) && count($item['options'])) {
         $options = $item['options'];
         $input .= sprintf('<select multiple name="%s[]" class="etn_es_event_select2 %s">', $key, $key, $class);
         foreach ($options as $option_key => $option) {
            if (is_array($value) && in_array($option_key, $value)) {
               $input .= sprintf(' <option %s value="%s"> %s </option>', 'selected', $option_key, $option);
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

   public function getSelectSingle($item, $key)
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
         $input .= sprintf('<select name="%s" class="etn_es_event_select2 %s">', $key, $key, $class);
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
            <div class="etn-desc">%s</div>
         </div>
          %s
     </div>', $class, $item['label'],$item['desc'],$input);

      echo ($html);
   }


   public function getUrlinput($item, $key)
   {

      $value = '';
      $class = $key;

      if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
      }

      if (isset($item['attr'])) {
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field ' : 'etn_event_meta_field ';
      }

      $html = sprintf('<div class="%s"> 
      <div class="etn-label"> <label for="%s"> %s : </label></div>
      <div class="etn-meta">
                <input class="etn-form-control" type="%s" name="%s" id="%s" value="%s"/>
          </div></div>', $class, $key, $item['label'], $item['type'], $key, $key, $value);

      echo   Helper::kses($html);
   }

   public function getUpload($item, $key)
   {

      $class = $key;
      $value = get_post_meta(get_the_ID(), $key, true);
      $image = ' button">Upload image';
      $image_size = 'full';
      $display = 'none';
      $multiple = 0;

      if (isset($item['multiple']) && $item['multiple']) {
         $multiple = true;
      }

      if (isset($item['attr'])) {

         if (isset($item['attr']['class']) && $item['attr']['class'] != '') {
            $class = ' etn_event_meta_field ' . $class . ' ' . $item['attr']['class'];
         } else {
            $class = ' etn_event_meta_field ';
         }
      }

      if ($image_attributes = wp_get_attachment_image_src($value, $image_size)) {

         $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
         $display = 'inline-block';
      }

      echo "<div class='{$class}'>";
      echo '
   
      <div class="etn-label"> <label>' . $item['label'] . '</label></div>
      <div class="etn-meta">
      <a data-multiple="' . $multiple . '" class="etn_event_upload_image_button' . $image . '</a>
      		<input type="hidden" name="' . $key . '" id="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />
		<a href="#" class="essential_event_remove_image_button" style="display:inline-block;display:' . $display . '">' . esc_html__('Remove image', 'essential-event-management') . '</a>
   </div></div>';
   }

   public function getTextarea($item, $key)
   {

      $rows = 14;
      $cols = 50;
      $value = '';
      $class = $key;
      if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
      }
      if (isset($item['attr'])) {
         $rows = isset($item['attr']['row']) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
         $cols = isset($item['attr']['col']) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field ' : 'etn_event_meta_field ';
      }

      $html = sprintf('<div class="%s form-group"><div class="etn-label"><label for="%s"> %s : </label></div> <div class="etn-meta"><textarea class="etn-form-control msg-control-box" id="%s" rows="%s" cols="%s" name="%s"> %s  </textarea></div> </div>', $class, $key, $item['label'], $key, $rows, $cols, $key, $value);

      echo   Helper::kses($html);
   }

   public function getWpEditor($item, $key)
   {

      $rows = 14;
      $cols = 50;
      $value = '';
      $class = $key;

      if (isset($item['settings']) && is_array($item['settings'])) {
         $settings = $item['settings'];
      }

      if (isset($item['value'])) {
         $value = get_post_meta(get_the_ID(), $key, true);
      }

      if (isset($item['attr'])) {
         $rows = isset($item['attr']['row']) && $item['attr']['row'] != '' ? $item['attr']['row'] : 14;
         $cols = isset($item['attr']['col']) && $item['attr']['col'] != '' ? $item['attr']['col'] : 50;
         $class = isset($item['attr']['class']) && $item['attr']['class'] != '' ? $item['attr']['class'] . ' etn_event_meta_field ' : 'etn_event_meta_field ';
      }

      echo "<div class='{$class}'>";

      wp_editor($value, $key, $settings);

      echo '</div>';
   }
}
