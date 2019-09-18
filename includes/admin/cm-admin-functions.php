<?php
add_action('wp_ajax_searchUsers', 'searchUsers');
add_action('wp_ajax_nopriv_searchUsers', 'searchUsers');

function searchUsers()
{

    $result = [];

    //search usertable
    $wp_user_query = new WP_User_Query(
        array(
            'search' => "*{$_REQUEST['term']}*",
            'search_columns' => array(
                'user_login',
                'user_nicename',
                'user_email',
            ),

        ) );
    $users = $wp_user_query->get_results();

//search usermeta
    $wp_user_query2 = new WP_User_Query(
        array(
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'first_name',
                    'value' => $_REQUEST['term'],
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'last_name',
                    'value' => $_REQUEST['term'],
                    'compare' => 'LIKE'
                )
            )
        )
    );

    $users2 = $wp_user_query2->get_results();

    $totalusers_dup = array_merge($users,$users2);

    $totalusers = array_unique($totalusers_dup, SORT_REGULAR);

    foreach ($totalusers as $user) {

        if (empty($user->user_email)) continue;

        $result[] = [
            'id' => $user->ID,
            'name' => get_user_meta($user->ID, 'first_name', true) . ' ' . get_user_meta($user->ID, 'last_name', true),
            'email' => $user->user_email,
            'label' => get_user_meta($user->ID, 'first_name', true) . ' ' . get_user_meta($user->ID, 'last_name', true) . '(' . $user->user_email . ')',
            'value' => get_user_meta($user->ID, 'first_name', true) . ' ' . get_user_meta($user->ID, 'last_name', true)
        ];
    }

    echo json_encode($result);
    die;
}


add_action('wp_ajax_addUserToEmergencyPage', 'addUserToEmergencyPage');
add_action('wp_ajax_nopriv_addUserToEmergencyPage', 'addUserToEmergencyPage');

function addUserToEmergencyPage()
{
    if (isset($_POST['user_id'])) {
        update_user_meta($_POST['user_id'], 'emergency_access', 1);
    }
}


add_action('wp_ajax_removeUserFromEmergencyPage', 'removeUserFromEmergencyPage');
add_action('wp_ajax_nopriv_removeUserFromEmergencyPage', 'removeUserFromEmergencyPage');

function removeUserFromEmergencyPage()
{
    if (isset($_POST['user_id'])) {
        delete_user_meta($_POST['user_id'], 'emergency_access');
    }
}
