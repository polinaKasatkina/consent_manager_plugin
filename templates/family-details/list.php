<?php
/**
 * List
 *
 * Shows list of users children
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<div class="woocommerce-family-details">
    <div class="my-children row">

        <div class="children-text-left">Children </div>

<?php if ($has_children) : ?>
    <div class="children-container">

    <div class="children-row">

    <?php foreach ($children as $child) :
        $gender = $child->gender == 'f' ? 'female' : 'male';
        ?>

        <div class="child-icon <?=$gender?>">
            <img src="<?=site_url()?>/wp-content/plugins/consent-manager-for-woocommerce/assets/images/other_icons/face_<?=$gender?>.png">
            <p><?= $child->name ?></p>
            <a href="<?=home_url('my-account/family-details/' . $child->id); ?>">edit info</a>
        </div>

    <?php endforeach; ?>
    </div>
    </div>
<?php endif; ?>
        <div class="child-icon add-child">
            <a href="<?=home_url('my-account/family-details/add');?>">
                <span>+</span>
                Add new child
            </a>
        </div>

    </div>

    <div class="info">

        <?php if ($has_children) :
//            $childrenObj = new ChildrenFunctions();

            foreach ($children as $child) :
                $gender = $child->gender == 'f' ? 'female' : 'male';
                $emergency = cm_get_child_emergency_info($child->id);
                ?>
                <div class="child-block row <?=$gender?>">
                    <div class="img-container">
                    <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/children_photo/' . get_current_user_id() . '/' . $child->id . '.jpg')) : ?>
                        <div class="child-photo" style="background-image: url(/wp-content/uploads/children_photo/<?=get_current_user_id()?>/<?=$child->id?>.jpg)">
                        </div>
                    <?php else : ?>
                        <a href="#add-photo-modal" rel="modal:open">
                            <img src="<?=site_url()?>/wp-content/plugins/consent-manager-for-woocommerce/assets/images/other_icons/faces.png">
                            <p>Add photo</p>
                            <span>+</span>
                        </a>
                        <div id="add-photo-modal" class="modal">
                            <form enctype="multipart/form-data">
                                <input type="hidden" name="child_id" value="<?=$child->id?>"/>
                                <div class="form-group">
                                    <label>Photo</label>
                                    <input type="file" name="child_photo"/>
                                </div>
                                <button type="submit">Save</button>
                            </form>
                        </div>
                    <?php endif; ?>
                    </div>
                    <table class="main-info">
                        <thead>
                        <tr>
                            <th>Child's name</th>
                            <th>Age</th>
                            <th>Emergency Details</th>
                            <th>Consent</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?=$child->name?></td>
                            <td><?=$child->years?></td>
                            <td><?=has_emergency_info($child->id)
                                    ? $emergency->doctor_name . '<br/>' . $emergency->doctor_address . '<br/>' . $emergency->doctor_phone
                                    : 'No details' ?>
                            </td>
                            <td>
                                <p><?=$child->medical_agree ? 'Medical treatment' : ''?></p>
                                <p><?=$child->share_agree ? 'Sharing photo' : ''?></p>
                            </td>
                            <td class="actions">
                                <form method="post" action="" class="delete-form" onsubmit="return confirm('Are you sure you want to delete the information for this child?')">
                                    <input type="hidden" name="child_id" value="<?=$child->id?>"/>
                                    <input type="hidden" name="route_name" value="delete"/>
                                    <button type="submit" class="remove"></button>
                                </form>
                                <a href="<?=home_url('my-account/family-details/' . $child->id); ?>" class="edit"></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
