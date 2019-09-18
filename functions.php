<?php

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function cm_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
    if ( ! empty( $args ) && is_array( $args ) ) {
        extract( $args );
    }

    $located = cm_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $located ) ) {
        _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
        return;
    }

    // Allow 3rd party plugin filter template file from their plugin.
    $located = apply_filters( 'cm_get_template', $located, $template_name, $args, $template_path, $default_path );

    do_action( 'cm_before_template_part', $template_name, $template_path, $located, $args );

    include( $located );

    do_action( 'cm_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Like wc_get_template, but returns the HTML instead of outputting.
 * @see wc_get_template
 * @since 2.5.0
 * @param string $template_name
 */
function cm_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
    ob_start();
    cm_get_template( $template_name, $args, $template_path, $default_path );
    return ob_get_clean();
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function cm_locate_template( $template_name, $template_path = '', $default_path = '' ) {
    if ( ! $template_path ) {
        $template_path = 'consent-manager-for-woocommerce/';
    }

    if ( ! $default_path ) {
        $default_path = CM_PLUGIN_DIR . '../templates/';
    }

    // Look within passed path within the theme - this is priority.
    $template = locate_template(
        array(
            trailingslashit( $template_path ) . $template_name,
            $template_name
        )
    );

    // Get default template/
    if ( ! $template ) {
        $template = $default_path . $template_name;
    }

    // Return what we found.
    return apply_filters( 'user_family_details__locate_template', $template, $template_name, $template_path );
}

/**
 * @deprecated
 */
function user_family_details__locate_template( $template_name, $template_path = '', $default_path = '' ) {
    return cm_locate_template( $template_name, $template_path, $default_path );
}


add_filter( 'woocommerce_checkout_fields' , 'custom_override_default_checkout_fields' );

/**
 * Unset default fields for checkout page
 *
 * @param $fields
 * @return mixed
 */
function custom_override_default_checkout_fields( $fields ) {

    unset($fields['billing']['billing_company']);
//    unset($fields['billing']['billing_address_1']);
//    unset($fields['billing']['billing_address_2']);
//    unset($fields['billing']['billing_city']);
//    unset($fields['billing']['billing_postcode']);
//    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
//    unset($fields['order']['order_comments']);
//    unset($fields['billing']['billing_email']);
    unset($fields['account']['account_username']);
//    unset($fields['account']['account_password']);
//    unset($fields['account']['account_password-2']);

//    $address_fields['billing_email']['clear'] = false;
    $fields['billing']['billing_email']['class'] = array('form-row-first');

    $fields['order']	= array(
        'order_comments' => array(
            'type' => 'textarea',
            'class' => array('notes'),
            'label' => __( 'Booking Notes', 'woocommerce' ),
            'placeholder' => _x('Anything else we need to know before you arrive for the class?', 'placeholder', 'woocommerce')
        )
    );

    return $fields;
}

add_action('woocommerce_after_checkout_billing_form', 'my_custon_mobile_field', 0);

function my_custon_mobile_field($checkout)
{
    woocommerce_form_field( 'mobile', array(
        'type'         => 'text',
        'class'        => array('form-row-last', 'validate-phone'),
        'id'           => 'mobile',
        'label'        => __('Mobile'),
        'required'     => true,
        'placeholder'  => '0XXXX-XXX-XXX',
        'autocomplete' => 'tel',
        'default'      => is_user_logged_in() ? getPhone() : ''
    ), $checkout->get_value( 'mobile' ));
}

add_action('woocommerce_checkout_process', 'mobile_checkout_field_process');

function mobile_checkout_field_process() {

    if ( ! $_POST['mobile'] )
        wc_add_notice( __( 'Mobile is a required field.' ), 'error' );


    if ($_POST['add_child_years']) {
        foreach ( $_POST['add_child_years'] as $dob ) {
            if (!CM_Validate::checkDateOfBirth($dob))
                wc_add_notice( 'Field Date of Birth must be not more than ' . date('d.m.Y'), 'error' );

            if (!CM_Validate::checkDateOfBirthNoMoreThat18Years($dob))
                wc_add_notice( 'Field Date of Birth must be not more than ' . date('d.m.Y', strtotime("-18 years", time())), 'error' );
        }
    }

}

function getPhone()
{
    global $wpdb;

    $user_id = get_current_user_id();

    return $wpdb->get_row("SELECT mobile FROM {$wpdb->prefix}cm_parent_info WHERE user_id = {$user_id}")->mobile;

}

add_action('woocommerce_after_checkout_billing_form', 'my_custom_child_field', 1);

function my_custom_child_field($checkout)
{
    $personsCount = 1;
    $postTags = [];

    // TODO if there are 2 classes with different persons count
    foreach (WC()->cart->get_cart() as $item) {

        $tags = wp_get_post_terms($item['product_id'], 'product_tag');

        foreach ($tags as $tag) {

            $postTags[] = $tag->name;
        }

        $personsCount = $item['booking']['Persons'] ? : $item['quantity'];
    }

    if (array_search('Children', $postTags) !== false) {
        echo '<div class="adults_and_children">';
        add_children_counter($personsCount, $checkout);
        echo '</div>';

//        echo '<button id="next_step">Next</button>';

        echo '<div class="children_info"></div>'; }

    if (array_search('Adults', $postTags) !== false) {

        echo '<hr/>';

        echo '<div class="adults_counter_input">';
        add_adults_counter($personsCount, $checkout);
        echo '</div>';

//        echo '<button id="adult_next_step">Next</button>';

        echo '<div class="adults_info"></div>';
    }

}

function draw_children_select($checkout, $options, $count)
{
    for ($i = 0; $i < $count; $i++) {
        echo '<div class="child-row">';
        woocommerce_form_field( 'choose_child[]', array(
            'type'        => 'select',
            'class'       => array('my-field-class form-row-wide'),
            'label'       => __('Choose child (only children with completed emergency and consent details are shown)'),
            'options'     => $options,
            'required'    => true,
        ), $checkout->get_value( 'choose_child' ));
        echo '</div>';
    }
}

function draw_add_child_form($checkout, $count)
{
    for ($i = 0; $i < $count; $i++) {
        echo '<div class="child-row">';
        woocommerce_form_field( 'add_child_name[]', array(
            'type'        => 'text',
            'class'       => array('my-field-class', 'form-row-wide'),
            'label'       => __('Add child'),
            'required'    => true,
        ), $checkout->get_value( 'choose_child' ));

        woocommerce_form_field( 'add_child_years[]', array(
            'type'        => 'text',
            'id'          => 'datepicker_' . $i,
            'input_class' => array('my-field-class', 'form-row-wide', 'datepicker'),
            'placeholder' => 'DD.MM.YYYY',
            'label'       => __('Date of birth'),
            'required'    => true,
        ), $checkout->get_value( 'choose_child' ));
        echo '</div>';
    }
}

function add_children_counter($count, $checkout) {
    echo '<p class="form-row my-field-class form-row-wide validate-required" id="children_count_field">
    <label>Number of children attending</label>';
    echo '<div class="input-group number-spinner">
				<span class="input-group-btn">
					<a class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></a>
				</span>';
    echo '<input type="text" name="children_count" min="0" max="' . $count . '" class="input-text form-control text-center" value="0" />';


//    woocommerce_form_field( 'children_count', array(
//        'type'        => 'number',
//        'max'         => $count,
//        'class'       => array('my-field-class form-row-wide'),
//        'label'       => __('Number of children attending'),
//        'required'    => true,
//    ), $checkout->get_value( 'children_count' ));

    echo '<span class="input-group-btn">
					<a class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></a>
				</span>
			</div></p>';
}

function add_adults_counter($count, $checkout) {
//    woocommerce_form_field( 'adults_count', array(
//        'type'        => 'number',
//        'max'         => $count,
//        'class'       => array('my-field-class form-row-wide'),
//        'label'       => __('Number of adults (excluding you)'),
//        'required'    => true,
//    ), $checkout->get_value( 'adults_count' ));


    echo '<p class="form-row my-field-class form-row-wide validate-required" id="adults_count_field">
    <label>Number of adults (excluding you)</label>';
    echo '<div class="input-group number-spinner">
				<span class="input-group-btn">
					<a class="btn btn-default" data-dir="dwn"><span class="glyphicon glyphicon-minus"></span></a>
				</span>';
    echo '<input type="text" id="adults_count" name="adults_count" min="0" max="' . $count . '" class="input-text form-control text-center" value="0" />';


//    woocommerce_form_field( 'children_count', array(
//        'type'        => 'number',
//        'max'         => $count,
//        'class'       => array('my-field-class form-row-wide'),
//        'label'       => __('Number of children attending'),
//        'required'    => true,
//    ), $checkout->get_value( 'children_count' ));

    echo '<span class="input-group-btn">
					<a class="btn btn-default" data-dir="up"><span class="glyphicon glyphicon-plus"></span></a>
				</span>
			</div></p>';

}

/**
 * Update the order meta with field value
 **/
add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_child_field_update_order_meta');

function my_custom_checkout_child_field_update_order_meta( $order_id )
{

    if ($_POST['mobile']) {

        update_parent_mobile($_POST);
    }

    if ($_POST['choose_child']) {

        update_child_order_info($_POST['choose_child'], $order_id);

    }

    if ($_POST['add_child_name']) {
        insert_child_and_update_order($_POST, $order_id);

    }

//    if ($_POST['choose_adult']) {
//        update_adult_order_info($_POST['choose_adult'], $order_id);
//    }

    if ($_POST['add_adult_f_name']) {
        insert_adult_and_update_order($_POST, $order_id);
    }

    $parent = new ParentInfo();
    $parent->updateParentMobile(['mobile' => $_POST['billing_phone']]);

    if ($_POST['terms']) {
        update_user_meta(get_current_user_id(), 'booking_terms', $_POST['terms']);
    }

    if ($_POST['social']) {

        global $wpdb;

        $wpdb->update(
            $wpdb->prefix . 'cm_parent_info',
            [
                'share_agree' => 1
            ],
            [
                'user_id' => get_current_user_id()
            ]
        );

    }

    if ($_POST['subscribe_to_news']) {

        if (!get_user_meta(get_current_user_id(), 'subscribe_to_news', true)) {
            update_user_meta(get_current_user_id(), 'subscribe_to_news', [0 => 'email']);
        }

    }

}

function update_child_to_order($child_id, $order_id)
{
    global $wpdb;

    $data = [
        'child_id' => $child_id,
        'order_id' => $order_id
    ];

    $wpdb->insert(
        $wpdb->prefix . 'cm_child_to_order',
        $data
    );

}


//add_action('woocommerce_order_details_after_customer_details', 'custom_child_info', 20);
add_action('woocommerce_order_details_after_order_table', 'custom_child_info', 20);

function custom_child_info($order)
{
    global $wpdb;

    $children = $wpdb->get_results("SELECT ci.name, ci.DOB FROM {$wpdb->prefix}cm_child_to_order co
                                    LEFT JOIN {$wpdb->prefix}cm_children_info ci
                                    ON (co.child_id = ci.id)
                                    WHERE order_id = {$order->id}");

    echo '<table><tr><td colspan="2"><strong>Children Info</strong></td></tr>';

//    $childrenObj = new ChildrenFunctions();

    foreach($children as $child) {
        echo '<tr>
			<td>' . $child->name . '</td>
			<td>' . cm_get_child_years($child->DOB) . '</td>
		</tr>';
    }

    echo '</table>';
}

add_action('woocommerce_order_details_after_order_table', 'custom_adult_info', 21);

function custom_adult_info($order)
{
    global $wpdb;

    $adults = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cm_adult_to_order co
                                    WHERE order_id = {$order->id}");

    echo '<table><tr><td colspan="2"><strong>Adults Info</strong></td></tr>';


    foreach($adults as $adult) {
        echo '<tr>
			<td>' . $adult->first_name . ' ' . $adult->last_name . '</td>
			<td>' . $adult->email . '</td>
		</tr>';
    }

    echo '</table>';
}


add_action('add_checkbox', 'add_checkbox_for_address_duplicate');

function add_checkbox_for_address_duplicate()
{

    $user_id = get_current_user_id();
    $parent = new ParentInfo();
    $address = $parent->getParentInfo($user_id)->address;

    $checked = $parent->get_billing_address()['address'] == $address ? 'checked' : '';

    // TODO remove style to styles.css
    echo '<div id="billing_checkbox" style="float: right; margin-top: 10px;">
            <label>
              <input type="checkbox" name="same_to_mydetails" class="same_to_mydetails" ' .
        $checked . '/>
                Use this address in My Details
            </label>
          </div>';
}


add_action('woocommerce_customer_save_address', 'check_if_checkbox_is_checked', 10, 2);

function check_if_checkbox_is_checked($user_id)
{

    if ($_POST['same_to_mydetails']) {

        $address = '';
        $parent = new ParentInfo();

        if (!empty($_POST['billing_address_2'])) $address .= $_POST['billing_address_2'] . ' ';
        if (!empty($_POST['billing_address_1'])) $address .= $_POST['billing_address_1'] . ' ';
        if (!empty($_POST['billing_city'])) $address .= $_POST['billing_city'] . ' ';
        if (!empty($_POST['billing_country'])) $address .= $_POST['billing_country'] . ' ';

        $parent->updateParent([
            "address"  => $address,
            "postcode" => $_POST['billing_postcode'],
            "user_id"  => $user_id
        ]);

    }
}

function check_emergency_login() {

    $emergency = $_SERVER["HTTP_ORIGIN"] . '/emergency/';

    if ($_SERVER['HTTP_REFERER'] == $emergency) {
        add_emergency_log();
    }
}
add_action('wp_login', 'check_emergency_login', 10, 2);

function add_emergency_log()
{
    global $wpdb;

    $data = [
        'user_id' => get_current_user_id(),
        'date_added' => date('Y-m-d H:i:s')
    ];

    $wpdb->insert(
        $wpdb->prefix . 'cm_emergency_log',
        $data
    );
}

function has_emergency_access($user_id)
{
    return get_user_meta($user_id, 'emergency_access', true);
}


add_action( 'init', 'cm_add_shortcodes' );

function cm_add_shortcodes() {

    add_shortcode( 'my-login-form', 'my_login_form_shortcode' );
}


function get_emergency_list($pageNum = 1, $count = false)
{
    global $wpdb;
    $result = [];
    $limit = 20;
    $offset = $limit * ($pageNum - 1);

    $children = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cm_children_info");

    foreach ($children as $child) {

        $parent = get_parent_info($child->user_id);
        $doctor = get_emergency_info($child->id);

        $result[] = [
            'child_id'      => $child->id,
            'years'         => cm_get_child_years($child->DOB),
            'name'          => $child->name,
            'parent_name'   => $parent['first_name'][0] . ' ' . $parent['last_name'][0],
            'parent_phone'  => $parent['mobile']->mobile,
            'parent_id'     => $child->user_id,
            'doctor_name'   => $doctor->name,
            'doctor_phone'  => $doctor->phone,
            'medical_agree' => $child->medical_agree,
            'share_agree'   => $child->share_agree,
            'adult'         => 0
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

    if ($count) {
        return count($result);
    }

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        foreach ($result as $key => $res) {
            if (strpos($res['name'], $_GET['search']) !== false || strpos($res['parent_name'], $_GET['search']) !== false) {

            } else {
                unset($result[$key]);
            }
        }
    }

    return array_slice($result, $offset, $limit);
}

function get_pagination()
{
    $children_count = get_emergency_list(1, true);

    $page = get_query_var('paged') ? get_query_var('paged') : 1;

    return [
        'current_page' => $page,
        'count'        => $children_count,
        'pages_count'  => ceil($children_count / 20)
    ];
}

function get_parent_info($user_id)
{
    global $wpdb;

    $info = get_user_meta($user_id); //get_user_by('id', $user_id)->data;

    if ($info) {
        $info['mobile'] = $wpdb->get_row("SELECT mobile FROM {$wpdb->prefix}cm_parent_info WHERE user_id = {$user_id}");
    }

    return $info;
}
function get_emergency_info($child_id)
{
    global $wpdb;

    return $wpdb->get_row("SELECT d.name, d.phone, d.address FROM {$wpdb->prefix}cm_emergency e
                                      LEFT JOIN {$wpdb->prefix}cm_doctors d
                                      ON (e.doctor_id = d.id)
                                      WHERE e.child_id = {$child_id}");

}


add_action('woocommerce_review_order_before_submit', 'cm_terms_and_policies_before_submit', 1);

function cm_terms_and_policies_before_submit()
{

    if (get_option('cm_show_on_checkout'))  { ?>
        <ul class="checkout_consent">
            <li>
                <label>
                    <input type="checkbox" name="terms" <?=get_user_meta(get_current_user_id(), 'booking_terms', true) ? 'checked' : ''?> />
                    <?= get_option('cm_checkout_terms_text') ? : 'I\'ve read and accept the <a href="' . get_option('cm_booking_terms') . '">bookings terms & conditions</a>' ?>
                </label>
            </li>
            <?php
            $parent = new ParentInfo();
            $parent_info = $parent->getParentInfo(get_current_user_id());
            ?>
            <li>
                <label>
                    <input type="checkbox" name="social" <?=$parent_info && $parent_info->share_agree ? 'checked' : ''?> />
                    <?= get_option('cm_checkout_photo_consent_text') ? : 'I give consent for my photos to be used on social media and other marketing' ?>
                </label>
            </li>
            <li>
                <label>
                    <input type="checkbox" name="subscribe_to_news" <?=get_user_meta(get_current_user_id(), 'subscribe_to_news', true) ? 'checked' : ''?> />
                    <?= get_option('cm_checkout_newsletter_text') ? : 'Add me to your mailing list' ?>
                </label>
            </li>
            <li><a href="<?=get_option('cm_privacy_policy')?>">Data Privacy Policy</a></li>
        </ul>
    <?php
    }
        ?>

<?php
}
