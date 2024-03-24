@extends('templates.policies.all')

@section('new_payment_button')
            <button class="ui labeled icon button primary" data-target="#newPolicyModal" data-toggle="modal">
                <i class="ion-ios-plus-outline icon"></i>
                {{ trans('policies.button.new') }}
            </button>
@endsection

@section('modals')
    <!-- new policy modal -->
    <div class="ui tiny modal" id="newPolicyModal">
        <div class="header">{{ trans('policies.modal.header.new') }}</div>
        <div class="scrolling content">
            <p>{{ trans('policies.modal.instruction.new') }}</p>
            <form action="{{ action('PolicyController@add') }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.product') }}</label>
                            <select class="ui fluid search dropdown" name="product" required value="{{ old('product') }}">
                                <option value="">{{ trans('policies.input.placeholder.product') }}</option>
                                @forelse ($user->company->products as $product)
                                <option{{ old('product') === $product->id ? ' selected' : '' }} value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                <option disabled value="">{{ trans('policies.input.option.empty.product') }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="field required">
                            <label>{{ trans('policies.input.label.type') }}</label>
                            <select class="ui fluid dropdown selection" name="type" required value="{{ old('type') }}">
                                <option value="">{{ trans('policies.input.placeholder.type') }}</option>
                                <option{{ old('type') === 'annually' ? ' selected' : '' }} value="annually">{{ trans('policies.input.option.type.annually') }}</option>
                                <option{{ old('type') === 'monthly' ? ' selected' : '' }} value="monthly">{{ trans('policies.input.option.type.monthly') }}</option>
                                <option{{ old('type') === 'weekly' ? ' selected' : '' }} value="weekly">{{ trans('policies.input.option.type.weekly') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.expiry') }}</label>
                            <div class="ui labeled input">
                                <label for="expiry" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" id="expiry" class="datepicker" name="expiry" placeholder="{{ trans('policies.input.placeholder.expiry') }}" required value="{{ old('expiry') }}"/>
                            </div>
                        </div>
                        <div class="field required">
                            <label>{{ trans('policies.input.label.renewal') }}</label>
                            <div class="ui labeled input">
                                <label for="renewal" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" id="renewal" class="datepicker" name="renewal" placeholder="{{ trans('policies.input.placeholder.renewal') }}" required value="{{ old('renewal') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="field required">
                        <label>{{ trans('policies.input.label.payer') }}</label>
                        <input type="text" name="payer" maxlength="64" placeholder="{{ trans('policies.input.placeholder.payer') }}" required value="{{ old('payer') }}"/>
                    </div>
                    <div class="field required">
                        <label>{{ trans('policies.input.label.owners') }}</label>
                        <select class="dropdown fluid ui" name="owners[]" multiple required>
                            <option value="">{{ trans('policies.input.label.owners') }}</option>
                            @forelse ($clients as $client)
                            <option{{ collect(old('owners'))->contains($client->id) ? ' selected' : '' }} value="{{ $client->id }}">{{ $client->first_name . ' ' . $client->last_name }}</option>
                            @empty
                            <option disabled value="">{{ trans('policies.input.option.empty.owners') }}</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="field">
                        <label>{{ trans('policies.input.label.beneficiaries') }}</label>
                        <input type="text" name="beneficiaries" placeholder="{{ trans('policies.input.placeholder.beneficiaries') }}" value="{{ old('beneficiaries') }}"/>
                    </div>
                    <div class="field">
                        <label>{{ trans('policies.input.label.special_remarks') }}</label>
                        <textarea name="special_remarks" placeholder="{{ trans('policies.input.placeholder.special_remarks') }}" rows="4">{{ old('special_remarks') }}</textarea>
                    </div>
                    @forelse($clients as $client)
                    <h5 class="client client{{ $client->id }} ui dividing header" style="display:none;">{{ trans('policies.input.label.payments', array(
                        'name'  => $client->first_name . ' ' . $client->last_name
                    )) }}</h5>
                    <div class="client client{{ $client->id }} two fields" style="display:none;">
                        <div class="field required" data-input-name="premium">
                            <label>{{ trans('policies.input.label.premium') }}</label>
                            <div class="ui labeled input">
                                <label for="premium" class="ui label">{{ $policies->currency_symbol }}</label>
                                <input type="number" id="premium" min="0" name="premium[{{ $client->id }}]" placeholder="{{ trans('policies.input.placeholder.premium') }}" required value="{{ old('premium.' . $client->id) }}"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $policies->currency_symbol }}</label>
                                <input type="number" id="amount" name="amount[{{ $client->id }}]" min="0" placeholder="{{ trans('policies.input.placeholder.amount') }}" value="{{ old('amount.' . $client->id) }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="client client{{ $client->id }} two fields" style="display:none;">
                        <div class="field">
                            <label>{{ trans('policies.input.label.payment_date') }}</label>
                            <input type="text" class="datepicker" name="date[{{ $client->id }}]" placeholder="{{ trans('policies.input.placeholder.payment_date') }}" value="{{ old('date.' . $client->id) }}"/>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.payment_method') }}</label>
                            <div class="ui selection dropdown required">
                                <input type="hidden" name="method[{{ $client->id }}]" value="{{ old('method.' . $client->id) }}"/>
                                <div class="default text">{{ trans('policies.input.placeholder.payment_method') }}</div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div class="item{{ old('method.' . $client->id) === 'card' ? ' selected' : null }}" data-value="card">
                                        <i class="credit card alternative icon"></i>
                                        {{ trans('policies.input.option.method.card') }}
                                    </div>
                                    <div class="item{{ old('method.' . $client->id) === 'cash' ? ' selected' : null }}" data-value="cash">
                                        <i class="money icon"></i>
                                        {{ trans('policies.input.option.method.cash') }}
                                    </div>
                                    <div class="item{{ old('method.' . $client->id) === 'paypal' ? ' selected' : null }}" data-value="paypal">
                                        <i class="paypal card icon"></i>
                                        {{ trans('policies.input.option.method.paypal') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <h5 class="ui dividing header disabled">{{ trans('policies.input.label.payments', array(
                        'name'  => '?'
                    )) }}</h5>
                    <div class="two fields">
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.premium') }}</label>
                            <div class="ui labeled input">
                                <label for="premium" class="ui label">{{ $policies->currency_symbol }}</label>
                                <input type="number" disabled id="premium" min="0" placeholder="{{ trans('policies.input.placeholder.premium') }}" required/>
                            </div>
                        </div>
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $policies->currency_symbol }}</label>
                                <input type="number" disabled id="amount" min="0" placeholder="{{ trans('policies.input.placeholder.amount') }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.payment_date') }}</label>
                            <input type="text" class="datepicker" disabled placeholder="{{ trans('policies.input.placeholder.payment_date') }}"/>
                        </div>
                        <div class="disabled field">
                            <label>{{ trans('policies.input.label.payment_method') }}</label>
                            <div class="ui selection disabled dropdown">
                                <input type="hidden" value="{{ old('method') }}"/>
                                <div class="default text">{{ trans('policies.input.placeholder.payment_method') }}</div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    <div class="item" data-value="card">
                                        <i class="credit card alternative icon"></i>
                                        {{ trans('policies.input.option.method.card') }}
                                    </div>
                                    <div class="item" data-value="cash">
                                        <i class="money icon"></i>
                                        {{ trans('policies.input.option.method.cash') }}
                                    </div>
                                    <div class="item" data-value="paypal">
                                        <i class="paypal card icon"></i>
                                        {{ trans('policies.input.option.method.paypal') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforelse
                    <div class="divider"></div>
                    @foreach (collect(json_decode($user->company->custom_fields_metadata ?: '[]'))->where('model', 'policy')->all() as $key => $field)
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
                    <input type="hidden" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]" value="{{ $field->default }}">
                        @elseif ($field->type === 'number')
                    <div class="field{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                        <input type="number" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value]"{{ isset($field->required) ? ' required' : '' }} value="{{ $field->default }}">
                    </div>
                        @elseif ($field->type === 'select')
                            @foreach ($field->default->choices as $option)
                    <input type="hidden" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value][choices][]" value="{{ $option }}"/>
                            @endforeach
                            @if (count($field->default->choices) > 2)
                    <div class="field{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                        <select class="ui fluid search dropdown" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value][choice]">
                            <option value="">{{ $field->label }}</option>
                                @foreach ($field->default->choices as $option)
                            <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                @endforeach
                        </select>
                    </div>
                            @else
                    <div class="inline fields{{ isset($field->required) ? ' required' : '' }}">
                        <label>{{ $field->label }}</label>
                                @foreach ($field->default->choices as $option)
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio" name="custom_fields[C{{ $user->company->id }}F{{ $key }}][value][choice]"{{ isset($field->required) ? ' required' : '' }} value="{{ trim($option) }}">
                                <label>{{ trim($option) }}</label>
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
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('policies.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('policies.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('policies.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>
@endsection

@section('extra_scripts')
    <script type="text/javascript">
        (function($) {
            $(document).ready(function() {
                $('div#newPolicyModal input[name^="premium["]').change(function() {
                    var input = $(this);
                    input.closest('div.fields').find('input[name^="amount["]').attr('max', input.val());
                }).change();
            });
        })(window.jQuery);
    </script>
@endsection
