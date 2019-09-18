<?php
/**
 * Wrapper for get_posts specific to children.
 *
 * This function should be used for order retrieval so that when we move to
 * custom tables, functions still work.
 *
 * Args:
 * 		status array|string List of order statuses to find
 * 		type array|string Order type, e.g. shop_order or shop_order_refund
 * 		parent int post/order parent
 * 		customer int|string|array User ID or billing email to limit orders to a
 * 			particular user. Accepts array of values. Array of values is OR'ed. If array of array is passed, each array will be AND'ed.
 * 			e.g. test@test.com, 1, array( 1, 2, 3 ), array( array( 1, 'test@test.com' ), 2, 3 )
 * 		limit int Maximum of orders to retrieve.
 * 		offset int Offset of orders to retrieve.
 * 		page int Page of orders to retrieve. Ignored when using the 'offset' arg.
 * 		exclude array Order IDs to exclude from the query.
 * 		orderby string Order by date, title, id, modified, rand etc
 * 		order string ASC or DESC
 * 		return string Type of data to return. Allowed values:
 * 			ids array of order ids
 * 			objects array of order objects (default)
 * 		paginate bool If true, the return value will be an array with values:
 * 			'orders'        => array of data (return value above),
 * 			'total'         => total number of orders matching the query
 * 			'max_num_pages' => max number of pages found
 *
 * @since  2.6.0
 * @param  array $args Array of args (above)
 * @return array|stdClass Number of pages and an array of order objects if
 *                             paginate is true, or just an array of values.
 */

function cm_get_children( $args )
{

    $children = new Children();

    return $children->getChildren($args);

}

function cm_get_child_info($child_id)
{
    $children = new Children();

    return $children->getChild($child_id);
}

function cm_get_child_years($dob)
{
    $birthday_timestamp = strtotime($dob);
    $age = date('Y') - date('Y', $birthday_timestamp);
    if (date('md', $birthday_timestamp) > date('md')) {
        $age--;
    }
    return $age;
}

function cm_get_child_emergency_info($child_id)
{
    $emergency = new Emergency();

    return $emergency->getEmergency($child_id);
}

function has_emergency_info($child_id)
{
    $emergency = new Emergency();

    return $emergency->isEmergency($child_id);
}

function has_required_info($child_id)
{

    $children = new Children();

    return $children->hasRequired($child_id);
}

function has_consent($child_id)
{
    $children = new Children();

    return $children->hasConsent($child_id);
}

function update_child_info($request)
{

    $children = new Children();

    if (!empty($request['child_id'])) {
        $children->updateChild($request);
    } else {

        $request['child_id'] = $children->addChild($request);;
    }


    $emergency = new Emergency();

    $check_info = $emergency->isEmergency($request['child_id']);


    if ($check_info) {

        $emergency->updateEmergency($request);


    } else {
        $emergency->addEmergency($request);
    }


    wc_add_notice(__('Child details changed successfully', 'woocommerce'));

    wp_safe_redirect( home_url('my-account/family-details/') );

    return;
}

function update_child_emergency_info($request)
{
    global $wpdb;

    validate_emergency($request);

    setcookie('req', base64_encode(json_encode($request)));

    if (wc_notice_count( 'error' ) == 0) {

        setcookie('req', '');

        $emergency = new Emergency();

        $check_info = $emergency->isEmergency($request['child_id']);

        if ($check_info) {

            $emergency->updateEmergency($request);


        } else {
            $emergency->addEmergency($request);
        }

        wc_add_notice( __( 'Child emergency info changed successfully', 'woocommerce' ) );
    }

    wp_safe_redirect( home_url(add_query_arg( NULL, NULL )) );

    return;
}

function get_doctor_info($request)
{
    global $wpdb;
    $data = [];

    $query = "SELECT * FROM {$wpdb->prefix}cm_doctors WHERE `name` LIKE '%{$request}%'";

    $doctors = $wpdb->get_results($query);

    foreach ($doctors as $doctor) {
        $suggestion = [
            'id' => $doctor->id,
            'doctor_name' => $doctor->name,
            'doctor_address' => $doctor->address,
            'doctor_postcode' => $doctor->postcode,
            'doctor_phone' => $doctor->phone,
            'label' => "<b>{$doctor->name}</b>, phone: {$doctor->phone}",
            'value' => $doctor->name . ', ' . $doctor->phone
        ];

        $data[] = $suggestion;
    }

    return $data;
}

function add_new_doctor($req)
{
    global $wpdb;

    $data = [
        'name' => $req['doctor_name'],
        'address' => $req['doctor_address'],
        'postcode' => $req['doctor_postcode'],
        'phone'   => $req['doctor_phone']
    ];

    $wpdb->insert($wpdb->prefix . 'cm_doctors', $data);

    return $wpdb->insert_id;
}

function check_docor_info($request)
{
    global $wpdb;

    $doctor_info = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cm_doctors WHERE id = {$request['doctor_id']}");

    if ($doctor_info->address != $request['doctor_address']) {
        $wpdb->update(
            $wpdb->prefix . 'cm_doctors',
            [
                'address'  => $request['doctor_address'],
                'postcode' => $request['doctor_postcode']
            ],
            [
                'id' => $doctor_info->id
            ]
        );
    }

    return $doctor_info->name != $request['doctor_name'] || $doctor_info->phone != $request['doctor_phone']
        ? add_new_doctor($request)
        : $doctor_info->id;
}

function check_child_id($child_id)
{
    if (is_numeric($child_id)) {

        $children = new Children();

        if (!$children->getChildByParentId($child_id, get_current_user_id())) {

            echo '<div class="woocommerce-error">Can not find child with this id in your list. <a href="' . wc_get_page_permalink( 'myaccount' ).'" class="wc-forward">'. __( 'My Account', 'woocommerce' ) .'</a>' . '</div>';
            exit;
        } else {
            return;
        }

    } else {
        echo '<div class="woocommerce-error">Can not find child with this id in your list. <a href="' . wc_get_page_permalink( 'myaccount' ).'" class="wc-forward">'. __( 'My Account', 'woocommerce' ) .'</a>' . '</div>';
        exit;
    }
}

function update_child_agree_info($request)
{
    $children = new Children();

    $children->updateAgreeInfo($request);

    wc_add_notice( __( 'Child agree info changed successfully', 'woocommerce' ) );

    wp_safe_redirect( home_url(add_query_arg( NULL, NULL )) );

    return;
}

function validate_info($request)
{
    $rules = [
        'name'   => 'required|max:255',
        'dob'    => 'required|validDate',
        'school' => 'required'
    ];

    $flag = true;

    foreach ($rules as $key => $rule) {
        $rulesArr = explode("|", $rule);

        foreach ($rulesArr as $item) {

            $itemLength = preg_match('/max/', $rule) ? str_replace('max:', '', $item) : 0;

            if (preg_match('/max/', $item)) {
                if (! CM_Validate::MaxLength($request[$key], $itemLength)) {
                    wc_add_notice( sprintf('Field %s must be %d characters length', $key, $itemLength), 'error' );
                    $flag = false;
                }
            }

            switch ($item) {
                case 'required' :
                    if (! CM_Validate::requiredVal($request[$key]) ) {
                        wc_add_notice( sprintf('Field %s is required', $key), 'error' );
                        $flag = false;
                    }

                    break;
                case 'numeric' :

                    if (! CM_Validate::isNumeric($request[$key])) {
                        wc_add_notice( sprintf('Field %s must contain numeric value', $key), 'error' );
                        $flag = false;
                    }


                    break;

                case 'validDate' :

                    if (! CM_Validate::checkDateOfBirth($request[$key])) {
                        wc_add_notice( sprintf('Field %s must be not more than ' . date('d.m.Y'), $key), 'error' );
                        $flag = false;
                    }

                    break;
            }
        }
    }

    return $flag;
}

function validate_emergency($request)
{
    $rules = [
        'name'            => 'required|max:128',
        'relationship'    => 'required|max:28',
        'phone'           => 'required|phone',
        'mobile'          => 'required|mobile'
    ];

    foreach ($rules as $key => $rule) {
        $rulesArr = explode("|", $rule);

        foreach ($rulesArr as $item) {

            $itemLength = preg_match('/max/', $rule) ? str_replace('max:', '', $item) : 0;


            if (preg_match('/max/', $item)) {
                if (! CM_Validate::MaxLength($request[$key], $itemLength)) {
                    wc_add_notice( sprintf('Field %s must be %d characters length', $key, $itemLength), 'error' );
                }
            }

            switch ($item) {
                case 'required' :
                    if (! CM_Validate::requiredVal($request[$key]) ) {
                        wc_add_notice( sprintf('Field %s is required', $key), 'error' );
                    }

                    break;
                case 'phone' :
                    if (! CM_Validate::isPhone($request[$key]) ) {
                        wc_add_notice( sprintf('Field %s must be formatted like 0XX XXXX XXXX', $key), 'error' );
                    }
                    break;
                case 'mobile' :
                    if (! CM_Validate::isMobile($request[$key]) ) {
                        wc_add_notice( sprintf('Field %s must be formatted like 0XXXX-XXX-XXX', $key), 'error' );
                    }
                    break;
            }
        }
    }
}

function cm_update_data($request)
{

    switch ($request['route_name'])  {
        case 'child_info' :
            return update_child_info($request);
            break;
        case 'info':
            return update_child_info($request);
            break;
        case 'emergency':
            return update_child_emergency_info($request);
            break;
        case 'agree':
            return update_child_agree_info($request);
            break;
        case 'parent_info':
            return update_parent_info($request);
            break;
        case 'privacy':
            return update_parent_privacy($request);
            break;
        case 'delete':
            return delete_child($request);
            break;
        case 'adult_info' :
            return update_adult_info($request);
            break;
        case 'delete_adult':
            return delete_adult($request);
            break;
        default:
            //throw new Exception('Can not find update path');
            break;
    }
    return;
}

function delete_child($request)
{
    check_child_id($request['child_id']);

    $children = new Children();

    $children->deleteChild($request['child_id']);

    wc_add_notice( __( 'Child info removed successfully', 'woocommerce' ) );

    wp_safe_redirect( home_url('my-account/family-details') );

    return;
}

function update_child_order_info($child_ids, $order_id)
{
    global $wpdb;

    foreach ($child_ids as $child_id) {
        $wpdb->insert(
            $wpdb->prefix . 'cm_child_to_order',
            [
                'child_id' => $child_id,
                'order_id' => $order_id
            ]
        );
    }

}

function insert_child_and_update_order($request, $order_id)
{
    global $wpdb;

    foreach ($request['add_child_name'] as $key => $child) {
        $wpdb->insert(
            $wpdb->prefix . 'cm_children_info',
            [
                'user_id' => get_current_user_id(),
                'name'    => $child,
                'DOB'     => date('Y-m-d', strtotime($request['add_child_years'][$key]))
            ]
        );

        $wpdb->insert(
            $wpdb->prefix . 'cm_child_to_order',
            [
                'child_id' => $wpdb->insert_id,
                'order_id' => $order_id
            ]
        );
    }
}

function cm_get_child_classes($child_id)
{
    $orders = ChildOrder::getChildOrders($child_id);
    $items = [];

    foreach ($orders as $order) {
        $orderObj = new WC_Order( $order->order_id );

        foreach ($orderObj->get_items() as $item) {
            $items[] = [
                'id'       => $item['item_meta']['_product_id'][0],
                'name'     => $item['name'],
                'date'     => $item['item_meta']['Booking Date'][0],
                'time'     => $item['item_meta']['Booking Time'][0],
                'duration' => $item['item_meta']['Duration'][0],
                'ts'       => strtotime($item['item_meta']['Booking Date'][0] . ' ' . $item['item_meta']['Booking Time'][0])
            ];
        }
    }

    usort($items, function ($a, $b) {
        return ($a['ts'] <= $b['ts'])
            ? -1
            : 1;
    });

    return $items;

}

/**
 * Check if there is an emergency info for each child
 */
function check_info()
{
    $children = cm_get_children([
        'parent_id' => get_current_user_id()
    ]);

    foreach ($children as $child) {

        $info = has_required_info($child->id);

        if (!$info) {

            echo '<div class="alert-error">Please complete information for ' . $child->name . '! <a href="' . home_url('my-account/family-details/' . $child->id) . '" class="wc-forward btn">Continue</a>' . '</div>';

        } else {

            $emergency = has_emergency_info($child->id);

            if (!$emergency) {

                echo '<div class="alert-error">Please complete emergency information for ' . $child->name . '! <a href="' . home_url('my-account/family-details/' . $child->id . '/emergency') .'" class="wc-forward btn">Continue</a>' . '</div>';

            } else {

                $consent = has_consent($child->id);

                if (!$consent->medical_agree) {
                    echo '<div class="alert-error">Please complete consent section for ' . $child->name . '! <a href="' . home_url('my-account/family-details/' . $child->id . '/agree') .'" class="wc-forward btn">Continue</a>' . '</div>';
                }
            }
        }


    }
}

function count_children_to_order($order_id)
{
    $children = new Children();

    return count($children->getChildrenToOrder($order_id));
}


function get_children_names_to_order($order_id)
{
    $children = new Children();
    $childrenInfo = $children->getChildrenToOrder($order_id);
    $names = [];

    if ($childrenInfo) {
        foreach ($childrenInfo as $child) {
            $childInfo = cm_get_child_info($child->child_id);

            $names[] = $childInfo->name;
        }
    }

    return $names;
}
