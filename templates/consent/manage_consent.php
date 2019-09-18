<table>
    <thead>
    <tr>
        <th colspan="2">General</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2">
            Please click on the terms and conditions and privacy policy links below to read them. Tick the checkboxes to accept.
        </td>
    </tr>
    <tr>
        <td>
            <?php if (get_option('cm_terms_and_conditions')) { ?>
                <a href="<?=get_option('cm_terms_and_conditions')?>" target="_blank">Website usage terms and conditions</a>
            <?php } else { ?>
                Website usage terms and conditions
            <?php } ?>
        </td>
        <td>
            <input type="checkbox" name="terms" <?=get_user_meta(get_current_user_id(), 'gdpr_terms', true) ? 'checked' : ''?> />
        </td>
    </tr>
    <tr>
        <td>
            <?php if (get_option('cm_booking_terms')) { ?>
                <a href="<?=get_option('cm_booking_terms')?>" target="_blank">Bookings and Purchase terms and conditions</a>
            <?php } else { ?>
                Bookings and Purchase terms and conditions
            <?php } ?>
        </td>
        <td>
            <input type="checkbox" name="bookings_terms" <?=get_user_meta(get_current_user_id(), 'booking_terms', true) ? 'checked' : ''?> />
        </td>
    </tr>
    <tr>
        <td>
            <?php if (get_option('cm_privacy_policy')) { ?>
                <a href="<?=get_option('cm_privacy_policy')?>" target="_blank">Our privacy policy</a>
            <?php } else { ?>
                Our privacy policy
            <?php } ?>
        </td>
        <td>
            <input type="checkbox" name="policy" <?=get_user_meta(get_current_user_id(), 'gdpr_policy', true) ? 'checked' : ''?> />
        </td>
    </tr>

    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th colspan="6">Communication Preferences</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="6">Yes I would like to receive updates about products and services, promotions, special offers, news and events from Kite Studios online via:</td>
    </tr>
    <?php
    $subscription = get_user_meta(get_current_user_id(), 'subscribe_to_news', true) ? : [];
    if ($subscription == 1)
        $subscription = [0 => 'email'];
    ?>
    <tr>
        <td>Email</td>
        <td>
            <input type="checkbox" name="subscription" value="email" <?=in_array('email', $subscription) ? 'checked' : ''?>  />
        </td>
        <td>SMS</td>
        <td>
            <input type="checkbox" name="subscription" value="sms" <?=in_array('sms', $subscription) ? 'checked' : ''?>  />
        </td>
        <td>Post</td>
        <td>
            <input type="checkbox" name="subscription" value="post" <?=in_array('post', $subscription) ? 'checked' : ''?>  />
        </td>
    </tr>
    <tr>
        <td>Phone</td>
        <td>
            <input type="checkbox" name="subscription" value="phone" <?=in_array('phone', $subscription) ? 'checked' : ''?>  />
        </td>
        <td>WhatsApp</td>
        <td>
            <input type="checkbox" name="subscription" value="whatsapp" <?=in_array('whatsapp', $subscription) ? 'checked' : ''?>  />
        </td>
        <td>Facebook</td>
        <td>
            <input type="checkbox" name="subscription" value="facebook" <?=in_array('facebook', $subscription) ? 'checked' : ''?>  />
        </td>
    </tr>
    </tbody>
</table>

<div style="margin-bottom: 20px;">
    <button id="save_consent">Save</button>
</div>


<table>
    <thead>
    <tr>
        <th colspan="3">Children</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="3">
            Click on the child’s name below to review or change emergency contact and consent details.
        </td>
    </tr>
    <tr>
        <td><strong>Children</strong></td>
        <td align="center"><strong>Medical treatment</strong></td>
        <td align="center"><strong>Photo Consent</strong></td>
    </tr>
    <?php
    if (!empty($children_info)) {
        foreach ($children_info as $child) { ?>

            <tr>
                <td><a href="<?=site_url('/my-account/family-details/' . $child->id)?>"><?=$child->name?></a></td>
                <td align="center">
                        <input type="checkbox" disabled <?=$child->medical_agree ? 'checked' : ''?> /></td>
                <td align="center">
                        <input type="checkbox" disabled <?=$child->share_agree ? 'checked' : ''?> /></td>
            </tr>
            <?php

        }
    }
    ?>
    </tbody>
</table>
