<?php

function cm_children_list()
{

    $parent_info = cm_get_children([
        'parent_id' => get_current_user_id()
    ]);

    cm_get_template(
        'family-details/list.php',
        array(
            'children'     => $parent_info,
            'has_children' => count($parent_info),
        )
    );
}

function add_new_child($current_page)
{
    $route_name = get_route_name($current_page);

    cm_get_template(
        'family-details/edit-layout.php', compact('route_name')
    );
}

function edit_child_info($current_page)
{
    $child_id = preg_match('/\//', $current_page)
        ? explode('/', $current_page)[0]
        : $current_page;

    check_child_id($child_id);

    $child_info = cm_get_child_info($child_id);
    $child_info->years = cm_get_child_years($child_info->DOB);
    $child_emergency_info = cm_get_child_emergency_info($child_id);
    $child_classes = cm_get_child_classes($child_id);
    $route_name = get_route_name($current_page);

    $relationships = ['mother', 'father', 'grandmother', 'grandfather', 'uncle', 'aunt', 'nanny'];

    cm_get_template(
        'family-details/edit-layout.php',
        compact('child_info', 'child_emergency_info', 'relationships', 'child_classes', 'route_name')
    );

    return;
}

function get_route_name($page)
{
    $current_page = explode('/', $page);

    return isset($current_page[1]) ? $current_page[1] : 'home';
}

/**
 * Check page if it is listing, current child or adding child page
 * @param $page
 */
function check_family_page($page)
{

    if (preg_match('/[0-9]/', $page)) { // child edit page
        edit_child_info($page);
    } elseif (preg_match('/add/', $page)) { // adding child page
        add_new_child($page);
    } elseif ($page == "") { // list page
        cm_children_list();
    } else { // trow error
        echo '<div class="woocommerce-error">Can not find child with this id in your list. <a href="' . wc_get_page_permalink( 'myaccount' ).'" class="wc-forward">'. __( 'My Account', 'woocommerce' ) .'</a>' . '</div>';
        exit;
    }
}

function cm_parent_details_page()
{
    global $current_user;
    get_currentuserinfo();

    $parent_info = cm_get_parent_info(get_current_user_id());

    cm_get_template(
        'parent-details/info.php',
        array(
            'parent_info'      => $current_user->data,
            'parent_meta'      => get_user_meta($current_user->data->ID),
            'parent_extra'     => $parent_info
        )
    );
}


function udf_consent_summary_page()
{
    $user_id = get_current_user_id();
    cm_get_template(
        'consent/summary.php',
        array(
            'parent_info'   => cm_get_parent_info($user_id),
            'children_info' => cm_get_children([
                'parent_id' => $user_id
            ])
        )
    );
}


function cm_adult_details_page($page)
{

    edit_adult_info($page);

}

function edit_adult_info($current_page)
{

    $user_info = get_userdata(get_current_user_id());
    $parent_info = cm_get_parent_info(get_current_user_id());
    $route_name = get_route_name($current_page);

    cm_get_template(
        'adult-details/edit-layout.php',
        compact('user_info', 'parent_info', 'route_name')
    );

    return;
}
