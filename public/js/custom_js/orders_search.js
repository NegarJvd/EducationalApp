// <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>

$(document).ready(function() {
    $(".select_order").select2({
        ajax: {
            url: '/panel/orders',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;

                return {
                    results: $.map(data.data, function(obj) {
                        return {
                            id: obj.id,
                            order_number: obj.order_number,
                            total: obj.total,
                            pay_type: obj.pay_type,
                            user: obj.user.phone,
                            products: obj.products,
                            disabled : obj.is_purified
                        };
                    }),
                    pagination: {
                        more: (params.page * 15) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: 'شماره سفارش مورد نظر را جست و جو کنید.',
        minimumInputLength: 6,
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

    function formatRepo (repo) {
        if (repo.loading) {
            return repo.text;
        }
        var products = '';

        for(var i=0; i<repo.products.length; i++){
            products += repo.products[i].fa_name;

            if(i != repo.products.length - 1){
                products += " , ";
            }
        }

        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='mt-0 mb-2 text-dark'>" + repo.order_number + "</div>";

        markup += "<ul class='list-unstyled'>"

        markup+= "<li class='d-flex mb-1'><i class='mdi mdi-account mr-1'></i>" + repo.user + "</li>"

        markup+= "<li class='d-flex mb-1'><i class='mdi mdi-cash-multiple mr-1'></i>"+ repo.total + "ریال" + "</li>"

        markup+= "<li class='d-flex mb-1'><i class='mdi mdi-food mr-1'></i>" + products + "</li>"

        markup += "</ul>"

        markup += "</div></div>";

        return markup;
    }

    function formatRepoSelection (repo) {
        if (repo.hasOwnProperty('order_number')) {
            $('#transaction_type').val(repo.pay_type).trigger('change');

            // $('#transaction_price').AutoNumeric('destroy');
            $('#transaction_price').val(repo.total).trigger('change');

            // new AutoNumeric('#transaction_price',{
            //     unformatOnSubmit: true,
            //     decimalPlaces: 0
            // });
        }

        return repo.order_number || repo.text;
    }
});
