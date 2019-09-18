<div class="row">
    <div class="form-group col-lg-12">
        <label>Contact Name</label>
        <input type="text" name="name_emergency" class="blue-input required" value="<?= $child_emergency_info ? $child_emergency_info->name : '' ?>"/>
    </div>
</div>


<div class="row">
    <div class="form-group col-lg-12">
        <label>Relationship</label>
        <select name="relationship" class="pink-input" required>
            <?php if (!isset($child_emergency_info)) { ?>
                <option selected value="">How are you related</option>
                <?php
            } ?>
            <?php // TODO fix this
            $relationships = ['mother', 'father', 'grandmother', 'grandfather', 'uncle', 'aunt', 'nanny'];
            foreach ($relationships as $relationship) { ?>
                <option value="<?= $relationship ?>"
                    <?= $child_emergency_info->relationship == $relationship
                        ? 'selected'
                        : '' ?> >
                    <?= ucfirst($relationship) ?>
                </option>
                <?php
            } ?>
        </select>
    </div>
</div>


<div class="row">
    <div class="form-group col-lg-6">
        <label>Home Telephone</label>
        <input type="text" name="phone" class="pink-input required" id="phone" value="<?= $child_emergency_info ? $child_emergency_info->phone : '' ?>"
               placeholder="0XX XXXX XXXX"/>
    </div>
    <div class="form-group col-lg-6">
        <label>Mobile</label>
        <input type="text" name="mobile" id="mobile" class="pink-input required" value="<?= $child_emergency_info ? $child_emergency_info->mobile : '' ?>"
               placeholder="0XXXX-XXX-XXX"/>
    </div>

</div>

<div class="row">
    <div class="form-group col-lg-6">
        <label>Child will be collected by</label>
        <input type="text" name="collect_person" class="blue-input" value="<?= $child_emergency_info ? $child_emergency_info->collect_person : '' ?>"/>
    </div>
    <input type="hidden" name="doctor_id"
           value="<?= $child_emergency_info && $child_emergency_info->doctor_id ? $child_emergency_info->doctor_id : '' ?>"/>
    <div class="form-group col-lg-6">
        <label>Doctor's name</label>
        <input type="text" name="doctor_name" class="blue-input" id="doctor_name"
               value="<?= $child_emergency_info ? $child_emergency_info->doctor_name : '' ?>"/>

        <div id="doctorSearchSuggestions"></div>
    </div>
</div>


<div class="row">
    <div class="form-group col-lg-6 address-group">
        <label>Doctor's address</label>
        <input name="doctor_address" class="doctor-address blue-input" type="text"
               value="<?= $child_emergency_info ? $child_emergency_info->doctor_address : '' ?>"
               placeholder="Type in house/apartment number and street address here"/>
        <span class="address-loader"></span>
        <div class="addressSearchSuggestions-doctor"></div>

        <input type="text" name="doctor_postcode" class="doctor_postcode blue-input top-border-none"
               value="<?= $child_emergency_info ? $child_emergency_info->doctor_postcode : '' ?>"
               placeholder="Enter postcode"/>

    </div>

    <div class="form-group col-lg-6">
        <label>Doctor's phone</label>
        <input type="text" name="doctor_phone" class="blue-input"
               value="<?= $child_emergency_info ? $child_emergency_info->doctor_phone : '' ?>"
               placeholder="0XXXX-XXX-XXX"/>
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-12">
        <label class="checkbox">
            Has your child received a tetanus injection in the last 5 years?
            <input type="checkbox" name="injection"
                <?= $child_emergency_info && $child_emergency_info->injections ? 'checked' : '' ?>/>
        </label>
    </div>
</div>

<div class="row">
    <div class="form-group col-lg-12">
        <div>
            <label class="checkbox">
                Does your child have any medical problems, allergies or disability?
                <input type="checkbox" name="allergies"
                    <?= $child_emergency_info && $child_emergency_info->allergies ? 'checked' : '' ?>/>
            </label>
        </div>

        <div>
            <label>Please specify</label>
            <input type="text" name="allergies_details" <?=$child_emergency_info->allergies ? '' : 'disabled'?>
                   class="pink-input <?=$child_emergency_info->allergies ? 'required' : ''?>"
                   value="<?= $child_emergency_info ? $child_emergency_info->allergies_details : '' ?>"/>
        </div>

    </div>
</div>

<div class="row">
    <div class="form-group col-lg-12">
        <div>
            <label class="checkbox">
                Does your child use any prescribed medication?
                <input type="checkbox" name="medications"
                    <?= $child_emergency_info && $child_emergency_info->medications ? 'checked' : '' ?>/>
            </label>
        </div>

        <div>
            <label>Please specify and include dosage</label>
            <input type="text" name="dosage" <?= $child_emergency_info->medications ? '' : 'disabled'?> class="pink-input <?=$child_emergency_info->medications ? 'required' : ''?>"
                   value="<?= $child_emergency_info ? $child_emergency_info->dosage : '' ?>"/>
        </div>
    </div>
</div>

<div class="row">

    <a class="btn btn-pink prev-btn" data-goto="1">Back</a>
    <a class="btn btn-blue pull-right next-btn" data-goto="3">Next</a>
</div>


<?php
//    $request = isset($_COOKIE['req']) && !empty($_COOKIE['req'])
//        ? json_decode(base64_decode($_COOKIE['req']), true)
//        : '';
//    if (!empty($request)) {
//        $child_name = $request['name'];
//        $phone = $request['phone'];
//        $mobile = $request['mobile'];
//        $rel = $request['relationship'];
//        $collect_person = $request['collect_person'];
//        $allergies_details = $request['allergies_details'];
//        $allergies = $request['allergies'] ? 'checked' : '';
//        $medications = $request['medications'] ? 'checked' : '';
//        $dosage = $request['dosage'];
//    } else {
//        $rel = isset($child_emergency_info) && $child_emergency_info->relationship ? $child_emergency_info->relationship : '';
//        $medications = $child_emergency_info && $child_emergency_info->medications ? 'checked' : '';
//    }
//?>
<!--<form method="post" action="">-->
<!--    <div class="form-group">-->
<!--        <label>Name</label>-->
<!--        <input type="text" name="name" value="--><? //=$child_name?><!--" required />-->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label>Relationship</label>-->
<!--        <select name="relationship">-->
<!--            --><?php //if (!isset($child_emergency_info)) { ?>
<!--                <option selected>How are you related</option>-->
<!--                --><?php
//            } ?>
<!--            --><?php //foreach ($relationships as $relationship) { ?>
<!--                <option value="--><? //=$relationship?><!--"-->
<!--                    --><? //=$rel == $relationship
//                        ? 'selected'
//                        : ''?><!-- >-->
<!--                    --><? //=ucfirst($relationship)?>
<!--                </option>-->
<!--            --><?php
//            } ?>
<!--        </select>-->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label>Home Telephone</label>-->
<!--        <input type="text" name="phone" id="phone" value="--><? //=$phone?><!--" placeholder="(020) XXXX XXXX" required />-->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label>Mobile</label>-->
<!--        <input type="text" name="mobile" id="mobile" value="--><? //=$mobile?><!--" placeholder="0XXXX-XXX-XXX" required />-->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label>Child will be collected by</label>-->
<!--        <input type="text" name="collect_person" value="--><? //=$collect_person?><!--"/>-->
<!--    </div>-->
<!---->
<!--    <input type="hidden" name="doctor_id" value="--><? //=$child_emergency_info && $child_emergency_info->doctor_id ? $child_emergency_info->doctor_id : ''?><!--"/>-->
<!--    <div class="form-group">-->
<!--        <label>Doctor's name</label>-->
<!--        <input type="text" name="doctor_name" id="doctor_name" value="--><? //=$child_emergency_info ? $child_emergency_info->doctor_name : ''?><!--"/>-->
<!---->
<!--        <div id="doctorSearchSuggestions"></div>-->
<!--    </div>-->
<!---->
<!--    <div class="form-group address-group">-->
<!--        <label>Doctor's address</label>-->
<!--        <input name="doctor_address" class="address" type="text" value="--><? //=$child_emergency_info ? $child_emergency_info->doctor_address : ''?><!--" placeholder="Type in house/apartment number and street address here"  />-->
<!--        <span class="address-loader"></span>-->
<!--        <div class="addressSearchSuggestions"></div>-->
<!---->
<!--        <input type="text" name="doctor_postcode" class="postcode" value="--><? //=$child_emergency_info ? $child_emergency_info->doctor_postcode : ''?><!--" placeholder="or enter postcode here and we'll find your address" />-->
<!---->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label>Doctor's phone</label>-->
<!--        <input type="text" name="doctor_phone" value="--><? //=$child_emergency_info ? $child_emergency_info->doctor_phone : ''?><!--"/>-->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label>-->
<!--            Has your child received a tetanus injection in the last 5 years?-->
<!--            <input type="checkbox" name="injection"-->
<!--                --><? //=$child_emergency_info && $child_emergency_info->injections ? 'checked' : ''?><!--/>-->
<!--        </label>-->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label>-->
<!--            Does your child have any medical problems, allergies or disability?-->
<!--            <input type="checkbox" name="allergies"-->
<!--                --><? //=$allergies?><!--/>-->
<!--        </label>-->
<!---->
<!--        <div>-->
<!--            <label>Please specify</label>-->
<!--            <input type="text" name="allergies_details" --><? //=$allergies == 'checked' ? 'required' : ''?><!-- value="--><? //=$allergies_details?><!--"/>-->
<!--        </div>-->
<!---->
<!--    </div>-->
<!---->
<!--    <div class="form-group">-->
<!--        <label>-->
<!--            Does your child use any prescribed medication?-->
<!--            <input type="checkbox" name="medications"-->
<!--                --><? //=$medications?><!--/>-->
<!--        </label>-->
<!---->
<!--        <div>-->
<!--            <label>Please specify and include dosage</label>-->
<!--            <input type="text" name="dosage" --><? //=$medications == 'checked' ? 'required' : ''?><!-- value="--><? //=$dosage?><!--"/>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <input type="hidden" name="child_id" value="--><? //=isset($child_info) ? $child_info->id : ''?><!--"/>-->
<!--    <input type="hidden" name="route_name" value="emergency"/>-->
<!--    <input type="submit" value="Save" name="save_emergency" />-->
<!--</form>-->
