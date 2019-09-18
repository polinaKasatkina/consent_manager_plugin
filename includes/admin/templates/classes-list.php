<?php include "pagination.php"; ?>

<table class="wp-list-table widefat fixed striped posts">
    <thead>
    <tr>
        <th>Class Name</th>
        <th>Date</th>
        <th>Time</th>
        <th id="sort_by_number" data-value="<?=isset($_GET['sort']) ? $_GET['sort'] : 'asc'?>">Number of children</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($classes)) :
        foreach ($classes as $class) { ?>
            <tr>
                <td><a href="/wp-admin/admin.php?page=classes_list&item_id=<?=$class['order_item_id']?>"><?=$class['name']?></a></td>
                <td><?=$class['date']?></td>
                <td><?=$class['time']?></td>
                <td><?=$class['children_count']?></td>
            </tr>
    <?php
        }

        else : ?>
            <tr>
                <td colspan="5">No classes selected</td>
            </tr>
    <?php

        endif;
    ?>
    </tbody>
</table>
