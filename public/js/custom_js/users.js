$(document).ready(function() {
    var search_value = "";
    $(".select_user").select2({
        ajax: {
            url: '/panel/users_list',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
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
                            name: obj.name,
                            phone: obj.phone,
                            email: obj.email,
                            addresses: obj.addresses
                        };
                    }),
                    pagination: {
                        more: (params.page * 15) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: 'نام، شماره همراه یا ایمیل کاربر مورد نظر را جست و جو کنید.',
        minimumInputLength: 3,
        language: {
            noResults: function(term) {
                search_value = $('.select2-search__field').val();
                return "یافت نشد.";
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

    $('.select_user').on('select2:select', function (e) {
        search_value = ""; // clear
        $('#create_user_div').hide();
        $('#user_phone').val("");
        $('#user_name').val("");

    });

    function formatRepo (repo) {
        if (repo.loading) {
            return repo.text;
        }

        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" ;
        if (repo.name) {
            markup += "<div class='mt-0 mb-2 text-dark'>" + repo.name + "</div>";
        }else{
            markup += "<div class='mt-0 mb-2 text-dark'> - </div>";
        }

        markup += "<ul class='list-unstyled'>"
        if(repo.phone){
            markup+= "<li class='d-flex mb-1'><i class='mdi mdi-phone mr-1'></i>" + repo.phone + "</li>"
        }
        if(repo.email){
            markup+= "<li class='d-flex mb-1'><i class='mdi mdi-email mr-1'></i>" + repo.email + "</li>"
        }
        markup += "</ul>"

        markup += "</div></div>";
        return markup;
    }

    function formatRepoSelection (repo) {
        if (repo.hasOwnProperty('addresses')) {
            //hear is for select user addresses. new users addresses have written down.
            var div = $('#address_div');

            div.html('<label for="address">آدرس:</label>');

            var str = '';
            if (repo.addresses.length > 0){
                str = '<div class="input-group">\n' +
                    '                <div class="input-group-prepend">\n' +
                    '                     <span class="btn btn-outline-secondary" title="افزودن آدرس به کاربر" data-toggle="modal" data-target="#create_address_form">\n' +
                    '                            <i class="mdi mdi-map-marker-plus"></i>\n' +
                    '                     </span>\n' +
                    '                </div>';

                str += '<select id="address" class="form-control" name="address">';
                str += '<option value=""></option>';

                for(var i =0; i < repo.addresses.length; i++){
                    str += '<option value="'+ repo.addresses[i].address +'" lat="'+ repo.addresses[i].lat +'" lon="'+ repo.addresses[i].lon +'">'+ repo.addresses[i].address +'</option>';
                }

                str += '</select></div>';


            }else{
                str += '<button type="button" class="form-control btn btn-outline-primary" data-toggle="modal" data-target="#create_address_form"><i class="mdi mdi-map-marker-plus mr-2"></i>افزودن آدرس به کاربر </button>';
            }

            div.append(str);
        }

        return repo.name || repo.phone|| repo.email || repo.text;
    }

    $('#add_user').on('click', function () {
        if(phone_validate(search_value)){
            $('.select_user').val(null).trigger("change");

            $('#create_user_div').show();

            $('#user_phone').val(search_value);
        }else{
            swal("خطا!", "شماره همراه وارد شده متبر نیست.", "error");
        }
    });

    function phone_validate(phone){
        if(phone.length !== 11){
            return false;
        }

        var re = new RegExp("^([09]{2,}[0-9]{9,})$");

        if (re.test(phone)) {
            return true;
        } else {
            return false;
        }
    }

    $('#create_user_submit').on('click', function () {
        var user_name = $('#user_name').val();
        var user_phone = $('#user_phone').val();

        $.ajax({
            url: '/panel/users',
            type: 'POST',
            async: true,
            dataType: 'json',
            data:{
                "name": user_name,
                "phone": user_phone
            },
            success: function (data, textStatus, jQxhr) {
                response = JSON.parse(jQxhr.responseText);

                var select_element = $('.select_user');

                var user_id = response.data.id;
                var new_option = new Option(response.data.phone, user_id, true, true)

                select_element.append(new_option).trigger("change");

                select_element.select2();

                //this code is for new users addresses
                var div = $('#address_div');

                div.html('<label for="address">آدرس:</label>');

                var str = '';
                if (response.data.addresses.length > 0){
                    str = '<div class="input-group">\n' +
                        '                <div class="input-group-prepend">\n' +
                        '                     <span class="btn btn-outline-secondary" title="افزودن آدرس به کاربر" data-toggle="modal" data-target="#create_address_form">\n' +
                        '                            <i class="mdi mdi-map-marker-plus"></i>\n' +
                        '                     </span>\n' +
                        '                </div>';

                    str += '<select id="address" class="form-control" name="address">';
                    str += '<option value=""></option>';

                    for(var i =0; i < response.data.addresses.length; i++){
                        str += '<option value="'+ response.data.addresses[i].address +'" lat="'+ response.data.addresses[i].lat +'" lon="'+ response.data.addresses[i].lon +'">'+ response.data.addresses[i].address +'</option>';
                    }

                    str += '</select></div>';


                }else{
                    str += '<button type="button" class="form-control btn btn-outline-primary" data-toggle="modal" data-target="#create_address_form"><i class="mdi mdi-map-marker-plus mr-2"></i>افزودن آدرس به کاربر </button>';
                }

                div.append(str);

                swal("", response.message, "success");
            },
            error: function (jqXhr, textStatus, errorThrown) {
                response = JSON.parse(jqXhr.responseText);

                swal("خطا!", "خطایی در ایجاد کاربر رخ داده است. دوباره تلاش کنید.", "error");
            },
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
});
