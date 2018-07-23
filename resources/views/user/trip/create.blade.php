@extends('user.layouts.app_user')
@section('css')
<style type="text/css">
    #map {
        height: 500px;
    }
</style>
@endsection
@section('mapjs')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyiIshpnLak_30s9Z954mltbs97Iu4EpI" type="text/javascript"></script>
<script type="text/javascript">
    var directionsService;
    var directionsRenderer;
    var map;

    function initialize() {
        var position = new google.maps.LatLng(-8.05, -34.89);

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();
        map = new google.maps.Map($('#map')[0], {
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: position
        });


        directionsRenderer.setMap(map);

        google.maps.event.addListener(map, 'click', function(event) {
            addWayPointToRoute(event.latLng);
        });


    }


    var markers = [];
    var polylines = [];
    var isFirst = true;

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
                google.maps.event.addListener(marker, 'dragend', function() {
                    recalculateRoute(marker);
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
                var marker = new google.maps.Marker({
                    position: response.routes[0].legs[0].end_location,
                    map: map,
                    draggable: true,
                });
                markers.push(marker);
                showInfo(marker);
                marker.arrayIndex = markers.length - 1;

                google.maps.event.addListener(marker, 'dragend', function() {
                    recalculateRoute(marker);
                });


                var polyline = new google.maps.Polyline();
                var path = response.routes[0].overview_path;
                for (var x in path) {
                    polyline.getPath().push(path[x]);
                }
                polyline.setMap(map);
                polylines.push(polyline);



                google.maps.event.addListener(marker, 'dblclick', function() {
                    appendWayPoint(markers[0].position);
                });


                google.maps.event.addListener(marker, 'rightclick', function() {
                    deleteMarker(marker);
                });

            }
        });

    }

    function deleteMarker(marker) {

        marker.setMap(null);

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

    // function drawAgain() {
    // 		addWayPointToRoute(new google.maps.LatLng(-8.05,-34.89));
    // 		alert(markers.length);
    // 		//addWayPointToRoute( new google.maps.LatLng(-8.15,-34.89));
    // }

    function placeMarker(location) {
        var request = {
            origin: location,
            destination: location,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                var marker = new google.maps.Marker({
                    position: response.routes[0].legs[0].start_location,
                    map: map
                });
            }
        });
    }

    // function add_the_middle_waypoint(){

    // }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>

@endsection
@section('content')

<div class="container">
    <div class="row">


        <div class="col-md-12">
            <div class="card">

                <div class="card-body">

                    <br/>
                    <br/>
                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Create Trip In Map <small>Sessions</small></h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">Settings 1</a>
                                                </li>
                                                <li><a href="#">Settings 2</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div id="map">

                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
