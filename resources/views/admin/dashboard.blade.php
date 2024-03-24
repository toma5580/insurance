@extends('templates.dashboard')

@section('content')
        @parent
        @include('global.status')
        <div class="row m-t-15 widgets">
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-primary">
                    <div class="box bg-info text-center">
                        <h1 class="font-light text-white">{{ $company->clients->count() }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.clients') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-primary bg-purple">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->policies->count() }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.policies') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-success">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $company->policies->sum('premium') + 0 }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.sales') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-primary">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $company->payments->sum('amount') + 0 }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.conversions') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-danger">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $company->payments->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                            return $count + ($broker->inviteePayments->sum('amount') * $broker->commission_rate / 100);
                        }, 0) + $company->staff->reduce(function($count, $staff) {
                            return $count + ($staff->inviteePayments->sum('amount') * $staff->commission_rate / 100);
                        }, 0)) }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.income') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-warning">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->policies()->expiring('after', 30)->count() }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.expiring') }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 m-b-15">
                <div class="ui segment white">
                    <div class="segment-header">
                        <h3>{{ trans('dashboard.graph.header.monthly') }}</h3>
                    </div>
                    <div id="monthlySales"></div>
                </div>
            </div>
            <div class="col-md-8 m-b-15">
                <div class="ui segment white">
                    <div class="segment-header">
                        <h3>{{ trans('dashboard.graph.header.annual') }}</h3>
                    </div>
                    <div id="annualSales"></div>
                </div>
            </div>
        </div>

        <div class="ui segment white">
            <div class="segment-header">
                <h3>{{ trans('dashboard.table.title') }}</h3>
            </div>
            <table class="ui striped table">
                <thead>
                    <tr>
                        <th>{{ trans('dashboard.table.header.number') }}</th>
                        <th>{{ trans('dashboard.table.header.ref_no') }}</th>
                        <th>{{ trans('dashboard.table.header.client') }}</th>
                        <th>{{ trans('dashboard.table.header.product') }}</th>
                        <th>{{ trans('dashboard.table.header.premium') }}</th>
                        <th>{{ trans('dashboard.table.header.due') }}</th>
                        <th class="center aligned">{{ trans('dashboard.table.header.status') }}</th>
                        <th class="center aligned">{{ trans('dashboard.table.header.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latest_policies as $policy)
                    <tr class="{{ $policy->statusClass }}">
                        <td>{{ $policy->id }}</td>
                        <td>{{ $policy->ref_no }}</td>
                        <td>{{ $policy->payer }}</td>
                        <td class="text-ellipsis">{{ $policy->product->name }}</td>
                        <td>{{ $company->currency_symbol }}{{ $policy->premium + 0 }}</td>
                        <td>{{ $company->currency_symbol }}{{ $policy->premium - $policy->payments->sum('amount') }}</td>
                        <td class="center aligned">
                            @if ($policy->premium <= $policy->paid && $policy->paid > 0)
                            <div class="ui green mini label"> {{ trans('dashboard.table.data.status.paid') }}</div>
                            @elseif ($policy->premium > $policy->paid && $policy->paid > 0)
                            <div class="ui orange mini label"> {{ trans('dashboard.table.data.status.partial') }}</div>
                            @elseif ($policy->premium == $policy->paid && $policy->paid === 0)
                            <div class="ui yellow mini label"> {{ trans('dashboard.table.data.status.free') }}</div>
                            @else ($policy->premium > $policy->paid && $policy->paid === 0)
                            <div class="ui red mini label"> {{ trans('dashboard.table.data.status.unpaid') }}</div>
                            @endif
                        </td>
                        <td class="center aligned"><a href="{{ action('PolicyController@getOne', array($policy->id)) }}" class="ui mini grey label"> {{ trans('dashboard.table.data.action') }} </a></td>
                    </tr>
                    @empty

                    <tr>
                        <td colspan="8" style="text-align:center;">{{ trans('dashboard.table.data.not_available') }}</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
@endsection

@section('extra_scripts')
    <script type="text/javascript">
        // this Month
        Morris.Donut({
            element: 'monthlySales',
            data: [
                {label: '{{ trans('dashboard.graph.label.monthly.paid') }}', value: {{ $company->payments()->madeWithin('month')->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {label: '{{ trans('dashboard.graph.label.monthly.due') }}', value: {{ $company->policies->sum('premium') - $company->payments->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + (($broker->inviteePolicies()->sum('premium') - $broker->inviteePayments()->sum('amount')) * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + (($staff->inviteePolicies()->sum('premium') - $staff->inviteePayments()->sum('amount')) * $staff->commission_rate / 100);
                }, 0)) }}}
            ],
            colors: ['#21ba45','#ff0000'],
            formatter: function (x) { return '{{ $company->currency_symbol }}' + x }
        }).on('click', function(i, row){
            console.log(i, row);
        });

        // income
        Morris.Bar({
            element: 'annualSales',
            data: [
                {   x: '{{ trans('dashboard.graph.label.annual.jan') }}', y: {{ $company->payments()->madeWithin('month', '01')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '01')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '01')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.feb') }}', y: {{ $company->payments()->madeWithin('month', '02')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '02')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '02')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.mar') }}', y: {{ $company->payments()->madeWithin('month', '03')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '03')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '03')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.apr') }}', y: {{ $company->payments()->madeWithin('month', '04')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '04')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '04')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.may') }}', y: {{ $company->payments()->madeWithin('month', '05')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '05')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '05')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.jun') }}', y: {{ $company->payments()->madeWithin('month', '06')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '06')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '06')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.jul') }}', y: {{ $company->payments()->madeWithin('month', '07')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '07')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '07')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.aug') }}', y: {{ $company->payments()->madeWithin('month', '08')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '08')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '08')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.sep') }}', y: {{ $company->payments()->madeWithin('month', '09')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '09')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '09')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.oct') }}', y: {{ $company->payments()->madeWithin('month', '10')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '10')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '10')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.nov') }}', y: {{ $company->payments()->madeWithin('month', '11')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '11')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '11')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}},
                {   x: '{{ trans('dashboard.graph.label.annual.dec') }}', y: {{ $company->payments()->madeWithin('month', '12')->get()->sum('amount') - ($company->brokers->reduce(function($count, $broker) {
                    return $count + ($broker->inviteePayments()->madeWithin('month', '12')->sum('amount') * $broker->commission_rate / 100);
                }, 0) + $company->staff->reduce(function($count, $staff) {
                    return $count + ($staff->inviteePayments()->madeWithin('month', '12')->sum('amount') * $staff->commission_rate / 100);
                }, 0)) }}}
            ],
            xkey: 'x',
            ykeys: 'y',
            labels: ['{{ trans('dashboard.graph.pop_over.annual') . ' ' . $company->currency_symbol }}'],
            barColors: ['#4D7CFE'],
            resize: true
        });
    </script>
@endsection
