<?php

function cm_get_parent_info($parent_id)
{
    $parent = new ParentInfo();

    return $parent->getParentInfo($parent_id);

}

function update_parent_info($request)
{
    $parent = new ParentInfo();

    $parent->updateParentMeta($request);

    $parent_extra = $parent->getParentQuery($request['user_id']);


    if ($parent_extra) {
        $parent->updateParent($request);

    } else {

        $data['user_id'] = $request['user_id'];

        $parent->addParent($request);

    }

    $parent->updateParentEmergencyMeta($request);


    wc_add_notice( __( 'Your detailed info changed successfully', 'woocommerce' ) );

    wp_safe_redirect( home_url('my-account/adult-details') );

    return;

}

function update_parent_privacy($request)
{

    global $wpdb;
    $parent = new ParentInfo();

//    $parent->updateParentMeta($request);

    $parent_extra = $parent->getParentQuery($request['user_id']);

    $data = [
        'subscription' => $request['subscription'] ? 1 : 0
    ];

    if ($parent_extra) {

        $wpdb->update(
            $wpdb->prefix . 'cm_parent_info',
            $data,
            ['user_id' => $request['user_id']]
        );

    } else {

        $data['user_id'] = $request['user_id'];

        $parent->addParent($request);

    }

    wc_add_notice( __( 'Your privacy info changed successfully', 'woocommerce' ) );

    wp_safe_redirect( home_url(add_query_arg( NULL, NULL )) );

    return;
}

function update_parent_mobile($request)
{

    $user_id = get_current_user_id();

    $parent = new ParentInfo();

    $parent_extra = $parent->getParentQuery($user_id);

    $data = [
        'mobile' => str_replace(['-', ' '], '', $request['mobile'])
    ];

    if ($parent_extra) {
        $parent->updateParent($request);
    } else {

        $data['user_id'] = $user_id;
        $request['user_id'] = $user_id;

        $parent->addParent($request);

    }

}


function validate_parent_info($request)
{
    $rules = [
     //   'fname'        => 'required|max:255',
      //  'lname'        => 'required|max:255',
        'phone'        => 'required|phone',
        'mobile'       => 'required|mobile'
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
            } elseif ($item == 'required') {
                if (! CM_Validate::requiredVal($request[$key]) ) {
                    wc_add_notice( sprintf('Field %s is required', $key), 'error' );
                    $flag = false;
                }
            } elseif ($item == 'phone') {
                if (! CM_Validate::isPhone($request[$key]) ) {
                    wc_add_notice( sprintf('Field %s must be formatted like 0XX XXXX XXXX', $key), 'error' );
                    $flag = false;
                }
            } elseif ($item == 'mobile') {
                if (! CM_Validate::isMobile($request[$key]) ) {
                    wc_add_notice( sprintf('Field %s must be formatted like 0XXXX-XXX-XXX', $key), 'error' );
                    $flag = false;
                }
            } elseif ($item == 'address') {

            }

        }
    }

    return $flag;
}
