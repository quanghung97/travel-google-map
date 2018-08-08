function deleteMarker(marker) {

    marker.setMap(null);
    deleteRow(marker);
        if (marker.arrayIndex == markers.length - 1) {
        polylines[marker.arrayIndex - 1].setMap(null);
        polylines.length--;
        markers.length--;
    } else {

        for (var i = marker.arrayIndex; i < markers.length - 1; i++) {

            markers[i] = markers[i + 1];
            markers[i].arrayIndex--;

        }
        markers.length--;

        polylines[marker.arrayIndex - 1].setMap(null);
        polylines[marker.arrayIndex].setMap(null);

        var request = {
            origin: markers[marker.arrayIndex - 1].position,
            destination: markers[marker.arrayIndex].position,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };

        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                var polyline = new google.maps.Polyline();
                var path = response.routes[0].overview_path;
                for (var x in path) {
                    polyline.getPath().push(path[x]);
                }
                polyline.setMap(map);
                polylines[marker.arrayIndex - 1] = polyline;
            }
        });

        for (var i = marker.arrayIndex; i < polylines.length - 1; i++) {
            polylines[i] = polylines[i + 1];
        }
        polylines.length--;
    }

    for (var i = marker.arrayIndex; i < polylines.length - 1; i++) {
        polylines[i].setMap(map);
    }

}