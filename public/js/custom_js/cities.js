$(document).ready(function() {
    $(".select_city").select2({
        ajax: {
            url: '/panel/cityList',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term // search term
                };
            },
            processResults: function (data, params) {
                return {
                    results: $.map(data.data, function(obj) {
                        return {
                            id: obj.id,
                            name: obj.name,
                            parent_name: obj.parent_name
                        };
                    })
                };
            },
            cache: true
        },
        placeholder: 'نام شهر مورد نظر را جست و جو کنید.',
        minimumInputLength: 2,
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: formatRepoCity,
        templateSelection: formatRepoSelectionCity
    });

    function formatRepoCity (repo) {
        if (repo.loading) {
            return repo.text;
        }

        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" ;

        markup += "<div class='mt-0 mb-2 text-dark'>" + repo.name + "-" + repo.parent_name + "</div>";

        markup += "</div></div>";
        return markup;
    }

    function formatRepoSelectionCity (repo) {
        return repo.name || repo.text;
    }
});
