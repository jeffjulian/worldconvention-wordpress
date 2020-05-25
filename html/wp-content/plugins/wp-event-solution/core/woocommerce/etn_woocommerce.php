<?php

if ( !defined( 'ABSPATH' ) ) {
    die( 'Forbidden' );
}

if ( !class_exists( 'WC_Product_Data_Store_CPT' ) ) {
    return;
}

class Etn_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT implements WC_Object_Data_Store_Interface, WC_Product_Data_Store_Interface {

    /**
     * Method to read a product from the database.
     * @param WC_Product
     */
    public function read( &$product ) {

        $product->set_defaults();

        if ( !$product->get_id() || !( $post_object = get_post( $product->get_id() ) ) || !in_array( $post_object->post_type, ['etn', 'product'] ) ) {
            throw new Exception( __( 'Invalid product.', 'eventin' ) );
        }

        $id = $product->get_id();

        $product->set_props( [
            'name'              => $post_object->post_title,
            'slug'              => $post_object->post_name,
            'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
            'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
            'status'            => $post_object->post_status,
            'description'       => $post_object->post_content,
            'short_description' => $post_object->post_excerpt,
            'parent_id'         => $post_object->post_parent,
            'menu_order'        => $post_object->menu_order,
            'reviews_allowed'   => 'open' === $post_object->comment_status,
        ] );

        $this->read_attributes( $product );
        $this->read_downloads( $product );
        $this->read_visibility( $product );
        $this->read_product_data( $product );
        $this->read_extra_data( $product );
        $product->set_object_read( true );
    }

    /**
     * Get the product type based on product ID.
     *
     * @since 3.0.0
     * @param int $product_id
     * @return bool|string
     */
    public function get_product_type( $product_id ) {

        $post_type = get_post_type( $product_id );

        if ( 'product_variation' === $post_type ) {
            return 'variation';
        } elseif ( in_array( $post_type, ['etn', 'product'] ) ) { // change birds with your post type
            $terms = get_the_terms( $product_id, 'product_type' );
            return !empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
        } else {
            return false;
        }

    }

}

/**
 * returns the price of the custom product
 * product is the custom post we are creating
 */
function etn_woocommerce_product_get_price( $price, $product ) {
    $product_id = $product->get_id();

    if ( get_post_type( $product_id ) == 'etn' ) {
        $price = get_post_meta( $product_id, 'etn_ticket_price', true );
        $price = isset( $price ) ? ( floatval( $price ) ) : 0;
    }

    return $price;
}

/**
 * overwrite woocommerce store and make our custom post as a product
 */
function etn_woocommerce_data_stores( $stores ) {
    $stores['product'] = 'Etn_Product_Data_Store_CPT';
    return $stores;
}

function wc_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {

// deactivate product quantity
    if ( is_cart() ) {
        if ( get_post_type( $cart_item['product_id'] ) == 'etn' ) {

            $product_quantity = sprintf( '%2$s <input type="hidden" name="cart[%1$s][qty]" value="%2$s" />', $cart_item_key, $cart_item['quantity'] );
        }

    }

    return $product_quantity;
}

add_filter( 'woocommerce_data_stores', 'etn_woocommerce_data_stores' );
add_filter( 'woocommerce_product_get_price', 'etn_woocommerce_product_get_price', 10, 2 );
add_filter( 'woocommerce_cart_item_quantity', 'wc_cart_item_quantity', 10, 3 );

/**
 * aftersuccessfull checkout, some data are returned from woocommerce
 * we can use these data to update our own data storage / tables
 */
add_action( 'woocommerce_thankyou', 'etn_checkout_callback', 10, 1 );
function etn_checkout_callback( $order_id ) {

    if ( !$order_id ) {
        return;
    }

// Allow code execution only once
    if ( !get_post_meta( $order_id, '_thankyou_action_done', true ) ) {

        global $wpdb;
        $order = wc_get_order( $order_id );

        if ( $order->is_paid() ) {
            $paid = 'yes';
        } else {
            $paid = 'no';
        }

        $userId = 0;
        if ( is_user_logged_in() ) {
            $userId = get_current_user_id();
        }

        foreach ( $order->get_items() as $item_id => $item ) {

            // Get the product name
            $product_name     = $item->get_name();
            $product_quantity = (int) $item->get_quantity();
            $product_total    = $item->get_total();
            $my_post          = get_page_by_title( $product_name, OBJECT, 'etn' );

            if ( !empty( $my_post ) ) {

                $post_id              = $my_post->ID;
                $etn_sold_tickets     = get_post_meta( $post_id, 'etn_sold_tickets', true );
                $etn_sold_tickets     = isset( $etn_sold_tickets ) ? intval( $etn_sold_tickets ) : 0;
                $updated_sold_tickets = $etn_sold_tickets + intval( trim( $product_quantity ) );
                update_post_meta( $post_id, 'etn_sold_tickets', $updated_sold_tickets );

                $post_status = isset( $my_post->post_status ) ? $my_post->post_status : '';

                if ( $post_status == 'wc-pending' ) {
                    $status = 'Pending';
                } else

                if ( $post_status == 'wc-processing' ) {
                    $status = 'Review';
                } else

                if ( $post_status == 'wc-on-hold' ) {
                    $status = 'Review';
                } else

                if ( $post_status == 'wc-completed' ) {
                    $status = 'Active';
                } else

                if ( $post_status == 'wc-refunded' ) {
                    $status = 'Refunded';
                } else

                if ( $post_status == 'wc-failed' ) {
                    $status = 'DeActive';
                } else {
                    $status = 'Pending';
                }

                $paymentType = get_post_meta( $order_id, '_payment_method', true );

                if ( $paymentType == 'cod' ) {
                    $etn_payment_method = 'offline_payment';
                } else

                if ( $paymentType == 'bacs' ) {
                    $etn_payment_method = 'bank_payment';
                } else

                if ( $paymentType == 'cheque' ) {
                    $etn_payment_method = 'check_payment';
                } else

                if ( $paymentType == 'stripe' ) {
                    $etn_payment_method = 'stripe_payment';
                } else {
                    $etn_payment_method = 'online_payment';
                }

                $pledge_id = "";

                $insert_post_id         = $post_id;
                $insert_form_id         = $order_id;
                $insert_invoice         = get_post_meta( $order_id, '_order_key', true );
                $insert_event_amount    = $product_total;
                $insert_user_id         = $userId;
                $insert_email           = get_post_meta( $order_id, '_billing_email', true );
                $insert_event_type      = "ticket";
                $insert_payment_type    = 'woocommerce';
                $insert_pledge_id       = $pledge_id;
                $insert_payment_gateway = $etn_payment_method;
                $insert_date_time       = date( "Y-m-d" );
                $insert_status          = $status;
                $inserted               = $wpdb->query( "INSERT INTO `" . $wpdb->prefix . "etn_events` (`post_id`, `form_id`, `invoice`, `event_amount`, `user_id`, `email`, `event_type`, `payment_type`, `pledge_id`, `payment_gateway`, `date_time`, `status`) VALUES ('$insert_post_id', '$insert_form_id', '$insert_invoice', '$insert_event_amount', '$insert_user_id', '$insert_email', '$insert_event_type', '$insert_payment_type', '$insert_pledge_id', '$insert_payment_gateway', '$insert_date_time', '$insert_status')" );
                $id_insert              = $wpdb->insert_id;

                if ( $inserted ) {
                    $metaKey                              = [];
                    $metaKey['_etn_first_name']           = get_post_meta( $order_id, '_billing_first_name', true );
                    $metaKey['_etn_last_name']            = get_post_meta( $order_id, '_billing_last_name', true );
                    $metaKey['_etn_email']                = get_post_meta( $order_id, '_billing_email', true );
                    $metaKey['_etn_post_id']              = $post_id;
                    $metaKey['_etn_order_key']            = '_etn_' . $id_insert;
                    $metaKey['_etn_order_shipping']       = get_post_meta( $order_id, '_order_shipping', true );
                    $metaKey['_etn_order_shipping_tax']   = get_post_meta( $order_id, '_order_shipping_tax', true );
                    $metaKey['_etn_order_qty']            = $product_quantity;
                    $metaKey['_etn_order_total']          = $product_total;
                    $metaKey['_etn_order_tax']            = get_post_meta( $order_id, '_order_tax', true );
                    $metaKey['_etn_addition_fees']        = 0;
                    $metaKey['_etn_addition_fees_amount'] = 0;
                    $metaKey['_etn_addition_fees_type']   = '';
                    $metaKey['_etn_country']              = get_post_meta( $order_id, '_billing_country', true );
                    $metaKey['_etn_currency']             = get_post_meta( $order_id, '_order_currency', true );
                    $metaKey['_etn_date_time']            = date( "Y-m-d H:i:s" );

                    foreach ( $metaKey as $k => $v ) {
                        $data               = [];
                        $data["event_id"]   = $id_insert;
                        $data["meta_key"]   = $k;
                        $data["meta_value"] = $v;
                        $wpdb->insert( $wpdb->prefix . "etn_trans_meta", $data );
                    }

                }

            }

        }

        // Output some data
        echo '<p>Order ID: ' . esc_html( $order_id ) . ' — Order Status: ' . esc_html( $order->get_status() ) . ' — Order is paid: ' . $paid . '</p>';
        $order->update_meta_data( '_thankyou_action_done', true );
        $order->save();
    }

}
