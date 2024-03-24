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
                    <div class="field required">
                        <label>{{ trans('clients.input.label.company') }}</label>
                        <select class="dropdown fluid search ui" name="company_id" required>
                            @foreach($companies as $company)
                            <option{{ !empty(old('company_id')) && old('company_id') === $company->id || empty(old('company_id')) && $company->id === $client->company->id ? ' selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field required">
                        <label>{{ trans('clients.input.label.inviter') }}</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="inviter_id" value="{{ old('inviter_id') ?: $user->id }}"/>
                            <div class="default text">{{ trans('policies.input.placeholder.inviter') }}</div>
                            <i class="dropdown icon"></i>
                            <div class="menu" id="">
                                @foreach($companies as $company)
                                <div class="company{{ $company->id }} header"{!! $company->id === $client->company->id ? '' : ' style="display:none;"' !!}>
                                    {{ trans('clients.input.option.header.company') }}
                                </div>
                                <div class="company{{ $company->id }} item{{ !empty(old('inviter_id')) && old('inviter_id') === $company->admin->id || empty(old('company_id')) && $company->admin->id === $client->inviter->id ? ' selected' : '' }}" data-value="{{ $company->admin->id }}"{!! $company->id === $client->company->id ? '' : ' style="display:none;"'  !!}>
                                    <i class="building icon"></i>
                                    {{ $company->name }} {{ $company->admin->id === $user->id ? trans('clients.input.option.you') : '' }}
                                </div>
                                <div class="company{{ $company->id }} divider"{!! $company->id === $client->company->id ? '' : ' style="display:none;"' !!}></div>
                                <div class="company{{ $company->id }} header"{!! $company->id === $client->company->id ? '' : ' style="display:none;"' !!}>
                                    {{ trans('clients.input.option.header.staff') }}
                                </div>
                                @forelse($company->staff as $staff)
                                <div class="company{{ $company->id }} item{{ !empty(old('inviter_id')) && old('inviter_id') === $staff->id || empty(old('inviter_id')) && $staff->id === $client->inviter->id ? ' selected' : '' }}" data-value="{{ $staff->id }}"{!! $company->id === $client->company->id ? '' : ' style="display:none;"'  !!}>
                                    <i class="address book icon"></i>
                                    {{ $staff->first_name }} - {{ $staff->email }} {{ $staff->id === $user->id ? trans('clients.input.option.you') : '' }}
                                </div>
                                @empty
                                <div class="company{{ $company->id }} disabled item" data-value=""{!! $company->id === $client->company->id ? '' : ' style="display:none;"' !!}>
                                    {{ trans('clients.input.option.empty.brokers', array(
                                        'company_name'  => $company->name
                                    )) }}
                                </div>
                                @endforelse
                                <div class="company{{ $company->id }} divider"{!! $company->id === $client->company->id ? '' : ' style="display:none;"' !!}></div>
                                <div class="company{{ $company->id }} header"{!! $company->id === $client->company->id ? '' : ' style="display:none;"' !!}>
                                    {{ trans('clients.input.option.header.brokers') }}
                                </div>
                                @forelse($company->brokers as $broker)
                                <div class="company{{ $company->id }} item{{ !empty(old('inviter_id')) && old('inviter_id') === $broker->id || empty(old('inviter_id')) && $broker->id === $client->inviter->id ? ' selected' : '' }}" data-value="{{ $broker->id }}"{!! $company->id === $client->company->id ? '' : ' style="display:none;"'  !!}>
                                    <i class="briefcase icon"></i>
                                    {{ $broker->first_name }} - {{ $broker->email }} {{ $broker->id === $user->id ? trans('clients.input.option.you') : '' }}
                                </div>
                                @empty
                                <div class="company{{ $company->id }} disabled item" data-value=""{!! $company->id === $client->company->id ? '' : ' style="display:none;"' !!}>
                                    {{ trans('clients.input.option.empty.brokers', array(
                                        'company_name'  => $company->name
                                    )) }}
                                </div>
                                @endforelse
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    @foreach ($client->customFields->all() as $custom_field)
                    <input type="hidden" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][label]" value="{{ $custom_field->label }}"/>
                    <input type="hidden" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][type]" value="{{ $custom_field->type }}"/>
                    <input type="hidden" name="custom_fields[C{{ $client->company->id }}F{{ $custom_field->id }}][uuid]" value="{{ $custom_field->uuid }}"/>
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
                    <div class="divider" style="display:none;"></div>
                    <div class="field">
                        <label>{{ trans('clients.input.label.profile_image') }}</label>
                        <input type="file" accept="image/*" data-allowed-file-extensions="bmp gif jpeg jpg png svg" class="file-upload" data-default-file="{{ asset('uploads/images/users/' . $client->profile_image_filename) }}" name="profile_image"/>
                    </div>
                </div>
            </form>
            @foreach ($companies as $company)
            <div id="company{{ $company->id }}CustomFields" style="display:none;">
                @foreach ($company->custom_fields_metadata->where('model', 'client')->all() as $key => $field)
                <input type="hidden" name="custom_fields[C{{ $company->id }}F{{ $key }}][label]" value="{{ $field->label }}"/>
                <input type="hidden" name="custom_fields[C{{ $company->id }}F{{ $key }}][type]" value="{{ $field->type }}"/>
                <input type="hidden" name="custom_fields[C{{ $company->id }}F{{ $key }}][uuid]" value="{{ $field->uuid }}"/>
                    @if ($field->type === 'checkbox')
                <div class="field{{ isset($field->required) ? ' required' : '' }}">
                    <div class="ui checkbox">
                        <input type="checkbox"{{ isset($field->default) ? ' checked' : '' }} name="custom_fields[C{{ $company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }}>
                        <label>{{ $field->label }}</label>
                    </div>
                </div>
                    @elseif ($field->type === 'date')
                <div class="field{{ isset($field->required) ? ' required' : '' }}">
                    <label>{{ $field->label }}</label>
                    <input type="text" class="datepicker" name="custom_fields[C{{ $company->id }}F{{ $key }}][value]""{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                </div>
                    @elseif ($field->type === 'email')
                <div class="field{{ isset($field->required) ? ' required' : '' }}">
                    <label>{{ $field->label }}</label>
                    <input type="email" name="custom_fields[C{{ $company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                </div>
                    @elseif ($field->type === 'hidden')
                <input type="hidden" name="custom_fields[C{{ $company->id }}F{{ $key }}][value]" value="{{ $field->default }}">
                    @elseif ($field->type === 'number')
                <div class="field{{ isset($field->required) ? ' required' : '' }}">
                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                    <input type="number" name="custom_fields[C{{ $company->id }}F{{ $key }}][value]""{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                </div>
                    @elseif ($field->type === 'select')
                        @foreach ($field->default->choices as $option)
                <input type="hidden" name="custom_fields[C{{ $client->company->id }}F{{ $key }}][value][choices][]" value="{{ trim($option) }}"/>
                        @endforeach
                        @if (count($field->default->choices) > 2)
                <div class="field{{ isset($field->required) ? ' required' : '' }}">
                    <label>{{ $field->label }}</label>
                    <select class="ui fluid search dropdown" name="custom_fields[C{{ $company->id }}F{{ $key }}][value][choice]">
                        <option value="">{{ trans('clients.input.placeholder.custom_select') }}</option>
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
                            <label>{{ trim($option) }}</label>
                        </div>
                    </div>
                            @endforeach
                </div>
                        @endif
                    @elseif ($field->type === 'tel')
                <div class="field{{ isset($field->required) ? ' required' : '' }}">
                    <label>{{ $field->label }}</label>
                    <input type="tel" name="custom_fields[C{{ $company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                </div>
                    @elseif ($field->type === 'text')
                <div class="field">
                    <label>{{ $field->label }}</label>
                    <input type="tel" name="custom_fields[C{{ $company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                </div>
                    @else
                <div class="field">
                    <label>{{ $field->label }}</label>
                    <textarea name="custom_fields[C{{ $company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} rows="2">{{ $field->default }}</textarea>
                </div>
                    @endif
                @endforeach
            </div>
            @endforeach
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

@section('extra_scripts')
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                // Watch for a company change
                $('select[name="company_id"]').change(function() {
                    var companyId = $(this).val();
                    var customFieldsDivider = $('div#editClientModal div.form > div.divider:first');
                    $('div[class^="company"]').hide();
                    $('div.company' + companyId).show();
                    $('input[name="inviter_id"]').val('').parent().find('div.text').text('');
                    while(!customFieldsDivider.next().hasClass('divider')) {
                        customFieldsDivider.next().remove();
                    }
                    customFieldsDivider.after($('div#company' + companyId + 'CustomFields').html());
                    (function semanticInit(div) {
                        if(div.length > 0) {
                            $insura.helpers.initDatepicker(div.find('input.datepicker'));
                            $insura.helpers.initDropdown(div.find('div.dropdown'));
                            $insura.helpers.initTelInput(div.find('input[type="tel"]'));
                            $insura.helpers.requireDropdownFields(div.find('div.required select, div.required div.dropdown input[type="hidden"]'));
                            semanticInit(div.next());
                        }
                    })(customFieldsDivider.next());
                });
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
