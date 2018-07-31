@extends('user.layouts.app_user')
@section('css')
<style type="text/css">
    #map {
        height: 330px;
    }

    .context_menu {
        background-color: white;
        border: 1px solid gray;
    }

    .context_menu_item {
        padding: 3px 6px;
    }

    .context_menu_item:hover {
        background-color: #CCCCCC;
    }

    .context_menu_separator {
        background-color: gray;
        height: 1px;
        margin: 0;
        padding: 0;
    }

    th {
        text-align: center;
    }
    .table td{
        position:relative;
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

        drawAgain();


    }


    var locations = [
        @foreach($trip->wayPoints as $item)
        [{{ $item->id }}, {{$item->lat}}, {{$item->lng}}],
        @endforeach
        [{{ $item->id }}, {{$trip->wayPoints[0]->lat}}, {{$trip->wayPoints[0]->lng}}]
    ];
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
                    draggable: false,
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
                        draggable: false,
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

    function drawAgain() {
        //addFirstWayPoint(new google.maps.LatLng(locations[0][1], locations[0][2]));
        var request = {
            origin: new google.maps.LatLng(locations[0][1], locations[0][2]),
            destination: new google.maps.LatLng(locations[0][1], locations[0][2]),
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                var marker = new google.maps.Marker({
                    position: response.routes[0].legs[0].start_location,
                    map: map,
                    draggable: false,
                    icon: new google.maps.MarkerImage(
                        'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                    )
                });
                marker.arrayIndex = 0;
                markers.push(marker);
                showInfo(marker);
            }
        });
        isFirst = false;
        for (var i = 0; i < locations.length - 1; i++) {
            var request = {
                origin: new google.maps.LatLng(locations[i][1], locations[i][2]),
                destination: new google.maps.LatLng(locations[i + 1][1], locations[i + 1][2]),
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            };
            directionsService.route(request, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    var marker = new google.maps.Marker({
                        position: response.routes[0].legs[0].end_location,
                        map: map,
                        draggable: false,
                    });
                    markers.push(marker);
                    showInfo(marker);
                    marker.arrayIndex = markers.length - 1;


                    var polyline = new google.maps.Polyline();
                    var path = response.routes[0].overview_path;
                    for (var x in path) {
                        polyline.getPath().push(path[x]);
                    }
                    polyline.setMap(map);
                    polylines.push(polyline);


                }
            });
        }




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
                    @if(session('message'))
                        <div class="alert alert-success">
                            <strong>{{session('message')}}</strong>
                        </div>
                    @endif

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Show Trip In Map <small>Sessions</small></h2>
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
                                <div class="x_content">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <h3>Tên chuyến đi: {{$trip->name}}</h3>
                                        <br>
                                    </div>
                                    <div class="col-md-4">
                                        <h3>Ảnh cover của chuyến đi:</h3>
                                    </div>
                                    <div class="col-md-8">
                                        <h3>Map: </h3>
                                    </div>
                                    <div class="col-md-4 col-sm-12">


                                            <img src="{{asset($trip->image_url)}}" style="width:100%" alt="Ảnh Cover">

                                        <br>
                                    </div>

                                    <div id="map" class="col-md-8 col-sm-12">
                                    </div>

                                        <div class="col-md-12">
                                            <br>
                                            <br>
                                        </div>
                                        <div class="col-md-12 col-sm-12">
                                        <table id="listwp" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>

                                                    <th>Điểm Xuất Phát</th>
                                                    <th>Thời gian xuất phát</th>
                                                    <th>Điểm đến</th>
                                                    <th>Thời gian tới</th>
                                                    <th>Phương tiện</th>
                                                    <th>Hoạt Động</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @for($i = 0; $i < count($trip->wayPoints)-1; $i++)
                                                    @if($trip->wayPoints[$i]->action == 'moving')
                                                        <tr>
                                                            <td>{{ $trip->wayPoints[$i]->address }}</td>
                                                            <td>
                                                                  {{  date('d-m-Y H:i:s',strtotime($trip->wayPoints[$i]->leave_time))  }}
                                                            </td>
                                                            <td>{{ $trip->wayPoints[$i+1]->address }}</td>
                                                            <td>
                                                                    {{  date('d-m-Y H:i:s',strtotime( $trip->wayPoints[$i+1]->arrival_time ))  }}
                                                            </td>

                                                            <td>{{ $trip->wayPoints[$i]->vehicle }}</td>
                                                            <td>
                                                                Di chuyển
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr>
                                                            <td>{{ $trip->wayPoints[$i]->address }}</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>
                                                                Vui chơi

                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ $trip->wayPoints[$i]->address }}</td>
                                                            <td>
                                                                    {{  date('d-m-Y H:i:s',strtotime($trip->wayPoints[$i]->leave_time))  }}
                                                            </td>
                                                            <td>{{ $trip->wayPoints[$i+1]->address }}</td>
                                                            <td>
                                                                    {{  date('d-m-Y H:i:s',strtotime( $trip->wayPoints[$i+1]->arrival_time ))  }}
                                                            </td>

                                                            <td>{{ $trip->wayPoints[$i]->vehicle }}</td>
                                                            <td>
                                                                Di chuyển
                                                            </td>
                                                        </tr>
                                                    @endif

                                                @endfor
                                            @if($trip->wayPoints[count($trip->wayPoints)-1]->action == 'moving')
                                                <tr>
                                                    <td>{{ $trip->wayPoints[count($trip->wayPoints)-1]->address }}</td>
                                                    <td>
                                                            {{  date('d-m-Y H:i:s',strtotime($trip->wayPoints[count($trip->wayPoints)-1]->leave_time ))  }}
                                                    </td>
                                                    <td>{{ $trip->wayPoints[0]->address }}</td>
                                                    <td>
                                                            {{  date('d-m-Y H:i:s',strtotime(  $trip->wayPoints[0]->arrival_time ))  }}
                                                    </td>

                                                    <td>{{ $trip->wayPoints[count($trip->wayPoints)-1]->vehicle }}</td>
                                                    <td>
                                                        Di chuyển
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>{{ $trip->wayPoints[count($trip->wayPoints)-1]->address }}</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>
                                                       Vui chơi

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $trip->wayPoints[count($trip->wayPoints)-1]->address }}</td>
                                                    <td>
                                                            {{  date('d-m-Y H:i:s',strtotime(  $trip->wayPoints[count($trip->wayPoints)-1]->leave_time  ))  }}
                                                    </td>
                                                    <td>{{ $trip->wayPoints[0]->address }}</td>
                                                    <td>
                                                            {{  date('d-m-Y H:i:s',strtotime(   $trip->wayPoints[0]->arrival_time   ))  }}
                                                    </td>
                                                    <td>{{ $trip->wayPoints[count($trip->wayPoints)-1]->vehicle }}</td>
                                                    <td>
                                                        Di chuyển
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                  @cannot('updateTrip', $trip)
                                    <a style="float:right" href=""><button class="btn btn-danger btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Join</button></a>
                                    @can('follow', $trip)
                                      <a style="float:right" href="{{ url('/user/trip/follow/unfollow/' . $trip->id) }}"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Unfollow</button></a>
                                    @else
                                      <a style="float:right" href="{{ url('/user/trip/follow/follow/' . $trip->id) }}"><button class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Follow</button></a>
                                    @endcan

                                  @endcannot

                                </div>
                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Show List Joiner <small>Sessions</small></h2>
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
                        <div class="x_content">
                                <table id="listwp" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên</th>
                                                    <th>Chức năng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    @foreach($join as $u)
                                                        <tr>
                                                                <td>{{ $loop->iteration or $item->id }}</td>
                                                                <td><a href="{{url('user/userProfile/profile/'.$u->id)}}"> {{ $u->name}}</a></td>
                                                                <td>
                                                                <a href="{{url('user/userProfile/profile/'.$u->id)}}"> <input type="button" class="btn btn-info" value="Xem trang cá nhân"></a>
                                                                @if(Auth::user()->id == $trip->owner->id)
                                                                <a href="{{url('user/trip/join/out/'.$u->id.'/'.$trip->id)}}"><input type="button" class="btn btn-danger" value="Kích thành viên này"></a>
                                                                @endif
                                                                
                                                                </td>
                                                        </tr>
                                                    @endforeach
                                            </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="clearfix"></div>

            @if(Auth::user()->id == $trip->owner->id)
               
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Show List waiting request <small>Sessions</small></h2>
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
                        <div class="x_content">
                                <table id="listwp" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên</th>
                                                    <th>Chức năng</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    @foreach($verify as $u)
                                                        <tr>
                                                                <td>{{ $loop->iteration or $item->id }}</td>
                                                                <td><a href="{{url('user/userProfile/profile/'.$u->id)}}"> {{ $u->name}}</a></td>
                                                                <td>
                                                                    <a href="{{url('user/userProfile/profile/'.$u->id)}}"> <input type="button" class="btn btn-info" value="Xem trang cá nhân"></a>
                                                                    
                                                                    <a href="{{url('user/trip/verify/accept/'.$u->id.'/'.$trip->id)}}"> <input type="button" class="btn btn-success" value="Chấp thuận"></a>
                                                                    <a href="{{url('user/trip/verify/deny/'.$u->id.'/'.$trip->id)}}"> <input type="button" class="btn btn-danger" value="Loại bỏ"></a>
                                                                   
                                                                </td>
                                                        </tr>
                                                    @endforeach
                                            </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
        
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

    function createTable() {
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
                                '<tr>' + // need to change closing tag to an opening `<tr>` tag.
                                '<td name="order_num' + i + '">' + (i + 1) + '<input type="hidden" name="order_num' + i + '" value="' + i + '">' + '</td>' +
                                '<td>' + results[0].geometry.location.lat() + '<input type="hidden" name="lat' + i + '" value="' + results[0].geometry.location.lat() + '">' + '</td>' +
                                '<td name="lng' + i + '">' + results[0].geometry.location.lng() + '<input type="hidden" name="lng' + i + '" value="' + results[0].geometry.location.lng() + '">' + '</td>' +
                                '<td name="address' + i + '">' + results[0].formatted_address + '<input type="hidden" name="address' + i + '" value="' + results[0].formatted_address + '">' + '</td>' +
                                '</tr>');
                        } else {
                            console.log('query limited');
                        }
                    });
                }, 3000 * i);
            })(i);
            i++;

        }
        setTimeout(function() {
            $("#submit").removeAttr("disabled");
        }, 3000 * markers.length);


    }
</script>

@endsection
