@extends('user.layouts.app_user')
@section('css')
    <link rel="stylesheet" href="{{asset('css/map.css')}}">
@endsection
@section('mapjs')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDdtVaJjFLyHdn0kTk9pF8upC4nNiLlqgM" type="text/javascript"></script>
<script src="{{asset('js/ContextMenu.js')}}" type="text/javascript"></script>
<script src="{{asset('js/deleteMarker.js')}}"></script>
<script src="{{asset('js/recalculateRoute.js')}}"></script>
<script src="{{asset('js/showInfo.js')}}"></script>
<script src="{{asset('js/addWayPointToRoute.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    var directionsService;
    var directionsRenderer;
    var map;
    var end_plan = false;

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

        google.maps.event.addListener(map, 'click', function(event) {
            if(!end_plan){
                addWayPointToRoute(event.latLng);
            }
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
            menuItems.push({
				className: 'context_menu_item',
				eventName: 'continute_plan',
				label: 'Continute'
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
                        $("#submit").removeAttr("disabled");
                        end_plan = true;
						break;
                    case 'continute_plan' :
                    if(end_plan){
                        end_plan = false;
                        $("#submit").attr("disabled","disabled");
                        deleteMarker(markers[markers.length-1]);
                    }
				}
			});


    }



    var markers = [];
    var polylines = [];
    var isFirst = true;


    var locations = [
        @foreach($trip->wayPoints as $item)
        [{{ $item->id }}, {{$item->lat}}, {{$item->lng}}],
        @endforeach
    ];

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
                    draggable: true,
                    icon: new google.maps.MarkerImage(
                        'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
                    )
                });
                marker.arrayIndex = 0;
                appendTable(marker);
                markers.push(marker);
                showInfo(marker);
                
                google.maps.event.addListener(marker, 'dragend', function() {
                    recalculateRoute(marker);
                    updateRow(marker);
                });
            }
        });
        isFirst = false;
        var i = 0;
        while( i < locations.length-1) {
            (function(i) {
            setTimeout(function(){
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
                        draggable: true,
                        
                    });
                    markers.push(marker);
                    appendTable(marker);
                    showInfo(marker);
                    marker.arrayIndex = markers.length - 1;
                
                    var polyline = new google.maps.Polyline();
                    var path = response.routes[0].overview_path;
                    for (var x in path) {
                        polyline.getPath().push(path[x]);
                    }
                    polyline.setMap(map);
                    polylines.push(polyline);
                    
                    google.maps.event.addListener(marker, 'dragend', function() {
                        recalculateRoute(marker);
                        // createTable();
                        updateRow(marker);
                    });
                google.maps.event.addListener(marker, 'rightclick', function() {
                    if(!end_plan){
                        // deleteRow(marker);
                        deleteMarker(marker);
                    }
                });
                
                }
            });
            } ,3000*i); })(i);
        i++;
        }
            
        




    }

    

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
                    @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $err)
                            <strong>{{$err}}</strong><br>
                        @endforeach
                    </div>
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

                                <div class="x_content">


                                <form action="{{url('user/trip/edit-waypoint/'.$trip->id)}}" enctype="multipart/form-data" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">

                                        
                                        <h3 class="col-md-12">Map:</h3>
                                        <div id="map" class="col-md-6 col-sm-12">
                                        </div>

                                    {{-- <a id="cretrip" class="btn btn-app">
                                        <i class="fa fa-plus"></i> Create Trip
                                    </a> --}}
                                    <div class="col-md-6 col-sm-12">
                                        <table id="listwp" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    {{-- <th>lat</th> --}}
                                                    {{-- <th>lng</th> --}}
                                                    <th>Địa chỉ</th>
                                                    <th>Chức năng</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="submit" id="submit" disabled="disabled" class="btn btn-danger" style="float:right">Cập nhật điểm</button>
                                </form>

                                <p><small>Chú ý: Do sự bất đồng bộ vui lòng không click vào map khi đang load các điểm đã tạo trước đó, số thứ tự trong bảng có thể bị sai. Vui lòng F5 để load lại trang</small></p>

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

@section('js')
<script type="text/javascript" src="{{asset('js/appendTable.js')}}">
</script>

<script src="{{asset('js/updateRow.js')}}"></script>

<script src="{{asset('js/deleteRow.js')}}"></script>
<script src="{{asset('js/changeImage.js')}}"></script>
<script>
    $(document).on('click', '.datetimepicker', function() {
             $(this).datetimepicker({widgetPositioning:{
                           horizontal: 'auto',
                           vertical: 'bottom'
                       },
                       format:'YYYY-MM-DD HH:mm:00'
           });
        });
</script>
@endsection
