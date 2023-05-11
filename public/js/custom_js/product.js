$(document).ready(function() {
    var initial_date = $('#alt_disable_until').val();
    if(initial_date){
        $("#disable_until").pDatepicker({
            autoClose: true,
            initialValueType: 'gregorian',
            format: 'HH:mm - YYYY/MM/DD',
            altField: '#alt_disable_until',
            timePicker: {
                enabled: true,
                second: {
                    enabled: false,
                },
                meridiem: {
                    enabled: true
                }
            }
        })
    }else{
        $("#disable_until").pDatepicker({
            autoClose: true,
            initialValue: false,
            initialValueType: 'gregorian',
            format: 'HH:mm - YYYY/MM/DD',
            altField: '#alt_disable_until',
            timePicker: {
                enabled: true,
                second: {
                    enabled: false,
                },
                meridiem: {
                    enabled: true
                }
            }
        });
    }

    $("#tag-input-33").tokenfield();
    $('#create_form').on('submit', function () {
        $("#ingredient").val( $("#tag-input-33").tokenfield('getTokensList'))
    });
    $('#edit_form').on('submit', function () {
        $("#ingredient").val( $("#tag-input-33").tokenfield('getTokensList'))
    });

    var have_types = $('#have_types');

    have_types.on("change", function () {
        if(have_types.is(":checked")){
            $('#types').show();
            $('#price_div').hide();
        }else{
            $('#types').hide();
            $('#price_div').show();
        }
    });

    if(have_types.is(":checked")){
        $('#types').show();
        $('#price_div').hide();
    }

    $('#add_type').on('click', function () {
        var all_rows_count =$('#types_table_body tr').length;
        var next_row = all_rows_count + 1;

        var id = 'price_type_' + next_row;

        $('#types_table_body').append(
            '<tr>\n' +
            '    <td><input type="text" class="form-control" name="fa_name_types[]"></td>\n' +
            '    <td><input type="text" class="form-control" name="en_name_types[]"></td>\n' +
            '    <td><input type="text" class="form-control price" name="price_types[]" id="' + id + '"></td>\n' +
            '    <td><i class="mdi mdi-trash-can-outline remove_type"></i></td>\n' +
            '</tr>'
        );

        new AutoNumeric.multiple('#' + id,{
            unformatOnSubmit: true,
            decimalPlaces: 0
        });
    });

    $('#types_table_body').on('click', '.remove_type', function () {
        var td = $(this).parent();
        var tr = td.parent();
        tr.remove();
    });

    new AutoNumeric.multiple('.price',{
        unformatOnSubmit: true,
        decimalPlaces: 0
    });

    $('.delete_upload').on('click', function () {
        var parent = $(this).parent().parent();
        var image_type = parent.attr('image_type')
        var upload_id = parent.find('.upload_id').val();

        if(confirm("برای حذف تصویر مطمئن هستید؟")){
            $.ajax({
                url: '/panel/delete_file/' + upload_id,
                type: 'DELETE',
                async: true,
                dataType: 'json',
                success: function (data, textStatus, jQxhr) {
                    response = JSON.parse(jQxhr.responseText);
                    console.log(response);

                    var upload_file_div = parent.find('.upload_file');

                    if(image_type === "main"){
                        upload_file_div.append(
                            '<input type="file" class="form-control" name="upload">'
                        );
                    }else if(image_type === "cover"){
                        upload_file_div.append(
                            '<input type="file" class="form-control" name="upload_cover">'
                        );
                    }

                    upload_file_div.show();

                    var picture_show_div = parent.find('.picture_show_div');
                    picture_show_div.remove();
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    response = JSON.parse(jqXhr.responseText);

                    swal("خطا!", "مشکلی در حذف فایل پیش آمده است.", "error");
                },
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }

    })

});
