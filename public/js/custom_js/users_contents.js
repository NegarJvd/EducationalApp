$(document).ready(function() {
    //showing options
    $('#contents_select').on('change', function () {
        $('#clusters_select').empty();
        $('#clusters_select').attr('disabled', 'disabled');
        $('#add_content').attr('disabled', 'disabled');

        if($(this).val() > 0){
            $.ajax({
                url: '/panel/get_each_contents_clusters_list/' + $(this).val(),
                method: 'GET',
                datatype: 'json',
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val(),
                    'Accept': 'application/json'
                },
                success: function (data) {
                    let list = data.data;
                    var options = "";

                    if(list.length > 0){
                        for(var i=0; i < list.length; i++){
                            options += '<option value="' + list[i].id + '">' + list[i].name + '</option>'
                        }

                        $('#clusters_select').append(options);
                        $('#clusters_select').removeAttr('disabled');
                        $('#add_content').removeAttr('disabled');
                    }else{
                        swal("هشدار!", "برای محتوای انتخاب شده، هیچ دسته بندی موجود نیست.", "warning");
                    }
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    let response = JSON.parse(jqXhr.responseText);
                    console.log(response)
                    swal("خطا!", response.message, "error");
                },

            });
        }
    });

    //adding contents for user
    $('#add_content').on('click', function () {
        let content_id = $('#contents_select').val();
        let cluster_id = $('#clusters_select').val();
        let user_id = $('#user_id').val();

        $.ajax({
            url: '/panel/add_content_for_user',
            type: 'POST',
            async: true,
            datatype: 'json',
            data: {
                content_id : content_id,
                cluster_id : cluster_id,
                user_id : user_id
            },
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val(),
                'Accept': 'application/json'
            },
            success: function (data) {
                let table_body = $('#contents_table_body');
                var tr = '<tr>'+
                    '<td>' + data.data.content_name + '</td>'+
                    '<td>' + data.data.cluster_name + '</td>'+
                    '<td>'+
                    '<input hidden value="' + data.data.cluster_id + '" class="cluster_id">' +
                    '<button type="button" class="btn p-0 view_actions" data-toggle="modal" data-target="#actions_charts" title="عملكرد"> <span class="mdi mdi-eye-outline mdi-dark mdi-18px"></span> </button>' +
                    '<button type="button" class="btn p-0 delete_content" title="حذف"> <span class="mdi mdi-trash-can-outline mdi-dark mdi-18px"></span> </button>'+
                    '</td>'+
                    '</tr>';

                table_body.append(tr);
            },
            error: function (jqXhr, textStatus, errorThrown) {
                let response = JSON.parse(jqXhr.responseText);
                swal("خطا!", response.message, "error");
            },

        });
    });

    //deleting content for user
    $(document).on('click', '.delete_content', function () {
        if(confirm('با حذف این رکورد، مراجه کننده قادر به دیدن ویدیو های این محتوا نخواهد بود. ادامه میدهید؟')){
            let td = $(this).parent();
            let cluster_id = td.find('.cluster_id').val();
            let user_id = $('#user_id').val();

            $.ajax({
                url: '/panel/delete_content_for_user',
                type: 'DELETE',
                async: true,
                datatype: 'json',
                data: {
                    cluster_id : cluster_id,
                    user_id : user_id
                },
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val(),
                    'Accept': 'application/json'
                },
                success: function (data) {
                    let tr = td.parent();
                    tr.remove();

                    swal("موفقیت آمیز:)", data.message, "success");
                },
                error: function (jqXhr, textStatus, errorThrown) {
                    let response = JSON.parse(jqXhr.responseText);
                    swal("خطا!", response.message, "error");
                },

            });
        }
    });
});
