<?php
$GDPRScreen = ( isset( $_GET['tab'] ) && 'gdpr' == $_GET['tab'] ) ? true : false;
$PolicyURLs = ( isset( $_GET['tab'] ) && 'policy' == $_GET['tab'] ) ? true : false;
$consentText = ( isset( $_GET['tab'] ) && 'consent' == $_GET['tab'] ) ? true : false;
?>
<div class="wrap">
    <h1>Settings</h1>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url( 'admin.php?page=consent_settings' ); ?>" class="nav-tab<?php if ( ! isset( $_GET['tab'] ) || isset( $_GET['tab'] ) && 'gdpr' != $_GET['tab'] && 'policy' != $_GET['tab'] && 'consent' != $_GET['tab'] ) echo ' nav-tab-active'; ?>">Organisation Details </a>

            <a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'gdpr' ), admin_url( 'admin.php?page=consent_settings' ) ) ); ?>" class="nav-tab<?php if ( $GDPRScreen ) echo ' nav-tab-active'; ?>">GDPR Policy Statements</a>
            <a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'policy' ), admin_url( 'admin.php?page=consent_settings' ) ) ); ?>" class="nav-tab<?php if ( $PolicyURLs ) echo ' nav-tab-active'; ?>">Policy Pages</a>
            <a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'consent' ), admin_url( 'admin.php?page=consent_settings' ) ) ); ?>" class="nav-tab<?php if ( $consentText ) echo ' nav-tab-active'; ?>">Consent Text</a>
        </h2>


    <div>

        <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
        <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>

            <?php


                if($GDPRScreen) {  // GDPR Policy Statements

                    include('gdpr-policy.php');


                } elseif ($PolicyURLs) { // Policy Pages

                    include('policy-pages.php');

                } elseif($consentText) { // Consent Text

                    include('consent_text.php');

                } else { // Organisation details

                    include('organisation_details.php');
                }


            ?>

    </div>
</div>

<style>
    .ajax_status {
        display: block;
        float: right;
        margin-top: 3px;
        margin-left: 3px;
    }
    .ajax_status .ajax_loader {
        width: 20px;
        background: url(/wp-content/uploads/2018/01/ajax-loader.gif) no-repeat 0 0;
        background-size: 100% auto;
        height: 20px;
        display: none;
    }
    .ajax_status .ajax_success {
        display: none;
        color: green;
        background: url(/wp-content/uploads/2018/01/ok.png) no-repeat scroll 0 0;
        padding-left: 20px;
        background-size: 16px 16px;
    }
    .ajax_status .ajax_error {
        display: none;
        color: red;
        background: url(/wp-content/uploads/2018/01/close.png) no-repeat scroll 0 0;
        background-size: 16px 16px;
        padding-left: 20px;
    }
    .member-tabs {
        margin-bottom: 20px;
    }
    .member-tabs::after {
        display: block;
        clear: both;
        height: 1px;
    }
    .member-tabs ul {
        list-style: none;
        margin-left: 0;
    }
    .member-tabs ul li {
        float: left;
        padding: 10px;
        cursor: pointer;
    }
    .member-tabs ul li.active {
        border-bottom: 2px solid #e6b1a6;
    }
    #download_reply_report {
        clear: both;
        margin: 20px 0;
    }
    #search {
        width: 50%;
        float: right;
        margin-top: 40px;
    }
    <?php
    if ($_SERVER['REQUEST_URI'] == "/edit-members/?tab=subscription") {
     ?>
    table tbody tr {
        display: none;
    }
    table tbody tr.face_channel {
        display: table-row;
    }
    <?php } ?>
</style>
<script type="text/javascript">
    (function($) {
        $('#download_reply_report').on('click', function() {
            $('.ajax_loader').css('display', 'block');
            $('.ajax_success').hide();
            $('.ajax_error').hide();
            var tab;

            <?php if ($trialScreen) { ?>

            tab = 'trial';

            <?php } elseif ($freeScreen) { ?>

            tab = 'free';

            <?php } else { ?>
            tab = 'face_channel';
            <?php } ?>


            <?php if ($_SERVER['REQUEST_URI'] == "/edit-members/?tab=subscription") { ?>
            tab = $('.member-tabs ul li.active').data('tab');
            <?php } ?>


            $.ajax({
                url: '/wp-admin/admin-ajax.php?action=getMembersReport',
                data: {
                    tab : tab
                },
                type: 'post',
                beforeSend: function() {
                    $('.ajax_loader').css('display', 'block');
                    $('.ajax_success').hide();
                    $('.ajax_error').hide();
                },
                success: function(data) {
                    $('.ajax_loader').hide();
                    if (data == "done") {
                        window.location.href = '/wp-content/uploads/members_' + tab + '_report.csv';
                        $('.ajax_success').show();
                        $('.ajax_error').hide();
                    } else {
                        $('.ajax_success').hide();
                        $('.ajax_error').show();
                    }

                },
                error: function() {
                    $('.ajax_loader').hide();
                    $('.ajax_success').hide();
                    $('.ajax_error').show();
                }
            });
        });

        <?php if ($_SERVER['REQUEST_URI'] == "/edit-members/?tab=subscription") { ?>
        var active = $('.member-tabs li.active').data('tab');
        <?php } else { ?>

        var active = <?php if ($trialScreen) { ?> 'trial' <?php  } elseif ($freeScreen) { ?> 'free' <?php } else { ?> 'face_channel' <?php } ?> ;

        <?php }  ?>

        var values = $('table tbody tr.' + active).map(function() {
            return $(this).find('td:eq(0)').text().replace(/(?:\r\n|\r|\n)/g, '').replace(/\s{2,}/g, '');
        }).toArray();

        /// Tabs switch

        $('.member-tabs ul li').on('click', function() {
            var dataTab = $(this).data('tab');
            active = dataTab;

            setUpValues();

            $('.member-tabs ul li').removeClass('active');
            $(this).addClass('active');

            $('table tbody tr').hide();
            $('table tbody tr.' + dataTab).show();
        });

        /// Tabs switch

        //// Search

        function setUpValues() {
            values = $('table tbody tr.' + active).map(function() {
                return $(this).find('td:eq(0)').text().replace(/(?:\r\n|\r|\n)/g, '').replace(/\s{2,}/g, '');
            }).toArray();
        }


        $("#search input").keyup(function() {
            var quer = $(this).val();

            if (quer.length == 0) {
                $('table tbody tr.' + active).show();
            }
            for (var i = 0; i < values.length; i++) {
                if (values[i].indexOf(quer) === -1) {
                    $('table tbody tr.' + active + ':eq(' + i + ')').hide()
                    // $('.user_name:eq(' + i + ')').parent().hide();
                } else {
                    $('table tbody tr.' + active + ':eq(' + i + ')').show()
                    // $('.user_name:eq(' + i + ')').parent().show();
                }
            }
        });
        //// Seacrh
    })(jQuery);
</script>
