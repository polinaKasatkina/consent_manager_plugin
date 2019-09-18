<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'CM_Organisation' ) ) {

    /**
     * CM Admin Settings page.
     */
    class CM_Organisation
    {

        static $limit = 20;
        static $classes_count = 0;

        /**
         * Settings page.
         *
         * Handles the display of the main consent manager settings page in admin.
         */
        public static function output() {

            include 'templates/settings.php';

        }


    }
}
