@extends('templates.products')

@section('modals')
    <!-- New product modal -->
    <div class="ui tiny modal" id="newProductModal">
        <div class="header">{{ trans('products.modal.header.new') }}</div>
        <div class="scrolling content">
            <p>{{ trans('products.modal.instruction.new') }}</p>
            <form action="{{ action('ProductController@add') }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('products.input.label.name') }}</label>
                        <input type="text" maxlength="64" minlength="3" name="name" placeholder="{{ trans('products.input.placeholder.name') }}" required value="{{ old('name') }}">
                    </div>
                    <div class="field required">
                        <label>{{ trans('products.input.label.insurer') }}</label>
                        <input type="text" maxlength="64" minlength="3" name="insurer" placeholder="{{ trans('products.input.placeholder.insurer') }}" required value="{{ old('insurer') }}">
                    </div>
                    <div class="field required">
                        <label>{{ trans('products.input.label.company') }}</label>
                        <select class="ui fluid search dropdown" name="company_id">
                            @foreach($companies as $company)
                            <option{{ !empty(old('company_id')) && old('company_id') === $company->id || empty(old('company_id')) && $company === $user->company ? ' selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            @foreach($companies as $company)
            <div id="company{{$company->id}}ProductCategoryTemplate" style="display:none;">
                <div class="company company-{{ $company->id }} field required">
                    <label>{{ trans('products.input.label.category') }}</label>
                    <select class="dropdown fluid search ui" name="category">
                        @forelse($company->product_categories as $category)
                        <option{{ !empty(old('category')) && old('category') === $category || empty(old('category')) && $category === $company->product_categories->first() ? ' selected' : '' }} value="{{ $category }}">{{ $category }}</option>
                        @empty
                        <option disabled value="">{{ trans('products.input.option.category') }}</option>
                        @endforelse
                    </select>
                </div>
                <div class="company company-{{ $company->id }} field required">
                    <label>{{ trans('products.input.label.sub_category') }}</label>
                    <select class="dropdown fluid search ui" name="sub_category">
                        @forelse($company->product_sub_categories as $sub_category)
                        <option{{ !empty(old('sub_category')) && old('sub_category') === $sub_category || empty(old('sub_category')) && $sub_category === $company->product_sub_categories->first() ? ' selected' : '' }} value="{{ $sub_category }}">{{ $sub_category }}</option>
                        @empty
                        <option disabled value="">{{ trans('products.input.option.sub_category') }}</option>
                        @endforelse
                    </select>
                </div>
            </div>
            @endforeach
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('products.modal.button.cancel.new') }}</button>
                <div class="or" data-text="{{ trans('products.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('products.modal.button.confirm.new') }}</button>
            </div>
        </div>
    </div>
    <!-- Edit product modals -->
    @foreach ($products as $product)

    <div class="ui tiny modal" id="editProduct{{ $product->id }}Modal">
        <div class="header">{{ trans('products.modal.header.edit') }}</div>
        <div class="scrolling content">
            <p>{{ trans('products.modal.instruction.edit') }}</p>
            <form action="{{ action('ProductController@edit', $product->id) }}" method="POST">
                {{ csrf_field() }}
                <div class="ui form">
                    <div class="field required">
                        <label>{{ trans('products.input.label.name') }}</label>
                        <input type="text" maxlength="64" minlength="3" name="name" placeholder="{{ trans('products.input.placeholder.name') }}" required value="{{ $product->name }}">
                    </div>
                    <div class="field required">
                        <label>{{ trans('products.input.label.insurer') }}</label>
                        <input type="text" maxlength="64" minlength="3" name="insurer" placeholder="{{ trans('products.input.placeholder.insurer') }}" required value="{{ $product->insurer }}">
                    </div>
                    <div class="field required">
                        <label>{{ trans('products.input.label.company') }}</label>
                        <select class="ui fluid search dropdown" name="company_id" required>
                            @foreach($companies as $company)
                            <option{{ $company->id === $product->company_id ? ' selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
            @foreach($companies as $company)
            <div id="company{{$company->id}}ProductCategoryTemplate" style="display:none;">
                <div class="company company-{{ $company->id }} field required">
                    <label>{{ trans('products.input.label.category') }}</label>
                    <select class="dropdown fluid search ui" name="category">
                        @forelse($company->product_categories as $category)
                        <option{{ $category === $product->category || (!$company->product_categories->contains($product->category) && $category === $company->product_categories->first()) ? ' selected' : '' }} value="{{ $category }}">{{ $category }}</option>
                        @empty
                        <option disabled value="">{{ trans('products.input.option.category') }}</option>
                        @endforelse
                    </select>
                </div>
                <div class="company company-{{ $company->id }} field required">
                    <label>{{ trans('products.input.label.sub_category') }}</label>
                    <select class="dropdown fluid search ui" name="sub_category">
                        @forelse($company->product_sub_categories as $sub_category)
                        <option{{ $sub_category === $product->sub_category || (!$company->product_sub_categories->contains($product->sub_category) && $sub_category === $company->product_sub_categories->first()) ? ' selected' : '' }} value="{{ $sub_category }}">{{ $sub_category }}</option>
                        @empty
                        <option disabled value="">{{ trans('products.input.option.sub_category') }}</option>
                        @endforelse
                    </select>
                </div>
            </div>
            @endforeach
        </div>
        <div class="actions">
            <div class="ui buttons">
                <button class="ui cancel button">{{ trans('products.modal.button.cancel.edit') }}</button>
                <div class="or" data-text="{{ trans('products.modal.button.or') }}"></div>
                <button class="ui positive primary button">{{ trans('products.modal.button.confirm.edit') }}</button>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('extra_scripts')
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $('div.modal select[name="company_id"]').change(function() {
                    var element = $(this);
                    var parentModal = element.parents('div.modal:first');
                    var companyElementsHTML = parentModal.find('div#company' + element.val() + 'ProductCategoryTemplate').html();
                    parentModal.find('form div.company').remove();
                    parentModal.find('form div.field').last().after(companyElementsHTML);
                    $insura.helpers.initDropdown(parentModal.find('form div.company div.dropdown'));
                    $insura.helpers.requireDropdownFields(parentModal.find('form div.required div.dropdown input[type="hidden"]'));
                }).change();
            });
        })(window.insura, window.jQuery);
    </script>
@endsection