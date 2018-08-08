function addWayPointToRoute(location) {
    if (isFirst) {
        addFirstWayPoint(location);
        isFirst = false;
    } else {
        appendWayPoint(location);
    }
}

function addFirstWayPoint(location) {
    var request = {
        origin: location,
        destination: location,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            var marker = new google.maps.Marker({
                position: response.routes[0].legs[0].start_location,
                map: map,
                draggable: true,
                icon: new google.maps.MarkerImage(
                    'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                )
            });
            marker.arrayIndex = 0;
            markers.push(marker);
            showInfo(marker);
            appendTable(marker);
            google.maps.event.addListener(marker, 'dragend', function() {
                recalculateRoute(marker);
                updateRow(marker);
            });
        }
    });
}

function appendWayPoint(location) {
    var request = {
        origin: markers[markers.length - 1].position,
        destination: location,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };

    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            if (location == markers[0].position) {
                    var marker = new google.maps.Marker({
                        position: response.routes[0].legs[0].end_location,
                        map: map,
                        draggable: false,
                        icon: new google.maps.MarkerImage(
                            'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                        )
                    });

            } else {
                    var marker = new google.maps.Marker({
                        position: response.routes[0].legs[0].end_location,
                        map: map,
                        draggable: true,
                });
                appendTable(marker);
            }
            // appendTable(marker);
            markers.push(marker);
            showInfo(marker);
            marker.arrayIndex = markers.length - 1;

            google.maps.event.addListener(marker, 'dragend', function() {
                    recalculateRoute(marker);
                    // createTable();
                    updateRow(marker);
            });


            var polyline = new google.maps.Polyline();
            var path = response.routes[0].overview_path;
            for (var x in path) {
                polyline.getPath().push(path[x]);
            }
            polyline.setMap(map);
            polylines.push(polyline);

            google.maps.event.addListener(marker, 'rightclick', function() {
                if(!end_plan){
                    // deleteRow(marker);
                    deleteMarker(marker);
                }
            });

            google.maps.event.addListener(marker, 'mouseover', function() {
                console.log(marker.arrayIndex);
            });
        }
    });

}