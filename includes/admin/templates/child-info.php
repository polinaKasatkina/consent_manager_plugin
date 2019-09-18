<div class="row">
    <div class="child_info" style="width: 45%; float: left; margin-right: 25px;">
        <h3><?=$child_info->name?>, <?=$child_info->years?> years</h3>
        <p><?=$child_info->address?></p>
        <p><strong>D.O.B:</strong> <?=date("m.d.Y", strtotime($child_info->DOB))?></p>
        <hr>

        <h3>Parent</h3>
        <p><strong>Name: </strong> <?=$parent_info->display_name?></p>
        <p><strong>Address: </strong><?=$parent_info->address->address?></p>
        <p><strong>Phones: </strong><?=$parent_info->phone->phone?>, <?=$parent_info->mobile->mobile?></p>
    </div>
    <div style="display: inline-block; width: 45%">
        <h3>Emergency Info</h3>

        <p><strong>Name:</strong> <?=$emergency_info->name?>, <?=$emergency_info->relationship?></p>
        <p><strong>Phones:</strong> <?=$emergency_info->phone?>, <?=$emergency_info->mobile?></p>
        <p><strong>Person who will collect:</strong> <?=$emergency_info->collect_person?></p>

        <hr>
        <p><strong>Doctor's name:</strong> <?=$emergency_info->doctor_name?></p>
        <p><strong>Doctor's phone:</strong> <?=$emergency_info->doctor_phone?></p>
        <p><strong>Doctor's address:</strong> <?=$emergency_info->doctor_address?></p>

        <br/>

        <p><strong>Tetanus injections:</strong>
            <input type="checkbox" <?=$emergency_info->injections ? 'checked' : ''?> disabled />
        </p>
        <p><strong>Medical problems, allergies or disability:</strong>
            <input type="checkbox" <?=$emergency_info->allergies ? 'checked' : ''?> disabled />
        </p>
        <p><strong>Prescribed medication</strong>
            <input type="checkbox" <?=$emergency_info->medications ? 'checked' : ''?> disabled />
            <?=$emergency_info->medications ? '(' . $emergency_info->dosage . ')' : ''?>
        </p>
    </div>
</div>
