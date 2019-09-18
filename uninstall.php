<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb, $wp_version;

// Tables.
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cm_parent_info" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cm_children_info" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cm_emergency" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cm_doctors" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cm_child_to_order" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cm_emergency_log" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}cm_adult_to_order" );


// Deleting options
// TODO getting options array from the Install class
$options = [
    'cm_organisation_name',
    'cm_organisation_address',
    'cm_organisation_first_name',
    'cm_organisation_last_name',
    'cm_organisation_contact_phone',
    'cm_organisation_email',
    'cm_personal_data',
    'cm_how_data_used',
    'cm_publish_personal_data',
    'cm_publish_how_data_used',
    'cm_terms_and_conditions',
    'cm_booking_terms',
    'cm_privacy_policy',
    'cm_medical_treatment_text',
    'cm_photo_consent_text'
];

foreach ($options as $option) {
    delete_option($option);
}

// Delete Emergency post
$post = get_page_by_title("Emergency");
$id = $post->ID;
wp_delete_post($id, true);

//Delete users access to emergency page
$users = get_users([
    'meta_key' => 'emergency_access',
]);

if (!empty($users)) {
    foreach ($users as $user) {
        delete_user_meta($user->ID, 'emergency_access');
    }
}


// Delete children photos
$dirChildren = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/children_photo';
if (is_dir($dirChildren)) {
    $it = new RecursiveDirectoryIterator($dirChildren, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it,
        RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()){
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dirChildren);
}


// Delete adults photos
$dirAdult = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/adult_photo';
if (is_dir($dirAdult)) {
    $it = new RecursiveDirectoryIterator($dirAdult, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it,
        RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()){
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($dirAdult);
}

//Delete all exports
array_map('unlink', glob($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/personal_data*.csv')); // Personal data
array_map('unlink', glob($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/children_list*.csv')); // children list export from admin panel


// Clear any cached data that has been removed
wp_cache_flush();
