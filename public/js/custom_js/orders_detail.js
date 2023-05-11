$(document).ready(function() {

    var products_total_value = 0;

    $('.product_total_price').each(function () {
        var tr = $(this).parent();

        var tr_product_price_after_discount = tr.find('.product_price_after_discount');
        var product_price_after_discount = tr_product_price_after_discount.html();

        var tr_product_count = tr.find('.product_count');
        var product_count = tr_product_count.html();

        var value = product_count * product_price_after_discount;

        products_total_value += parseInt(value);

        $(this).html(value);
    });

    $('#products_total').val(products_total_value);

    var total_discount = 0;

    $('.product_discount').each(function () {
        var discount_percent = parseInt($(this).html());

        if(discount_percent > 0){
            var tr = $(this).parent();

            var tr_product_count = tr.find('.product_count');
            var product_count = parseInt(tr_product_count.html());

            var tr_product_total_price = tr.find('.product_total_price');
            var product_total_price = parseInt(tr_product_total_price.html());

            var tr_product_price = tr.find('.product_price');
            var product_price = parseInt(tr_product_price.html());

            total_discount += ((product_price * product_count) - product_total_price);
        }
    });

    var discount_value = parseInt($('#discount').val());

    total_discount += discount_value;

    $('#total_discount').val(total_discount);

    new AutoNumeric.multiple('.price',{ //TODO
        unformatOnSubmit: true,
        decimalPlaces: 0
    });

});
