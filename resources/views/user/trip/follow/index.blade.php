@extends('user.layouts.app_user')


@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <br/>
                        <br/>
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
                          <h2>Show Trip Team <small>Sessions</small></h2>
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
                        <div class="x_content">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Name</th>
                                        <th>Tên Leader</th>
                                        <th>People Number</th><th>Status</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td><a href="{{url('user/userProfile/profile/'.$item->owner_id)}}"> {{ $item->owner->name}}</a></td>
                                        <td>{{ $item->people_number }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            <a href="{{ url('/user/trip/' . $item->id) }}" title="View trip"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            @if(Auth::user()->checkFollow(Auth::user()->id, $item->id))
                                            <a href="{{ url('/user/trip/follow/follow/' . $item->id) }}"><button class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Follow</button></a>
                                            @else 
                                            <a href="{{ url('/user/trip/follow/unfollow/' . $item->id) }}"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Unfollow</button></a>
                                            @endif
                                            
                                            <a href=""><button class="btn btn-danger btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Join</button></a>
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
        </div>
    </div>
@endsection
@section('js')
  <script type="text/javascript">
    $(document).ready( function () {
        $('#datatable').DataTable();
      } );
        </script>
@endsection
