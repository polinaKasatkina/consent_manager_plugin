<?php

/**
 * Wrapper for get_posts specific to adult.
 *
 */

function insert_adult_and_update_order($request, $order_id)
{
    global $wpdb;

    foreach ($request['add_adult_f_name'] as $key => $adult) {

        $wpdb->insert(
            $wpdb->prefix . 'cm_adult_to_order',
            [
                'user_id' => get_current_user_id(),
                'first_name' => $adult,
                'last_name' => $request['add_adult_l_name'][$key],
                'email' => $request['add_adult_email'][$key],
                'order_id' => $order_id
            ]
        );
    }
}
