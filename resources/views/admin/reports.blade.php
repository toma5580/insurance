@extends('templates.reports')

@section('content')
        @parent
        @include('global.status')
        <div class="row m-t-30 widgets">
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-primary">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->clients()->createdIn('year', $year)->count() }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.clients') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-purple">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->policies()->createdIn('year', $year)->count() }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.policies') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-danger">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $company->policies()->createdIn('year', $year)->sum('premium') + 0 }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.sales') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-primary">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $company->payments()->madeWithin('year', $year)->sum('amount') + 0 }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.conversions') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-success">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $company->payments()->madeWithin('year', $year)->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                            return $count + ($broker->inviteePayments()->madeWithin('year', $year)->sum('amount') * $broker->company->commission_rate / 100);
                        }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                            return $count + ($staff->inviteePayments()->madeWithin('year', $year)->sum('amount') * $staff->company->commission_rate / 100);
                        }, 0)) }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.income') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-warning">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->policies()->createdIn('year', $year)->expiring('after', 30)->count() }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.expiring') }}</h6>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row m-t-30 widgets">
            <!-- Statistic -->
            <div class="col-md-6 col-lg-4 col-xlg-4 m-b-15 text-center">
                <div class="ui teal statistic">
                    <div class="value">
                        <i class="users icon"></i>
                    </div>
                    <div class="value">
                        {{ $company->clients()->createdIn('year', $year)->count() }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.clients') }}
                    </div>
                </div>
            </div>
            <!-- Statistic -->
            <div class="col-md-6 col-lg-4 col-xlg-4 m-b-15 text-center">
                <div class="ui purple statistic">
                    <div class="value">
                        <i class="file archive outline icon"></i>
                    </div>
                    <div class="value">
                        {{ $company->policies()->createdIn('year', $year)->count() }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.policies') }}
                    </div>
                </div>
            </div>
            <!-- Statistic -->
            <div class="col-md-6 col-lg-4 col-xlg-4 m-b-15 text-center">
                <div class="ui red statistic">
                    <div class="value">
                        <i class="handshake outline icon"></i>
                    </div>
                    <div class="value">
                        {{ $company->currency_symbol }}{{ $company->policies()->createdIn('year', $year)->count() }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.sales') }}
                    </div>
                </div>
            </div>
            <!-- Statistic -->
            <div class="col-md-6 col-lg-4 col-xlg-4 m-b-15 text-center">
                <div class="ui brown statistic">
                    <div class="value">
                        <i class="cart plus icon"></i>
                    </div>
                    <div class="value">
                        {{ $company->currency_symbol }}{{ $company->payments()->madeWithin('year', $year)->sum('amount') + 0 }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.conversions') }}
                    </div>
                </div>
            </div>
            <!-- Statistic -->
            <div class="col-md-6 col-lg-4 col-xlg-4 m-b-15 text-center">
                <div class="ui green statistic">
                    <div class="value">
                        <i class="money icon"></i>
                    </div>
                    <div class="value">
                        {{ $company->currency_symbol }}{{ $company->payments()->madeWithin('year', $year)->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                            return $count + ($broker->inviteePayments()->madeWithin('year', $year)->sum('amount') * $broker->company->commission_rate / 100);
                        }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                            return $count + ($staff->inviteePayments()->madeWithin('year', $year)->sum('amount') * $staff->company->commission_rate / 100);
                        }, 0)) }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.income') }}
                    </div>
                </div>
            </div>
            <!-- Statistic -->
            <div class="col-md-6 col-lg-4 col-xlg-4 m-b-15 text-center">
                <div class="ui yellow statistic">
                    <div class="value">
                        <i class="calendar times outline icon"></i>
                    </div>
                    <div class="value">
                        {{ $company->policies()->createdIn('year', $year)->expiring('after', 30)->count() }}
                    </div>
                    <div class="label">
                        {{ trans('dashboard.counter.expiring') }}
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-md-12 m-t-15">
                <div class="ui segment white">
                    <div class="segment-header">
                        <h3><i class="chart area icon"></i>{{ trans('reports.graph.header.clients_vs_policies') }}</h3>
                    </div>
                    <div id="clientsVsPolicies"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 m-t-30">
                <div class="ui segment white">
                    <div class="segment-header">
                        <h3><i class="chart bar outline icon"></i>{{ trans('reports.graph.header.annual') }}</h3>
                    </div>
                    <div id="annualIncome"></div>
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
                    element: 'annualIncome',
                    data: [
                        {
                            month: '{{ trans('reports.graph.label.annual.jan') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '01')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '01')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '01')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.feb') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '02')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '02')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '02')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.mar') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '03')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '03')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '03')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.apr') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '04')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '04')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '04')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.may') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '05')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '05')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '05')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.jun') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '06')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '06')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '06')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.jul') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '07')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '07')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '07')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.aug') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '08')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '08')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '08')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.sep') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '09')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '09')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '09')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.oct') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '10')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '10')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '10')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.nov') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '11')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '11')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '11')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.dec') }}',
                            sales: {{ $company->payments()->madeWithin('year', $year)->madeWithin('month', '12')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) use($year) {
                                return $count + ($broker->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '12')->sum('amount') * $broker->company->commission_rate / 100);
                            }, 0) + $company->staff->reduce(function($count, $staff) use($year) {
                                return $count + ($staff->inviteePayments()->madeWithin('year', $year)->madeWithin('month', '12')->sum('amount') * $staff->company->commission_rate / 100);
                            }, 0)) }}
                        }
                    ],
                    xkey: 'month',
                    ykeys: ['sales'],
                    labels: ['{{ trans('reports.graph.pop_over.annual', array(
                        'currency_symbol'  => $company->currency_symbol
                    )) }}'],
                    barColors: ['#4D7CFE'],
                    resize: true
                });

                // Clients vs Policies comparison
                Morris.Area({
                    element: 'clientsVsPolicies',
                    data: [
                        {
                            month: '{{ trans('reports.graph.label.annual.jan') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '01')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '01')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.feb') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '02')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '02')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.mar') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '03')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '03')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.apr') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '04')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '04')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.mar') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '05')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '05')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.jun') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '06')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '06')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.jul') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '07')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '07')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.aug') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '08')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '08')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.sep') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '09')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '09')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.oct') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '10')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '10')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.nov') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '11')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '11')->count() }}
                        },
                        {
                            month: '{{ trans('reports.graph.label.annual.dec') }}',
                            clients: {{ $company->clients()->createdIn('year', $year)->createdIn('month', '12')->count() }},
                            policies: {{ $company->policies()->createdIn('year', $year)->createdIn('month', '12')->count() }}
                        }
                    ],
                    xkey: 'month',
                    ykeys: ['clients', 'policies'],
                    labels: ['{{ trans('reports.graph.pop_over.clients') }}', '{{ trans('reports.graph.pop_over.policies') }}'],
                    lineColors: ['grey', '#21ba45'],
                    parseTime: false
                });
            });
        })(window.jQuery);
    </script>
@endsection