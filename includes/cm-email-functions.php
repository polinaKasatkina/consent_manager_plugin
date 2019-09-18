<?php
add_action('woocommerce_email_customer_details', 'show_customer_order_details', 0);

function show_customer_order_details($order)
{

    get_customer_details($order);

    get_children_details($order);
}


function get_customer_details($order)
{

    $fields = array();

    if ($order->customer_note) {
        $fields['customer_note'] = array(
            'label' => __('Note', 'woocommerce'),
            'value' => wptexturize($order->customer_note)
        );
    }

    if ($order->billing_email) {
        $fields['billing_email'] = array(
            'label' => __('Email', 'woocommerce'),
            'value' => wptexturize($order->billing_email)
        );
    }

    if ($order->billing_phone) {
        $fields['billing_phone'] = array(
            'label' => __('Tel', 'woocommerce'),
            'value' => wptexturize($order->billing_phone)
        );
    }

    $customer_id = get_post_meta( $order->id, '_customer_user', true );

    $customer_info = cm_get_parent_info($customer_id);

    if ($customer_info->mobile) {
        $fields['billing_phone'] = array(
            'label' => __('Tel', 'woocommerce'),
            'value' => wptexturize($customer_info->mobile)
        );
    }

    //$fields = array_filter(apply_filters('woocommerce_email_customer_details_fields', $fields, $sent_to_admin, $order), array($this, 'customer_detail_field_is_valid'));

    wc_get_template('emails/email-customer-details.php', array('fields' => $fields));
//    cm_get_template('emails/email-customer-details.php', array('fields' => $fields));

    return;
}

function get_children_details($order)
{
    global $wpdb;

    $children = $wpdb->get_results("SELECT child_id, name, DOB FROM {$wpdb->prefix}cm_child_to_order cto
                                    LEFT JOIN {$wpdb->prefix}cm_children_info ci ON (cto.child_id = ci.`id`)
                                    WHERE order_id = {$order->id}");

    cm_get_template('emails/children_order_details.php', compact('children'));
}
