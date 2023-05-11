var map;
var lat;
var lng;
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

        marker = addMarker(event.latLng, map, 'yellow');
        lat = marker.getPosition().lat();
        lng = marker.getPosition().lng();

        $('#lat').val(lat.toFixed(6));
        $('#lng').val(lng.toFixed(6));
    });

    var user_id = $('#user_id').val();

    $.ajax({
        url: '/panel/addresses/' + user_id,
        type: 'GET',
        async: true,
        dataType: 'json',
        success: function (data, textStatus, jQxhr) {
            response = JSON.parse(jQxhr.responseText);

            for(var i=0; i<response.data.length; i++)
            {
                if(response.data[i].lat != null && response.data[i].lon != null){
                    new google.maps.Marker({
                        position: { lat: response.data[i].lat, lng: response.data[i].lon },
                        label: response.data[i].name,
                        map: map,
                    });
                }
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
function addMarker(location, map, color) {
    // Add the marker at the clicked location, and add the next-available label
    // from the array of alphabetical characters.
    let url = "http://maps.google.com/mapfiles/ms/icons/";
    url += color + "-dot.png";

    return new google.maps.Marker({
        map: map,
        position: location,
        icon: {
            url: url
        }
    });
}
