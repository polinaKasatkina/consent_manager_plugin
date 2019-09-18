<div>

    <form method="post" action="options.php">

        <?php settings_fields( 'cm-organisation-details-settings-group' ); ?>
        <?php do_settings_sections( 'cm-organisation-details-settings-group' ); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="cm_organisation_name">Organisation Name (Full Legal Name)</label>
                </th>
                <td>
                    <input type="text" name="cm_organisation_name" class="regular-text code" value="<?php echo esc_attr( get_option('cm_organisation_name') ); ?>" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="cm_organisation_address">Address</label>
                </th>
                <td>
                    <input type="text" name="cm_organisation_address" class="regular-text code" value="<?php echo esc_attr( get_option('cm_organisation_address') ); ?>" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="cm_organisation_first_name">Contact Person First Name</label>
                </th>
                <td>
                    <input type="text" name="cm_organisation_first_name" class="regular-text code" value="<?php echo esc_attr( get_option('cm_organisation_first_name') ); ?>" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="cm_organisation_last_name">Contact Person Last Name</label>
                </th>
                <td>
                    <input type="text" name="cm_organisation_last_name" class="regular-text code" value="<?php echo esc_attr( get_option('cm_organisation_last_name') ); ?>" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="cm_organisation_contact_phone">Contact Person Telephone</label>
                </th>
                <td>
                    <input type="text" name="cm_organisation_contact_phone" class="regular-text code" value="<?php echo esc_attr( get_option('cm_organisation_contact_phone') ); ?>" />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="cm_organisation_email">Contact Email</label>
                </th>
                <td>
                    <input type="email" name="cm_organisation_email" class="regular-text code" value="<?php echo esc_attr( get_option('cm_organisation_email') ); ?>" />
                </td>
            </tr>

            <tr valign="top">
                <td>
                    <?php submit_button(); ?>
                </td>
            </tr>
        </table>
    </form>

</div>

<div style="font-style: italic; position: absolute; bottom: 40px;">
    Created by Arqino Digital Limited - <a href="http://www.arqino.com" target="_blank">www.arqino.com</a>
</div>
