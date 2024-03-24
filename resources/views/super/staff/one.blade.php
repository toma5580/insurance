@extends('templates.staff.one')

@section('staff_modals')
    <!-- edit staff modal -->
    <div class="ui tiny modal" id="editStaffModal">
        <div class="header">{{ trans('staff.modal.header.edit') }}</div>
        <div class="content">
            <p>{{ trans('staff.modal.instruction.edit') }}</p>
            <form action="{{ action('StaffController@edit', array($staff->id)) }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('staff.input.label.first_name') }}</label>
                            <input type="text" name="first_name" placeholder="{{ trans('staff.input.placeholder.first_name') }}" required value="{{ old('first_name') ?: $staff->first_name }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('staff.input.label.last_name') }}</label>
                            <input type="text" name="last_name" placeholder="{{ trans('staff.input.placeholder.last_name') }}" value="{{ old('last_name') ?: $staff->last_name }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('staff.input.label.email') }}</label>
                            <input type="email" name="email" placeholder="{{ trans('staff.input.placeholder.email') }}" required value="{{ old('email') ?: $staff->email }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('staff.input.label.phone') }}</label>
                            <input type="tel" placeholder="{{ trans('staff.input.placeholder.phone') }}" value="{{ old('phone') ?: $staff->phone }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>{{ trans('staff.input.label.birthday') }}</label>
                            <input type="text" class="datepicker" name="birthday" placeholder="{{ trans('staff.input.placeholder.birthday') }}" value="{{ old('birthday') ?: $staff->birthday }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('staff.input.label.address') }}</label>
                            <input type="text" name="address" placeholder="{{ trans('staff.input.placeholder.address') }}" value="{{ old('address') ?: $staff->address }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('staff.input.label.company') }}</label>
                            <select class="dropdown fluid search ui" name="company_id" required>
                                @foreach($companies as $company)
                                <option{{ !empty(old('company_id')) && old('company_id') === $company->id || empty(old('company_id')) && $company === $staff->company ? ' selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field required">
                            <label>{{ trans('staff.input.label.commission_rate') }} (%)</label>
                            <input type="number" max="100" min="0" name="commission_rate" placeholder="{{ trans('staff.input.placeholder.commission_rate') }}" required step="0.01" value="{{ old('commission_rate') ?: $staff->commission_rate }}"/>
                        </div>
                    </div>
                    <div class="field">
                        <label>{{ trans('staff.input.label.profile_image') }}</label>
                        <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload" data-default-file="{{ asset('uploads/images/users/' . $staff->profile_image_filename) }}" name="profile_image"/>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('staff.modal.button.cancel.edit') }}</button>
                <div class="or" data-text="{{ trans('products.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('staff.modal.button.confirm.edit') }}</button>
            </div>
        </div>
    </div>
@endsection