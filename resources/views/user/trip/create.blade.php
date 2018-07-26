@extends('user.layouts.app_user')
@section('css')
<style type="text/css">
    #map {
        height: 500px;
    }

    .context_menu{
	    background-color:white;
	    border:1px solid gray;
    }
    .context_menu_item{
	    padding:3px 6px;
    }
    .context_menu_item:hover{
	    background-color:#CCCCCC;
    }
    .context_menu_separator{
	    background-color:gray;
	    height:1px;
	    margin:0;
	    padding:0;
    }
    th {
        text-align: center;
    }
</style>
@endsection
@section('mapjs')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyiIshpnLak_30s9Z954mltbs97Iu4EpI" type="text/javascript"></script>
<script src="{{asset('js/ContextMenu.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    var directionsService;
    var directionsRenderer;
    var map;

    function initialize() {
        var position = new google.maps.LatLng(21.016801, 105.784221);

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

        var contextMenuOptions={};
	    contextMenuOptions.classNames={menu:'context_menu', menuSeparator:'context_menu_separator'};

	    //	create an array of ContextMenuItem objects
	    //	an 'id' is defined for each of the four directions related items
	    var menuItems = [];
			//	a menuItem with no properties will be rendered as a separator
			menuItems.push({
				className: 'context_menu_item',
				eventName: 'end_plan',
				label: 'End plan'
			});
			menuItems.push({});
			menuItems.push({
				className: 'context_menu_item',
				eventName: 'zoom_in_click',
				label: 'Zoom in'
			});
			menuItems.push({
				className: 'context_menu_item',
				eventName: 'zoom_out_click',
				label: 'Zoom out'
			});
			menuItems.push({});
			menuItems.push({
				className: 'context_menu_item',
				eventName: 'center_map_click',
				label: 'Center map here'
			});
	    contextMenuOptions.menuItems=menuItems;

	    var contextMenu=new ContextMenu(map, contextMenuOptions);

	    google.maps.event.addListener(map, 'rightclick', function(mouseEvent){
		    contextMenu.show(mouseEvent.latLng);
	    });

        google.maps.event.addListener(contextMenu, 'menu_item_selected', function (latLng, eventName) {
				//	latLng is the position of the ContextMenu
				//	eventName is the eventName defined for the clicked ContextMenuItem in the ContextMenuOptions
				switch (eventName) {
					case 'zoom_in_click':
						map.setZoom(map.getZoom() + 1);
						break;
					case 'zoom_out_click':
						map.setZoom(map.getZoom() - 1);
						break;
					case 'center_map_click':
						map.panTo(latLng);
						break;
					case 'end_plan':
                        addWayPointToRoute(markers[0].position);
                        createTable();
						break;
				}
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
				}
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
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    
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
                                            <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="">Settings 1</a>
                                                </li>
                                                <li><a href="">Settings 2</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <form action="{{url('user/trip')}}" enctype="multipart/form-data" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-6">
                                                <h3>Nhập tên chuyến đi</h3>
                                                <input type="text" class="form-control" name="name">
                                                <br>
                                        </div>
                                        <div class="col-md-6">
                                                <h3>Chọn ảnh cover của chuyến đi</h3>
                                                <input type="file" class="form-control" name="file">
                                                <br>
                                        </div>

                                        <div id="map" class="col-md-6">
                                        </div>

                                    {{-- <a id="cretrip" class="btn btn-app">
                                        <i class="fa fa-plus"></i> Create Trip
                                    </a> --}}
                                    <div class="col-md-6">
                                        <table id="listwp" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>lat</th>
                                                    <th>lng</th>
                                                    <th>address</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="submit" id="submit" disabled="disabled" class="btn btn-danger" style="float:right">Tạo kế hoạch</button>
                                </form>
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

@section('js')
<script type="text/javascript">
    // $(document).ready(function() {
    //     var listaddress = [];
    //     $("#cretrip").click(function() {
    //         //var table = ;


    //         //$("#listwp").append('<div class="module_holder"><div class="module_item"><img src="images/i-5.png" alt="Sweep Stakes"><br>sendSMS</div></div>');

    //         var i = 0;
    //         while (i < markers.length) {

    //             (function(i) {
    //                 setTimeout(function() {
    //                     var geocoderr = new google.maps.Geocoder;
    //                     results = null;
    //                     geocoderr.geocode({
    //                         'location': markers[i].position
    //                     }, function(results, status) {
    //                         if (status == google.maps.GeocoderStatus.OK) {
    //                             // var table = $("#datatable").DataTable();
    //                             // table.row.add([
    //                             //     results[0].geometry.location.lat(),
    //                             //     results[0].geometry.location.lng(),
    //                             //     results[0].formatted_address,
    //                             // ]).draw();
    //                             // var address = results[0].formatted_address;
    //                             // $('#listwp').append(address + "<br>");
    //                             // address = '';
    //                             $('#listwp > tbody:last-child').append(
    //                                 '<tr>' // need to change closing tag to an opening `<tr>` tag.
    //                                  +
    //                                  '<td name="ordernum'+i+'">' + (i+1) + '</td>' +
    //                                 '<td name="lat'+i+'">' + results[0].geometry.location.lat() + '</td>' +
    //                                 '<td name="lng'+i+'">' + results[0].geometry.location.lng() + '</td>' +
    //                                 '<td name="address'+i+'">' + results[0].formatted_address + '</td>'
    //                                  +
    //                                 '</tr>');

    //                         } else {
    //                             console.log('query limited');
    //                         }

    //                     });
    //                 }, 3000 * i);
    //             })(i);

    //             i++;
    //         }

            //infowindow.setContent("double click to delete this waypoint");
            //infowindow.open(map, this);
            //updateMarkerPosition(event.latLng);

            //console.log(listaddress[0]);
            //console.log(markers[i].getPosition().lat());
            //using ajax to send to controller php
            //console.log(listaddress[i]);


    //     });

    // });

    function createTable(){
            var i = 0;
            while (i < markers.length) {

                (function(i) {
                    setTimeout(function() {
                        var geocoderr = new google.maps.Geocoder;
                        results = null;
                        geocoderr.geocode({
                            'location': markers[i].position
                        }, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                $('#listwp > tbody:last-child').append(
                                    '<tr>' +// need to change closing tag to an opening `<tr>` tag.
                                    '<td name="order_num'+i+'">' + (i+1) +'<input type="hidden" name="order_num'+i+'" value="'+i+'">' + '</td>' +
                                    '<td>'+ results[0].geometry.location.lat()+'<input type="hidden" name="lat'+i+'" value="'+results[0].geometry.location.lat()+'">'+ '</td>' +
                                    '<td name="lng'+i+'">' + results[0].geometry.location.lng() + '<input type="hidden" name="lng'+i+'" value="'+results[0].geometry.location.lng()+'">'+'</td>' +
                                    '<td name="address'+i+'">' + results[0].formatted_address + '<input type="hidden" name="address'+i+'" value="'+results[0].formatted_address+'">'+'</td>' +
                                    '</tr>');
                            } else {
                                console.log('query limited');
                            }
                        });
                    }, 3000 * i);
                })(i);
                i++;

            }
            setTimeout(function(){
                $("#submit").removeAttr("disabled");
            },3000*markers.length);


    }
</script>
@endsection
