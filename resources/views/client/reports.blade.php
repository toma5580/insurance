@extends('templates.reports')

@section('content')
        @parent
        @include('global.status')
        <div class="row m-t-30 widgets">
            <!-- Column -->
            <div class="col-md-6 col-lg-3 col-xlg-3">
                <div class="card card-inverse bg-primary">
                    <div class="box bg-info text-center">
                        <h1 class="font-light text-white">
                            {{ $user->incomingEmails()->createdIn('year', $year)->count() }}
                            <div class="ui fitted divider"></div>
                            {{ $user->outgoingEmails()->createdIn('year', $year)->count() }}
                        </h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.emails') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-3 col-xlg-3">
                <div class="card card-primary bg-purple">
                    <div class="box text-center">
                        <h1 class="font-light text-white">
                            {{ $user->policies()->createdIn('year', $year)->expiring('after', 30)->count() }}
                            <div class="ui fitted divider"></div>
                            {{ $user->policies()->createdIn('year', $year)->count() }}
                        </h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.expiring') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-3 col-xlg-3">
                <div class="card card-inverse bg-success">
                    <div class="box text-center">
                        <h1 class="font-light text-white">
                            {{ $company->currency_symbol }}{{ $user->payments()->madeWithin('year', $year)->sum('amount') + 0 }}
                            <div class="ui fitted divider"></div>
                            {{ $company->currency_symbol }}{{ $user->policies()->createdIn('year', $year)->sum('premium') + 0 }}
                        </h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.paid') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-3 col-xlg-3">
                <div class="card card-inverse bg-danger">
                    <div class="box text-center">
                        <h1 class="font-light text-white">
                            {{ $company->currency_symbol }}{{ $user->policies()->createdIn('year', $year)->sum('premium') - $user->payments()->madeWithin('year', $year)->sum('amount') }}
                            <div class="ui fitted divider"></div>
                            {{ $company->currency_symbol }}{{ $user->payments()->madeWithin('year', $year)->sum('amount') + 0 }}
                        </h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.due') }}</h6>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row m-t-30 widgets">
            <!-- Statistic -->
            <div class="col-md-6 col-lg-3 col-xlg-3 m-b-15 text-center">
                <div class="ui teal statistic">
                    <div class="value">
                        <i class="envelope outline icon"></i>
                    </div>
                    <div class="value">
                        {{ $user->incomingEmails->count() }}
                    </div>
                    <div class="ui fitted divider"></div>
                    <div class="value">
                        {{ $user->outgoingEmails->count() }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.emails') }}
                    </div>
                </div>
            </div>
            <!-- Statistic -->
            <div class="col-md-6 col-lg-3 col-xlg-3 m-b-15 text-center">
                <div class="ui purple statistic">
                    <div class="value">
                        <i class="file archive outline icon"></i>
                    </div>
                    <div class="value">
                        {{ $user->policies()->createdIn('year', $year)->expiring('after', 30)->count() }}
                    </div>
                    <div class="ui fitted divider"></div>
                    <div class="value">
                        {{ $user->policies()->createdIn('year', $year)->count() }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.expiring') }}
                    </div>
                </div>
            </div>
            <!-- Statistic -->
            <div class="col-md-6 col-lg-3 col-xlg-3 m-b-15 text-center">
                <div class="ui green statistic">
                    <div class="value">
                        <i class="money bill alternate icon"></i>
                    </div>
                    <div class="value">
                        {{ $company->currency_symbol }}{{ $user->payments()->madeWithin('year', $year)->sum('amount') + 0 }}
                    </div>
                    <div class="ui fitted divider"></div>
                    <div class="value">
                        {{ $company->currency_symbol }}{{ $user->policies()->createdIn('year', $year)->sum('premium') + 0 }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.paid') }}
                    </div>
                </div>
            </div>
            <!-- Statistic -->
            <div class="col-md-6 col-lg-3 col-xlg-3 m-b-15 text-center">
                <div class="ui red statistic">
                    <div class="value">
                        <i class="cart plus icon"></i>
                    </div>
                    <div class="value">
                        {{ $company->currency_symbol }}{{ $user->policies()->createdIn('year', $year)->sum('premium') - $user->payments()->madeWithin('year', $year)->sum('amount') + 0 }}
                    </div>
                    <div class="ui fitted divider"></div>
                    <div class="value">
                        {{ $company->currency_symbol }}{{ $user->payments()->madeWithin('year', $year)->sum('amount') + 0 }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.due') }}
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-md-12 m-t-30">
                <div class="ui segment white">
                    <div class="segment-header">
                        <h3><i class="chart bar outline icon"></i>{{ trans('reports.graph.header.payments') }}</h3>
                    </div>
                    <div id="annualPayments"></div>
                </div>
            </div>
        </div>
@endsection

@section('extra_scripts')
    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                // Income
                Morris.Bar({
                    element: 'annualPayments',
                    data: [
                        {
                            month: '{{ trans('reports.graph.label.annual.jan') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '01')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.feb') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '02')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.mar') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '03')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.apr') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '04')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.may') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '05')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.jun') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '06')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.jul') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '07')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.aug') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '08')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.sep') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '09')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.oct') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '10')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.nov') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '11')->sum('amount') + 0 }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.dec') }}',
                            paid: {{ $user->payments()->madeWithin('year', $year)->madeWithin('month', '12')->sum('amount') + 0 }}
                        }
                    ],
                    xkey: 'month',
                    ykeys: ['paid'],
                    labels: ['{{ trans('reports.graph.pop_over.payments', array(
                        'currency_symbol'  => $company->currency_symbol
                    )) }}'],
                    barColors: ['#4D7CFE'],
                    resize: true
                });
            });
        })(window.jQuery);
    </script>
@endsection