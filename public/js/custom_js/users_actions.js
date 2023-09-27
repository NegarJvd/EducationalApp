$(document).ready(function() {
    $(document).on('click', '.submit_action', function () {
        let td = $(this).parent();
        let cluster_id = td.find('.cluster_id').val();
        let content_name = td.parent().find('.content_name').text();
        let cluster_name = td.parent().find('.cluster_name').text();

        //fix cluster id for filtering
        $('#content_name_2').text(content_name).change();
        $('#cluster_name_2').text(cluster_name).change();
        $('#cluster_id').val(cluster_id).change();

        //clean previous data
        $('#action_count').val('');
        $('#action_result').val('');
        $('#submit_action_success_messages').html('');
        $('#submit_action_danger_messages').html('');
        $('#submit_action_success_messages').attr('hidden', 'hidden');
        $('#submit_action_danger_messages').attr('hidden', 'hidden');

        //showing options
        $.ajax({
            url: '/panel/get_each_clusters_steps_list/' + cluster_id,
            method: 'GET',
            datatype: 'json',
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val(),
                'Accept': 'application/json'
            },
            success: function (data) {
                $('#action_step_id').html('');
                let list = data.data;
                var options = "";

                if(list.length > 0){
                    for(var i=0; i < list.length; i++){
                        options += '<option value="' + list[i].id + '">' + list[i].number + '</option>'
                    }

                    $('#action_step_id').append(options);
                }else{
                    swal("هشدار!", "برای محتوا و دسته بندی انتخاب شده، هیچ مرحله ای موجود نیست.", "warning");
                }
            },
            error: function (jqXhr, textStatus, errorThrown) {
                let response = JSON.parse(jqXhr.responseText);
                swal("خطا!", response.message, "error");
            },

        });
    });

    $('#submit_action_button').on('click', function () {
        let user_id = $('#user_id').val();
        let step_id = $('#action_step_id').val();
        let count = $('#action_count').val();
        let result = $('#action_result').val();

        $.ajax({
            url: '/panel/submit_action',
            method: 'POST',
            datatype: 'json',
            data: {
                user_id : user_id,
                step_id : step_id,
                count : count,
                result : result,
            },
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val(),
                'Accept': 'application/json'
            },
            success: function (data) {
                let message = data.message;
                let message_p = '<i class="mdi mdi-check-decagram"></i>' + message + '<br>';
                $('#submit_action_success_messages').append(message_p);
                $('#submit_action_success_messages').removeAttr('hidden');
                $('#submit_action_danger_messages').attr('hidden', 'hidden');
            },
            error: function (jqXhr, textStatus, errorThrown) {
                $('#submit_action_danger_messages').html('');
                let message = JSON.parse(jqXhr.responseText).message;
                let message_p = '<i class="mdi mdi-alert-circle"></i>' + message;
                $('#submit_action_danger_messages').append(message_p);
                $('#submit_action_danger_messages').removeAttr('hidden');
            },

        });
    });
});
