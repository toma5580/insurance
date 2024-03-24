@extends('global.app')

@section('title', trans('companies.title'))

@section('sub_title', trans('companies.sub_title', array(
    'system'    => config('insura.name')
)))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
        @parent
        @include('global.status')
<div class="row m-t-30">
            <!-- start current company -->
            <div class="col-md-4">
                <div class="ui segment white company-item">
                    <p class="text-ellipsis company-name">{{ $user->company->name }}</p>
                    <div class="text-avatar" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($user->company->name[0] . collect(explode(' ', $user->company->name))->get(1, ' ')[0]) }}</div>
                    <h1>{{ collect(config('insura.currencies.list'))->keyBy('code')->get($user->company->currency_code)['symbol'] }} {{ $user->company->policies->sum('premium') }}</h1>
                    <span class="company-sales">Total Sales</span>
                    <div class="row company-info">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <p>{{ $user->company->clients->count() }}</p>
                            <span>{{ trans('companies.label.client') }}</span>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <p>{{ $user->company->policies->count() }}</p>
                            <span>{{ trans('companies.label.policy') }}</span>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <p>{{ $user->company->staff->count() }}</p>
                            <span>{{ trans('companies.label.staff') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end current company -->

            <!-- start other companies -->
            @forelse($companies as $company)
            <div class="col-md-4">
                <div class="ui segment white company-item">
                    <p class="text-ellipsis company-name">{{ $company->name }}</p>
                    <form action="{{ action('CompanyController@delete', array($company->id)) }}" class="delete-company" data-position="left center" data-inverted="" data-tooltip="{{ trans('companies.tooltip.delete') }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <i class="icon ion-ios-trash ui"></i>
                    </form>
                    <div class="text-avatar" style="background-color:{{ collect(config('insura.colors'))->random() }};">{{ strtoupper($company->name[0] . collect(explode(' ', $company->name))->get(1, ' ')[0]) }}</div>
                    <h1>{{ collect(config('insura.currencies.list'))->keyBy('code')->get($company->currency_code)['symbol'] }} {{ $company->policies->sum('premium') }}</h1>
                    <span class="company-sales">Total Sales</span>
                    <div class="row company-info">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <p>{{ $company->clients->count() }}</p>
                            <span>{{ trans('companies.label.client') }}</span>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <p>{{ $company->policies->count() }}</p>
                            <span>{{ trans('companies.label.policy') }}</span>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <p>{{ $company->staff->count() }}</p>
                            <span>{{ trans('companies.label.staff') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-xs-4 col-md-offset-3">
                <div class="segment text-center ui white">
                    <i class="huge icon ion-android-alert"></i>
                    <p>{{ trans('companies.message.empty') }}</p>
                </div>
            </div>
            @endforelse
            <!-- end other companies -->
        </div>
@endsection

@section('extra_scripts')
    <script src="{{ asset('assets/libs/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initDropdown('div.dropdown');
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initSwal('form i.ion-ios-trash', {
                    confirmButtonText: '{{ trans('companies.swal.warning.delete.confirm') }}',
                    text: '{{ trans('companies.swal.warning.delete.text') }}',
                    title: '{{ trans('companies.swal.warning.delete.title') }}'
                });
                $insura.helpers.listenForChats();
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
