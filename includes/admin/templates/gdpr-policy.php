<div>

    <form method="post" action="options.php">

        <?php settings_fields( 'cm-gdpr-policy-settings-group' ); ?>
        <?php do_settings_sections( 'cm-gdpr-policy-settings-group' ); ?>

        <div>
            <p><strong>Customize your policy statements regarding the following GDPR requirements:</strong></p>
            <ol>
                <li>List the personal data that is stored related to class bookings.</li>
                <li>Explain how the data is used.</li>
            </ol>
        </div>

        <div>
            <p><strong>Which personal data items are stored?</strong></p>

            <?php

            $default_text_data = "<p>We store the following data about customers who place a booking:</p>
                <ul class='col-3 list-alpha'>
                    <li>Name, Surname</li>
                    <li>Email</li>
                    <li>Physical address</li>
                    <li>Home telephone</li>
                    <li>Mobile telephone</li>
                    <li>Physical address</li>
                    <li>Payment method</li>
                </ul>
                <ul class='list-alpha'>
                    <li>Children names</li>
                    <li>Relationship</li>
                    <li>Medical emergency details</li>
                </ul>";

            $settings_data = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_personal_data', 'textarea_rows' => 10 );
            $content_data = get_option('cm_personal_data') ?  : $default_text_data;
            $editor_data_id = 'cm_personal_data';
            ?>

            <?php wp_editor( $content_data, $editor_data_id, $settings_data); ?>

            <div class="publish_checkbox">
                <label>
                    <input type="checkbox" name="cm_publish_personal_data" <?= get_option('cm_publish_personal_data') ? 'checked' : '' ?> />
                    Publish on My Account - Consent Manager section
                </label>
            </div>
        </div>

        <div>
            <p><strong>How is the data used?</strong></p>

            <?php

            $default_text_data_used = "<p>In the case of an adult, the contact details are used for newsletter (if chosen to opt in) and for emergency contact and consent purposes</p>";

            $settings_data_used = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_how_data_used', 'textarea_rows' => 5 );
            $content_data_used = get_option('cm_how_data_used') ? : $default_text_data_used;
            $editor_data_used_id = 'cm_how_data_used';
            ?>

            <?php wp_editor( $content_data_used, $editor_data_used_id, $settings_data_used); ?>

            <div class="publish_checkbox">
                <label>
                    <input type="checkbox" name="cm_publish_how_data_used" <?= get_option('cm_publish_how_data_used') ? 'checked' : '' ?> />
                    Publish on My Account - Consent Manager section
                </label>
            </div>
        </div>

        <?php submit_button(); ?>

    </form>

</div>

<div style="font-style: italic; position: absolute; bottom: 40px;">
    Created by Arqino Digital Limited - <a href="http://www.arqino.com" target="_blank">www.arqino.com</a>
</div>
<style>

    ul.col-3 {
        -webkit-column-count: 3;
        -moz-column-count: 3;
        column-count: 3;
    }
    ul.list-alpha li {
        list-style: lower-alpha;
        margin-left: 10px;
    }
    .publish_checkbox {
        margin-top: 10px;
    }
</style>
