@extends('user.layouts.app_user')
@section('css')
<style>
    td , th {
        text-align: center;
    }
</style>
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
                                    <h2>Newest Member <small>Sessions</small></h2>
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
                                <table id="listwp" class="table table-striped table-bordered">
                                    <thead>
                                            <th>#</th>
                                            <th>Tên</th>
                                            <th>Ngày tham gia</th>
                                            <th>Liên hệ</th>
                                            <th>Những chuyến đi tham gia</th>
                                            <th>Những chuyến đi theo dõi</th>
                                    </thead>

                                    <tbody>
                                        
                                            @foreach($user as $u)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td> {{$u->name}} </td>
                                                <td> {{ date("d-m-Y", strtotime($u->created_at)) }}</td>
                                                <td> <a href="{{url('user/userProfile/profile/'.$u->id)}}">Xem trang cá nhân</a></td>
                                                <td><a href="">Xem chi tiết</a></td>
                                                <td><a href="{{url('user/trip/follow/index/'.$u->id)}}">Xem chi tiết</a></td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                               
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
