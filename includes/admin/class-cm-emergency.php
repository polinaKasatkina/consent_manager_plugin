<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'CM_Emergency' ) ) {

    /**
     * CM_Admin_Children_emergency Class.
     */
    class CM_Emergency
    {

        static $limit = 20;
        static $classes_count = 0;

        /**
         * Settings page.
         *
         * Handles the display of the main woocommerce settings page in admin.
         */
        public static function output() {

            $users = get_users([
                'orderby'      => 'ID',
                'order'        => 'ASC',
            ]);
            include 'templates/emergency_settings.php';

        }


    }
}
