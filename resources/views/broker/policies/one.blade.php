@extends('templates.policies.one')

@section('new_payment_button')
            <button class="ui labeled icon button primary" data-target="#newPolicyModal" data-toggle="modal">
                <i class="ion-ios-plus-outline icon"></i>
                {{ trans('policies.button.new') }}
            </button>
@endsection

@section('policy_actions')
                    <div class="ui right floated successish button top m-w-140 right pointing dropdown" data-inverted="" data-tooltip="{{ trans('policies.menu.header.tooltip') }}" data-position="left center">
                        <i class="ion-more icon"></i>
                        <span class="text">{{ trans('policies.menu.header.button') }}</span>
                        <div class="menu">
                            <div class="header">
                                {{ trans('policies.menu.header.text') }}
                            </div>
                            <div class="divider"></div>
                            <div class="item">
                                <a href="#" data-target="#editPolicyModal" data-toggle="modal">
                                    <i class="write icon"></i> {{ trans('policies.menu.item.edit_policy') }} 
                                </a>
                            </div>
                            <form action="{{ action('PolicyController@delete', array($policy->id)) }}" class="item negative" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <a href="#" class="delete">
                                    <i class="trash icon"></i> {{ trans('policies.menu.item.delete') }} 
                                </a>
                            </form>
                        </div>
                    </div>
@endsection

@section('payments_button')
                    <button class="ui right floated button successish m-w-140" data-target="#newPaymentModal" data-toggle="modal">{{ trans('payments.button.new') }}</button>
@endsection

@section('policy_modals')
    <!-- new payment modal -->
    <div class="ui tiny modal" id="newPaymentModal">
        <div class="header">{{ trans('payments.modal.header.new') }}</div>
        <div class="content">
            <p>{{ trans('payments.modal.instruction.new', array(
                'name'  => $policy->client->first_name
            )) }}</p>
            <form action="{{ action('PaymentController@add') }}"method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="client" value="{{ $policy->client->id }}"/>
                <input type="hidden" name="policy" value="{{ $policy->id }}"/>
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('payments.input.label.method') }}</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="method">
                            <div class="default text">{{ trans('payments.input.placeholder.method') }}</div>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <div class="item{{ old('method') === 'card' ? ' selected' : null }}" data-value="card">
                                    <i class="credit card alternative icon"></i>
                                    {{ trans('payments.input.option.method.card') }}
                                </div>
                                <div class="item{{ old('method') === 'cash' ? ' selected' : null }}" data-value="cash">
                                    <i class="money icon"></i>
                                    {{ trans('payments.input.option.method.cash') }}
                                </div>
                                <div class="item{{ old('method') === 'paypal' ? ' selected' : null }}" data-value="paypal">
                                    <i class="paypal card icon"></i>
                                    {{ trans('payments.input.option.method.paypal') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('payments.input.label.date') }}</label>
                            <div class="ui labeled input">
                                <label for="paymentDate" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" class="datepicker" id="paymentDate" name="date" placeholder="{{ trans('payments.input.placeholder.date') }}" required value="{{ old('date') }}"/>
                            </div>
                        </div>
                        <div class="field required">
                            <label>{{ trans('payments.input.label.amount') }}</label>
                            <div class="ui labeled input">
                                <label for="amount" class="ui label">{{ $policy->client->currency_symbol }}</label>
                                <input type="number" id="amount" max="{{ $policy->due }}" name="amount" placeholder="{{ trans('payments.input.placeholder.amount') }}" required value="{{ old('amount') }}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('payments.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('payments.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('payments.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>

    <!-- edit policy modal -->
    <div class="ui tiny modal" id="editPolicyModal">
        <div class="header">{{ trans('policies.modal.header.edit') }}</div>
        <div class="scrolling content">
            <p>{{ trans('policies.modal.instruction.edit') }}</p>
            <form action="{{ action('PolicyController@edit', array($policy->id)) }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.product') }}</label>
                            <select class="ui fluid search dropdown" name="product" required value="{{ old('product') ?: $policy->product->id }}">
                                <option value="">{{ trans('policies.input.placeholder.product') }}</option>
                                @forelse ($policy->client->company->products as $product)
                                <option{{ old('product') === $product->id || $policy->product->id === $product->id ? ' selected' : '' }} value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                <option disabled value="">{{ trans('policies.input.option.empty.product') }}</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="field required">
                            <label>{{ trans('policies.input.label.type') }}</label>
                            <select class="ui fluid dropdown selection" name="type" required value="{{ old('type') ?: $policy->type }}">
                                <option value="">{{ trans('policies.input.placeholder.type') }}</option>
                                <option{{ old('type') === 'annually' || $policy->type === 'annually' ? ' selected' : '' }} value="annually">{{ trans('policies.input.option.type.annually') }}</option>
                                <option{{ old('type') === 'monthly' || $policy->type === 'monthly' ? ' selected' : '' }} value="monthly">{{ trans('policies.input.option.type.monthly') }}</option>
                                <option{{ old('type') === 'weekly' || $policy->type === 'weekly' ? ' selected' : '' }} value="weekly">{{ trans('policies.input.option.type.weekly') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>{{ trans('policies.input.label.expiry') }}</label>
                            <div class="ui labeled input">
                                <label for="expiry" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" id="expiry" class="datepicker" name="expiry" placeholder="{{ trans('policies.input.placeholder.expiry') }}" value="{{ old('expiry') ?: $policy->expiry }}"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.renewal') }}</label>
                            <div class="ui labeled input">
                                <label for="renewal" class="ui label"><i class="calendar icon"></i></label>
                                <input type="text" id="renewal" class="datepicker" name="renewal" placeholder="{{ trans('policies.input.placeholder.renewal') }}" value="{{ old('renewal') ?: $policy->renewal }}"/>
                            </div>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field required">
                            <label>{{ trans('policies.input.label.premium') }}</label>
                            <div class="ui labeled input">
                                <label for="premium" class="ui label">{{ $policy->currency_symbol }}</label>
                                <input type="number" id="premium" min="0" name="premium" placeholder="{{ trans('policies.input.placeholder.premium') }}" required value="{{ old('premium') ?: $policy->premium }}"/>
                            </div>
                        </div>
                        <div class="field">
                            <label>{{ trans('policies.input.label.payer') }}</label>
                            <input type="text" maxlength="64" name="payer" placeholder="{{ trans('policies.input.placeholder.payer') }}" value="{{ old('payer') ?: $policy->payer }}"/>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="field required">
                        <label>{{ trans('policies.input.label.beneficiaries') }}</label>
                        <input type="text" name="beneficiaries" placeholder="{{ trans('policies.input.placeholder.beneficiaries') }}" required value="{{ old('beneficiaries') ?: $policy->beneficiaries }}"/>
                    </div>
                    <div class="field">
                        <label>{{ trans('policies.input.label.special_remarks') }}</label>
                        <textarea name="special_remarks" placeholder="{{ trans('policies.input.placeholder.special_remarks') }}" rows="4">{{ old('special_remarks') ?: str_replace('<br/>', "\n", $policy->special_remarks) }}</textarea>
                    </div>
                    <div class="divider"></div>
                    @foreach ($policy->customFields->all() as $custom_field)
                    <input type="hidden" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][label]" value="{{ $custom_field->label }}"/>
                    <input type="hidden" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][type]" value="{{ $custom_field->type }}"/>
                    <input type="hidden" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][uuid]" value="{{ $custom_field->uuid }}"/>
                        @if ($custom_field->type === 'checkbox')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <div class="ui checkbox">
                            <input type="checkbox"{{ isset($custom_field->value) ? ' checked' : '' }} name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }}>
                            <label>{{ $custom_field->label }}</label>
                        </div>
                    </div>
                        @elseif ($custom_field->type === 'date')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <input type="text" class="datepicker" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value]""{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->value }}">
                    </div>
                        @elseif ($custom_field->type === 'email')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <input type="email" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->value }}">
                    </div>
                        @elseif ($custom_field->type === 'hidden')
                    <input type="hidden" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value]" value="{{ $custom_field->value }}">
                        @elseif ($custom_field->type === 'number')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <input type="number" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value]""{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->value }}">
                    </div>
                        @elseif ($custom_field->type === 'select')
                            @foreach ($custom_field->value->choices as $option)
                    <input type="hidden" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value][choices][]" value="{{ $option }}"/>
                            @endforeach
                            @if (count($custom_field->value->choices) > 2)
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <select class="ui fluid search dropdown" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value][choice]">
                            <option value="">{{ $custom_field->label }}</option>
                                @foreach ($custom_field->value->choices as $option)
                            <option{{ $option === $custom_field->value->choice ? ' selected' : '' }} value="{{ trim($option) }}">{{ trim($option) }}</option>
                                @endforeach
                        </select>
                    </div>
                            @else
                    <div class="inline fields{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                                @foreach ($custom_field->value->choices as $option)
                        <div class="field">
                            <div class="ui radio checkbox">
                                <input type="radio"{{ $option === $custom_field->value->choice ? ' checked' : '' }} name="custom_fields[C{{ $company->id }}F{{ $custom_field->id }}][value][choice]"{{ isset($custom_field->required) ? ' required' : '' }} value="{{ trim($option) }}">
                                <label>{{ trim($option) }}</label>
                            </div>
                        </div>
                                @endforeach
                    </div>
                            @endif
                        @elseif ($custom_field->type === 'tel')
                    <div class="field{{ isset($custom_field->required) ? ' required' : '' }}">
                        <label>{{ $custom_field->label }}</label>
                        <input type="tel" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->default }}">
                    </div>
                        @elseif ($custom_field->type === 'text')
                    <div class="field">
                        <label>{{ $custom_field->label }}</label>
                        <input type="tel" name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }} value="{{ $custom_field->default }}">
                    </div>
                        @else
                    <div class="field">
                        <label>{{ $custom_field->label }}</label>
                        <textarea name="custom_fields[C{{ $policy->client->company->id }}F{{ $custom_field->id }}][value]"{{ isset($custom_field->required) ? ' required' : '' }} rows="2">{{ $custom_field->default }}</textarea>
                    </div>
                        @endif
                    @endforeach
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('policies.modal.button.cancel.edit') }}</button>
                <div class="or" data-text="{{ trans('policies.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('policies.modal.button.confirm.edit') }}</button>
            </div>
        </div>
    </div>
@endsection

@section('extra_scripts')
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initModal('div#newPaymentModal', true);
                $insura.helpers.initModal('div#editPolicyModal', false);
                $insura.helpers.initSwal('form a.delete', {
                    confirmButtonText: '{{ trans('policies.swal.warning.delete.confirm') }}',
                    text: '{{ trans('policies.swal.warning.delete.text') }}',
                    title: '{{ trans('policies.swal.warning.delete.title') }}'
                });
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
