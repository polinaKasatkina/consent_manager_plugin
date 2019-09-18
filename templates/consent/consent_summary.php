<table>
    <thead>
    <tr>
        <th colspan="2">General</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Website usage terms and conditions</td>
        <td><div class="icon icon-<?= get_user_meta(get_current_user_id(), 'gdpr_terms', true) ? 'ok' : 'close' ?>"></div></td>
    </tr>
    <tr>
        <td>Bookings and Purchase terms and conditions</td>
        <td><div class="icon icon-<?= get_user_meta(get_current_user_id(), 'booking_terms', true) ? 'ok' : 'close' ?>"></div></td>
    </tr>
    <tr>
        <td>Our privacy policy</td>
        <td><div class="icon icon-<?= get_user_meta(get_current_user_id(), 'gdpr_policy', true) ? 'ok' : 'close' ?>"></div></td>
    </tr>
    <tr>
        <td>Mailing list, offers and services</td>
        <td><div class="icon icon-<?= get_user_meta(get_current_user_id(), 'subscribe_to_news', true) ? 'ok' : 'close' ?>"></div></td>
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
        <td colspan="6">We will communicate with you via:</td>
    </tr>
    <?php
    $subscription = get_user_meta(get_current_user_id(), 'subscribe_to_news', true) ? : [];
    if ($subscription == 1)
        $subscription = [0 => 'email'];
    ?>
    <tr>
        <td>Email</td>
        <td>
            <?php if (in_array('email', $subscription)) { ?><div class="icon icon-ok"><?php } ?>
        </td>
        <td>SMS</td>
        <td>
            <?php if (in_array('sms', $subscription)) { ?><div class="icon icon-ok"><?php } ?>
        </td>
        <td>Post</td>
        <td>
            <?php if (in_array('post', $subscription)) { ?><div class="icon icon-ok"><?php } ?>
        </td>
    </tr>
    <tr>
        <td>Phone</td>
        <td>
            <?php if (in_array('phone', $subscription)) { ?><div class="icon icon-ok"><?php } ?>
        </td>
        <td>WhatsApp</td>
        <td>
            <?php if (in_array('whatsapp', $subscription)) { ?><div class="icon icon-ok"><?php } ?>
        </td>
        <td>Facebook</td>
        <td>
            <?php if (in_array('facebook', $subscription)) { ?><div class="icon icon-ok"><?php } ?>
        </td>
    </tr>
    </tbody>
</table>


<table>
    <tbody>
    <tr>
        <td><strong>Children</strong></td>
        <td align="center"><strong>Medical treatment</strong></td>
        <td align="center"><strong>Photo Consent</strong></td>
    </tr>
    <?php
    if (!empty($children_info)) {
        foreach ($children_info as $child) { ?>

            <tr>
                <td><?=$child->name?></td>
                <td align="center">
                    <div class="icon icon-<?=$child->medical_agree ? 'ok' : 'close'?>">
                <td align="center">
                    <div class="icon icon-<?=$child->share_agree ? 'ok' : 'close' ?>">
            </tr>
            <?php

        }
    }
    ?>
    </tbody>
</table>
