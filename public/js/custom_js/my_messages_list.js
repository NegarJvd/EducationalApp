$(document).ready(function() {
    $('.messages_list').on('click', function () {

        $('.my_messages_list_content').remove();
        $('#my-sms').append('<ul class="list-unstyled my_messages_list_content" style="height: 360px" id="sms_list_content"></ul>');
        $('#my-notification').append('<ul class="list-unstyled my_messages_list_content" style="height: 360px" id="notification_list_content"></ul>');
        $('#my-ticket').append('<ul class="list-unstyled my_messages_list_content" style="height: 360px" id="ticket_list_content"></ul>');

        $('.my_messages_list_content').append(
            '<div class="card-body d-flex align-items-center justify-content-center" style="height: 160px">'+
            '<div class="sk-three-bounce">'+
            '<div class="bounce1"></div><div class="bounce2"></div><div class="bounce2"></div>'+
            '</div>'+
            '</div>'
        );

        $('.my_messages_list_content').each((index, element) => new SimpleBar(element));

        $.ajax({
            url: '/panel/message_list',
            type: 'GET',
            async: true,
            dataType: 'json',
            success: function (data, textStatus, jQxhr) {
                response = JSON.parse(jQxhr.responseText);

                $('.my_messages_list_content').remove();
                $('#my-sms').append('<ul class="list-unstyled my_messages_list_content" style="height: 360px" id="sms_list_content"></ul>');
                $('#my-notification').append('<ul class="list-unstyled my_messages_list_content" style="height: 360px" id="notification_list_content"></ul>');
                $('#my-ticket').append('<ul class="list-unstyled my_messages_list_content" style="height: 360px" id="ticket_list_content"></ul>');


                var sms_list = response.data.sms_list.list;
                var notification_list = response.data.notification_list.list;
                var ticket_list = response.data.ticket_list.list;

                for(i = 0; i < sms_list.length; i++){
                    $('#sms_list_content').append(
                        '<li><a class="media media-message media-notification">'
                        +
                        '<div class="media-body d-flex justify-content-between">'
                        +
                        '<div class="message-contents">'
                        +
                        '<h4 class="title">'+ sms_list[i].title +'</h4>'
                        +
                        '<p class="last-msg" title="' + sms_list[i].body + '">'+ sms_list[i].body +'</p>'
                        +
                        '<span class="font-size-12 font-weight-medium text-secondary">'
                        +
                        '<i class="mdi mdi-calendar mr-1"></i>' + sms_list[i].send_date
                        +
                        '<i class="mdi mdi-clock-outline mr-1"></i>' + sms_list[i].send_time
                        +
                        '</span>'
                        +
                        '</div>'
                        +
                        '</div>'
                        +
                        '</a></li>');
                }

                for(i = 0; i < notification_list.length; i++){
                    $('#notification_list_content').append(
                        '<li><a class="media media-message media-notification">'
                        +
                        '<div class="media-body d-flex justify-content-between">'
                        +
                        '<div class="message-contents">'
                        +
                        '<h4 class="title">'+ notification_list[i].title +'</h4>'
                        +
                        '<p class="last-msg" title="' + notification_list[i].body+ '">'+ notification_list[i].body +'</p>'
                        +
                        '<span class="font-size-12 font-weight-medium text-secondary">'
                        +
                        '<i class="mdi mdi-calendar mr-1"></i>' + notification_list[i].send_date
                        +
                        '<i class="mdi mdi-clock-outline mr-1"></i>' + notification_list[i].send_time
                        +
                        '</span>'
                        +
                        '</div>'
                        +
                        '</div>'
                        +
                        '</a></li>');
                }

                for(i = 0; i < ticket_list.length; i++){
                    $('#ticket_list_content').append(
                        '<li><a href="/panel/tickets/' + ticket_list[i].id +'" class="media media-message media-notification">'
                        +
                        '<div class="media-body d-flex justify-content-between">'
                        +
                        '<div class="message-contents">'
                        +
                        '<h4 class="title">'+ ticket_list[i].subject +'</h4>'
                        +
                        '<p class="last-msg" title="' + ticket_list[i].text+ '">'+ ticket_list[i].text +'</p>'
                        +
                        '<span class="font-size-12 font-weight-medium text-secondary">'
                        +
                        '<i class="mdi mdi-calendar mr-1"></i>' + ticket_list[i].date + '  '
                        +
                        '<i class="mdi mdi-clock-outline mr-1"></i>' + ticket_list[i].time + '  '
                        +
                        '<i class="mdi mdi-message-reply mr-1"></i>' + ticket_list[i].answers.length
                        +
                        '</span>'
                        +
                        '</div>'
                        +
                        '</div>'
                        +
                        '</a></li>');
                }

                $('.my_messages_list_content').each((index, element) => new SimpleBar(element));
            },
            error: function (jqXhr, textStatus, errorThrown) {
                response = JSON.parse(jqXhr.responseText);

            },
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })
});
