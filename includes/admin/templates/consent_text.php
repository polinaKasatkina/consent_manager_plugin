<div>

    <form method="post" action="options.php">

        <?php settings_fields( 'cm-consent-text-settings-group' ); ?>
        <?php do_settings_sections( 'cm-consent-text-settings-group' ); ?>

        <h2><strong>Customise your consent wording below.</strong></h2>

        <h3>Child Consent Form:</h3>

        <div>
            <p>Medical treatment</p>

            <div>
                <?php
                $default_text_medical_treatment = "<p>I give my consent for my child to receive emergency medical treatment.</p>";

                $settings_medical_treatment = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_medical_treatment_text', 'textarea_rows' => 5 );
                $content_medical_treatment = get_option('cm_medical_treatment_text') ? : $default_text_medical_treatment;
                $editor_medical_treatment_id = 'cm_medical_treatment_text';
                ?>

                <?php wp_editor( $content_medical_treatment, $editor_medical_treatment_id, $settings_medical_treatment); ?>
            </div>
        </div>

        <div>
            <p>Photo consent</p>

            <div>
                <?php
                $default_text_photo_consent = "<p>I give my consent for you to use a likeness of me and/or my child to be used on any film, photo or audio recording.</p>";

                $settings_photo_consent = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_photo_consent_text', 'textarea_rows' => 5 );
                $content_photo_consent = get_option('cm_photo_consent_text') ? : $default_text_photo_consent;
                $editor_photo_consent_id = 'cm_photo_consent_text';
                ?>

                <?php wp_editor( $content_photo_consent, $editor_photo_consent_id, $settings_photo_consent); ?>
            </div>
        </div>

        <hr/>

        <h3>Adult Consent Form:</h3>

        <div>
            <p>Emergency Details Usage Consent</p>

            <div>
                <?php
                $default_text_adult_medical_treatment = "<p>I give my consent for you to use the details provided in case of an emergency.</p>";

                $settings_adult_medical_treatment = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_adult_medical_treatment_text', 'textarea_rows' => 5 );
                $content_adult_medical_treatment = get_option('cm_adult_medical_treatment_text') ? : $default_text_adult_medical_treatment;
                $editor_adult_medical_treatment_id = 'cm_adult_medical_treatment_text';
                ?>

                <?php wp_editor( $content_adult_medical_treatment, $editor_adult_medical_treatment_id, $settings_adult_medical_treatment); ?>
            </div>
        </div>

        <div>
            <p>Photo consent</p>

            <div>
                <?php
                $default_adult_text_photo_consent = "<p>I give my consent for you to use a likeness of me on any film, photo or audio recording</p>";

                $settings_adult_photo_consent = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_adult_photo_consent_text', 'textarea_rows' => 5 );
                $content_adult_photo_consent = get_option('cm_adult_photo_consent_text') ? : $default_adult_text_photo_consent;
                $editor_adult_photo_consent_id = 'cm_adult_photo_consent_text';
                ?>

                <?php wp_editor( $content_adult_photo_consent, $editor_adult_photo_consent_id, $settings_adult_photo_consent); ?>
            </div>
        </div>

        <hr/>

        <h3>Checkout Page:</h3>

        <div>
            <p>Accept Terms & Conditions</p>

            <div>
                <?php
                $default_cm_checkout_terms_text = "<p>I've read and accept the <a href='" . get_option('cm_booking_terms') . "'>bookings terms & conditions</a></p>";

                $settings_cm_checkout_terms_text = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_checkout_terms_text', 'textarea_rows' => 5 );
                $content_cm_checkout_terms_text = get_option('cm_checkout_terms_text') ? : $default_cm_checkout_terms_text;
                $editor_cm_checkout_terms_text_id = 'cm_checkout_terms_text';
                ?>

                <?php wp_editor( $content_cm_checkout_terms_text, $editor_cm_checkout_terms_text_id, $settings_cm_checkout_terms_text); ?>
            </div>
        </div>

        <div>
            <p>Photo Consent</p>

            <div>
                <?php
                $default_cm_checkout_photo_consent_text = "<p>I give my consent for you to use a likeness of me on any film, photo or audio recording</p>";

                $settings_cm_checkout_photo_consent_text = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_checkout_photo_consent_text', 'textarea_rows' => 5 );
                $content_cm_checkout_photo_consent_text = get_option('cm_checkout_photo_consent_text') ? : $default_adult_text_photo_consent;
                $editor_cm_checkout_photo_consent_text_id = 'cm_checkout_photo_consent_text';
                ?>

                <?php wp_editor( $content_cm_checkout_photo_consent_text, $editor_cm_checkout_photo_consent_text_id, $settings_cm_checkout_photo_consent_text); ?>
            </div>
        </div>

        <div>
            <p>Newsletter</p>

            <div>
                <?php
                $default_cm_checkout_newsletter_text = "<p>Add me to your mailing list</p>";

                $settings_cm_checkout_newsletter_text = array( 'media_buttons' => false,'quicktags' => false, 'textarea_name' => 'cm_checkout_newsletter_text', 'textarea_rows' => 5 );
                $content_cm_checkout_newsletter_text = get_option('cm_checkout_newsletter_text') ? : $default_cm_checkout_newsletter_text;
                $editor_cm_checkout_newsletter_text_id = 'cm_checkout_newsletter_text';
                ?>

                <?php wp_editor( $content_cm_checkout_newsletter_text, $editor_cm_checkout_newsletter_text_id, $settings_cm_checkout_newsletter_text); ?>
            </div>
        </div>

        <div>
            <label>
                <input type="checkbox" name="cm_show_on_checkout" <?=get_option('cm_show_on_checkout') ? 'checked' : ''?> />
                Add to Checkout Page
            </label>
        </div>


        <?php submit_button(); ?>

    </form>

</div>

<div style="font-style: italic; position: absolute; bottom: 40px;">
    Created by Arqino Digital Limited - <a href="http://www.arqino.com" target="_blank">www.arqino.com</a>
</div>
