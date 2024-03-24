@extends('templates.staff.all')

@section('modals')
    <!-- new staff modal -->
    <div class="ui tiny modal" id="newStaffModal">
        <div class="header">{{ trans('staff.modal.header.new') }}</div>
        <div class="content">
            <p>{{ trans('staff.modal.instruction.new') }}</p>
            <form action="{{ action('StaffController@add') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('staff.input.label.first_name') }}</label>
                            <input type="text" name="first_name" placeholder="{{ trans('staff.input.placeholder.first_name') }}" required value="{{ old('first_name') }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('staff.input.label.last_name') }}</label>
                            <input type="text" name="last_name" placeholder="{{ trans('staff.input.placeholder.last_name') }}" value="{{ old('last_name') }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('staff.input.label.email') }}</label>
                            <input type="email" name="email" placeholder="{{ trans('staff.input.placeholder.email') }}" required value="{{ old('email') }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('staff.input.label.phone') }}</label>
                            <input type="tel" placeholder="{{ trans('staff.input.placeholder.phone') }}" value="{{ old('phone') }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>{{ trans('staff.input.label.birthday') }}</label>
                            <input type="text" class="datepicker" name="birthday" placeholder="{{ trans('staff.input.placeholder.birthday') }}" value="{{ old('birthday') }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('staff.input.label.address') }}</label>
                            <input type="text" name="address" placeholder="{{ trans('staff.input.placeholder.address') }}" value="{{ old('address') }}"/>
                        </div>
                    </div>
                    <div class="field required">
                        <label>{{ trans('staff.input.label.commission_rate') }} (%)</label>
                        <input type="number" max="100" min="0" name="commission_rate" placeholder="{{ trans('staff.input.placeholder.commission_rate') }}" required step="0.01" value="{{ old('commission_rate') ?: 0 }}"/>
                    </div>
                    <div class="field">
                        <label>{{ trans('staff.input.label.profile_image') }}</label>
                        <input type="file" accept="image/*" class="file-upload" data-allowed-file-extensions="bmp gif jpeg jpg png svg" data-default-file="{{ asset('uploads/images/users/default-profile.jpg') }}" name="profile_image"/>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('staff.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('products.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('staff.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>
@endsection