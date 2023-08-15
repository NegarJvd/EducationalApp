$(document).ready(function() {
    "use strict";

    $('#refresh').on('click', function () {
        window.location.reload();
    })

    $('.make_active_user').on('click', function () {
        //make active user
        $('.media-active').removeClass('media-active');
        $(this).addClass('media-active');

        //get tickets
        let user_id = $(this).attr('user_id');
        $.ajax({
            url: '/panel/tickets/' + user_id,
            type: 'GET',
            datatype: 'json',
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val(),
                'Accept': 'application/json'
            },
            success: function (data) {
                let user = data.data;
                let tickets = user.tickets;

                let tickets_html = "";

                for(var i=0; i<tickets.length; i++){
                    if(tickets[i].is_user === 1){
                        tickets_html += '<div class="media media-chat media-right">'+
                                            '<div class="media-body">'+
                                                '<p class="message">' +
                                                    '<small class="text-primary small" style="line-height: revert">' + tickets[i].author_name + '</small><br>'+
                                                    tickets[i].text +
                                                '</p>'+
                                                '<div class="date-time">' + tickets[i].jalali_datetime + '</div>'+
                                            '</div>'+
                                            '<img class="rounded-circle p-2" width="100%" src="/assets/img/account.png" alt="user image">'+
                                        '</div>';

                    }else{
                        tickets_html += '<div class="media media-chat media-left">'+
                                            '<img class="rounded-circle p-2" width="100%" src="/assets/img/account.png" alt="user image">'+
                                            '<div class="media-body">'+
                                                '<p class="message">' +
                                                    '<small class="text-primary small" style="line-height: revert">' + tickets[i].author_name + '</small><br>'+
                                                    tickets[i].text +
                                                '</p>'+
                                                '<div class="date-time">' + tickets[i].jalali_datetime + '</div>'+
                                            '</div>'+
                                        '</div>';
                    }
                }

                $('#active_user_name').text(user.name).change();
                $('#active_user_diagnosis').text(user.diagnosis).change();
                $('#user_reply').val(user.id).change();

                $('#chat-right-content').remove();
                $('#header_of_chat').after('<div class="chat-right-content" id="chat-right-content"></div>');
                $('#chat-right-content').append(tickets_html);
                $('#chat-right-content').each((index, element) => new SimpleBar(element));

                var container = document.querySelector('#chat-right-content .simplebar-content-wrapper');
                container.scrollTo({ top: 5000000, behavior: "smooth" });
            },
            error: function (jqXhr, textStatus, errorThrown) {
                let response = JSON.parse(jqXhr.responseText);
                swal("خطا!", response.message, "error");
            },

        });

    });

    $('#reply_button').on('click', function () {
        let user_id = $('#user_reply').val();
        let text = $('#text').val();

        $.ajax({
            url: '/panel/tickets/',
            type: 'POST',
            datatype: 'json',
            data: {
                user_id: user_id,
                text: text
            },
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val(),
                'Accept': 'application/json'
            },
            success: function (data) {
                $('#text').val('').change();

                let ticket = data.data;

                let ticket_html = '<div class="media media-chat media-left">'+
                    '<img class="rounded-circle p-2" width="100%" src="/assets/img/account.png" alt="user image">'+
                    '<div class="media-body">'+
                    '<p class="message">' +
                    '<small class="text-primary small" style="line-height: revert">' + ticket.author_name + '</small><br>'+
                    ticket.text +
                    '</p>'+
                    '<div class="date-time">' + ticket.jalali_datetime + '</div>'+
                    '</div>'+
                    '</div>';

                $('#chat-right-content').append(ticket_html);

                var container = document.querySelector('#chat-right-content .simplebar-content-wrapper');
                container.scrollTo({ top: 5000000, behavior: "smooth" });
            },
            error: function (jqXhr, textStatus, errorThrown) {
                let response = JSON.parse(jqXhr.responseText);
                swal("خطا!", response.message, "error");
            },

        });
    });
});
