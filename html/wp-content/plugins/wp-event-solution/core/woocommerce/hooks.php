<?php

namespace Etn\Core\Woocommerce;

defined('ABSPATH') || exit;
class Hooks
{

  use \Etn\Traits\Singleton;

  public $action;
  public $base;

  public function Init()
  {
    $_metabox = new  \Etn\Core\Metaboxs\Product_meta();
    $_speaker = new  \Etn\Core\Metaboxs\Speaker_meta();
    $_schedule = new  \Etn\Core\Metaboxs\Schedule_meta();

    add_action('add_meta_boxes', [$_metabox, 'register_meta_boxes']);
    add_action('save_post', [$_metabox, 'save_meta_box_data']);
    add_filter('woocommerce_add_to_cart_redirect', [$this, 'redirect_checkout_add_cart']);
    add_filter('wp_insert_post_data', array($_speaker, 'etn_set_speaker_title'), 500, 2);
    add_filter('wp_insert_post_data', array($_schedule, 'etn_set_schedule_title'), 500, 2);
    add_filter('woocommerce_cart_item_name', [$this, 'etn_cart_item_name'], 10, 3);
    add_action('woocommerce_cart_calculate_fees', [$this, '_add_cart_event_fee']);
    add_filter('woocommerce_cart_item_price', [$this, '_cart_item_price'], 10, 3);
    add_action('add_meta_boxes', [$this, 'etn_generate_report']);
  }



  /**
   * function etn_generate_report
   *  used for generating report inside metabox
   */
  function etn_generate_report()
  {
    add_meta_box(
      'etn-report',
      __('Order Report', 'eventin'),
      [$this, 'etn_report_callback'],
      'etn'
    );
  }

  /**
   * function etn_report_callback
   * gets the current event id, 
   * gets all details of this event, calculates total sold quantity and price
   * then finally generates report
   */
  function etn_report_callback()
  {
    global $wpdb;
    $report_options = get_option("etn_event_report_etn_options");
    $report_sorting = isset($report_options["event_list"]) ? strtoupper($report_options["event_list"]) : "DESC";

    $current_post_id = get_the_ID();
    $ticket_qty = get_post_meta($current_post_id, "etn_sold_tickets", true);
    $total_sold_ticket = isset($ticket_qty) ? intval($ticket_qty) : 0;
    $all_sales = $wpdb->get_results("SELECT * FROM wp_etn_events WHERE post_id = $current_post_id ORDER BY event_id $report_sorting");
    $total_sale_price = 0;
    foreach ($all_sales as $single_sale) {
      $total_sale_price += $single_sale->event_amount;
      $foreign_key_to_event_table = $single_sale->event_id;
      $single_sale_meta = $wpdb->get_results("SELECT * FROM wp_etn_trans_meta WHERE event_id = $foreign_key_to_event_table AND meta_key = '_etn_order_qty'");

?>
      <div>
        <div><strong>invoice no.</strong> <?php echo esc_html($single_sale->invoice); ?>
          &nbsp &nbsp <strong>total qty:</strong> <?php echo esc_html($single_sale_meta[0]->meta_value); ?>
          &nbsp &nbsp <strong>total amount:</strong> <?php echo esc_html($single_sale->event_amount); ?>
          &nbsp &nbsp <strong>email:</strong> <?php echo esc_html($single_sale->email); ?>
          &nbsp &nbsp <strong>status:</strong> <?php echo esc_html($single_sale->status); ?>
          &nbsp &nbsp<strong>payment type:</strong> <?php echo esc_html($single_sale->payment_gateway); ?>
        </div>
      </div>
      <hr>
    <?php
    }
    ?>

    <div>
      <strong>Total tickets sold:</strong> <?php echo esc_html($total_sold_ticket); ?>
    </div>
    <div>
      <strong>Total price sold:</strong> <?php echo esc_html($total_sale_price); ?>
    </div>
<?php
  }


  function _cart_item_price($price, $cart_item, $cart_item_key)
  {

    return $price;
  }


  /**
   * function redirect_checkout_add_cart
   * returns woocommerce checkout url
   */
  function redirect_checkout_add_cart($cart)
  {

    return wc_get_checkout_url();
  }


  function _add_cart_event_fee()
  {

    // WC()->cart->add_fee(__('Event extra charge', 'eventin'), 50);
  }


  function etn_cart_item_name($product_title, $cart_item, $cart_item_key)
  {

    if (get_post_type($cart_item['product_id']) == 'etn') {

      $_product   = $cart_item['data'];
      $return_value =  $_product->get_title();
      $product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';
      if (!$product_permalink) {
        $return_value = $_product->get_title() . '&nbsp;';
      } else {
        $return_value = sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_title());
      }
      return $return_value;
    }
  }
}
