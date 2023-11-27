@extends('layouts.team')

@section('teamcontent')
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('teams.partials._nav')
        </div>
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header">Confirm team deletion</div>

                <div class="card-body">
                    <p>Are you sure you'd like to delete <strong>{{ $team->name }}</strong>?</p>

                    <form action="{{ route('teams.destroy', [$team]) }}" method="post">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">Yes, delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
