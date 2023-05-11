var map;
var lat;
var lng;
var polygons = [];
var marker = null;

function initialize() {
    var iw = new google.maps.InfoWindow();

    var myLatlng = new google.maps.LatLng(38.03609422705547,46.3630139921252);
    var myOptions = {
        zoom: 14,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);

    // This event listener calls addMarker() when the map is clicked.
    google.maps.event.addListener(map, "click", (event) => {
        if(marker != null) marker.setMap(null);

        marker = addMarker(event.latLng, map);
        lat = marker.getPosition().lat();
        lng = marker.getPosition().lng();
    });

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

                // bermudaTriangle.setMap(map);

                polygons.push([bermudaTriangle, firstData[i]]);
            }
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

// Adds a marker to the map.
function addMarker(location, map) {
    // Add the marker at the clicked location, and add the next-available label
    // from the array of alphabetical characters.
    return new google.maps.Marker({
        position: location,
        map: map,
    });
}

$(document).ready(function() {

    $('#save_new_address_info').on('click', function () {
        var address = $('#address_text').val();
        var user_id = $('.select_user').val();

        $.ajax({
            url: '/panel/addresses',
            type: 'POST',
            async: true,
            dataType: 'json',
            data : {
                'user_id' : user_id,
                'address' : address,
                'lat' : lat.toFixed(6),
                'lon' : lng.toFixed(6),
            },
            success: function (data, textStatus, jQxhr) {
                response = JSON.parse(jQxhr.responseText);

                var div = $('#address_div');

                div.html('<label for="address">آدرس:</label>');

                var str = '';
                if (response.data.length > 0){
                    str = '<div class="input-group">\n' +
                        '                <div class="input-group-prepend">\n' +
                        '                     <span class="btn btn-outline-secondary" title="افزودن آدرس به کاربر" data-toggle="modal" data-target="#create_address_form">\n' +
                        '                            <i class="mdi mdi-map-marker-plus"></i>\n' +
                        '                     </span>\n' +
                        '                </div>';

                    str += '<select id="address" class="form-control" name="address">';
                    str += '<option value=""></option>';

                    for(var i =0; i < response.data.length; i++){
                        if(i == response.data.length - 1) {
                            str += '<option value="'+ response.data[i].address +'" lat="'+ response.data[i].lat +'" lon="'+ response.data[i].lon +'" selected>'+ response.data[i].address +'</option>';
                        }else{
                            str += '<option value="'+ response.data[i].address +'" lat="'+ response.data[i].lat +'" lon="'+ response.data[i].lon +'">'+ response.data[i].address +'</option>';
                        }
                    }

                    str += '</select></div>';

                }else{
                    str += '<button type="button" class="form-control btn btn-outline-primary" data-toggle="modal" data-target="#create_address_form"><i class="mdi mdi-map-marker-plus mr-2"></i>افزودن آدرس به کاربر </button>';
                }

                div.append(str);

                swal("عملیات موفقیت آمیز بود!", response.message, "success");

                $('#create_address_form').modal('hide');
                $('#address_text').val('');
                initialize();
                $('#address_div').find('#address').trigger('change');
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
    });

    $('#address_div').on('change', '#address', function () {
        var select_lat = $(this).find(':selected').attr('lat');
        var select_lng = $(this).find(':selected').attr('lon');

        var location = new google.maps.LatLng(select_lat, select_lng);

        var in_polygon = false;

        var shipping_cost_input = $('#shipping_cost');
        for(var j=0; j<polygons.length; j++){
            if(google.maps.geometry.poly.containsLocation(location, polygons[j][0]))
            {
                shipping_cost_input.val(polygons[j][1].shipping_cost).trigger('change');
                in_polygon = true;
            }
        }

        if(!in_polygon){
            shipping_cost_input.val(0).trigger('change');
            swal("هشدار!", "آدرس انتخاب شده در مناطق انتخاب شده نیست. هزینه پیک را وارد کنید.", "warning");
        }
    });
});
