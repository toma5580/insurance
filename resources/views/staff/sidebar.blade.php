@extends('templates.sidebar')

@section('clients')
            <a class="item" href="{{ action('ClientController@getAll') }}">
                <i class="ion-ios-people-outline icon"></i> <div class="content">{{ trans('sidebar.link.clients') }} </div>
            </a>
@endsection

@section('products')
            <a class="item" href="{{ action('ProductController@getAll') }}">
                <i class="ion-ios-lightbulb-outline icon"></i> <div class="content">{{ trans('sidebar.link.products') }} </div>
            </a>
@endsection
