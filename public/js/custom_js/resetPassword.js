$(document).ready(function () {
    $('#body').on('click', '.forgot_password', function () {
        let phone = $('#phone').val();
        let click_button = $(this);

        $.ajax({
            url: '/forgotPassword',
            type: 'POST',
            data: {
                phone: phone
            },
            async: true,
            dataType: 'json',
            success: function (data, textStatus, jQxhr) {
                response = JSON.parse(jQxhr.responseText);

                $('#phone').addClass('border');

                $('#phone').removeClass('is-invalid');
                $('#phone').removeClass('border-danger');

                if (response.code === 1) {
                    $('#title_of_page').text("بازیابی رمز عبور");
                    $('#link').remove();

                    $('#forgot_password_phone').hide();

                    if(! $('#token_div').length){
                        var token_div = '<div class="form-group col-md-12 mb-4" id="token_div">\n' +
                            '                  <input id="token" type="text" class="form-control input-lg " placeholder="کد تایید">\n' +
                            '            </div>';

                        var new_password_div = '<div class="form-group col-md-12 mb-4" id="new_password_div">\n' +
                            '                         <input id="password" type="text" class="form-control input-lg " placeholder="رمز عبور جدید">\n' +
                            '                  </div>';

                        var new_password_confirm_div = '<div class="form-group col-md-12 mb-4" id="new_password_confirm_div">\n' +
                            '                                 <input id="password_confirmation" type="text" class="form-control input-lg " placeholder="تکرار رمز عبور جدید">\n' +
                            '                            </div>';


                        $('#card_body').prepend( token_div + new_password_div + new_password_confirm_div);
                    }

                    if(! $('.btn-secondary').length){
                        var send_again = '<button type="button" class="ladda-button btn btn-lg btn-secondary btn-block mb-4 forgot_password">\n' +
                            '                     <span class="ladda-label">ارسال مجدد به ' + phone + '</span>\n' +
                            '                     <span class="ladda-spinner"></span>\n' +
                            '             </button>';

                        $('#buttons').append(send_again)

                        /*======== 8. LOADING BUTTON ========*/
                        /* 8.1. BIND NORMAL BUTTONS */
                        Ladda.bind(".ladda-button", {
                            timeout: 5000
                        });

                        /* 7.2. BIND PROGRESS BUTTONS AND SIMULATE LOADING PROGRESS */
                        Ladda.bind(".progress-demo button", {
                            callback: function(instance) {
                                var progress = 0;
                                var interval = setInterval(function() {
                                    progress = Math.min(progress + Math.random() * 0.1, 1);
                                    instance.setProgress(progress);

                                    if (progress === 1) {
                                        instance.stop();
                                        clearInterval(interval);
                                    }
                                }, 200);
                            }
                        });
                    }

                    click_button.removeClass('forgot_password').trigger('change');
                    click_button.attr('id', 'reset_password').trigger('change');

                }else {
                    $('#phone').addClass('is-invalid');
                    $('#phone').addClass('border');
                    $('#phone').addClass('border-danger');

                    $('#forgot_password_phone').append('<div class="invalid-feedback" style="display: none"></div>');
                    $('.invalid-feedback').text(response.message);
                    $('.invalid-feedback').addClass('text-danger');
                    $('.invalid-feedback').show();
                }
            },
            error: function (jqXhr, textStatus, errorThrown) {
                response = JSON.parse(jqXhr.responseText);

                $('#phone').addClass('is-invalid');
                $('#phone').addClass('border');
                $('#phone').addClass('border-danger');

                if(response.errors.phone){
                    for (i=0; i<response.errors.phone.length; i++){
                        $('#forgot_password_phone').append('<div class="invalid-feedback' + i + '"' + ' style="display: none"></div>');
                        $('.invalid-feedback' + i).text(response.errors.phone[i]);
                        $('.invalid-feedback' + i).addClass('text-danger');
                        $('.invalid-feedback' + i).show();
                    }
                }

            },
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $('#body').on('click', '#reset_password', function () {
        let phone = $('#phone').val();
        let token = $('#token').val();
        let password = $('#password').val();
        let password_confirmation = $('#password_confirmation').val();

        $.ajax({
            url: '/resetPassword',
            type: 'POST',
            data: {
                phone: phone,
                token: token,
                password: password,
                password_confirmation: password_confirmation
            },
            async: true,
            dataType: 'json',
            success: function (data, textStatus, jQxhr) {
                response = JSON.parse(jQxhr.responseText);

                $('#token').addClass('border');
                $('#token').removeClass('is-invalid');
                $('#token').removeClass('border-danger');

                $('#password').addClass('border');
                $('#password').removeClass('is-invalid');
                $('#password').removeClass('border-danger');

                if (response.code === 1) {
                    alert(response.message);

                    window.location = "/login";

                }else {
                    $('#token').addClass('is-invalid');
                    $('#token').addClass('border');
                    $('#token').addClass('border-danger');

                    $('#token_div').append('<div class="invalid-feedback" style="display: none"></div>');
                    $('.invalid-feedback').text(response.message);
                    $('.invalid-feedback').addClass('text-danger');
                    $('.invalid-feedback').show();
                }
            },
            error: function (jqXhr, textStatus, errorThrown) {
                response = JSON.parse(jqXhr.responseText);

                if(response.errors.token){
                    $('#token').addClass('is-invalid');
                    $('#token').addClass('border');
                    $('#token').addClass('border-danger');

                    for (i=0; i<response.errors.token.length; i++){
                        $('#token_div').append('<div class="invalid-feedback' + i + '"' + ' style="display: none"></div>');
                        $('.invalid-feedback' + i).text(response.errors.token[i]);
                        $('.invalid-feedback' + i).addClass('text-danger');
                        $('.invalid-feedback' + i).show();
                    }
                }

                if(response.errors.password){
                    $('#password').addClass('is-invalid');
                    $('#password').addClass('border');
                    $('#password').addClass('border-danger');

                    for (j=0; j<response.errors.password.length; j++){
                        $('#new_password_div').append('<div class="p_invalid-feedback' + j + '"' + ' style="display: none"></div>');
                        $('.p_invalid-feedback' + j).text(response.errors.password[j]);
                        $('.p_invalid-feedback' + j).addClass('text-danger');
                        $('.p_invalid-feedback' + j).show();
                    }
                }

            },
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
});
