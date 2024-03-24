@extends('templates.policies.one')

@section('client_action')
                <div class="m-t-25">
                    <a class="ui button positive" href="{{ action('SettingController@get') }}"><i class="write outline icon"></i> {{ trans('policies.button.profile') }} </a>
                </div>
@endsection
