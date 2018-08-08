
    function recalculateRoute(marker) { //recalculate the polyline to fit the new position of the dragged marker
        if (marker.arrayIndex > 0) { //its not the first so recalculate the route from previous to this marker
            polylines[marker.arrayIndex - 1].setMap(null);

            var request = {
                origin: markers[marker.arrayIndex - 1].position,
                destination: marker.position,
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
        }
        if (marker.arrayIndex < markers.length - 1) { //its not the last, so recalculate the route from this to next marker
            polylines[marker.arrayIndex].setMap(null);

            var request = {
                origin: marker.position,
                destination: markers[marker.arrayIndex + 1].position,
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
                    polylines[marker.arrayIndex] = polyline;
                }
            });
        }
    }