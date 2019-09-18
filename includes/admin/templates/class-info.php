<div class="row">
    <div class="child_info" style="width: 45%; float: left; margin-right: 25px;">
        <h3><?=$class->order_item_name?></h3>
        <p><strong>Date: </strong><?=$class->item_meta['Booking Date'][0]?></p>
        <p><strong>Time: </strong><?=$class->item_meta['Booking Time'][0]?></p>
        <p><strong>Duration: </strong><?=$class->item_meta['Duration'][0]?></p>
        <hr>

    </div>
    <div style="display: inline-block; width: 45%">
        <h3>Children</h3>
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($children as $child) :
//                $childObj = new ChildrenFunctions();
                ?>
            <tr>
                <td><a href="/wp-admin/admin.php?page=children_list&child_id=<?=$child->id?>"><?=$child->name?></a></td>
                <td><?=cm_get_child_years($child->DOB)?> years <?php if ($child->months) : ?><?=$child->months?> months<?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
