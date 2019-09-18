<?php
/**
 * Consent Summary
 *
 * Shows list of summary consents of the user
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div id="summary">
    <div class="tabs">
        <ul>
            <li class="active" data-tab="summary">Consent Summary</li>
            <li data-tab="manage">Manage consent</li>
            <li data-tab="personal">Personal Data</li>
        </ul>
    </div>
    <div class="tabs-content">
        <div class="item active" id="summary">
            <?php include 'consent_summary.php'; ?>
        </div>
        <div class="item" id="manage">
            <?php include 'manage_consent.php'; ?>
        </div>
        <div class="item" id="personal">
            <?php include 'personal_data.php'; ?>
        </div>
    </div>
    <div>


        <ul style="display: none;">
            <li>
                You don’t want to be added to our mailing list
            <span class="status">
                <input type="checkbox" disabled/>
            </span>
            </li>
            <li>
                Children
                <ul>
                    <li>
                        Name
                    <span class="status">
                        <input type="checkbox" disabled/>
                        <input type="checkbox" disabled/>
                    </span>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>


<script>
    (function($) {
        $('.tabs ul li').on('click', function() {
            var tabId = $(this).data('tab');

            $('.tabs ul li').removeClass('active');
            $(this).addClass('active');

            $('.tabs-content .item').removeClass('active');
            $('.tabs-content .item#' + tabId).addClass('active');
        });

        $('#save_consent').on('click', function() {

            var terms = $('input[name="terms"]').is(':checked') ? 1 : 0;
            var policy = $('input[name="policy"]').is(':checked') ? 1 : 0;
            var booking_terms = $('input[name="bookings_terms"]').is(':checked') ? 1 : 0;
            var subscr = $('input[name="subscription"]').map(function() {
                if ($(this).is(':checked')) {
                    return $(this).val();
                }
            }).toArray();

            $.ajax({
                url: CM_variables.siteurl + '/wp-admin/admin-ajax.php?action=saveConsent',
                type: 'post',
                data: {
                    terms : terms,
                    policy : policy,
                    booking_terms: booking_terms,
                    subscr : subscr
                },
                success: function() {
                    window.location.reload();
                }
            });

        });

        $('#download_personal_data').on('click', function() {
            $.ajax({
                url: CM_variables.siteurl + '/wp-admin/admin-ajax.php?action=getUserData',
                dataType: 'json',
                beforeSend: function() {
                },
                success: function(data) {
                    window.location.href = CM_variables.siteurl + '/wp-content/uploads/personal_data_' + data.user_login + '.csv';
                },
                error: function() {

                }
            });
        });

        $('#delete_personal_data').on('click', function() {
            if (confirm('Deleting your data will remove all your details and your login from our site. Are you sure you want to proceed?')) {
                // Deleting user

                $.ajax({
                    url: CM_variables.siteurl + '/wp-admin/admin-ajax.php?action=deleteUserData',
                    success: function() {
                        window.location.href = CM_variables.siteurl;
                    }
                });
            } else {
                return;
            }
        });
    })(jQuery);
</script>
