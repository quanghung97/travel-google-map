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

    /* Outer */
.popup {
	width:100%;
	height:100%;
	display:none;
	position:fixed;
	top:0px;
	left:0px;
	background:rgba(0,0,0,0.75);
}

/* Inner */
.popup-inner {
	max-width:700px;
	width:90%;
	padding:40px;
	position:absolute;
	top:50%;
	left:50%;
	-webkit-transform:translate(-50%, -50%);
	transform:translate(-50%, -50%);
	box-shadow:0px 2px 6px rgba(0,0,0,1);
	border-radius:3px;
	background:#fff;
}

/* Close Button */
.popup-close {
	width:30px;
	height:30px;
	padding-top:4px;
	display:inline-block;
	position:absolute;
	top:0px;
	right:0px;
	transition:ease 0.25s all;
	-webkit-transform:translate(50%, -50%);
	transform:translate(50%, -50%);
	border-radius:1000px;
	background:rgba(0,0,0,0.8);
	font-family:Arial, Sans-Serif;
	font-size:20px;
	text-align:center;
	line-height:100%;
	color:#fff;
}

.popup-close:hover {
	-webkit-transform:translate(50%, -50%) rotate(180deg);
	transform:translate(50%, -50%) rotate(180deg);
	background:rgba(0,0,0,1);
	text-decoration:none;
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
                                    @can('ablePlan', $trip)
                                  @cannot('updateTrip', $trip)

                                    @can('follow', $trip)
                                      <a style="float:right" href="{{ url('/user/trip/follow/unfollow/' . $trip->id) }}"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Unfollow</button></a>
                                    @else
                                      <a style="float:right" href="{{ url('/user/trip/follow/follow/' . $trip->id) }}"><button class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Follow</button></a>
                                    @endcan
                                    @can('joinAble', $trip)
                                      <a style="float:right" href="{{url('user/trip/verify/verify/'.$trip->id)}}"><button class="btn btn-danger btn-sm"><i class="fa fa-group"></i> Join</button></a>
                                    @endcan
                                    @can('join', $trip)
                                      <a style="float:right" href="{{url('user/trip/join/unjoin/'.$trip->id)}}"><button class="btn btn-danger btn-sm"><i class="fa fa-group"></i> Unjoin</button></a>
                                    @endcan
                                    @can('verify', $trip)
                                      <a style="float:right" href="{{url('user/trip/verify/unverify/'.$trip->id)}}"><button class="btn btn-danger btn-sm"><i class="fa fa-group"></i> Unverify</button></a>
                                    @endcan
                                  @endcannot
                              @endcan
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
                                                                @can('updateTrip', $trip)
                                                                <a href="{{url('user/trip/join/out/'.$u->id.'/'.$trip->id)}}"><input type="button" class="btn btn-danger" value="Kích thành viên này"></a>
                                                            @endcan

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
            @can('ablePlan', $trip)
            @can('updateTrip', $trip)

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
          @endcan
      @endcan
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Show List Comment <small>Sessions</small></h2>
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
                <div class="comment-list">
                    @foreach($trip->comments as $comment)
                        <div class="row">
                            <div class="col-md-1">
                                <a href="{{url('user/userProfile/profile/'.$comment->user->id)}}">
                                <img style="border-radius: 50%; width:50px; height:50px;" src="{{asset($comment->user->g_avatar_url)}}" alt="Avatar"></a>
                            </div>


                            <div class="col-md-11">


                                <div style="border: 1px solid; width:90%; padding:10px; margin:10px; border-radius: 15px; background-color:#eff1f3">
                                    <a href="{{url('user/userProfile/profile/'.$comment->user->id)}}"><strong>{{$comment->user->name}}</strong></a>
                                    {{$comment->content}}
                                </div>
                                <div style="margin:10px; width:90%">
                                    <input type="button" style="background-color:white; border:none;" onclick="addReply({{$comment->id}})" value="Reply">
                                    <span style="float:right; font-size:11px;" >{{$comment->address}}</span>
                                </div>

                                    <div id="reply">


                        {{-- reply to comment --}}

                        @foreach($comment->comments as $reply)
                            <div class="row">
                                <div class="col-md-1">
                                <a href="{{url('user/userProfile/profile/'.$reply->user->id)}}">
                                <img style="border-radius: 50%; width:50px; height:50px;" src="{{asset($reply->user->g_avatar_url)}}" alt="Avatar"></a>
                                </div>
                                <div class="col-md-11">
                                    <div style="border: 1px solid; width:90%; padding:11px; border-radius: 15px; background-color:#eff1f3">
                                    <a href="{{url('user/userProfile/profile/'.$reply->user->id)}}"><strong>{{$reply->user->name}}</strong></a>
                                    {{$reply->content}}
                                </div>
                                <br>
                                </div>
                            </div>

                        @endforeach
                        <div class="row">
                            <div class="col-md-1">
                            <img class="reply-form-{{$comment->id}}" style="border-radius: 50%; width:50px; height:50px; display:none" src="{{asset(Auth::user()->g_avatar_url)}}" alt="Avatar">
                        </div>
                        <div class="col-md-11">
                            <form class="reply-form-{{$comment->id}}" method="post" action="{{route('replycomment.store', $comment->id)}}" style="display:none" >
                                      {{ csrf_field() }}
                                  <input type="hidden" name="trip_id" value="{{ $trip->id }}" >
                                  <input type="hidden" name="user_address" class="user_address_input">
                                  <div class="row" style="padding: 10px;">
                                      <div class="form-group">

                                    <input type="text" style="background-color:#eff1f3; border-radius: 15px; height:50px; width:90%;" class="form-control" name="content" placeholder="reply.........">

                                      </div>
                                  </div>
                              <div class="row" style="padding: 0 10px 0 10px;">
                                  <div class="form-group">
                                      <input style="display:none" type="submit" class="btn btn-primary" value="Reply">
                                  </div>
                              </div>
                              </form>
                        </div>
                        </div>


                        </div>
                        </div>

                        </div>


                    @endforeach
                </div>
            <form id="comment-form-{{$trip->id}}" method="post" action="{{route('tripcomment.store', $trip->id)}}">
                                {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" >
                            <input type="hidden" name="user_address" class="user_address_input">
                            <p id="user_address"></p>
                            <div class="row" style="padding: 10px;">

                                <div class="form-group">
                                    <span><a class="fa fa-search" data-popup-open="popup-1" href="#"> Check in</a></span>
                                    
                                    
                            <input type="text" style="background-color:#eff1f3; border-radius: 15px" class="form-control" name="content" placeholder="Viết bình luận............">
                                    <br>
                            <div id="results"></div>
                            </div>
                            </div>
                        <div class="row" style="padding: 0 10px 0 10px;">
                            <div class="form-group">
                                <input style="display:none" type="submit" class="btn btn-primary" style="width: 100%;" value="Gửi bình luận">
                            </div>
                        </div>
                        </form>

            </div>
        </div>
    </div>
</div>




<div class="popup" data-popup="popup-1">
	<div class="popup-inner">
        <h2>Chụp ảnh sefie</h2>
        
        
        <form>
            <div id="my_camera"></div>
		    <input type="button" value="Take Snapshot" onClick="take_snapshot()">
        </form>
		<a class="popup-close" data-popup-close="popup-1" href="#">x</a>
	</div>
</div>
            

</div>
@endsection

@section('js')

<script type="text/javascript">
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

<script>
    function addReply(id){
        $('.reply-form-'+id).toggle();
    }
</script>

<script>
    function getCurrentPos() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var p = new google.maps.LatLng(pos.lat, pos.lng);
            var geocoderr = new google.maps.Geocoder;
             results = null;
             geocoderr.geocode({
                        'location': p
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            $('.user_address_input').val(results[0].formatted_address);
                        }
                    });

        });
    }

    }
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
        getCurrentPos();
});
</script>


    <script>
        $(function() {
	//----- OPEN
	$('[data-popup-open]').on('click', function(e) {
		var targeted_popup_class = jQuery(this).attr('data-popup-open');
		$('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

        e.preventDefault();
        
        Webcam.set({
			width: 480,
			height: 480,
			image_format: 'png',
			jpeg_quality: 90
		});
		Webcam.attach( '#my_camera' );
	});

	//----- CLOSE
	$('[data-popup-close]').on('click', function(e) {
		var targeted_popup_class = jQuery(this).attr('data-popup-close');
		$('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

        e.preventDefault();
      
	});
});
    </script>
    
    <script type="text/javascript" src="{{asset('js/webcam.min.js')}}"></script>

    
        
    <script language="JavaScript">
		function take_snapshot() {
			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
                // display results in page
				document.getElementById('results').innerHTML =  
                    '<img style="width:80px; height:80px" src="'+data_uri+'"/>'+
                    '<input type="hidden" value="'+data_uri+'" name="check_in"/>'
                
            } );
            // $("#my_camera").remove();
		}
	</script>

@endsection
