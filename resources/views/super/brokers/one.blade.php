@extends('templates.brokers.one')

@section('broker_modals')
    <!-- edit broker modal -->
    <div class="ui tiny modal" id="editBrokerModal">
        <div class="header">{{ trans('brokers.modal.header.edit') }}</div>
        <div class="scrolling content">
            <p>{{ trans('brokers.modal.instruction.edit') }}</p>
            <form action="{{ action('BrokerController@edit', array($broker->id)) }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('brokers.input.label.first_name') }}</label>
                            <input type="text" name="first_name" placeholder="{{ trans('brokers.input.placeholder.first_name') }}" required value="{{ old('first_name') ?: $broker->first_name }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('brokers.input.label.last_name') }}</label>
                            <input type="text" name="last_name" placeholder="{{ trans('brokers.input.placeholder.last_name') }}" value="{{ old('last_name') ?: $broker->last_name }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('brokers.input.label.email') }}</label>
                            <input type="email" name="email" placeholder="{{ trans('brokers.input.placeholder.email') }}" required value="{{ old('email') ?: $broker->email }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('brokers.input.label.phone') }}</label>
                            <input type="tel" placeholder="{{ trans('brokers.input.placeholder.phone') }}" value="{{ old('phone') ?: $broker->phone }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>{{ trans('brokers.input.label.birthday') }}</label>
                            <input type="text" class="datepicker" name="birthday" placeholder="{{ trans('brokers.input.placeholder.birthday') }}" value="{{ old('birthday') ?: $broker->birthday }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('brokers.input.label.address') }}</label>
                            <input type="text" name="address" placeholder="{{ trans('brokers.input.placeholder.address') }}" value="{{ old('address') ?: $broker->address }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('brokers.input.label.company') }}</label>
                            <select class="dropdown fluid search ui" name="company_id" required>
                                @foreach($companies as $company)
                                <option{{ !empty(old('company_id')) && old('company_id') === $company->id || empty(old('company_id')) && $company === $broker->company ? ' selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field required">
                            <label>{{ trans('staff.input.label.commission_rate') }} (%)</label>
                            <input type="number" max="100" min="0" name="commission_rate" placeholder="{{ trans('staff.input.placeholder.commission_rate') }}" required step="0.01" value="{{ old('commission_rate') ?: $broker->commission_rate }}"/>
                        </div>
                    </div>
                    <div class="field">
                        <label>{{ trans('brokers.input.label.profile_image') }}</label>
                        <input type="file" accept="image/*" class="file-upload" data-allowed-file-extensions="bmp gif jpeg jpg png svg" data-default-file="{{ asset('uploads/images/users/' . $broker->profile_image_filename) }}" name="profile_image"/>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('brokers.modal.button.cancel.edit') }}</button>
                <div class="or" data-text="{{ trans('products.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('brokers.modal.button.confirm.edit') }}</button>
            </div>
        </div>
    </div>
@endsection
