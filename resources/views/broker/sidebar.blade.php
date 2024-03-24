@extends('templates.sidebar')

@section('clients')
            <a class="item" href="{{ action('ClientController@getAll') }}">
                <i class="ion-ios-people-outline icon"></i> <div class="content">{{ trans('sidebar.link.clients') }} </div>
            </a>
@endsection
