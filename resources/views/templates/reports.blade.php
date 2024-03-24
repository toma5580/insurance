@extends('global.app')

@section('title', trans('reports.title', array(
    'year'  => $year
)))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/morris/morris.css') }}" rel="stylesheet"/>
@endsection

@section('extra_stylesheets')
    <style>
        h1.font-light.text-white {
            margin-left:10px;
            margin-right:10px;
        }
    </style>
@endsection

@section('action_buttons')
            <div class="ui right floated segment transparent page-actions">
                <div class="ui labeled icon button primary top m-w-140 right pointing dropdown">
                    <i class="calendar alternate outline icon"></i> 
                    {{ $year }}
                    <div class="menu">
                        <div class="header">
                            {{ trans('reports.dropdown.header') }}
                        </div>
                        <div class="divider"></div>
                        @if($year != date('Y'))
                        <div class="item">
                            <a href="{{ action('ReportController@get') }}">{{ date('Y') }}</a>
                        </div>
                        @endif
                        @if($year != (date('Y') - 1))
                        <div class="item">
                            <a href="{{ action('ReportController@get', array(
                                'year'  => date('Y') - 1
                            )) }}">{{ date('Y') - 1 }}</a>
                        </div>
                        @endif
                        @if($year != (date('Y') - 2))
                        <div class="item">
                            <a href="{{ action('ReportController@get', array(
                                'year'  => date('Y') - 2
                            )) }}">{{ date('Y') - 2 }}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/libs/morris/morris.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/morris/raphael.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initDropdown('div.dropdown');
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.listenForChats();
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
