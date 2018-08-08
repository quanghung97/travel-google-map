function showInfo(marker) {
    google.maps.event.addListener(marker, 'click', function(event) {
        var geocoder = new google.maps.Geocoder;
        var infowindow = new google.maps.InfoWindow;
        geocoder.geocode({
            'location': event.latLng
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                if (results && results.length > 0) {
                    marker.formatted_address = results[0].formatted_address;
                    //updateMarkerAddress(results[0].formatted_address);
                } else {
                    marker.formatted_address = 'Cannot determine address at this location.';
                    //updateMarkerAddress('Cannot determine address at this location.');
                }
                infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));

            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }

        });
        //infowindow.setContent("double click to delete this waypoint");
        infowindow.open(map, this);
        //updateMarkerPosition(event.latLng);
        google.maps.event.addListener(marker, "dragstart", function() {
            infowindow.close();
        });
    });
}