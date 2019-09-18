<div>

    <form method="post" action="options.php">

        <?php settings_fields( 'cm-gdpr-policy-url-settings-group' ); ?>
        <?php do_settings_sections( 'cm-gdpr-policy-url-settings-group' ); ?>

        <table style="width: 100%; margin-top: 20px;">
            <tbody>
            <tr>
                <td width="35%">Website usage terms and conditions</td>
                <td>
                    <input type="text" name="cm_terms_and_conditions" value="<?=get_option('cm_terms_and_conditions')?>" placeholder="enter page URL" style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <td>Bookings & Purchase terms and conditions</td>
                <td>
                    <input type="text" name="cm_booking_terms" value="<?=get_option('cm_booking_terms')?>" placeholder="enter page URL" style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <td>Privacy Policy</td>
                <td>
                    <input type="text" name="cm_privacy_policy" value="<?=get_option('cm_privacy_policy')?>" placeholder="enter page URL" style="width: 100%;" />
                </td>
            </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>

    </form>

</div>

<div style="font-style: italic; position: absolute; bottom: 40px;">
    Created by Arqino Digital Limited - <a href="http://www.arqino.com" target="_blank">www.arqino.com</a>
</div>
