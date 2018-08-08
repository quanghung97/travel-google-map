
    @extends('user.layouts.app_user') 

@section('content')
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">

                        <a href="{{ url('/admin/admin/role') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/admin/role/' . $role->id . '/edit') }}" title="Edit Role"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['admin/admin/role', $role->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Delete Role',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        <br/>
                        <br/>
                        <div class="clearfix"></div>

                        <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                        <div class="x_title">
                          <h2>Role {{ $role->name }}<small>Sessions</small></h2>
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
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $role->id }}</td>
                                    </tr>
                                    <tr><th> Name </th><td> {{ $role->name }} </td></tr>
                                    <tr>
                                        <th>Permissions</th><td>{{ implode(', ',$permissions) }}</td>
                                    </tr>
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
@endsection
