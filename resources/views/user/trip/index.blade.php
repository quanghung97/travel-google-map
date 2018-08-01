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
                                        <th>#</th><th>Name</th><th>People Number</th><th>Status</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($trip as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->people_number }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            <a href="{{ url('/user/trip/' . $item->id) }}" title="View trip"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            @can('ablePlan', $item)
                                            <a href="{{ url('/user/trip/' . $item->id . '/edit') }}" title="Edit trip"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/user/trip', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', [
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-sm',
                                                        'title' => 'Delete user',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                ]) !!}
                                            {!! Form::close() !!}
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
