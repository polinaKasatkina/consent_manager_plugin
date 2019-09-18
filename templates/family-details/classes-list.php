<table class="woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
    <thead>
    <tr>
        <th>Class name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Duration</th>
<!--        <th></th>-->
    </tr>
    </thead>

    <tbody>
        <?php
            if (!empty($child_classes)) :
                foreach ($child_classes as $class) :
                ?>
                <tr>
                    <td><?=$class['name']?></td>
                    <td><?=$class['date']?></td>
                    <td><?=$class['time']?></td>
                    <td><?=$class['duration']?></td>
<!--                    <td></td>-->
                </tr>
        <?php
                endforeach;
            endif;
        ?>
    </tbody>
</table>
