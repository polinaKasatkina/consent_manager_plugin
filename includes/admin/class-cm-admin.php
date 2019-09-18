<?php
/**
 * Consent Manager Admin
 *
 * @class    CM_Admin
 * @author   Arqino Digital Limited
 * @category Admin
 * @version  2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WC_Admin class.
 */
class CM_Admin {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'includes' ) );
        add_action( 'init', array( $this, 'init_script' ) );
        add_action( 'admin_init', array( $this, 'buffer' ), 1 );
    }

    /**
     * Output buffering allows admin screens to make redirects later on.
     */
    public function buffer() {
        ob_start();
    }

    /**
     * Include any classes we need within admin.
     */
    public function includes() {

        include_once('cm-admin-functions.php' );
        include_once('class-cm-admin-menu.php' );
        include_once('class-cm-admin-children-list.php');
        include_once('class-cm-classes.php');
        include_once('class-cm-organisation.php');
        include_once('class-cm-emergency.php');
    }

    public function init_script()
    {

        wp_register_style( 'cm_admin_style', dirname(dirname(plugin_dir_url(__FILE__))) . '/assets/css/admin/style1.css');
        wp_enqueue_style( 'cm_admin_style' );

        wp_register_style( 'jquery_modal', plugin_dir_url(__FILE__) . '../../assets/css/jquery.modal.min.css');
        wp_enqueue_style( 'jquery_modal' );

        wp_register_script( 'jquery_modal', plugin_dir_url(__FILE__) . '../../assets/js/jquery.modal.min.js', array('jquery'),null,true);
        wp_enqueue_script( 'jquery_modal' );

        wp_register_script( 'cm-admin-script', dirname(dirname(plugin_dir_url(__FILE__))) . '/assets/js/admin/script3.js', array('jquery','jquery-ui-datepicker'),null,true);
        $translation_array = array(
            'siteurl' => get_option('siteurl')
        );

        wp_localize_script('cm-admin-script', 'CM_variables', $translation_array);
        wp_enqueue_script( 'cm-admin-script' );

    }
    
}

return new CM_Admin();
