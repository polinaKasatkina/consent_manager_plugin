<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

final class Consent_Manager {

    public $version = "1.0";

    /**
     * The single instance of the class.
     *
     *
     */
    protected static $_instance = null;

    /**
     * Main Consent Manager Instance.
     *
     * Ensures only one instance of Consent Manager is loaded or can be loaded.
     *
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Consent Manager Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

        do_action( 'user_details_loaded' );
    }

    /**
     * Hook into actions and filters.
     * @since  2.3
     */
    private function init_hooks() {

        register_activation_hook( CM_PLUGIN_FILE, array( 'CM_Install', 'install' ) );

        add_action( 'wp_loaded', array( 'PageTemplater', 'get_instance' ) );

//        add_action('woocommerce_account_content', array('ChildrenFunctions', 'check_info'));
        add_action('wp_ajax_getDoctorInfo', array($this, 'doctors_ajax'));
        add_action('wp_ajax_nopriv_getDoctorInfo', array($this, 'doctors_ajax'));
        add_action('wp_ajax_getAddress', array($this, 'get_address_from_google'));
        add_action('wp_ajax_nopriv_getAddress', array($this, 'get_address_from_google'));
        add_action('wp_ajax_getAddressByPostCode', array($this, 'get_address_by_postcode'));
        add_action('wp_ajax_nopriv_getAddressByPostCode', array($this, 'get_address_by_postcode'));
        add_action('wp_enqueue_scripts', array($this, 'my_custom_scripts'));

        add_action('wp_ajax_getBookingAddress', array($this, 'get_booking_address'));
        add_action('wp_ajax_nopriv_getBookingAddress', array($this, 'get_booking_address'));

        add_action('wp_ajax_getChildrenList', array($this, 'get_children_list'));
        add_action('wp_ajax_nopriv_getChildrenList', array($this, 'get_children_list'));

        add_action('wp_ajax_uploadPhoto', array($this, 'upload_child_photo'));
        add_action('wp_ajax_nopriv_uploadPhoto', array($this, 'upload_child_photo'));

        add_action('wp_ajax_saveConsent', array($this, 'saveConsent'));
        add_action('wp_ajax_nopriv_saveConsent', array($this, 'saveConsent'));

        add_action('wp_ajax_getUserData', array($this, 'getUserData'));
        add_action('wp_ajax_nopriv_getUserData', array($this, 'getUserData'));

        add_action('wp_ajax_deleteUserData', array($this, 'deleteUserData'));
        add_action('wp_ajax_nopriv_deleteUserData', array($this, 'deleteUserData'));

        add_action('wp_ajax_getChildrenFieldsForCheckout', array($this, 'getChildrenFieldsForCheckout'));
        add_action('wp_ajax_nopriv_getChildrenFieldsForCheckout', array($this, 'getChildrenFieldsForCheckout'));


        add_action( 'wp', array($this, 'change_post_per_page_wpent') );

        // Adding link to Settings page
        add_filter( 'plugin_action_links_' . CM_PLUGIN_BASENAME, array($this, 'cm_action_links') );

        add_action( 'cm_flush_rewrite_rules', array( $this, 'cm_flush_rewrite_rules' ) );
    }

    public function cm_action_links( $links ) {
        $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=consent_settings') ) .'">Settings</a>';
        return $links;
    }

    public function change_post_per_page_wpent( $query ) {

        if (isset($_POST['route_name'])) {

            cm_update_data($_POST);

            exit;
        }

        return $query;
    }


    /* Proper way to enqueue scripts and styles */
    public function my_custom_scripts()
    {
        // Register and Enqueue a Stylesheet
        // get_template_directory_uri will look up parent theme location
        wp_register_style( 'cm_style', plugin_dir_url(__FILE__) . '../assets/css/style_default13.css');
        wp_enqueue_style( 'cm_style' );

        $this->check_theme();

        wp_register_style( 'jquery_modal', plugin_dir_url(__FILE__) . '../assets/css/jquery.modal.min.css');
        wp_enqueue_style( 'jquery_modal' );

        // Register and Enqueue a Script
        // get_stylesheet_directory_uri will look up child theme location
        wp_register_script( 'maskedinput', plugin_dir_url(__FILE__) . '../assets/js/jquery.maskedinput121.js', array('jquery'),null,true);
        wp_enqueue_script( 'maskedinput' );

        wp_register_script( 'jquery_modal', plugin_dir_url(__FILE__) . '../assets/js/jquery.modal.min.js', array('jquery'),null,true);
        wp_enqueue_script( 'jquery_modal' );

        wp_register_script( 'cm-script', plugin_dir_url(__FILE__) . '../assets/js/main251.js', array('jquery','jquery-ui-autocomplete', 'maskedinput'),null,true);
        $translation_array = array(
            'siteurl' => get_option('siteurl')
        );

        wp_localize_script('cm-script', 'CM_variables', $translation_array);
        wp_enqueue_script( 'cm-script' );


    }

    public function check_theme()
    {
        $theme = wp_get_theme();

        switch ($theme->Name) {
            case 'Avada' :
                wp_register_style( 'cm_style_avada', plugin_dir_url(__FILE__) . '../assets/css/style_avada.css');
                wp_enqueue_style( 'cm_style_avada' );
                break;
            case 'X' :
                wp_register_style( 'cm_style_x', plugin_dir_url(__FILE__) . '../assets/css/style_x_theme.css');
                wp_enqueue_style( 'cm_style_x' );
                break;
            case 'Twenty Seventeen':
                wp_register_style( 'cm_style_twenty_seventeen', plugin_dir_url(__FILE__) . '../assets/css/style_twenty_seventeen.css');
                wp_enqueue_style( 'cm_style_twenty_seventeen' );
                break;
            case 'Betheme':
                wp_register_style( 'cm_style_betheme', plugin_dir_url(__FILE__) . '../assets/css/style_betheme.css');
                wp_enqueue_style( 'cm_style_betheme' );
                break;
            case "Oshin":
                wp_register_style( 'cm_style_oshin', plugin_dir_url(__FILE__) . '../assets/css/style_oshin.css');
                wp_enqueue_style( 'cm_style_oshin' );
                break;

        }
    }

    public function doctors_ajax()
    {

        echo json_encode(Doctor::getDoctorAjax($_REQUEST['term']));

        die();
    }

    public function get_address_from_google()
    {
        $data = [
            'key' => $this->api_key,
            'input' => $_REQUEST['term'],
            'location' => '51.507222,-0.1275', // london coordinates
            'radius' => 10000
        ];
        $result = [];

        $params = [];
        foreach ($data as $key => $item) {
            $params[] = $key . '=' . $item;
        }

        $myCurl = curl_init();
        curl_setopt_array($myCurl, array(
            CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/autocomplete/json?' . implode('&', $params),
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($myCurl);
        curl_close($myCurl);

        $response = json_decode($response, true);

        foreach ($response['predictions'] as $value) {
            $suggestion = [
                'label' => $value['description'],
                'value' => $value['main_text']
            ];

            $result[] = $suggestion;
        }

        echo json_encode($result);
        die;
    }

    public function get_address_by_postcode()
    {
        $data = [
            'api-key'  => 'FqsrU4n7zEy0adGwXWOzuw13408', //'W0s1nw5yVkqCB4K6Yb9lgw6746',
            'postcode' => str_replace(' ', '', $_REQUEST['term'])
        ];
        $result = [];

        $myCurl = curl_init();
        curl_setopt_array($myCurl, array(
            CURLOPT_URL => "https://api.getAddress.io/v2/uk/{$data['postcode']}?api-key={$data['api-key']}",
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($myCurl);
        curl_close($myCurl);

        $response = json_decode($response, true);

        foreach ($response['Addresses'] as $value) {
            $val = str_replace([', , , , ', ', , , , , '], '', $value);
            $suggestion = [
                'label' => $val,
                'value' => $val
            ];

            $result[] = $suggestion;
        }

        echo json_encode($result);
        die;
    }

    public function get_booking_address()
    {
        $parent = new ParentInfo();
        $result = $parent->get_billing_address();

        echo json_encode($result);
        die;
    }

    public function get_children_list()
    {
        global $wpdb;

        $children = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cm_children_info");

        if (!empty($children)) {

            $fp = fopen('../wp-content/uploads/children_list.csv', 'w');

            fputcsv($fp, [
                'Child name',
                'Years',
                'Parent',
                'Doctor Info',
                'Has medical consent',
                'Has sharing consent'
            ]);

//            $childrenObj = new ChildrenFunctions();

            foreach ($children as $child) {

                $parent = get_user_by('id', $child->user_id)->data;
                $parent_info = '';
                if ($parent) {
                    $mobile = $wpdb->get_row("SELECT mobile FROM {$wpdb->prefix}cm_parent_info WHERE user_id = {$child->user_id}");

                    $parent_info = $parent->display_name ? $parent->display_name : get_user_meta($child->user_id, "first_name", true) . ' ' . get_user_meta($child->user_id, "last_name", true);
                    $parent_info .= $mobile->mobile ? ', ' . $mobile->mobile : '';
                    $parent_info .= $parent->user_email ? ', ' . $parent->user_email : '';
                }

                $doctor = $wpdb->get_row("SELECT d.name, d.phone, d.address FROM {$wpdb->prefix}cm_emergency e
                                      LEFT JOIN {$wpdb->prefix}cm_doctors d
                                      ON (e.doctor_id = d.id)
                                      WHERE e.child_id = {$child->id}");

                $doctor_info = $doctor->name;
                $doctor_info .= $doctor->phone ? ', ' . $doctor->phone : '';

                fputcsv($fp, [
                    $child->name,
                    cm_get_child_years($child->DOB) . ' years',
                    $parent_info,
                    $doctor_info,
                    $child->medical_agree ? 'Yes' : 'No',
                    $child->share_agree ? 'Yes' : 'No'
                ]);

            }

            fclose($fp);
        }

        die;
    }


    /**
     * Define CM Constants.
     */
    private function define_constants() {
        $upload_dir = wp_upload_dir(null, false);

        $this->define( 'CM_ABSPATH', dirname( CM_PLUGIN_FILE ) . '/' );
        $this->define( 'CM_PLUGIN_FILE', __FILE__ );
        $this->define( 'CM_PLUGIN_BASENAME', plugin_basename( CM_PLUGIN_FILE ) );
        $this->define( 'CM_PLUGIN_DIR', plugin_dir_path(__FILE__));
        $this->define( 'CM_VERSION', $this->version );
        $this->define( 'CM_LOG_DIR', $upload_dir['basedir'] . '/logs/' );
    }

    /**
     * Define constant if not already set.
     *
     * @param  string $name
     * @param  string|bool $value
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes() {

        // models
        include_once(CM_ABSPATH . 'includes/models/children.php');
        include_once(CM_ABSPATH . 'includes/models/doctor.php');
        include_once(CM_ABSPATH . 'includes/models/emergency.php');
        include_once(CM_ABSPATH . 'includes/models/child_order.php');
        include_once(CM_ABSPATH . 'includes/models/parent.php');

        include_once(CM_ABSPATH . 'includes/cm-child-functions.php');
        include_once(CM_ABSPATH . 'includes/cm-parent-functions.php');
        include_once(CM_ABSPATH . 'includes/cm-adult-functions.php');

        // functions
        include_once(CM_ABSPATH . 'includes/class-cm-install.php');
        include_once(CM_ABSPATH . 'includes/class-cm-custom-endpoint.php');
        include_once(CM_ABSPATH . 'includes/cm-template-functions.php');
        include_once(CM_ABSPATH . 'includes/cm-template-hooks.php');
        include_once(CM_ABSPATH . 'includes/class-cm-validate.php');
        include_once(CM_ABSPATH . 'includes/cm-email-functions.php');

        include_once(CM_ABSPATH . 'includes/admin/class-cm-admin.php');

        include_once(CM_ABSPATH . 'includes/class-cm-page-templater.php');
        include_once(CM_ABSPATH . 'includes/class-cm-page-creator.php');

        include_once(CM_ABSPATH . 'functions.php');
    }

    public function upload_child_photo()
    {
        $children = new Children();

        $children->uploadPhoto($_FILES, $_POST['child_id']);

        die;
    }

    public function saveConsent()
    {

        if (isset($_POST['terms'])) {
            update_user_meta(get_current_user_id(), 'gdpr_terms', $_POST['terms']);
        }

        if (isset($_POST['policy'])) {
            update_user_meta(get_current_user_id(), 'gdpr_policy', $_POST['policy']);
        }

        if (isset($_POST['booking_terms'])) {
            update_user_meta(get_current_user_id(), 'booking_terms', $_POST['booking_terms']);
        }

        if (isset($_POST['subscr'])) {
            // subscribe user to newsletter
            update_user_meta(get_current_user_id(), 'subscribe_to_news', $_POST['subscr']);
        }

        die;


    }

    public function getUserData()
    {

        $current_user = wp_get_current_user();

        $fp = fopen('../wp-content/uploads/personal_data_' . $current_user->user_login . '.csv', 'w');

        fputcsv($fp, [
            'Name, Surname',
            'Email',
            'Physical Address',
            'Home phone',
            'Mobile Phone',
            'Children Names',
            'Relationship',
            'Medical emergency details',
            'Website usage terms and conditions',
            'Bookings and Purchase terms and conditions',
            'Privacy policy',
            'Mailing list, offers and services'
        ]);

        $user_info = cm_get_parent_info($current_user->ID);

        $children = cm_get_children([
            'parent_id' => $current_user->ID
        ]);

        $childrenNames = [];
        $relations = [];
        $doctors = [];

        foreach ($children as $child) {

            $emergency = cm_get_child_emergency_info($child->id);

            if (!empty($emergency)) {
                $relations[] = $emergency->relationship;
                $doctor_phone = $emergency->doctor_phone ? '(' . $emergency->doctor_phone . ')' : '';
                $doctor_address = $emergency->doctor_address ? $emergency->doctor_address . ' ' . $emergency->doctor_postcode : '';
                $doctors[] = $emergency->doctor_name . ' ' . $doctor_phone . ' ' . $doctor_address;
            }

            $childrenNames[] = $child->name;
        }

        fputcsv($fp, [
            $current_user->user_firstname . ' ' . $current_user->user_lastname,
            $current_user->user_email,
            $user_info->address ? $user_info->address . ' ' . $user_info->postcode : '',
            $user_info->phone,
            $user_info->mobile,
            implode(', ', $childrenNames),
            implode('; ', $relations),
            implode('; ', $doctors),
            get_user_meta(get_current_user_id(), 'gdpr_terms', true) ? 'Yes' : 'No',
            get_user_meta(get_current_user_id(), 'booking_terms', true) ? 'Yes' : 'No',
            get_user_meta(get_current_user_id(), 'gdpr_policy', true) ? 'Yes' : 'No',
            get_user_meta(get_current_user_id(), 'subscribe_to_news', true) ? 'Yes' : 'No'
        ]);

        fclose($fp);

        echo json_encode(array('user_login' => $current_user->user_login));
        die;
    }

    public function deleteUserData()
    {

        $userId = get_current_user_id();

        $children = cm_get_children([
            'parent_id' => $userId
        ]);

        if (!empty($children)) { // deleting all users children

            foreach ($children as $child) {

                $childrenObj = new Children();

                $childrenObj->deleteChild($child->id); // delete child details

            }

        }

        $parent = new ParentInfo();

        $parent->deleteParentDetails($userId); // deleting details created by CM

        wp_delete_user( $userId );

        die;
    }


    public function getChildrenFieldsForCheckout()
    {
        $result = [
            'child_rows' => []
        ];

        if (is_user_logged_in()) {

            $children = cm_get_children([
                'parent_id'     => get_current_user_id(),
                'has_emergency' => true
            ]);

            if (!empty($children)) {


                foreach ($children as $child) {
                    $result['child_rows'][] = [
                        'child_id' => $child->id,
                        'name' => $child->name
                    ];
                }


            }

        }

        echo json_encode($result);
        die;
    }


    public function cm_flush_rewrite_rules()
    {
        flush_rewrite_rules();
    }

}
