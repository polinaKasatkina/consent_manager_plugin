<?php
/**
 * Setup menus in WP admin.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'CM_Admin_Menu' ) ) :

    /**
     * CM_Admin_Menus Class.
     */
    class CM_Admin_Menu {

        /**
         * Hook in tabs.
         */
        public function __construct() {
            // Add menus
            add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );

        }

        /**
         * Add menu items.
         */
        public function admin_menu() {
           // global $menu;
            global $submenu;

            add_menu_page( 'Settings', 'Consent Manager', 'administrator', 'cm_plugin', array($this, 'consent_settings'), '' );
            add_submenu_page( 'cm_plugin', 'Settings', 'Settings', 'administrator', 'consent_settings', array( $this, 'consent_settings' ) );

            add_submenu_page( 'cm_plugin', 'Emergency access', 'Emergency access', 'read', 'emergency_settings', array( $this, 'emergency_settings' ) );

            add_submenu_page( 'cm_plugin', 'Search', 'Search', 'read', 'children_list', array( $this, 'children_list' ) );
            add_submenu_page( 'cm_plugin', 'Reports', 'Reports', 'read', 'classes_list', array( $this, 'classes_list' ) );


            // Remove 'Parent's Consent' sub menu item
            unset($submenu['cm_plugin'][0]);

            add_action( 'admin_init', array($this, 'register_cm_organisation_details_settings') );
            add_action( 'admin_init', array($this, 'register_cm_gdpr_policy_settings') );
            add_action( 'admin_init', array($this, 'register_cm_gdpr_policy_urls') );
            add_action( 'admin_init', array($this, 'register_cm_consent_text_settings') );
        }


        public function register_cm_organisation_details_settings() {

            //register organisation details settings
            register_setting( 'cm-organisation-details-settings-group', 'cm_organisation_name' );
            register_setting( 'cm-organisation-details-settings-group', 'cm_organisation_address' );
            register_setting( 'cm-organisation-details-settings-group', 'cm_organisation_first_name' );
            register_setting( 'cm-organisation-details-settings-group', 'cm_organisation_last_name' );
            register_setting( 'cm-organisation-details-settings-group', 'cm_organisation_contact_phone' );
            register_setting( 'cm-organisation-details-settings-group', 'cm_organisation_email' );
        }

        public function register_cm_gdpr_policy_settings()
        {
            register_setting( 'cm-gdpr-policy-settings-group', 'cm_personal_data' );
            register_setting( 'cm-gdpr-policy-settings-group', 'cm_how_data_used' );
            register_setting( 'cm-gdpr-policy-settings-group', 'cm_publish_personal_data' );
            register_setting( 'cm-gdpr-policy-settings-group', 'cm_publish_how_data_used' );
        }

        public function register_cm_gdpr_policy_urls()
        {
            register_setting( 'cm-gdpr-policy-url-settings-group', 'cm_terms_and_conditions' );
            register_setting( 'cm-gdpr-policy-url-settings-group', 'cm_booking_terms' );
            register_setting( 'cm-gdpr-policy-url-settings-group', 'cm_privacy_policy' );
        }

        public function register_cm_consent_text_settings()
        {
            register_setting( 'cm-consent-text-settings-group', 'cm_medical_treatment_text' );
            register_setting( 'cm-consent-text-settings-group', 'cm_photo_consent_text' );

            register_setting( 'cm-consent-text-settings-group', 'cm_adult_medical_treatment_text' );
            register_setting( 'cm-consent-text-settings-group', 'cm_adult_photo_consent_text' );

            register_setting( 'cm-consent-text-settings-group', 'cm_checkout_terms_text' );
            register_setting( 'cm-consent-text-settings-group', 'cm_checkout_photo_consent_text' );
            register_setting( 'cm-consent-text-settings-group', 'cm_checkout_newsletter_text' );
            register_setting( 'cm-consent-text-settings-group', 'cm_show_on_checkout' );
        }

        /**
         * Init settings page
         */
        public function consent_settings()
        {

            CM_Organisation::output();

        }

        public function emergency_settings()
        {

            CM_Emergency::output();
        }

        /**
         * Init the search page (children list page)
         */
        public function children_list()
        {

            CM_Admin_Children_list::output();

        }

        /**
         * Init the reports page (classes list)
         */
        public function classes_list()
        {
            CM_Classes::output();
        }

    }

endif;

return new CM_Admin_Menu();
