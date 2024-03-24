@extends('templates.products')

@section('modals')
    <!-- New Product modal -->
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
                        <label>{{ trans('products.input.label.category') }}</label>
                        <select class="ui fluid search dropdown" name="category">
                            @forelse($company->product_categories as $category)
                            <option{{ !empty(old('category')) && old('category') === $category || empty(old('category')) && $category === $user->company->product_categories->first() ? ' selected' : '' }} value="{{ $category }}">{{ $category }}</option>
                            @empty
                            <option disabled value="">{{ trans('products.input.option.category') }}</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="field required">
                        <label>{{ trans('products.input.label.sub_category') }}</label>
                        <select class="ui fluid search dropdown" name="sub_category">
                            @forelse($company->product_sub_categories as $sub_category)
                            <option{{ !empty(old('sub_category')) && old('sub_category') === $sub_category || empty(old('sub_category')) && $sub_category === $user->company->product_sub_categories->first() ? ' selected' : '' }} value="{{ $sub_category }}">{{ $sub_category }}</option>
                            @empty
                            <option disabled value="">{{ trans('products.input.option.sub_category') }}</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </form>
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
                        <label>{{ trans('products.input.label.category') }}</label>
                        <select class="ui fluid search dropdown" name="category">
                            @forelse($company->product_categories as $category)
                            <option{{ $category === $product->category || (!$company->product_categories->contains($product->category) && $category === $company->product_categories->first()) ? ' selected' : '' }} value="{{ $category }}">{{ $category }}</option>
                            @empty
                            <option disabled value="">{{ trans('products.input.option.category') }}</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="field required">
                        <label>{{ trans('products.input.label.sub_category') }}</label>
                        <select class="ui fluid search dropdown" name="sub_category">
                            @forelse($company->product_sub_categories as $sub_category)
                            <option{{ $sub_category === $product->sub_category || (!$company->product_sub_categories->contains($product->sub_category) && $sub_category === $company->product_sub_categories->first()) ? ' selected' : '' }} value="{{ $sub_category }}">{{ $sub_category }}</option>
                            @empty
                            <option disabled value="">{{ trans('products.input.option.sub_category') }}</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </form>
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
