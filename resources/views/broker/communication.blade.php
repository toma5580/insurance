@extends('templates.communication')

@section('modals')
    <!-- new email modal -->
    <div class="ui tiny modal" id="newEmailModal">
        <div class="header">{{ trans('communication.modal.header.email') }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.email') }}</p>
            <form action="{{ action('EmailController@add') }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('communication.input.label.recipients') }}</label>
                        <select name="recipient" class="ui fluid search dropdown" required>
                            <option value="">{{ trans('communication.input.option.default') }}</option>
                            @if ($user->invitees->count() > 0)
                            <option{{ old('recipient') === 'clients' ? ' selected' : '' }} value="clients">{{ trans('communication.input.option.clients') }}</option>
                            @endif
                            @forelse ($user->company->users->keyBy('id')->except($user->id) as $company_user)
                            <option{{ old('recipient') == $company_user->id ? ' selected' : '' }} value="{{ $company_user->id }}">{{ $company_user->first_name . ' ' . $company_user->last_name }} - {{ studly_case($company_user->role) }}</option>
                            @empty
                            <option value="">{{ trans('communication.input.option.empty', array(
                                'company_name'  => $user->company->name
                            )) }}</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="field required">
                        <label>{{ trans('communication.input.label.subject') }}</label>
                        <input type="text" name="subject" placeholder="{{ trans('communication.input.placeholder.subject') }}" required value="{{ old('subject') }}"/>
                    </div>
                    <div class="field required">
                        <label>{{ trans('communication.input.label.message') }}</label>
                        <textarea rows="5" name="message" placeholder="{{ trans('communication.input.placeholder.message') }}" required>{{ session('text') ? '' : old('message') }}</textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('communication.modal.button.cancel') }}</button>
                <div class="or" data-text="{{ trans('communication.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('communication.modal.button.confirm') }}</button>
            </div>
        </div>
    </div>

    <!-- new text modal -->
    <div class="ui tiny modal" id="newTextModal">
        <div class="header">{{ trans('communication.modal.header.text') }}</div>
        <div class="content">
            <p>{{ trans('communication.modal.instruction.text') }}</p>
            <form action="{{ action('TextController@add') }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('communication.input.label.recipients') }}</label>
                        <select name="recipient" class="ui fluid search dropdown" required>
                            <option value="">{{ trans('communication.input.option.default') }}</option>
                            @if ($user->invitees->count() > 0)
                            <option{{ old('recipient') === 'clients' ? ' selected' : '' }} value="clients">{{ trans('communication.input.option.clients') }}</option>
                            @endif
                            @forelse ($user->company->users->keyBy('id')->except($user->id) as $company_user)
                            <option{{ old('recipient') == $company_user->id ? ' selected' : '' }} value="{{ $company_user->id }}">{{ $company_user->first_name . ' ' . $company_user->last_name }} - {{ studly_case($company_user->role) }}</option>
                            @empty
                            <option value="">{{ trans('communication.input.option.empty', array(
                                'company_name'  => $user->company->name
                            )) }}</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="field required">
                        <label>{{ trans('communication.input.label.message') }}</label>
                        <textarea rows="5" name="message" placeholder="{{ trans('communication.input.placeholder.message') }}" required>{{ session('text') ? old('message') : '' }}</textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('communication.modal.button.cancel') }}</button>
                <div class="or" data-text="{{ trans('communication.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('communication.modal.button.confirm') }}</button>
            </div>
        </div>
    </div>
@endsection
