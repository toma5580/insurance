@extends('templates.settings')

@section('company_menu')
<a class="item{{ session()->get('tab', null) === 'company' ? ' active' : '' }}" data-tab="company">{{ trans('settings.tab.menu.company') }}</a>
@endsection

@section('reminders_menu')
<a class="item{{ session()->get('tab', null) === 'reminders' ? ' active' : '' }}" data-tab="reminders">{{ trans('settings.tab.menu.reminders') }}</a>
@endsection

@section('company_tab')
        <div class="ui bottom attached tab segment{{ session()->get('tab', null) === 'company' ? ' active' : '' }}" data-tab="company">
            <div class="row">
                <div class="col-md-6">
                    <p>{{ trans('settings.tab.message.company') }}</p>
                    <form action="{{ action('CompanyController@edit', [$company->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="field required">
                                <label>{{ trans('settings.input.label.name') }}</label>
                                <input type="text" maxlength="64" name="name" placeholder="{{ trans('settings.input.placeholder.name') }}" required value="{{ $company->name }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.email') }}</label>
                                <input type="email" maxlength="64" name="email" placeholder="{{ trans('settings.input.placeholder.email') }}" value="{{ $company->email }}">
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.phone') }}</label>
                                <input type="tel" maxlength="16" placeholder="{{ trans('settings.input.placeholder.phone') }}" value="{{ $company->phone }}">
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.commission_rate') }}</label>
                                <input type="number" name="commission_rate" placeholder="{{ trans('settings.input.placeholder.commission_rate') }}" step="0.01" value="{{ $company->commission_rate }}">
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.address') }}</label>
                                <input type="text" maxlength="256" name="address" placeholder="{{ trans('settings.input.placeholder.address') }}" value="{{ $company->address }}">
                            </div>
                            <div class="field required">
                                <label>{{ trans('settings.input.label.currency') }}</label>
                                <select class="ui fluid search dropdown" name="currency_code">
                                    @foreach(config('insura.currencies.list') as $currency)
                                    <option{{ $company->currency_code === $currency['code'] ? ' selected' : '' }} value="{{ $currency['code'] }}">{{ $currency['name_plural'] }} ({{ $currency['code'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.email_signature') }}</label>
                                <textarea name="email_signature" rows="9">{{ str_replace('<br/>', "\n", $company->email_signature) }}</textarea>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.text_signature') }}</label>
                                <textarea maxlength="32" name="text_signature" rows="2">{{ $company->text_signature }}</textarea>
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.product_categories') }}</label>
                                <textarea name="product_categories" placeholder="{{ trans('settings.input.placeholder.product_categories') }}" rows="2">{{ $company->product_categories }}</textarea>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.product_sub_categories') }}</label>
                                <textarea name="product_sub_categories" placeholder="{{ trans('settings.input.placeholder.product_sub_categories') }}" rows="2">{{ $company->product_sub_categories }}</textarea>
                            </div>
                            <h5 class="ui dividing header disabled">{{ trans_choice('settings.input.label.custom_field', 1) }}</h5>
                            @foreach (collect(json_decode($company->custom_fields_metadata ?: '[]'))->where('model', 'client')->all() as $key => $field)
                            <div class="fields two">
                                <input type="hidden" name="custom_fields[C{{ $key }}][model]" value="client"/>
                                <input type="hidden" name="custom_fields[C{{ $key }}][uuid]" value="{{ $field->uuid }}"/>
                                <div class="field required">
                                    <label>{{ trans('settings.input.label.custom_type') }}</label>
                                    <select class="ui fluid search dropdown" name="custom_fields[C{{ $key }}][type]" value="{{ $field->type }}">
                                        <option value="">{{ trans('settings.input.placeholder.custom_type') }}</option>
                                        <option{{ $field->type === 'checkbox' ? ' selected' : '' }} value="checkbox">{{ trans('settings.input.option.custom_type.checkbox') }}</option>
                                        <option{{ $field->type === 'date' ? ' selected' : '' }} value="date">{{ trans('settings.input.option.custom_type.date') }}</option>
                                        <option{{ $field->type === 'email' ? ' selected' : '' }} value="email">{{ trans('settings.input.option.custom_type.email') }}</option>
                                        <option{{ $field->type === 'hidden' ? ' selected' : '' }} value="hidden">{{ trans('settings.input.option.custom_type.hidden') }}</option>
                                        <option{{ $field->type === 'number' ? ' selected' : '' }} value="number">{{ trans('settings.input.option.custom_type.number') }}</option>
                                        <option{{ $field->type === 'select' ? ' selected' : '' }} value="select">{{ trans('settings.input.option.custom_type.select') }}</option>
                                        <option{{ $field->type === 'tel' ? ' selected' : '' }} value="tel">{{ trans('settings.input.option.custom_type.tel') }}</option>
                                        <option{{ $field->type === 'text' ? ' selected' : '' }} value="text">{{ trans('settings.input.option.custom_type.text') }}</option>
                                        <option{{ $field->type === 'textarea' ? ' selected' : '' }} value="textarea">{{ trans('settings.input.option.custom_type.textarea') }}</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_label') }}</label>
                                    <input type="text" maxlength="32" name="custom_fields[C{{ $key }}][label]" placeholder="{{ trans('settings.input.placeholder.custom_label') }}" value="{{ $field->label }}">
                                </div>
                                <i class="close icon"></i>
                            </div>
                            <div class="fields two">
                                @if ($field->type === 'checkbox')
                                <div class="field">
                                    <div class="ui checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->default) ? ' checked' : '' }}  name="custom_fields[C{{ $key }}][default]">
                                        <label>{{ trans('settings.input.label.custom_default') }} {{ $field->default }} {{ $field->required }}</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'date')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="text" class="datepicker" name="custom_fields[C{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'email')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="email" name="custom_fields[C{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'hidden')
                                <div class="field required">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="text" name="custom_fields[C{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" required value="{{ $field->default }}">
                                </div>
                                <div class="field disabled">
                                    <div class="ui toggle disabled checkbox m-t-30">
                                        <input type="checkbox" disabled name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'number')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="number" name="custom_fields[C{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" step="0.01" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'select')
                                <div class="field required">
                                    <label>{{ trans('settings.input.label.custom_options') }}</label>
                                    <textarea name="custom_fields[C{{ $key }}][default][choices]" placeholder="{{ trans('settings.input.placeholder.custom_options') }}" required rows="2">{{ implode("\n", $field->default->choices) }}</textarea>
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'tel')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="tel" name="custom_fields[C{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'text')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="tel" name="custom_fields[C{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @else
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <textarea name="custom_fields[C{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" rows="2">{{ $field->default }}</textarea>
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[C{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                            <div class="field">
                                <button class="ui button m-w-140" id="addCustomClientField" type="button">{{ trans('settings.button.add_client_field') }}</button>
                            </div>
                            <h5 class="ui dividing header disabled">{{ trans_choice('settings.input.label.custom_field', 2) }}</h5>
                            @foreach (collect(json_decode($company->custom_fields_metadata ?: '[]'))->where('model', 'policy')->all() as $key => $field)
                            <div class="fields two">
                                <input type="hidden" name="custom_fields[P{{ $key }}][model]" value="policy"/>
                                <input type="hidden" name="custom_fields[P{{ $key }}][uuid]" value="{{ $field->uuid }}"/>
                                <div class="field required">
                                    <label>{{ trans('settings.input.label.custom_type') }}</label>
                                    <select class="ui fluid search dropdown" name="custom_fields[P{{ $key }}][type]" value="{{ $field->type }}">
                                        <option value="">{{ trans('settings.input.placeholder.custom_type') }}</option>
                                        <option{{ $field->type === 'checkbox' ? ' selected' : '' }} value="checkbox">{{ trans('settings.input.option.custom_type.checkbox') }}</option>
                                        <option{{ $field->type === 'date' ? ' selected' : '' }} value="date">{{ trans('settings.input.option.custom_type.date') }}</option>
                                        <option{{ $field->type === 'email' ? ' selected' : '' }} value="email">{{ trans('settings.input.option.custom_type.email') }}</option>
                                        <option{{ $field->type === 'hidden' ? ' selected' : '' }} value="hidden">{{ trans('settings.input.option.custom_type.hidden') }}</option>
                                        <option{{ $field->type === 'number' ? ' selected' : '' }} value="number">{{ trans('settings.input.option.custom_type.number') }}</option>
                                        <option{{ $field->type === 'select' ? ' selected' : '' }} value="select">{{ trans('settings.input.option.custom_type.select') }}</option>
                                        <option{{ $field->type === 'tel' ? ' selected' : '' }} value="tel">{{ trans('settings.input.option.custom_type.tel') }}</option>
                                        <option{{ $field->type === 'text' ? ' selected' : '' }} value="text">{{ trans('settings.input.option.custom_type.text') }}</option>
                                        <option{{ $field->type === 'textarea' ? ' selected' : '' }} value="textarea">{{ trans('settings.input.option.custom_type.textarea') }}</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_label') }}</label>
                                    <input type="text" maxlength="32" name="custom_fields[P{{ $key }}][label]" placeholder="{{ trans('settings.input.placeholder.custom_label') }}" value="{{ $field->label }}">
                                </div>
                                <i class="close icon"></i>
                            </div>
                            <div class="fields two">
                                @if ($field->type === 'checkbox')
                                <div class="field">
                                    <div class="ui checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->default) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][default]">
                                        <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'date')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="text" class="datepicker" name="custom_fields[P{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'email')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="email" name="custom_fields[P{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'hidden')
                                <div class="field required">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="text" name="custom_fields[P{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" required value="{{ $field->default }}">
                                </div>
                                <div class="field disabled">
                                    <div class="ui toggle disabled checkbox m-t-30">
                                        <input type="checkbox" disabled name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'number')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="number" name="custom_fields[P{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" step="0.01" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'select')
                                <div class="field required">
                                    <label>{{ trans('settings.input.label.custom_options') }}</label>
                                    <textarea name="custom_fields[P{{ $key }}][default][choices]" placeholder="{{ trans('settings.input.placeholder.custom_options') }}" required rows="2">{{ implode("\n", $field->default->choices) }}</textarea>
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'tel')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="tel" name="custom_fields[P{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @elseif ($field->type === 'text')
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <input type="tel" name="custom_fields[P{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" value="{{ $field->default }}">
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @else
                                <div class="field">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                    <textarea name="custom_fields[P{{ $key }}][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" rows="2">{{ $field->default }}</textarea>
                                </div>
                                <div class="field">
                                    <div class="ui toggle checkbox m-t-30">
                                        <input type="checkbox"{{ isset($field->required) ? ' checked' : '' }} name="custom_fields[P{{ $key }}][required]">
                                        <label>{{ trans('settings.input.label.custom_required') }}</label>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                            <div class="field">
                                <button class="ui button m-w-140" id="addCustomPolicyField" type="button">{{ trans('settings.button.add_policy_field') }}</button>
                            </div>
                            <div class="divider"></div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.text_provider') }}</label>
                                <select class="ui fluid search dropdown" name="text_provider">
                                    <option value="">Please Select</option>
                                    <option{{ $company->text_provider === 'aft' ? ' selected' : '' }} value="aft">Africa's Talking</option>
                                    <option{{ $company->text_provider === 'twilio' ? ' selected' : '' }} value="twilio">Twilio</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.twilio_auth_token') }}</label>
                                <input type="text" maxlength="64" name="twilio_auth_token" placeholder="{{ trans('settings.input.placeholder.twilio_auth_token') }}" value="{{ $company->twilio_auth_token }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.twilio_number') }}</label>
                                <input type="text" maxlength="32" name="twilio_number" placeholder="{{ trans('settings.input.placeholder.twilio_number') }}" value="{{ $company->twilio_number }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.twilio_sid') }}</label>
                                <input type="text" maxlength="64" name="twilio_sid" placeholder="{{ trans('settings.input.placeholder.twilio_sid') }}" value="{{ $company->twilio_sid }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.aft_api_key') }}</label>
                                <input type="text" maxlength="64" name="aft_api_key" placeholder="{{ trans('settings.input.placeholder.aft_api_key') }}" value="{{ $company->aft_api_key }}"/>
                            </div>
                            <div class="field">
                                <label>{{ trans('settings.input.label.aft_username') }}</label>
                                <input type="text" maxlength="64" name="aft_username" placeholder="{{ trans('settings.input.placeholder.aft_username') }}" value="{{ $company->aft_username }}"/>
                            </div>
                            <div class="field">
                                <button class="ui right floated button primary m-w-140" type="submit">{{ trans('settings.button.save') }}</button>
                            </div>
                        </div>
                    </form>
                    <div id="customFieldTemplate" style="display:none;">
                        <div class="fields two">
                            <input type="hidden" name="custom_fields[][model]"/>
                            <div class="field required">
                                <label>{{ trans('settings.input.label.custom_type') }}</label>
                                <select class="ui fluid search dropdown" name="custom_fields[][type]">
                                    <option value="">{{ trans('settings.input.placeholder.custom_type') }}</option>
                                    <option value="checkbox">{{ trans('settings.input.option.custom_type.checkbox') }}</option>
                                    <option value="date">{{ trans('settings.input.option.custom_type.date') }}</option>
                                    <option value="email">{{ trans('settings.input.option.custom_type.email') }}</option>
                                    <option value="hidden">{{ trans('settings.input.option.custom_type.hidden') }}</option>
                                    <option value="number">{{ trans('settings.input.option.custom_type.number') }}</option>
                                    <option value="select">{{ trans('settings.input.option.custom_type.select') }}</option>
                                    <option value="tel">{{ trans('settings.input.option.custom_type.tel') }}</option>
                                    <option value="text">{{ trans('settings.input.option.custom_type.text') }}</option>
                                    <option value="textarea">{{ trans('settings.input.option.custom_type.textarea') }}</option>
                                </select>
                            </div>
                            <div class="field required">
                                <label>{{ trans('settings.input.label.custom_label') }}</label>
                                <input type="text" maxlength="32" name="custom_fields[][label]" placeholder="{{ trans('settings.input.placeholder.custom_label') }}" required>
                            </div>
                            <i class="close icon"></i>
                        </div>
                        <div class="fields two">
                            <div class="field disabled">
                                <label>{{ trans('settings.input.label.custom_default') }}</label>
                                <input type="text" disabled name="custom_fields[][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}">
                            </div>
                            <div class="field disabled">
                                <div class="ui toggle disabled checkbox m-t-30">
                                    <input type="checkbox" disabled name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraCheckboxTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field">
                                <div class="ui checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][default]">
                                    <label>{{ trans('settings.input.label.custom_default') }}</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraDateTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field">
                                <label>{{ trans('settings.input.label.custom_default') }}</label>
                                <input type="text" class="datepicker" name="custom_fields[][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}">
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraEmailTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field">
                                <label>{{ trans('settings.input.label.custom_default') }}</label>
                                <input type="email" name="custom_fields[][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}">
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraHiddenTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field required">
                                <label>{{ trans('settings.input.label.custom_default') }}</label>
                                <input type="text" name="custom_fields[][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" required>
                            </div>
                            <div class="field disabled">
                                <div class="ui toggle disabled checkbox m-t-30">
                                    <input type="checkbox" disabled name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraNumberTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field">
                                <label>{{ trans('settings.input.label.custom_default') }}</label>
                                <input type="number" name="custom_fields[][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" step="0.01">
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraSelectTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field required">
                                <label>{{ trans('settings.input.label.custom_options') }}</label>
                                <textarea name="custom_fields[][default][choices]" placeholder="{{ trans('settings.input.placeholder.custom_options') }}" required rows="2"></textarea>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraTelTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field">
                                <label>{{ trans('settings.input.label.custom_default') }}</label>
                                <input type="tel" name="custom_fields[][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}">
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraTextTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field">
                                <label>{{ trans('settings.input.label.custom_default') }}</label>
                                <input type="tel" name="custom_fields[][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}">
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="insuraTextareaTemplate" style="display:none;">
                        <div class="fields two">
                            <div class="field">
                                <label>{{ trans('settings.input.label.custom_default') }}</label>
                                <textarea name="custom_fields[][default]" placeholder="{{ trans('settings.input.placeholder.custom_default') }}" rows="2"></textarea>
                            </div>
                            <div class="field">
                                <div class="ui toggle checkbox m-t-30">
                                    <input type="checkbox" name="custom_fields[][required]">
                                    <label>{{ trans('settings.input.label.custom_required') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('reminders_tab')
        <div class="ui bottom attached tab segment{{ session()->get('tab', null) === 'reminders' ? ' active' : '' }}" data-tab="reminders">
            <div class="row">
                <div class="col-md-6">
                    <p>{{ trans('settings.tab.message.reminders') }}</p>
                    <form action="{{ action('ReminderController@update', array($company->id)) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="ui form">
                            <div class="field">
                                <div class="ui toggle checkbox">
                                    <input type="checkbox"{{ empty($company->reminder_status) ? '' : ' checked' }} id="enableReminders" name="status" />
                                    <label for="enableReminders">{{ trans('settings.input.label.reminder_status') }}</label>
                                </div>
                            </div>
                            <div class="ui styled fluid accordion">
                                @forelse($reminders as $r_key => $reminder)
                                <div class="{{ $reminder === $reminders[0] ? 'active ' : '' }} title">
                                    <i class="dropdown icon"></i>
                                    {{ trans('settings.accordion.header.reminder') }} #{{ $r_key + 1 }}
                                    <i class="close icon pull-right" id="deleteReminder{{ $reminder->id }}"></i>
                                </div>
                                <div class="{{ $reminder === $reminders[0] ? 'active ' : '' }} content">
                                    <input type="hidden" name="reminders[{{ $r_key + 1 }}][id]" value="{{ $reminder->id }}" />
                                    <p class="transition visible">
                                        <div class="field required">
                                            <label>{{ trans('settings.input.label.reminder_type') }}</label>
                                            <select class="ui fluid search dropdown" name="reminders[{{ $r_key + 1 }}][type]">
                                                <option{{ $reminder->type === 'email' ? ' selected' : '' }} value="email">{{ trans('settings.input.option.email') }} </option>
                                                <option{{ $reminder->type === 'text' ? ' selected' : '' }} value="text">{{ trans('settings.input.option.text') }}</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label>{{ trans('settings.input.label.subject') }}</label>
                                            <input type="text" name="reminders[{{ $r_key + 1 }}][subject]" placeholder="{{ trans('settings.input.placeholder.subject') }}" value="{{ $reminder->subject }}"/>
                                        </div>
                                        <div class="two fields">
                                            <div class="field required">
                                                <label>{{ trans('settings.input.label.timeline') }}</label>
                                                <select class="ui fluid search dropdown" name="reminders[{{ $r_key + 1 }}][timeline]">
                                                    <option{{ $reminder->timeline === 'after' ? ' selected' : '' }} value="after">{{ trans('settings.input.option.after_expiry') }} </option>
                                                    <option{{ $reminder->timeline === 'before' ? ' selected' : '' }} value="before">{{ trans('settings.input.option.before_expiry') }}</option>
                                                </select>
                                            </div>
                                            <div class="field">
                                                <label>{{ trans('settings.input.label.days') }} <span class="timeline"></span></label>
                                                <input type="number" max="365" min="1" name="reminders[{{ $r_key + 1 }}][days]" placeholder="" required step="1" value="{{ $reminder->days }}"/>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label>{{ trans('settings.input.label.message') }}</label>
                                            <textarea rows="9" name="reminders[{{ $r_key + 1 }}][message]" placeholder="{{ trans('settings.input.placeholder.message') }}" required>{{ $reminder->message }}</textarea>
                                        </div>
                                    </p>
                                </div>
                                @empty
                                <div class="active title">
                                    <i class="dropdown icon"></i>
                                    {{ trans('settings.accordion.header.reminder') }} #1
                                </div>
                                <div class="active content">
                                    <p class="transition visible">
                                        <div class="field required">
                                            <label>{{ trans('settings.input.label.reminder_type') }}</label>
                                            <select class="ui fluid search dropdown" name="reminders[1][type]">
                                                <option{{ old('reminders')['1']['type'] === 'email' || empty(old('reminders')) ? ' selected' : '' }} value="email">{{ trans('settings.input.option.email') }} </option>
                                                <option{{ old('reminders')['1']['type'] === 'text' ? ' selected' : '' }} value="text">{{ trans('settings.input.option.text') }}</option>
                                            </select>
                                        </div>
                                        <div class="field required">
                                            <label>{{ trans('settings.input.label.subject') }}</label>
                                            <input type="text" name="reminders[1][subject]" placeholder="{{ trans('settings.input.placeholder.subject') }}" required value="{{ old('reminders')['1']['subject'] }}"/>
                                        </div>
                                        <div class="two fields">
                                            <div class="field required">
                                                <label>{{ trans('settings.input.label.timeline') }}</label>
                                                <select class="ui fluid search dropdown" name="reminders[1][timeline]">
                                                    <option{{ old('reminders')['1']['timeline'] === 'after' || empty(old('reminders')) ? ' selected' : '' }} value="after">{{ trans('settings.input.option.after_expiry') }} </option>
                                                    <option{{ old('reminders')['1']['timeline'] === 'before' ? ' selected' : '' }} value="before">{{ trans('settings.input.option.before_expiry') }}</option>
                                                </select>
                                            </div>
                                            <div class="field">
                                                <label>{{ trans('settings.input.label.days') }} <span class="timeline"></span></label>
                                                <input type="number" max="365" min="1" name="reminders[1][days]" placeholder="" required step="1" value="{{ old('reminders')['1']['days'] }}" />
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label>{{ trans('settings.input.label.message') }}</label>
                                            <textarea rows="9" name="reminders[1][message]" placeholder="{{ trans('settings.input.placeholder.message') }}" required>{{ old('reminders')['1']['message'] }}</textarea>
                                        </div>
                                    </p>
                                </div>
                                @endforelse
                            </div>
                            <div class="field m-t-15">
                                <button class="ui right floated button primary m-w-140" type="submit">{{ trans('settings.button.save') }}</button>
                                <button class="ui right floated button m-w-140" id="addReminder" type="button">{{ trans('settings.button.add_reminder') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section('extra_scripts')
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                function capitaliseFirstLetter(string) {
                    string = $.trim(string);
                    return !!string[0] ? string.replace(string[0], string[0].toUpperCase()) : null;
                }
                function deleteCustomField(e) {
                    var parentField = $(e.target).parent();
                    parentField.next().remove();
                    parentField.remove();
                }
                // Add a reminder
                $('button#addReminder').click(function() {
                    var divContents = $('div.accordion > div.content');
                    var divTitles =  $('div.accordion > div.title');
                    var newContent = divContents.first().clone();
                    var newTitle = divTitles.last().clone();
                    var newKey = divTitles.length + 1;
                    newTitle.html(
                        '<i class="dropdown icon"></i>Reminder #' +
                        newKey +
                        '<i class="close icon pull-right"></i>'
                    );
                    newContent.find('[name^="reminders["]').each(function(i, el) {
                        $(el).attr('name', el.name.replace(/\[[0-9]*\]\[/, '[' + newKey + ']['));
                    });
                    newContent.find('input[name$="[id]"]').remove();
                    newContent.find('input[name], textarea').val('');
                    $insura.helpers.initDropdown(newContent.find('div.dropdown'));
                    divContents.last().after(newTitle, newContent);
                });

                // Add a new custom field
                $('button#addCustomClientField, button#addCustomPolicyField').click(function() {
                    var element = $(this);
                    var field = element.parent();
                    var model = element.attr('id') === 'addCustomClientField' ? 'client' : 'policy';
                    var newIndex = model[0].toUpperCase() + $('form select[name^="custom_fields["]').length;
                    var newFields = element.parent().before($('div#customFieldTemplate').html()).prev();
                    newFields = newFields.add(newFields.prev());
                    var hiddenModelInput = newFields.find('input[name$="model]"]');
                    hiddenModelInput.val(model)
                    newFields.find('[name^="custom_fields[]"]').each(function(index, element) {
                        $(element).attr('name', element.name.replace('ds[]', 'ds[' + newIndex + ']'));                        
                    });
                    newFields.find('i.close').click(deleteCustomField);
                    newFields.find('select').change(function(e) {
                        var fieldsDiv = $(this).parents('div.fields:first'), fieldType = capitaliseFirstLetter(e.target.value);
                        if(!!fieldType) {
                            var nextFieldsDiv = fieldsDiv.next();
                            nextFieldsDiv.replaceWith($('div#insura' + fieldType  + 'Template').html());
                            nextFieldsDiv = fieldsDiv.next();
                            $insura.helpers.initDatepicker(nextFieldsDiv.find('input.datepicker'));
                            $insura.helpers.initTelInput(nextFieldsDiv.find('input[type="tel"]'));
                            nextFieldsDiv.find('[name^="custom_fields[]"]').each(function(index, element) {
                                $(element).attr('name', element.name.replace('ds[]', 'ds[' + newIndex + ']'));                        
                            });
                        }
                    }).change();
                    $insura.helpers.initDropdown(newFields.find('div.dropdown'));
                    $insura.helpers.requireDropdownFields(newFields.find('div.required select'));
                });

                // Delete a custom field
                $('div[data-tab="company"] div.fields > i.close').click(deleteCustomField);

                // Delete a reminder
                $('div.accordion').on('click', 'div.title > i.close', function(e) {
                    e.preventDefault();
                    var divContent, divTitle, element = $(this);
                    function removeReminder() {
                        divContent.fadeOut(200, function() {
                            $(this).remove();
                            // Reset name keys
                            $('div.accordion > div.content').each(function(index, element) {
                                $(element).find('[name^="reminders["]').each(function(i, el) {
                                    $(el).attr('name', el.name.replace(/\[[0-9]*\]\[/, '[' + (index + 1) + ']['));
                                });
                            });
                        });
                        divTitle.fadeOut(200, function() {
                            $(this).remove();
                            // Reset reminder numbers
                            $('div.accordion > div.title').each(function(index, element) {
                                $(element).html(element.innerHTML.replace(/#[0-9]*\s*</, '#' + (index + 1) + '<'));
                            });
                        });
                    }
                    divTitle = element.parent();
                    divContent = divTitle.next();
                    // Check [id]
                    if(element.is('[id]')) {
                        var id = element.attr('id').replace('deleteReminder', '');
                        // Delete via ajax from database
                        $.ajax({
                            beforeSend: function() {
                                // Change icon to show loading
                                element.toggleClass('ui active close icon inline loader tiny');
                                // Fade (div.title) and ('div.content') in DOM synchronously
                                divTitle.css({cursor:'not-allowed', opacity: 0.5});
                                divContent.css({cursor:'not-allowed', opacity: 0.5});
                            },
                            method: 'DELETE',
                            url: '{{ action('ReminderController@delete', array(0)) }}?'.replace('0?', id)
                        }).done(removeReminder).fail(function() {
                            divContent.css(null);
                            divTitle.css(null);
                            // Change icon to show delete
                            element.toggleClass('ui active close icon inline loader tiny');
                        });
                    }else {
                        removeReminder();
                    }
                });

                // Toggle custom field types
                $('form select[name^="custom_fields["]').change(function(e) {
                    if(e.hasOwnProperty('originalEvent')) {
                        var fieldType = capitaliseFirstLetter(e.target.value);
                        if(!!fieldType) {
                            var fieldsDiv = $(this).parents('div.fields:first');
                            var namePrefix = e.target.name.match(/custom_fields\[[C|P][0-9]*\]/)[0];
                            var nextFieldsDiv = fieldsDiv.next();
                            nextFieldsDiv.replaceWith($('div#insura' + fieldType  + 'Template').html());
                            nextFieldsDiv = fieldsDiv.next();
                            nextFieldsDiv.find('input.datepicker').datepicker({
                                autoHide: true,
                                format: 'yyyy-mm-dd'
                            });
                            nextFieldsDiv.find('[name^="custom_fields[]"]').each(function(index, element) {
                                $(element).attr('name', element.name.replace('custom_fields[]', namePrefix));                        
                            });
                        }
                    }
                });

                // Toggle reminders
                $('input[name="status"]').change(function() {
                    var divForm = $(this).parents('div.ui.form:first');
                    if($(this).is(':checked')) {
                        divForm.find('button#addReminder, div.accordion').fadeIn(500);
                        divForm.find('input[name$="][days]"], select[name$="][timeline]"], select[name$="][type]"], textarea[name$="][message]"]').attr('required', true);
                        divForm.find('select[name$="][type]"]').each(function(i, e) {
                            var element = $(e);
                            if(element.val() === 'email') {
                                element.parents('div.content').find('input[name$="][subject]"]').attr('required', true);
                            }
                        });
                    }else {
                        divForm.find('button#addReminder, div.accordion').fadeOut(500);
                        divForm.find('input[name$="][days]"], input[name$="][subject]"], select[name$="][timeline]"], select[name$="][type]"], textarea[name$="][message]"]').attr('required', false);
                    }
                });

                // Toggle text providers
                $('select[name="text_provider"]').change(function() {
                    var element = $(this);
                    var parentTab = element.parents('div.segment.tab:first'),
                        value = element.val();
                    if(value === 'aft') {
                        parentTab.find('input[name^="aft_"]').parent().fadeIn();
                    }else {
                        parentTab.find('input[name^="aft_"]').parent().fadeOut();
                    }
                    if(value === 'twilio') {
                        parentTab.find('input[name^="twilio_"]').parent().fadeIn();
                    }else {
                        parentTab.find('input[name^="twilio_"]').parent().fadeOut();
                    }
                });

                // Toggle reminder timeline text
                $('div.accordion').on('change', 'select[name$="[timeline]"]', function() {
                    var element = $(this), timelineMap = {
                        'after':    '{{ strtolower(trans('settings.input.option.after_expiry')) }}',
                        'before':   '{{ strtolower(trans('settings.input.option.before_expiry')) }}'
                    };
                    var value = element.val();
                    element.parents('div.content:first').find('span.timeline').text(timelineMap[value]);
                });

                // Toggle reminder subject field
                $('div.accordion').on('change', 'select[name$="[type]"]', function() {
                    var element = $(this);
                    var subjectInput = element.parents('div.content:first').find('input[name$="[subject]"]');
                    if(element.val() === 'email') {
                        subjectInput.attr('required', true).parents('div.field:first').fadeIn(300);
                    }else {
                        subjectInput.attr('required', false).parents('div.field:first').fadeOut(300);
                    }
                });

                // Fire change events manually to adjust variables that depend on select(s) or checkboxes
                $('input[name="status"], select[name="text_provider"], select[name$="[timeline]"], select[name$="[type]"]').change();
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
