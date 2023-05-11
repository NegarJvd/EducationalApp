$(document).ready(function() {
    $(".select_product").select2({
        allowClear: true,
        ajax: {
            url: '/panel/productList',
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
                            fa_name: obj.fa_name,
                            en_name: obj.en_name,
                            ingredient : obj.ingredient,
                            have_types: obj.have_types,
                            price: obj.price
                        };
                    }),
                    pagination: {
                        more: (params.page * 15) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: 'نام محصول مورد نظر را جست و جو کنید.',
        minimumInputLength: 1,
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

        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='mt-0 mb-2 text-dark'>" + repo.fa_name;

        if(repo.en_name){
            markup += "(" + repo.en_name + ")" + "</div>"
        }else{
            markup +=  "</div>";
        }

        markup += "<ul class='list-unstyled'>"

        markup+= "<li class='d-flex mb-1'><i class='mdi mdi-food mr-1'></i>" + repo.ingredient + "</li>"

        markup += "</ul>"

        markup += "</div></div>";
        return markup;
    }

    function formatRepoSelection (repo) {
        return repo.fa_name || repo.text;
    }
});
