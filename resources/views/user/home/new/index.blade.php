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
                                    <h2> Newest Trips <small>Sessions</small></h2>
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
                                            <th>Người tạo</th>
                                            <th>Ngày tạo</th>
                                            <th>Số lượng người tham gia</th>
                                            <th>Trạng thái</th>
                                            <th>Chức năng</th>
                                    </thead>

                                    <tbody>
                                        
                                            @foreach($trip as $t)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td> {{$t->name}} </td>
                                                <td><a href="{{url('user/userProfile/profile/'.$t->owner_id)}}">{{$t->owner_id}}</a></td>
                                                <td> {{ date("d-m-Y", strtotime($t->created_at)) }}</td>
                                                <td>{{$t->people_number}}</td>
                                                <td>{{$t->status}}</td>
                                                <td><a href=""><button class="btn btn-info btn-sm"><i class="fa fa-eye"></i> View</button></a>
                                                    <a href=""><button class="btn btn-warning btn-sm"><i class="fa fa-star"></i> Flow</button></a>
                                                    <a href=""><button class="btn btn-danger btn-sm"><i class="fa fa-group"></i> Join</button></a>
                                                </td>
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