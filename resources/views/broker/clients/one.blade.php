@extends('templates.clients.one')

@section('client_modals')
    <!-- edit client modal -->
    <div class="ui tiny modal" id="editClientModal">
        <div class="header">{{ trans('clients.modal.header.edit') }}</div>
        <div class="scrolling content">
            <p>{{ trans('clients.modal.instruction.edit') }}</p>
            <form action="{{ action('ClientController@edit', array($client->id)) }}" enctype="multipart/form-data" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('clients.input.label.first_name') }}</label>
                            <input type="text" name="first_name" placeholder="{{ trans('clients.input.placeholder.first_name') }}" required value="{{ old('first_name') ?: $client->first_name }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('clients.input.label.last_name') }}</label>
                            <input type="text" name="last_name" placeholder="{{ trans('clients.input.placeholder.last_name') }}" value="{{ old('last_name') ?: $client->last_name }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('clients.input.label.email') }}</label>
                            <input type="email" name="email" placeholder="{{ trans('clients.input.placeholder.email') }}" required value="{{ old('email') ?: $client->email }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('clients.input.label.phone') }}</label>
                            <input type="tel" placeholder="{{ trans('clients.input.placeholder.phone') }}" value="{{ old('phone') ?: $client->phone }}"/>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>{{ trans('clients.input.label.birthday') }}</label>
                            <input type="text" class="datepicker" name="birthday" placeholder="{{ trans('clients.input.placeholder.birthday') }}" value="{{ old('birthday') ?: $client->birthday }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('clients.input.label.address') }}</label>
                            <input type="text" name="address" placeholder="{{ trans('clients.input.placeholder.address') }}" value="{{ old('address') ?: $client->address }}"/>
                        </div>
                    </div>
                    <div class="divider"></div>
                    @foreach ($client->customFields as $custom_field)
                    <input type="hidden" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][type]" value="{{ $custom_field->type }}"/>
                    <input type="hidden" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][label]" value="{{ $custom_field->label }}"/>
                        @if ($custom_field->type === 'checkbox')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <div class="ui checkbox">
                            <input type="checkbox"{{ isset($custom_field->value) ? ' checked' : '' }} name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }}>
                            <label>{{ $custom_field->label }}</label>
                        </div>
                    </div>
                        @elseif ($custom_field->type === 'date')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <input type="text" class="datepicker" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value]""{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->value }}">
                    </div>
                        @elseif ($custom_field->type === 'email')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <input type="email" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->value }}">
                    </div>
                        @elseif ($custom_field->type === 'hidden')
                    <input type="hidden" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value]" value="{{ $custom_field->value }}">
                        @elseif ($custom_field->type === 'number')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <input type="number" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value]""{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->value }}">
                    </div>
                        @elseif ($custom_field->type === 'select')
                            @foreach (json_decode($custom_field->value)->choices as $option)
                    <input type="hidden" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value][choices][]" value="{{ $option }}"/>
                            @endforeach
                            @if (count(json_decode($custom_field->value)->choices) > 2)
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <select class="ui fluid search dropdown" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value][choice]">
                            <option value="">{{ $custom_field->label }}</option>
                                @foreach (json_decode($custom_field->value)->choices as $option)
                            <option{{ $option === json_decode($custom_field->value)->choice ? ' selected' : '' }} value="{{ trim($option) }}">{{ trim($option) }}</option>
                                @endforeach
                        </select>
                    </div>
                            @else
                    <div class="inline fields{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                                @foreach (json_decode($custom_field->value)->choices as $option)
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="custom_fields[C{{ $company->id }}F{{ $custom_field->id }}][value][choice]"{{ isset($custom_field->required) ? ' required' : '' }} value="{{ trim($option) }}">
                                <label>{{ trim($option) }}</label>
                            </div>
                        </div>
                                @endforeach
                    </div>
                            @endif
                        @elseif ($custom_field->type === 'tel')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <input type="tel" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->default }}">
                    </div>
                        @elseif ($custom_field->type === 'text')
                    <div class="field">
                        <label>{{ $custom_field->label }}</label>
                        <input type="tel" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->default }}">
                    </div>
                        @else
                    <div class="field">
                        <label>{{ $custom_field->label }}</label>
                        <textarea name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }} rows="2">{{ $custom_field->default }}</textarea>
                    </div>
                        @endif
                    @endforeach
                    <div class="field">
                        <label>{{ trans('clients.input.label.profile_image') }}</label>
                        <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload" data-default-file="{{ asset('uploads/images/users/' . $client->profile_image_filename) }}" name="profile_image"/>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('clients.modal.button.cancel.edit') }}</button>
                <div class="or" data-text="{{ trans('products.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('clients.modal.button.confirm.edit') }}</button>
            </div>
        </div>
    </div>
@endsection
