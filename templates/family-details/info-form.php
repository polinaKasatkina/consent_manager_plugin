<div class="row">
    <div class="form-group col-lg-8 col-xs-12">
        <label>Child name</label>
        <input type="text" name="name" placeholder="Full name" class="blue-input required" value="<?=$child_info ? $child_info->name : ''?>"/>
    </div>
    <div class="form-group col-lg-4">
        <label>Date of Birth</label>
        <input type="text" name="dob" id="dob" placeholder="MM.DD.YYYY" class="blue-input required" value="<?=$child_info ? date("d.m.Y", strtotime($child_info->DOB)) : ''?>"/>
    </div>
</div>
<?php $theme = wp_get_theme(); ?>
<div class="row">
    <div class="form-group col-lg-4 col-xs-12">
        <div class="<?=$theme == "Twenty Seventeen" ? 'col-lg-12  col-xs-12' : 'col-lg-6  col-xs-6'?>">
            <label>Female</label>
            <div class="female gender <?=$child_info->gender == 'f' ? 'active' : ''?>" data-value="f"></div>
            <label class="radio-label">
                <input type="radio" name="gender" value="f" <?=!$child_info->gender ? 'checked' : $child_info->gender == 'f' ? 'checked' : ''?>>
            </label>
        </div>
        <div class="<?=$theme == "Twenty Seventeen" ? 'col-lg-12  col-xs-12' : 'col-lg-6  col-xs-6'?>">
            <label>Male</label>
            <div class="male gender <?=$child_info->gender == 'm' ? 'active' : ''?>" data-value="m"></div>
            <label class="radio-label">
                <input type="radio" name="gender" value="m" <?=$child_info->gender == 'm' ? 'checked' : ''?>>
            </label>
        </div>
    </div>
    <div class="form-group col-lg-8 col-xs-12">
        <label>School</label>
        <input type="text" name="school" class="pink-input required"
               placeholder="School/Nursery (enter Home school if applicable)"
               value="<?=$child_info ? $child_info->school : ''?>"
        />
    </div>
</div>
<hr>

<div class="row">
    <div class="form-group col-lg-3 col-xs-12">
        <label>Photo</label>
        <input type="file" name="child_photo"/>
        <div class="img-container add-photo">
            <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/children_photo/' . get_current_user_id() . '/' . $child_info->id . '.jpg')) : ?>
                <div class="child_photo" style="background-image: url(/wp-content/uploads/children_photo/<?=get_current_user_id()?>/<?=$child_info->id?>.jpg);">
                </div>
            <?php else : ?>
            <div>
                <img src="<?=site_url()?>/wp-content/plugins/consent-manager-for-woocommerce/assets/images/other_icons/faces.png">
                <p>Add photo</p>
                <span>+</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group col-lg-9 col-xs-12 address-group">
        <label>Address</label>
        <input type="text" name="address" class="address pink-input"
               placeholder="Type in your house/apartment number and street address here"
               value="<?=$child_info ? $child_info->address : ''?>"
        />
        <span class="address-loader"></span>
        <div class="addressSearchSuggestions"></div>

        <input type="text" name="postcode" class="postcode pink-input top-border-none"
               placeholder="Enter your postcode"
               value="<?=$child_info ? $child_info->postcode : ''?>"
        />
    </div>
</div>

<a class="btn btn-blue pull-right next-btn" data-goto="2">Next</a>
