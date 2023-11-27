@extends('layouts.team')

@section('teamcontent')
  <div class="row justify-content-center">
    <div class="col-md-3">
      @include('teams.partials._nav')
    </div>
    <div class="col-md-9">
      <div class="card mb-4">
        <div class="card-header">
          Dashboard
        </div>

        <div class="card-body">
          This is your team dashboard
        </div>
      </div>
    </div>
  </div>
@endsection
