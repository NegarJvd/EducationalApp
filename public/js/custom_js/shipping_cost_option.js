var map;
var lat_longs = new Array();
var markers = new Array();
var drawingManager;

function initialize() {
    var iw = new google.maps.InfoWindow();

    var myLatlng = new google.maps.LatLng(38.03609422705547,46.3630139921252);
    var myOptions = {
        zoom: 14,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);

    $.ajax({
        url: '/panel/shipping_cost_option',
        type: 'GET',
        async: true,
        dataType: 'json',
        success: function (data, textStatus, jQxhr) {
            response = JSON.parse(jQxhr.responseText);

            var firstData = response.data;

            for(var i=0; i<firstData.length; i++){
                var triangleCoords = [];
                for(var j=0; j<firstData[i].coordinates_array.length; j++){
                    triangleCoords.push({ lat: parseFloat(firstData[i].coordinates_array[j][0]), lng: parseFloat(firstData[i].coordinates_array[j][1])});
                }
                var bermudaTriangle = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: "#fd7e14",
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: "rgba(253,126,20,0.62)",
                    fillOpacity: 0.35
                });

                bermudaTriangle.setMap(map);


                bermudaTriangle.addListener("mouseover", showArrays(firstData[i]));
                bermudaTriangle.addListener("click", showArrays(firstData[i]));

                infoWindow = new google.maps.InfoWindow();
            }

            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                },
                polygonOptions: {
                    editable: true
                }
            });
            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                var newShape = event.overlay;
                newShape.type = event.type;
            });

            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event) {
                // overlayClickListener(event.overlay);
                $('#coordinates').val(event.overlay.getPath().getArray());
            });

        },
        error: function (jqXhr, textStatus, errorThrown) {
            response = JSON.parse(jqXhr.responseText);
            swal("خطا!", response.message, "error");
        },
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

// function overlayClickListener(overlay) {
//     google.maps.event.addListener(overlay, "mouseup", function(event) {
//         $('#coordinates').val(overlay.getPath().getArray());
//     });
// }

function showArrays(area) {
    return function (event) {
        let contentString =
            '<div class="form-group pr-2 pl-2">' +
            '<h4>اطلاعات منطقه</h4><br>' +
            '<div class="text-dark">نام:</div>' +
            area.name +
            '<br>' +
            '<div class="text-dark">هزینه ارسال:</div>' +
            area.shipping_cost +
            'ريال' +
            '<br></div>'
        ;

        // Replace the info window's content and position.
        infoWindow.setContent(contentString);
        infoWindow.setPosition(event.latLng);
        infoWindow.open(map);
    }
}

// google.maps.event.addDomListener(window, 'load', initialize);

$(document).ready(function() {
    new AutoNumeric.multiple('.price',{
        unformatOnSubmit: true,
        decimalPlaces: 0
    });

    $('#save_shipping_cost').click(function() {
        var name = $('#name').val();
        var coordinates = $('#coordinates').val();
        var shipping_cost = $('#shipping_cost').val();
        shipping_cost = shipping_cost.replaceAll(',', '');

        $.ajax({
            url: '/panel/shipping_cost_option',
            type: 'POST',
            async: true,
            dataType: 'json',
            data: {
                'name' : name,
                'coordinates' : coordinates,
                'shipping_cost' : shipping_cost
            },
            success: function (data, textStatus, jQxhr) {
                Ladda.stopAll();

                response = JSON.parse(jQxhr.responseText);
                swal("ذخیره شد!", "منطقه مورد نظر با موفقت ثبت شد.", "success");

                var table_body = $('#shipping_cost_table_body');
                var str = '<tr>';
                str += '<td class="id">' + response.data.id + '</td>';
                str += '<td><input type="text" class="form-control name" value="' + response.data.name + '"></td>';
                str += '<td hidden>' + response.data.coordinates + '</td>';
                str += '<td><input type="text" class="form-control price new_price" value="' + response.data.shipping_cost + '"></td>';

                str += '<td>\n'+
                       '<i class="btn p-0 mdi mdi-square-edit-outline mdi-dark mdi-18px update_shipping_cost" title="ویرایش"></i>\n'+
                       '<i class="btn p-0 mdi mdi-trash-can-outline mdi-dark mdi-18px remove_shipping_cost" title="حذف"></i>\n'+
                       '</td>';

                str += '</tr>';

                table_body.append(str);


                new AutoNumeric.multiple('.new_price',{
                    unformatOnSubmit: true,
                    decimalPlaces: 0
                });

                $('#name').val('');
                $('#coordinates').val('');
                $('#shipping_cost').val(0);

                $('#map-canvas').html('');
                initialize();

            },
            error: function (jqXhr, textStatus, errorThrown) {
                Ladda.stopAll();
                response = JSON.parse(jqXhr.responseText);

                if(response.hasOwnProperty('errors')){
                    var error_message = "";

                    if(response.errors.hasOwnProperty('name')){
                        error_message += " انتخاب نام برای منطقه اجباریست. ";
                    }

                    if(response.errors.hasOwnProperty('shipping_cost')){
                        error_message += "  انتخاب هزینه ارسال برای منطقه اجباریست و این مقدار حداقل باید 0 ریال باشد. ";
                    }

                    if(response.errors.hasOwnProperty('coordinates')){
                        error_message += " انتخاب محدوده برای منطقه اجباریست. ";
                    }

                    swal("خطا!", error_message, "error");
                }else{
                    swal("خطا!", "مشکلی در ذخیره منطقه پیش آمده است.", "error");
                }
            },
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $('#shipping_cost_table_body').on('click', '.update_shipping_cost', function () {
        //loading
        var clicked_button = $(this);
        clicked_button.removeClass('mdi-square-edit-outline');
        clicked_button.addClass('mdi-loading mdi-spin');

        var td = $(this).parent();
        var tr = td.parent();

        var id = tr.find('.id').html();
        var name_input = tr.find('.name');
        var name = name_input.val();
        var shipping_cost_input = tr.find('.price');
        var shipping_cost = shipping_cost_input.val();
        shipping_cost = shipping_cost.replaceAll(',', '');

        if(confirm("برای ویرایش مطمئن هستید؟")){
            $.ajax({
                url: '/panel/shipping_cost_option/' + id,
                type: 'PUT',
                async: true,
                dataType: 'json',
                data: {
                    'name' : name,
                    'shipping_cost' : shipping_cost
                },
                success: function (data, textStatus, jQxhr) {
                    Ladda.stopAll();
                    clicked_button.addClass('mdi-square-edit-outline');
                    clicked_button.removeClass('mdi-loading mdi-spin');

                    response = JSON.parse(jQxhr.responseText);
                    swal("ذخیره شد!", "منطقه مورد نظر با موفقیت ویرایش شد.", "success");

                    name_input.val(response.data.name);
                    shipping_cost_input.val(response.data.shipping_cost);

                    $('#map-canvas').html('');
                    initialize();

                },
                error: function (jqXhr, textStatus, errorThrown) {
                    Ladda.stopAll();
                    clicked_button.addClass('mdi-square-edit-outline');
                    clicked_button.removeClass('mdi-loading mdi-spin');

                    response = JSON.parse(jqXhr.responseText);

                    if(response.hasOwnProperty('errors')){
                        var error_message = "";

                        if(response.errors.hasOwnProperty('name')){
                            error_message += " انتخاب نام برای منطقه اجباریست. ";
                        }

                        if(response.errors.hasOwnProperty('shipping_cost')){
                            error_message += "  انتخاب هزینه ارسال برای منطقه اجباریست و این مقدار حداقل باید 0 ریال باشد. ";
                        }

                        swal("خطا!", error_message, "error");
                    }else{
                        swal("خطا!", "مشکلی در ذخیره منطقه پیش آمده است.", "error");
                    }
                },
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    });

    $('#shipping_cost_table_body').on('click', '.remove_shipping_cost', function () {
        //loading
        var clicked_button = $(this);
        clicked_button.removeClass('mdi-trash-can-outline');
        clicked_button.addClass('mdi-loading mdi-spin');

        var td = $(this).parent();
        var tr = td.parent();

        var id = tr.find('.id').html();

        if(confirm("برای حذف مطمئن هستید؟")){
            $.ajax({
                url: '/panel/shipping_cost_option/' + id,
                type: 'DELETE',
                async: true,
                dataType: 'json',
                success: function (data, textStatus, jQxhr) {
                    Ladda.stopAll();

                    response = JSON.parse(jQxhr.responseText);
                    swal("ذخیره شد!", "منطقه مورد نظر با موفقیت حذف شد.", "success");

                    tr.remove();

                    $('#map-canvas').html('');
                    initialize();

                },
                error: function (jqXhr, textStatus, errorThrown) {
                    Ladda.stopAll();
                    clicked_button.addClass('mdi-trash-can-outline');
                    clicked_button.removeClass('mdi-loading mdi-spin');

                    response = JSON.parse(jqXhr.responseText);
                    swal("خطا!", response.message, "error");
                },
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
    });
});
