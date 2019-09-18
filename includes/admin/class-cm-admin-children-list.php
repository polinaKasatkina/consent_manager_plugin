<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'CM_Admin_Children_list' ) ) {

    /**
     * CM_Admin_Children_list Class.
     */
    class CM_Admin_Children_list
    {

        static $limit = 20;

        /**
         * Settings page.
         *
         * Handles the display of the main woocommerce settings page in admin.
         */
        public static function output() {

            if ($_GET['child_id']) {

                $child_info = self::get_child_info($_GET['child_id']);
                $child_info->years = self::get_child_years($child_info->DOB);

                $parent_info = self::get_parent_info($child_info->user_id);
                $emergency_info = self::get_child_emergency_info($_GET['child_id']);

                include 'templates/child-info.php';
            } else {
                $children = self::get_emergency_list($_GET['paged']);

                $pagination = self::get_pagination();

                include 'templates/children-list.php';
            }
        }

        private function get_children($pageNum = 1)
        {
            global $wpdb;
            $result = [];
            $offset = self::$limit * ($pageNum - 1);

            $where = isset($_GET['s'])
                ? " WHERE name LIKE '%{$_GET['s']}%'"
                : '';

            $children = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cm_children_info" . $where);

            foreach ($children as $child) {

                $parent = self::get_parent_info($child->user_id);
                $doctor = self::get_doctor_info($child->id);

                $result[] = [
                    'child_id'      => $child->id,
                    'years'         => self::get_child_years($child->DOB),
                    'name'          => $child->name,
                    'parent_name'   => $parent->display_name,
                    'parent_phone'  => $parent->mobile->mobile,
                    'parent_id'     => $parent->ID,
                    'doctor_name'   => $doctor->name,
                    'doctor_phone'  => $doctor->phone,
                    'medical_agree' => $child->medical_agree,
                    'share_agree'   => $child->share_agree
                ];

            }



            return array_slice($result, $offset, self::$limit);
        }

        private function get_emergency_list($pageNum = 1)
        {


            global $wpdb;
            $result = [];
            $offset = self::$limit * ($pageNum - 1);

            $where = isset($_GET['s'])
                ? " WHERE name LIKE '%{$_GET['s']}%'"
                : '';

            $children = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cm_children_info" . $where);

            foreach ($children as $child) {

                $parent = self::get_parent_info($child->user_id);
                $doctor = self::get_doctor_info($child->id);

                $result[] = [
                    'child_id'      => $child->id,
                    'years'         => self::get_child_years($child->DOB),
                    'name'          => $child->name,
                    'parent_name'   => $parent->display_name,
                    'parent_phone'  => $parent->mobile->mobile,
                    'parent_id'     => $parent->ID,
                    'doctor_name'   => $doctor->name,
                    'doctor_phone'  => $doctor->phone,
                    'medical_agree' => $child->medical_agree,
                    'share_agree'   => $child->share_agree
                ];

            }

            $adults = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cm_parent_info WHERE medical_agree = 1");

            if ($adults) {

                foreach ($adults as $adult) {
                    $result[] = [
                        'child_id'      => $adult->user_id,
                        'years'         => cm_get_child_years($adult->dob),
                        'name'          => get_user_meta($adult->user_id, 'first_name', true) . ' ' . get_user_meta($adult->user_id, 'last_name', true),
                        'medical_agree' => $adult->medical_agree,
                        'share_agree'   => $adult->share_agree,
                        'adult'         => 1
                    ];
                }

            }

            if (isset($_GET['search']) && !empty($_GET['search'])) {
                foreach ($result as $key => $res) {
                    if (strpos($res['name'], $_GET['search']) !== false || strpos($res['parent_name'], $_GET['search']) !== false) {

                    } else {
                        unset($result[$key]);
                    }
                }
            }

            return array_slice($result, $offset, self::$limit);
        }

        private function get_child_years($dob)
        {
            $birthday_timestamp = strtotime($dob);
            $age = date('Y') - date('Y', $birthday_timestamp);
            if (date('md', $birthday_timestamp) > date('md')) {
                $age--;
            }
            return $age;
        }

        private function get_children_count()
        {
            global $wpdb;

            $where = isset($_GET['s'])
                ? " WHERE name LIKE '%{$_GET['s']}%'"
                : '';

            return $wpdb->query("SELECT * FROM {$wpdb->prefix}cm_children_info" . $where);
        }


        private function get_pagination()
        {
            $children_count = self::get_children_count();

            return [
                'current_page' => $_GET['paged'] ? : 1,
                'count'        => $children_count,
                'pages_count'  => ceil($children_count / self::$limit)
            ];
        }

        private function get_parent_info($user_id)
        {
            global $wpdb;

            $info = get_user_by('id', $user_id)->data;

            if ($info) {
                $info->mobile = $wpdb->get_row("SELECT mobile FROM {$wpdb->prefix}cm_parent_info WHERE user_id = {$user_id}");
            }

            return $info;
        }

        private function get_doctor_info($child_id)
        {
            global $wpdb;

            return $wpdb->get_row("SELECT d.name, d.phone, d.address FROM {$wpdb->prefix}cm_emergency e
                                      LEFT JOIN {$wpdb->prefix}cm_doctors d
                                      ON (e.doctor_id = d.id)
                                      WHERE e.child_id = {$child_id}");

        }

        private function get_child_info($child_id)
        {
            global $wpdb;

            return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cm_children_info WHERE id = {$child_id}");
        }

        private function get_child_emergency_info($child_id)
        {
            global $wpdb;

            $emergency = $wpdb->get_row("SELECT e.*,
                                      d.name as doctor_name, d.phone as doctor_phone, d.address as doctor_addr
                                      FROM {$wpdb->prefix}cm_emergency e
                                      LEFT JOIN {$wpdb->prefix}cm_doctors d
                                      ON (e.doctor_id = d.id)
                                      WHERE e.child_id = {$child_id}");

            return $emergency;
        }
    }
}
