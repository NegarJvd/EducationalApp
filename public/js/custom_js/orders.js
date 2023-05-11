$(document).ready(function() {
    var initial_date = $('#alt_delivery_time').val();
    if(initial_date){
        $("#delivery_time").pDatepicker({
            autoClose: true,
            initialValueType: 'gregorian',
            format: 'HH:mm - YYYY/MM/DD',
            altField: '#alt_delivery_time',
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
        $("#delivery_time").pDatepicker({
            autoClose: true,
            initialValue: false,
            initialValueType: 'gregorian',
            format: 'HH:mm - YYYY/MM/DD',
            altField: '#alt_delivery_time',
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

    //--------------------------------status change------------------------
    $('#status').on('change', function () {
        var value = $(this).val();

        if(value === "admin_canceled" || value === "admin_refused"){
            $('#explanation_of_cancellation_div').show();
            $('#explanation_of_cancellation').attr('required', 'required');
        }else{
            $('#explanation_of_cancellation_div').hide();
            $('#explanation_of_cancellation').removeAttr('required');
        }
    });

    //------------------product part---------------------------------------

    var products_array = [];
    var products_table_body = $('#products_table_body');
    var options = $('#select_product_options').html();

    var initial_option = $('.initial_option');
    initial_option.html(options);
    $('.select_product').select2();

    update_order(products_array);

    products_table_body.on('change', '.select_product', function () {
        var product_id = $(this).val();

        var duplicate_product = 0;
        $('.select_product').each(function () {
            if($(this).val() === product_id){
                duplicate_product += 1;
            }
        });

        if(duplicate_product > 1){
            $(this).val("").trigger('change');
            swal("محصول تکراری", "این محصول را قبلا به این سفارش اضافه کرده اید.", "warning");
        }else{
            var td = $(this).parent();
            var tr = td.parent();

            var tr_type = tr.find('.product_type');
            tr_type.html("");

            var tr_product_price = tr.find('.product_price');
            var tr_product_price_value = tr_product_price.find('.product_price_value');

            var tr_product_discount = tr.find('.product_discount');
            var tr_product_discount_value = tr_product_discount.find('.product_discount_value');

            var tr_product_price_after_discount = tr.find('.product_price_after_discount');
            var tr_product_price_after_discount_value = tr_product_price_after_discount.find('.product_price_after_discount_value');

            $.ajax({
                url: '/panel/products/' + product_id,
                type: 'GET',
                async: true,
                dataType: 'json',
                success: function (data, textStatus, jQxhr) {
                    response = JSON.parse(jQxhr.responseText);

                    products_array.push(response.data);

                    var discount_percent = parseInt(response.data.discount) + parseInt(response.data.category_discount);

                    var discount_value = parseInt(discount_percent);
                    tr_product_price_value.val(response.data.price);
                    tr_product_discount_value.val(discount_value);
                    var product_price_after_discount = ((100 - discount_value) /100) * response.data.price;
                    tr_product_price_after_discount_value.val(product_price_after_discount);

                    var types = response.data.types;

                    var type_selection = '';

                    if(types.length > 0){
                        type_selection = '<select class="form-control select_product_type" name="product_types[]">' ;
                        type_selection += '<option value=""></option>';

                        for(var i=0; i < types.length; i++){
                            type_selection += '<option value="' + types[i].id + '">' + types[i].fa_name + '</option>';
                        }

                        type_selection += '</select>';


                    }else {
                        type_selection = '<select class="form-control select_product_type" name="product_types[]" hidden>' ;
                        type_selection += '<option value="" selected></option>';
                        type_selection += '</select>';
                    }

                    tr_type.append(type_selection);

                    update_order(products_array);
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    response = JSON.parse(jqXhr.responseText);

                },
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        }

    });

    products_table_body.on('change', '.select_product_type', function () {
        var product_type_id = $(this).val();

        var td = $(this).parent();
        var tr = td.parent();

        var tr_product_price = tr.find('.product_price');
        var tr_product_price_value = tr_product_price.find('.product_price_value');

        var tr_product_discount = tr.find('.product_discount');
        var tr_product_discount_value = tr_product_discount.find('.product_discount_value');

        var tr_product_price_after_discount = tr.find('.product_price_after_discount');
        var tr_product_price_after_discount_value = tr_product_price_after_discount.find('.product_price_after_discount_value');


        for(var j=0; j < products_array.length; j++){
            var types = products_array[j].types;

            for(var z=0; z < types.length; z++){
                if(types[z].id == product_type_id){
                    tr_product_price_value.val(types[z].price);
                    tr_product_discount_value.val(products_array[j].discount);
                    var product_price_after_discount = ((100 - parseInt(products_array[j].discount)) /100) * parseInt(types[z].price);
                    tr_product_price_after_discount_value.val(product_price_after_discount);

                    break;
                }

            }

        }

        update_order(products_array);
    });

    products_table_body.on('input', '.product_count_value', function () {
        update_order(products_array);
    });

    $('#discount').on('input', function () {
        update_order(products_array);
    });

    $('#shipping_cost').on('change', function () {
        update_order(products_array);
    });

    $('#shipping_cost').on('input', function () {
        update_order(products_array);
    });

    // new AutoNumeric.multiple('.price',{ //TODO
    //     unformatOnSubmit: true,
    //     decimalPlaces: 0
    // });

    //-----------------add row to products table------------------------
    $('#add_product').on('click', function () {
        // var all_rows_count =$('#products_table_body tr').length;
        // var next_row = all_rows_count + 1;
        //
        // var id = 'product_price_' + next_row;

        $('#products_table_body').append(
            '<tr>\n' +
            '    <td><select class="select_product form-control initial_option" name="products[]">'+ options +'</select></td>\n' +
            '    <td class="product_type"></td>\n' +
            '    <td class="product_count"><input type="number" class="form-control product_count_value" name="products_count[]" min="1" value="1"></td>\n' +
            '    <td class="product_price"><input type="text" class="form-control price product_price_value" readonly></td>\n' +
            '    <td class="product_discount"><input type="text" class="form-control product_discount_value" readonly></td>\n' +
            '    <td class="product_price_after_discount"><input type="text" class="form-control price product_price_after_discount_value" readonly></td>\n' +
            '    <td class="product_total_price"><input type="text" class="form-control price product_total_price_value" readonly></td>\n' +
            '    <td><i class="mdi mdi-trash-can-outline remove_product"></i></td>\n' +
            '</tr>'
        );

        $('.select_product').select2();

        // new AutoNumeric.multiple('#' + id,{
        //     unformatOnSubmit: true,
        //     decimalPlaces: 0
        // });
    });

    //-----------------remove row from products table-------------------
    products_table_body.on('click', '.remove_product', function () {
        var td = $(this).parent();
        var tr = td.parent();
        tr.remove();

        update_order(products_array);
    });

    //---------------update price functions-----------------------------
    function min_cooking_time(products_array) {
        var max = $('#min_cooking_time').val();

        if(products_array.length > 0){
            $('.select_product').each(function () {
                for(var m=0; m < products_array.length; m++){
                    if(products_array[m].id == $(this).val()){
                        if (max < products_array[m].min_cooking_time){
                            max = products_array[m].min_cooking_time;
                        }
                    }
                }
            });
        }

        $('#min_cooking_time').val(max);
    }

    function update_product_total_price() {
        $('.product_total_price_value').each(function () {
            var td = $(this).parent();
            var tr = td.parent();

            var tr_product_price_after_discount = tr.find('.product_price_after_discount');
            var tr_product_price_after_discount_value = tr_product_price_after_discount.find('.product_price_after_discount_value');
            var product_price = tr_product_price_after_discount_value.val();

            var tr_product_count = tr.find('.product_count');
            var tr_product_count_value = tr_product_count.find('.product_count_value');
            var product_count = tr_product_count_value.val();

            $(this).val(product_count * product_price);
        });
    }

    function update_products_total_price() {
        var products_total_value = 0;

        $('.product_total_price_value').each(function () {
            products_total_value += parseInt($(this).val());
        });

        $('#products_total').val(products_total_value);
    }

    function update_total_price() {
        var total = 0;

        var products_total = parseInt($('#products_total').val());
        var shipping_cost = parseInt($('#shipping_cost').val());
        var discount = parseInt($('#discount').val());

        total = (products_total + shipping_cost) - discount;

        if(total < 0){
            total = 0;
        }

        $('#total').val(total);
    }

    function update_total_discount() {
        var total_discount = 0;

        $('.product_discount_value').each(function () {
            var discount_percent = $(this).val();

            if(discount_percent > 0){
                var td = $(this).parent();
                var tr = td.parent();

                var tr_product_count = tr.find('.product_count');
                var tr_product_count_value = tr_product_count.find('.product_count_value');
                var product_count = parseInt(tr_product_count_value.val());

                var tr_product_total_price = tr.find('.product_total_price');
                var tr_product_total_price_value = tr_product_total_price.find('.product_total_price_value');
                var product_total_price = parseInt(tr_product_total_price_value.val());

                var tr_product_price = tr.find('.product_price');
                var tr_product_price_value = tr_product_price.find('.product_price_value');
                var product_price = parseInt(tr_product_price_value.val());

                total_discount += ((product_price * product_count) - product_total_price);
            }
        });

        var discount_value = parseInt($('#discount').val());

        total_discount += discount_value;

        $('#total_discount').val(total_discount);
    }

    function update_order(products_array){
        update_product_total_price();
        update_products_total_price();
        update_total_price();
        update_total_discount();
        min_cooking_time(products_array);
    }

});
