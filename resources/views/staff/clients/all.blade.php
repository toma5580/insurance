@extends('templates.clients.all')

@section('modals')
    <!-- new client modal -->
    <div class="ui tiny modal" id="newClientModal">
        <div class="header">{{ trans('clients.modal.header.new') }}</div>
        <div class="scrolling content">
            <p>{{ trans('clients.modal.instruction.new') }}</p>
            <form action="{{ action('ClientController@add') }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('clients.input.label.first_name') }}</label>
                            <input type="text" name="first_name" placeholder="{{ trans('clients.input.placeholder.first_name') }}" required value="{{ old('first_name') }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('clients.input.label.last_name') }}</label>
                            <input type="text" name="last_name" placeholder="{{ trans('clients.input.placeholder.last_name') }}" value="{{ old('last_name') }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('clients.input.label.email') }}</label>
                            <input type="email" name="email" placeholder="{{ trans('clients.input.placeholder.email') }}" required value="{{ old('email') }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('clients.input.label.phone') }}</label>
                            <input type="tel" placeholder="{{ trans('clients.input.placeholder.phone') }}" value="{{ old('phone') }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>{{ trans('clients.input.label.birthday') }}</label>
                            <input type="text" class="datepicker" name="birthday" placeholder="{{ trans('clients.input.placeholder.birthday') }}" value="{{ old('birthday') }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('clients.input.label.address') }}</label>
                            <input type="text" name="address" placeholder="{{ trans('clients.input.placeholder.address') }}" value="{{ old('address') }}"/>
                        </div>
                    </div>
                    <div class="field required">
                        <label>{{ trans('clients.input.label.inviter') }}</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="product" value="{{ old('product_id') ?: $user->id }}"/>
                            <div class="default text">{{ trans('policies.input.placeholder.inviter') }}</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <div class="header">
                                    {{ trans('clients.input.option.header.company') }}
                                </div>
                                <div class="item{{ !empty(old('inviter_id')) && old('inviter_id') === $user->company->admin->id || empty(old('company_id')) && $user->company->admin->id === $user->id ? ' selected' : '' }}" data-value="{{ $user->company->admin->id }}">
                                    <i class="building icon"></i>
                                    {{ $user->company->name }} {{ $user->company->admin->id === $user->id ? trans('clients.input.option.you') : '' }}
                                </div>
                                <div class="divider"></div>
                                <div class="header">
                                    {{ trans('clients.input.option.header.staff') }}
                                </div>
                                @forelse($user->company->staff as $staff)
                                <div class="item{{ !empty(old('inviter_id')) && old('inviter_id') === $staff->id || empty(old('inviter_id')) && $staff->id === $user->id ? ' selected' : '' }}" data-value="{{ $staff->id }}">
                                    <i class="address book icon"></i>
                                    {{ $staff->first_name }} - {{ $staff->email }} {{ $staff->id === $user->id ? trans('clients.input.option.you') : '' }}
                                </div>
                                @empty
                                <div class="disabled item" data-value="">
                                    {{ trans('clients.input.option.empty.brokers', array(
                                        'company_name'  => $user->company->name
                                    )) }}
                                </div>
                                @endforelse
                                <div class="divider"></div>
                                <div class="header">
                                    {{ trans('clients.input.option.header.brokers') }}
                                </div>
                                @forelse($user->company->brokers as $broker)
                                <div class="item{{ !empty(old('inviter_id')) && old('inviter_id') === $broker->id || empty(old('inviter_id')) && $broker->id === $user->id ? ' selected' : '' }}" data-value="{{ $broker->id }}">
                                    <i class="briefcase icon"></i>
                                    {{ $broker->first_name }} - {{ $broker->email }} {{ $broker->id === $user->id ? trans('clients.input.option.you') : '' }}
                                </div>
                                @empty
                                <div class="disabled item" data-value="">
                                    {{ trans('clients.input.option.empty.staff', array(
                                        'company_name'  => $user->company->name
                                    )) }}
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    @foreach (collect(json_decode($user->company->custom_fields_metadata ?: '[]'))->where('model', 'client')->all() as $key => $field)
                    <input type="hidden" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][label]" value="{{ $field->label }}"/>
                    <input type="hidden" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][type]" value="{{ $field->type }}"/>
                    <input type="hidden" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][uuid]" value="{{ $field->uuid }}"/>
                        @if ($field->type === 'checkbox')
                    <div class="field{{ isset($field->required) ? ' required' : '' }}">
                        <div class="ui checkbox">
                            <input type="checkbox"{{ isset($field->default) ? ' checked' : '' }} name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }}>
                            <label>{{ $field->label }}</label>
                        </div>
                    </div>
                        @elseif ($field->type === 'date')
                    <div class="field{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                        <input type="text" class="datepicker" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]""{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                    </div>
                        @elseif ($field->type === 'email')
                    <div class="field{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                        <input type="email" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                    </div>
                        @elseif ($field->type === 'hidden')
                    <input type="hidden" name="{{ $field->label }}" value="{{ $field->default }}">
                        @elseif ($field->type === 'number')
                    <div class="field{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                        <input type="number" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]""{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                    </div>
                        @elseif ($field->type === 'select')
                            @foreach ($field->default->choices as $option)
                    <input type="hidden" name="custom_fields[C{{ $company->id }}F{{ $key }}][value][choices][]" value="{{ $option }}"/>
                            @endforeach
                            @if (count($field->default->choices) > 2)
                    <div class="field{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                        <select class="ui fluid search dropdown" name="custom_fields[C{{ $company->id }}F{{ $key }}][value][choice]">
                            <option value="">{{ $field->label }}</option>
                                @foreach ($field->default->choices as $option)
                            <option{{ $option === $field->default->choice ? ' selected' : '' }} value="{{ trim($option) }}">{{ trim($option) }}</option>
                                @endforeach
                        </select>
                    </div>
                            @else
                    <div class="inline fields{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                                @foreach ($field->default->choices as $option)
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio"{{ $option === $field->default->choice ? ' checked' : '' }} name="custom_fields[C{{ $company->id }}F{{ $key }}][value][choice]"{{ isset($field->required) ? ' required' : '' }} value="{{ trim($option) }}">
                                <label>{{ $option }}</label>
                            </div>
                        </div>
                                @endforeach
                    </div>
                            @endif
                        @elseif ($field->type === 'tel')
                    <div class="field{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                        <input type="tel" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                    </div>
                        @elseif ($field->type === 'text')
                    <div class="field">
                        <label>{{ $field->label }}</label>
                        <input type="tel" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                    </div>
                        @else
                    <div class="field">
                        <label>{{ $field->label }}</label>
                        <textarea name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} rows="2">{{ $field->default }}</textarea>
                    </div>
                        @endif
                    @endforeach
                    <div class="field">
                        <label>{{ trans('clients.input.label.profile_image') }}</label>
                        <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload" data-default-file="{{ asset('uploads/images/users/default-profile.jpg') }}" name="profile_image"/>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('clients.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('products.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('clients.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>
@endsection
