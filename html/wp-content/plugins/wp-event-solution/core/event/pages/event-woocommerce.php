<?php

namespace Etn\Core\Event\Pages;

defined('ABSPATH') || exit;

class Event_Woocommerce
{

   function cart_template($template)
   {

      if (class_exists('WooCommerce')) {

         if (is_page('cart') || is_cart()) {
            return ETN_DIR . '/views/template/woocommerce/cart/cart.php';
         }
      }

      return $template;
   }

   public function after_cart_table()
   {

      require ETN_DIR . '/views/template/woocommerce/cart/cart-attendee.php';
   }

   public function order_processed($order_id)
   {

      $attendee = $_SESSION['attendee'];
      update_post_meta($order_id, 'etn_es_wc_order_attendee_data', $attendee);

      $this->save_ticket($order_id, $attendee);
      unset($_SESSION['attendee']);
   }

   public function order_attendee_details($order)
   {

      $attendee_data = get_post_meta($order->get_id(), 'etn_es_wc_order_attendee_data', true);

      if (!is_array($attendee_data)) {
         return;
      }
      try {
         echo "<h2>" . esc_html__('Attendee', 'eventin') . "</h2>";
         echo "<div class='etn-es-single-page-flex-container etn-extra-attendee'>";

         foreach ($attendee_data as $key => $item) {

            $_product = wc_get_product($key);

            echo "<div class='etn-es-event-cart-attendee'>";
            echo "<h3>" . esc_html($_product->get_title()) . "</h3>";

            if (is_array($item)) {

               foreach ($item as $k => $attendee) {
                  echo "<div>" . esc_html($attendee['name']) . ' - ' . esc_html($attendee['phone']) . "</div>";
               }
            }

            echo "</div>";
         } //end foreach

         echo "</div>";
      } catch (Exception $e) {

         return;
      }
   }

   public function save_ticket($order_id = null, $data = [])
   {

      if ($order_id == '' || is_null($order_id)) {
         return;
      }

      $ticket = array(
         'post_title'    =>  "#order-" . $order_id,
         'post_status'   => 'pending',
         'post_content'  => serialize($data),
         'post_type' => 'etn',
      );

      wp_insert_post($ticket);
   }

   public function order_status_completed($order_id)
   {
      var_dump($order_id);
      //  wp_mail(); send attendee tickets
      die();
   }
   public function order_refunded($order_id, $refund_id)
   {

      //  wp_mail(); send attendee tickets
   }
}
