<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'CM_Classes' ) ) {

    /**
     * CM_Admin_Children_list Class.
     */
    class CM_Classes
    {

        static $limit = 20;
        static $classes_count = 0;

        /**
         * Settings page.
         *
         * Handles the display of the main woocommerce settings page in admin.
         */
        public static function output() {

            if ($_GET['item_id']) {

                $class = self::get_class_info($_GET['item_id']);
                $children = self::get_children_in_class($class->order_id);
                include "templates/class-info.php";

            } else {

                $classes = self::get_classes_list($_GET['paged']);
                $pagination = self::get_pagination();
                include 'templates/classes-list.php';

            }

        }

        private function get_classes_list($pageNum = 1)
        {
            global $wpdb;
            $items = [];
            $offset = self::$limit * ($pageNum - 1);

            $orders = $wpdb->get_results("SELECT COUNT(child_id) as c_count, order_id FROM {$wpdb->prefix}cm_child_to_order GROUP BY order_id");

            foreach ($orders as $order) {

                if (!get_post( $order->order_id )) continue;

                $orderObj = new WC_Order( $order->order_id );

                foreach ($orderObj->get_items() as $key =>  $item) {

                    if (isset($_GET['date']) && !empty($_GET['date'])) {
                        $orderDate = strtotime($item['item_meta']['Booking Date'][0]);
                        $selectedDate = strtotime($_GET['date']);

                        if ($orderDate != $selectedDate) continue;
                    }

                    //get_tribe_event_ID_from_product
                    $items[] = [
                        'id'       => $item['item_meta']['_product_id'][0],
                        'order_item_id' => $key,
                        'name'     => $item['name'],
                        'date'     => $item['Booking Date'],
                        'time'     => $item['Booking Time'],
                        'duration' => $item['Duration'],
                        'children_count' => $order->c_count, //self::get_children_count($order->order_id)->number_of_children,
                        'order_id' => $order->order_id,
                        'ts'       => strtotime($item['Booking Date'] . ' ' . $item['Booking Time'])
                    ];
                }
            }


            if (isset($_GET['sort'])) {
                usort($items, function ($a, $b) {
                    switch ($_GET['sort']) {
                        case 'desc' :
                            return ($a['children_count'] >= $b['children_count'])
                                ? -1
                                : 1;
                            break;
                        case 'asc' :
                            return ($a['children_count'] <= $b['children_count'])
                                ? -1
                                : 1;
                            break;
                    }
                });
            } else {
                usort($items, function ($a, $b) {
                    return ($a['ts'] >= $b['ts'])
                        ? -1
                        : 1;
                });
            }

            self::$classes_count = count($items);

            return array_slice($items, $offset, self::$limit);
        }

        private function get_pagination()
        {

            return [
                'current_page' => $_GET['paged'] ? : 1,
                'count'        => self::$classes_count,
                'pages_count'  => ceil(self::$classes_count / self::$limit)
            ];
        }

        private function get_class_info($item_id)
        {
            global $wpdb;

            $get_items_sql  = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_id = %d ", $item_id );
            $item_info     = $wpdb->get_row( $get_items_sql );

            $orderObj = new WC_Order();
            $item_info->item_meta = $orderObj->get_item_meta($item_id);

            return $item_info;
        }

        private function get_children_in_class($order_id)
        {
            global $wpdb;

            return $wpdb->get_results("SELECT ci.name, ci.DOB, ci.id FROM {$wpdb->prefix}cm_children_info ci
                                      LEFT JOIN {$wpdb->prefix}cm_child_to_order co
                                      ON (co.child_id = ci.id)
                                      WHERE co.order_id = {$order_id}");
        }
    }
}
