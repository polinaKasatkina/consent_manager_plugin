<div>
    <h1>Emergency Access Control List</h1>

    <?php
    $post = get_page_by_title("Emergency");
    ?>
    <p><strong>Your emergency access URL is:</strong> <a href="<?=get_permalink($post->ID)?>" target="_blank"><?=get_permalink($post->ID)?></a></p>

    <div style="margin-bottom: 20px;">
        <form id="emergency_form">
            <div>
                <label>First name or Email</label>
                <input type="text" name="search" placeholder="Start typing user name or email" style="width: 400px;" />
                <input type="hidden" name="new_user"/>
                <button id="save_emergency_user" style="margin-left: 20px;">Add user</button>

                <div id="emergency_suggestions"></div>
            </div>
            <div>

            </div>
        </form>
    </div>

    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <th>User</th>
            <th>Role</th>
            <th>Has access to emergency page</th>
        </tr>
        </thead>
        <?php if (!empty($users)) {
            foreach ($users as $user) {
                $user_info = get_userdata($user->ID);

                if (empty($user->user_email)) continue;

                if (!get_user_meta($user->ID, 'emergency_access', true)) continue;

                ?>
                <tr data-user="<?=$user->ID?>">
                    <td><?=get_user_meta($user->ID, 'first_name', true)?> <?=get_user_meta($user->ID, 'last_name', true)?> (<?=$user->user_email?>)</td>
                    <td><?=implode(', ', $user_info->roles)?></td>
                    <td><input type="checkbox" id="emergancy_access" <?= get_user_meta($user->ID, 'emergency_access', true) ? 'checked' : ''?>></td>
                </tr>
                <?php
            }
        } ?>
    </table>
</div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.css" />
<script>
    (function($) {
        var ajaxUrl = '<?=admin_url('admin-ajax.php?action=searchUsers')?>';

        $('input[name="search"]').autocomplete({
            appendTo: '#emergency_suggestions',
            source: function source(req, response) {
                $.getJSON(ajaxUrl, req, response);
            },
            select: function select(event, ui) {

                $('input[name="new_user"]').val(ui.item.id);
                $('input[name="search"]').val(ui.item.label);

                return false;
            },
            maxHeight: '',
            width: '60%',
            minLength: 3,
            html: true
        });

        $('#save_emergency_user').on('click', function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= admin_url('admin-ajax.php?action=addUserToEmergencyPage') ?>',
                type: 'post',
                data: {
                    user_id : $('input[name="new_user"]').val()
                },
                success: function() { window.location.reload(); }
            });
        });

        $('#emergancy_access').on('click', function() {

            var tr = $(this).closest('tr');

            $.ajax({
                url: '<?= admin_url('admin-ajax.php?action=removeUserFromEmergencyPage') ?>',
                type: 'post',
                data: {
                    user_id : tr.data('user')
                },
                success: function() { tr.remove(); }
            });
        });
    })(jQuery);
</script>
