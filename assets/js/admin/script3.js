(function($) {
    $(document).ready(function () {

        var date = new Date();

        $('#post-date-picker').datepicker({
            dateFormat: 'dd.mm.yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "2000:" + date.getFullYear()
        });

        $('#sort_by_number').on('click', function() {
            var sortValue = $(this).data('value'),
                sortOrder = sortValue == 'asc' ? 'desc' : 'asc',
                currentUrl = window.location.href;

            window.location.href = window.location.href + '&sort=' + sortOrder;
        });

        $('#to_csv').on('click', function() {
            $('.ajax_loader').css('display', 'block');
            $('.ajax_success').hide();
            $('.ajax_error').hide();
            $.ajax({
                url: CM_variables.siteurl + '/wp-admin/admin-ajax.php?action=getChildrenList',
                beforeSend: function() {
                    $('.ajax_loader').css('display', 'block');
                    $('.ajax_success').hide();
                    $('.ajax_error').hide();
                },
                success: function() {
                    window.location.href = CM_variables.siteurl + '/wp-content/uploads/children_list.csv';
                    $('.ajax_loader').hide();
                    $('.ajax_success').show();
                    $('.ajax_error').hide();
                },
                error: function() {
                    $('.ajax_loader').hide();
                    $('.ajax_success').hide();
                    $('.ajax_error').show();
                }
            });
        });

        $('.child_photo').on('click', function() {
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

    })
})(jQuery);
