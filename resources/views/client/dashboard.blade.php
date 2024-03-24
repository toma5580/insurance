@extends('templates.dashboard')

@section('content')
        @parent
        @include('global.status')
        <div class="row m-t-15 widgets">
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-primary">
                    <div class="box bg-info text-center">
                        <h1 class="font-light text-white">{{ $user->incomingEmails->count() }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.emails') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-primary bg-purple">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $user->policies->count() }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.policies') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-success">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $user->policies->sum('premium') + 0 }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.covers') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-primary">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $user->payments->sum('amount') + 0 }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.paid') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-danger">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $company->currency_symbol }}{{ $user->policies->reduce(function($count, $policy) {
                            return $policy->premium - $policy->payments->sum('amount') + $count;
                         }, 0) }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.due') }}</h6>
                    </div>
                </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-2">
                <div class="card card-inverse bg-warning">
                    <div class="box text-center">
                        <h1 class="font-light text-white">{{ $user->policies()->expiring('after', 30)->count()  }}</h1>
                        <h6 class="text-white">{{ trans('dashboard.counter.expiring') }}</h6>
                    </div>
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
                        <th>#</th>
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
                        <td class="center aligned"><a href="{{ url('') }}" class="ui mini grey label"> {{ trans('dashboard.table.data.action') }} </a></td>
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
