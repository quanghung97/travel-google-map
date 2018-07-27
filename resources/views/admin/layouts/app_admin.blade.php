
@if (auth()->user()->can('Access Admin'))
  @extends('layouts.app_admin')


  @section ('sidebar')
    @include ('admin.layouts.sidebar_admin')
  @endsection

@endif
