<?php

class Emergency {

    public function getEmergency($child_id)
    {
        global $wpdb;

        $query = "SELECT ufde.*, ufdd.id as doctor_id, ufdd.name as doctor_name, ufdd.address as doctor_address, ufdd.postcode as doctor_postcode, ufdd.phone as doctor_phone
             FROM {$wpdb->prefix}cm_emergency ufde
             LEFT JOIN {$wpdb->prefix}cm_doctors ufdd
             ON (ufde.doctor_id = ufdd.id)
             WHERE ufde.child_id = {$child_id}";

        return $wpdb->get_results($query)[0];
    }


    public function isEmergency($child_id)
    {
        global $wpdb;

        return $wpdb->query("SELECT * from {$wpdb->prefix}cm_emergency where child_id = {$child_id}");
    }

    public function addEmergency($request)
    {
        global $wpdb;

        $doctorObj = new Doctor();

        $doctor_id = !$request['doctor_id'] ? $doctorObj->addDoctor($request) : $doctorObj->checkDoctor($request);

        $data = [
            'child_id' => $request['child_id'] ? $request['child_id'] : 0,
            'doctor_id' => $doctor_id,
            'name' => $request['name_emergency'],
            'relationship' => $request['relationship'],
            'phone' => $request['phone'],
            'mobile' => $request['mobile'],
            'collect_person' => $request['collect_person'] ? : '',
            'injections' => $request['injection'] ? 1 : 0,
            'allergies' => $request['allergies'] ? 1 : 0,
            'allergies_details' => $request['allergies_details'],
            'medications' => $request['medications'] ? 1 : 0,
            'dosage' => $request['dosage'] ? : '',
        ];



        $wpdb->insert(
            $wpdb->prefix . 'cm_emergency',
            $data
        );

    }

    public function updateEmergency($request)
    {
        global $wpdb;

        $doctorObj = new Doctor();


        $doctor_id = !$request['doctor_id'] ? $doctorObj->addDoctor($request) : $doctorObj->checkDoctor($request);


        $data = [
            'doctor_id' => $doctor_id,
            'name' => $request['name_emergency'],
            'relationship' => $request['relationship'],
            'phone' => $request['phone'],
            'mobile' => $request['mobile'],
            'collect_person' => $request['collect_person'],
            'injections' => $request['injection'] ? 1 : 0,
            'allergies' => $request['allergies'] ? 1 : 0,
            'allergies_details' => $request['allergies_details'],
            'medications' => $request['medications'] ? 1 : 0,
            'dosage' => $request['dosage']
        ];

        $wpdb->update(
            $wpdb->prefix . 'cm_emergency',
            $data,
            [
                'child_id' => $request['child_id']
            ]
        );

    }



    public function deleteEmergencyInfo($child_id)
    {
        global $wpdb;

        $wpdb->delete(
            $wpdb->prefix . 'cm_emergency',
            [ 'child_id' => $child_id ]
        );

    }

}
