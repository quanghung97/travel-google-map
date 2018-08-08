@extends('user.layouts.app_user')
@section('css')
    <link rel="stylesheet" href="{{asset('css/map.css')}}">
@endsection
@section('mapjs')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDdtVaJjFLyHdn0kTk9pF8upC4nNiLlqgM" type="text/javascript"></script>
<script src="{{asset('js/ContextMenu.js')}}" type="text/javascript"></script>
<script src="{{asset('js/create.js')}}"></script>
<script src="{{asset('js/deleteMarker.js')}}"></script>
<script src="{{asset('js/recalculateRoute.js')}}"></script>
<script src="{{asset('js/addWayPointToRoute.js')}}"></script>
<script src="{{asset('js/showInfo.js')}}"></script>
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


                                <form action="{{url('user/trip')}}" enctype="multipart/form-data" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                                <h3>Nhập tên chuyến đi</h3>
                                                <input type="text" class="form-control" name="name">
                                                <br>
                                        </div>


                                        <div class="col-md-6">

                                            <label >Thời gian bắt đầu</label>

                                            <div class='input-group date datetimepicker'>

                                                <input name="leave_time0" type='text' class="form-control" />

                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                                <label >Thời gian kết thúc</label>

                                                <div class='input-group date datetimepicker'>

                                                    <input name="arrival_time0" type='text' class="form-control" />

                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>


                                        </div>
                                        <h3 class="col-md-12">Ảnh cover: </h3>
                                        <div class="col-md-12 col-sm-12">
                                            <img src="{{asset('image/no-image.jpg')}}" id="logo-img" onclick="document.getElementById('add-new-logo').click();" style="width:100%; height:300px;">

                                            <input class="form-control" type="file" style="display: none" id="add-new-logo" name="file" accept="image/*" onchange="addNewLogo(this)" />

                                            <br>
                                        </div>
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
</div>
@endsection

@section('js')
<script src="{{asset('js/appendTable.js')}}"></script>
<script src="{{asset('js/updateRow.js')}}"></script>
<script type="text/javascript" src="{{asset('js/deleteRow.js')}}">  
</script>

<script src="{{asset('js/changeImage.js')}}">
</script>
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
