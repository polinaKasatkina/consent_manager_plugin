<?php
/*
 * Template Name: Emergency new
 * Description: A Page Template with a darker design.
 */
get_header();
?>

<div class="container-wrap">

    <div class="main-content">

        <div class="container" id="emergency">

            <?php
            if(have_posts()) : while(have_posts()) : the_post(); ?>


                <?php if (is_user_logged_in()) : ?>

                    <?php if (has_emergency_access(get_current_user_id())) : ?>
                        <?php
                        $pageNum = get_query_var('paged') ? get_query_var('paged') : 1;
                        $children = get_emergency_list($pageNum);
                        $pagination = get_pagination();
                        ?>
                        <div class="tablenav top">

                            <div class="actions bulkactions">

                                <form method="get">
                                    <label class="screen-reader-text" for="post-search-input">Search:</label>
                                    <input type="search" id="post-search-input" name="search" value="<?=isset($_GET['search']) ? $_GET['search'] : ''?>">
                                    <input type="submit" id="search-submit" class="button" value="Search">
                                </form>

                            </div>

                            <br class="clear">
                        </div>


                        <table class="wp-list-table widefat fixed striped posts rwd-table">
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Parent Info</th>
                                <th>Emergency Info</th>
                                <th>Medical agree</th>
                                <th>Share info agree</th>
                            </tr>
                            <?php if (!empty($children)) {
                                foreach ($children as $child) { ?>
                                    <tr>
                                        <td data-th="Name">
                                            <?=$child['name']?>
                                            <?php if ($child['adult']) : ?>
                                                <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/adult_photo/' . $child['child_id'] . '.jpg')) : ?>
                                                    <a class="child_photo_icon" data-child="<?=$child['child_id']?>" data-parent="<?=$child['child_id']?>"></a>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/wp-content/uploads/children_photo/' . $child['parent_id'] . '/' . $child['child_id'] . '.jpg')) : ?>
                                                    <a class="child_photo_icon" data-child="<?=$child['child_id']?>" data-parent="<?=$child['parent_id']?>"></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td data-th="Age"><?=$child['years']?></td>
                                        <td data-th="Parent Info">
                                            <?php if ($child['adult']) : ?>
                                                -
                                            <?php else : ?>
                                            <?=$child['parent_name']?>, <strong><a href="tel:<?=$child['parent_phone']?>" class="phone"><?=$child['parent_phone']?></a></strong>
                                            <?php endif; ?>
                                        </td>
                                        <td data-th="Doctor Info">
                                            <?php if ($child['adult']) : ?>
                                                <?= get_user_meta($child['child_id'], 'emergency_contact_name', true) ?>
                                                <?php if (get_user_meta($child['child_id'], 'emergency_mobile')) { ?>
                                                    , <a href="tel:<?=get_user_meta($child['child_id'], 'emergency_mobile', true)?>">
                                                        <?= get_user_meta($child['child_id'], 'emergency_mobile', true)?>
                                                    </a>
                                                <?php } ?>
                                            <?php else : ?>
                                            <?=$child['doctor_name'] ? $child['doctor_name'] : '-'?><strong><?=$child['doctor_phone'] ? ', <a class="phone" href="tel:' . $child['doctor_phone'] . '">' . $child['doctor_phone'] . '</a>' : ''?></strong>
                                            <?php endif; ?>
                                        </td>
                                        <td data-th="Medical agree" class="text-center">
                                            <input type="checkbox" <?=$child['medical_agree'] ? 'checked' : ''?> disabled />
                                        </td>
                                        <td data-th="Share info agree" class="text-center"><input type="checkbox" <?=$child['share_agree'] ? 'checked' : ''?> disabled /></i></td>
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
                        </table>

                        <div id="child_photo_modal" class="modal" style="display: none;">
                            <img src="" />
                        </div>

                        <div class="tablenav top">
                            <div class="tablenav-pages">
                                <span class="displaying-num"><?= $pagination['count'] ?> items</span>

        <span class="pagination-links">
            <?php if ($pagination['current_page'] > 1) : ?>
                <a class="first-page" href="/emergency/"><span
                        class="screen-reader-text">First Page</span><span aria-hidden="true">«</span></a>
                <a class="prev-page"
                   href="/emergency/page/<?= $pagination['current_page'] - 1 ?>">
                    <span class="screen-reader-text">Previous page</span>
                    <span aria-hidden="true">‹</span>
                </a>
                <?php
            else : ?>
            <span class="pagination-links"><span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                <?php
                endif;
                ?>
                <span class="paging-input">
                <label for="current-page-selector" class="screen-reader-text">Current Page</label>
                <input class="current-page" id="current-page-selector" type="text" name="paged"
                       value="<?= $pagination['current_page'] ?>" size="1" aria-describedby="table-paging" style="padding: 0 3px !important;
    font-size: 13px !important;
    width: 30px;">
                <span class="tablenav-paging-text"> of <span
                        class="total-pages"><?= $pagination['pages_count'] ?></span>
                </span>
            </span>
            <a class="next-page"
               href="/emergency/page/<?= $pagination['current_page'] + 1 ?>">
                <span class="screen-reader-text">Next page</span>
                <span aria-hidden="true">›</span>
            </a>
            <a class="last-page"
               href="/emergency/page/<?= $pagination['pages_count'] ?>">
                <span class="screen-reader-text">Last page</span>
                <span aria-hidden="true">»</span>
            </a>
        </span>
                            </div>
                        </div>

                    <?php else : ?>

                        <div>
                            <p>Sorry you don't have rights to access this page. Please get back to <a href="/">main page</a></p>
                        </div>

                    <?php endif; ?>

                <?php else : ?>

                    <div class="login-form">


                        <?php echo do_shortcode('[my-login-form]'); ?>

                    </div>

                <?php endif; ?>

                <?php

            endwhile; endif;

            ?>

        </div><!--/row-->

    </div><!--/container-->

</div><!--/container-wrap-->
<script>
    (function($) {
        $('.child_photo_icon').on('click', function() {
            var child_id = $(this).data('child');
            var parent_id = $(this).data('parent');
            var src;

            if (parent_id == child_id) { // adult photo
                src = '/wp-content/uploads/adult_photo/' + child_id + '.jpg';
            } else { // child photo
                src = '/wp-content/uploads/children_photo/' + parent_id + '/' + child_id + '.jpg';
            }

            $('#child_photo_modal img').attr('src', src);
            $('#child_photo_modal').modal('show');
        });
    })(jQuery);
</script>
<?php get_footer(); ?>
