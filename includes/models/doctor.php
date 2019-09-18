<?php

class Doctor {

    public function getDoctor($id)
    {
        global $wpdb;

        return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cm_doctors WHERE id = {$id}");
    }

    public static function getDoctorAjax($request)
    {
        global $wpdb;
        $data = [];

        $query = "SELECT * FROM {$wpdb->prefix}cm_doctors WHERE `name` LIKE '%{$request}%'";

        $doctors = $wpdb->get_results($query);

        foreach ($doctors as $doctor) {
            $suggestion = [
                'id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'doctor_address' => $doctor->address,
                'doctor_postcode' => $doctor->postcode,
                'doctor_phone' => $doctor->phone,
                'label' => "<b>{$doctor->name}</b>, phone: {$doctor->phone}",
                'value' => $doctor->name . ', ' . $doctor->phone
            ];

            $data[] = $suggestion;
        }

        return $data;
    }

    public function addDoctor($req)
    {
        global $wpdb;

        $data = [
            'name' => $req['doctor_name'],
            'address' => $req['doctor_address'],
            'postcode' => $req['doctor_postcode'],
            'phone'   => $req['doctor_phone']
        ];

        $wpdb->insert($wpdb->prefix . 'cm_doctors', $data);

        return $wpdb->insert_id;
    }

    public function updateDoctor($request)
    {

    }

    public function checkDoctor($request)
    {
        global $wpdb;

        $doctor_info = $this->getDoctor($request['doctor_id']);

        if ($doctor_info->address != $request['doctor_address']) {
            $wpdb->update(
                $wpdb->prefix . 'cm_doctors',
                [
                    'address'  => $request['doctor_address'],
                    'postcode' => $request['doctor_postcode']
                ],
                [
                    'id' => $doctor_info->id
                ]
            );
        }

        return $doctor_info->name != $request['doctor_name'] || $doctor_info->phone != $request['doctor_phone']
            ? $this->addDoctor($request)
            : $doctor_info->id;
    }
}
