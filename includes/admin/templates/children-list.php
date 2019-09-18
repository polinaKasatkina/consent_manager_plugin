<?php include "pagination.php"; ?>

<table class="wp-list-table widefat fixed striped posts">
    <thead>
    <tr>
        <th>Name</th>
        <th>Age</th>
        <th>Parent Info</th>
        <th>Doctor Info</th>
        <th>Medical agree</th>
        <th>Share info agree</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($children)) {
        foreach ($children as $child) { ?>
            <tr>
                <td>
                    <a href="/wp-admin/admin.php?page=children_list&child_id=<?=$child['child_id']?>"><?=$child['name']?></a>

                    <?php if ($child['adult']) : ?>
                        <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/adult_photo/' . $child['child_id'] . '.jpg')) : ?>
                            <a class="child_photo" data-child="<?=$child['child_id']?>" data-parent="<?=$child['child_id']?>"></a>
                        <?php endif; ?>
                    <?php else : ?>
                        <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/children_photo/' . $child['parent_id'] . '/' . $child['child_id'] . '.jpg')) : ?>
                            <a class="child_photo" data-child="<?=$child['child_id']?>" data-parent="<?=$child['parent_id']?>"></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td><?=$child['years']?></td>
                <td>
                    <?php if ($child['adult']) : ?>
                        -
                    <?php else : ?>
                        <?= $child['parent_name'] ?>, <?= $child['parent_phone'] ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($child['adult']) : ?>
                        <?= get_user_meta($child['child_id'], 'emergency_contact_name', true) ?>
                        <?php if (get_user_meta($child['child_id'], 'emergency_mobile')) { ?>
                            , <a href="tel:<?= get_user_meta($child['child_id'], 'emergency_mobile', true) ?>">
                                <?= get_user_meta($child['child_id'], 'emergency_mobile', true) ?>
                            </a>
                        <?php } ?>
                    <?php else : ?>
                        <?= $child['doctor_name'] ? $child['doctor_name'] : '-' ?><?= $child['doctor_phone'] ? ', ' . $child['doctor_phone'] : '' ?>
                    <?php endif; ?>
                </td>
                <td>
                    <input type="checkbox" <?=$child['medical_agree'] ? 'checked' : ''?> disabled />
                </td>
                <td><input type="checkbox" <?=$child['share_agree'] ? 'checked' : ''?> disabled /></i></td>
            </tr>
        <?php
        }
        ?>
    <?php
    } else { ?>
        <tr>
          <td colspan="5">No children added</td>
        </tr>
    <?php
    } ?>
    </tbody>
</table>

<div id="child_photo_modal" class="modal" style="display: none;">
    <img src="" />
</div>
