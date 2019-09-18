<?php

class ParentInfo {

    public function getParentQuery($id)
    {
        global $wpdb;

        return $wpdb->query("SELECT * FROM {$wpdb->prefix}cm_parent_info WHERE user_id = {$id}");

    }

    public function getParentInfo($id)
    {
        global $wpdb;

        return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cm_parent_info WHERE user_id = {$id}");

    }

    public function addParent($request)
    {
        global $wpdb;

        $data = [
            'user_id' => $request['user_id'],
            'dob' => $request['dob'] ? date('Y-m-d', strtotime($request['dob'])) : '',
            'gender' => $request['gender'] ? : '',
            'address' => $request['address'] ? : '',
            'postcode' => $request['postcode'] ? : '',
            'phone' => $request['phone'] ? : '',
            'mobile' => $request['mobile'] ? str_replace(['-', ' '], '', $request['mobile']) : '',
            'medical_agree' => $request['medical_agree'] ? 1 : 0,
            'share_agree' => $request['sharing_agree'] ? 1 : 0
        ];

        if (isset($request['subscription']))
            $data['subscription'] = 1;

        $wpdb->insert(
            $wpdb->prefix . 'cm_parent_info',
            $data
        );

        if ($_FILES) {

            $this->uploadPhoto($_FILES);
        }
    }

    public function updateParent($request)
    {
        global $wpdb;

        $data = [
            'dob' => $request['dob'] ? date('Y-m-d', strtotime($request['dob'])) : '',
            'gender' => $request['gender'] ? : '',
            'address' => $request['address'] ? : '',
            'postcode' => $request['postcode'] ? : '',
            'phone' => $request['phone'] ? : '',
            'mobile' => $request['mobile'] ? str_replace(['-', ' '], '', $request['mobile']) : '',
            'medical_agree' => $request['medical_agree'] ? 1 : 0,
            'share_agree' => $request['sharing_agree'] ? 1 : 0
        ];

        if (isset($request['subscription']))
            $data['subscription'] = 1;

        $wpdb->update(
            $wpdb->prefix . 'cm_parent_info',
            $data,
            ['user_id' => $request['user_id']]
        );

        if ($_FILES) {

            $this->uploadPhoto($_FILES);
        }
    }

    public function deleteParentDetails($id)
    {
        global $wpdb;

        $wpdb->delete(
            $wpdb->prefix . 'cm_parent_info',
            [ 'user_id' => $id ]
        );
    }

    public function updateParentMobile($request) {

        global $wpdb;

        $data = [
            'mobile' => $request['mobile'] ? str_replace(['-', ' '], '', $request['mobile']) : ''
        ];

        $wpdb->update(
            $wpdb->prefix . 'cm_parent_info',
            $data,
            ['user_id' => $request['user_id']]
        );
    }

    public function updateParentMeta($request)
    {

        $name = explode(' ', $request['name']);

        $fname = $name[0] ? $name[0] : '';
        $lname = $name[1] ? $name[1] : '';

        update_user_meta($request['user_id'], 'first_name', $fname);
        update_user_meta($request['user_id'], 'billing_first_name', $fname);
        update_user_meta($request['user_id'], 'last_name', $lname);
        update_user_meta($request['user_id'], 'billing_last_name', $lname);

        update_user_meta($request['user_id'], 'billing_phone', $request['adult_mobile']);

        update_user_meta($request['user_id'], 'account_email', $request['adult_email']);
        update_user_meta($request['user_id'], 'billing_email', $request['adult_email']);


        wp_update_user([
            'ID' => $request['user_id'],
            'user_email' => $request['adult_email']
        ]);

    }

    public function updateParentEmergencyMeta($request)
    {
        update_user_meta($request['user_id'], 'emergency_contact_name', $request['name_emergency']);
        update_user_meta($request['user_id'], 'emergency_relationship', $request['relationship']);
        update_user_meta($request['user_id'], 'emergency_phone', $request['phone']);
        update_user_meta($request['user_id'], 'emergency_mobile', $request['mobile']);
        update_user_meta($request['user_id'], 'emergency_medical_notes', $request['medical_notes']);
    }

    public function get_billing_address()
    {
        global $wpdb;

        $user_id = get_current_user_id();

        $address = '';

        $house =  $wpdb->get_row("SELECT meta_value
                                  FROM {$wpdb->prefix}usermeta
                                  WHERE user_id = {$user_id}
                                  AND meta_key = 'billing_address_2'")->meta_value;
        if (!empty($house))
            $address .= $house . ' ';

        $street = $wpdb->get_row("SELECT meta_value
                                  FROM {$wpdb->prefix}usermeta
                                  WHERE user_id = {$user_id}
                                  AND meta_key = 'billing_address_1'")->meta_value;

        if (!empty($street))
            $address .= $street . ' ';

        $city = $wpdb->get_row("SELECT meta_value
                                  FROM {$wpdb->prefix}usermeta
                                  WHERE user_id = {$user_id}
                                  AND meta_key = 'billing_city'")->meta_value;

        if (!empty($city))
            $address .= $city . ' ';

        $country = $wpdb->get_row("SELECT meta_value
                                  FROM {$wpdb->prefix}usermeta
                                  WHERE user_id = {$user_id}
                                  AND meta_key = 'billing_country'")->meta_value;

        if (!empty($country))
            $address .= $country . ' ';

        $postcode = $wpdb->get_row("SELECT meta_value
                                  FROM {$wpdb->prefix}usermeta
                                  WHERE user_id = {$user_id}
                                  AND meta_key = 'billing_postcode'")->meta_value;


        $result = [
            'address' => $address,
            'postcode' => $postcode
        ];

        return $result;
    }

    public function uploadPhoto($file)
    {
        $user_id = get_current_user_id();

        // If there is no photo to be upload skip this step
        if (empty($file["adult_photo"]["name"])) return;

        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/uploads/adult_photo/";
        if (!is_dir($target_dir)) mkdir( $target_dir, 0755, true );
        $target_file = $target_dir . $user_id . '.jpg';
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Check file size
//        if ($file["child_photo"]["size"] > 500000) {
//            wc_add_notice( __( 'Sorry, your file is too large.', 'woocommerce' ) );
//            $uploadOk = 0;
//        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            wc_add_notice( __( 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.', 'woocommerce' ) );

            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            wc_add_notice( __( 'Sorry, your file was not uploaded.', 'woocommerce' ) );

            // if everything is ok, try to upload file
        } else {

//            $this->createPreviewImage($file["child_photo"]["tmp_name"], $target_file, 0, 250, 250);
            if (move_uploaded_file($file["adult_photo"]["tmp_name"], $target_file)) {

            } else {
                wc_add_notice( __( 'Sorry, there was an error uploading your file.', 'woocommerce' ) );

            }
        }
    }


}
