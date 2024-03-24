@extends('global.app')

@section('title', trans('products.title'))

@section('page_stylesheets')
    <link href="{{ asset('assets/libs/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('action_buttons')
            <div class="ui right floated segment transparent page-actions">
                <button class="ui labeled icon button primary open-modal" data-target="#newProductModal" data-toggle="modal">
                    <i class="ion-ios-plus-outline icon"></i> 
                    {{ trans('products.button.new') }} 
                </button>
            </div>
@endsection

@section('content')
        @parent
        @include('global.status')
        <div class="ui segment white">
            <table class="ui celled striped table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ trans('products.table.header.name') }}</th>
                        <th>{{ trans('products.table.header.insurer') }}</th>
                        <th>{{ trans('products.table.header.category') }}</th>
                        <th>{{ trans('products.table.header.sub_category') }}</th>
                        <th class="center aligned">{{ trans('products.table.header.policies') }}</th>
                        <th class="center aligned">{{ trans('products.table.header.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $key => $product)
                    <tr>
                        <td>{{ $products->lastOnPreviousPage + $key + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->insurer }}</td>
                        <td>{{ $product->category }} </td>
                        <td>{{ $product->sub_category }}</td>
                        <td class="center aligned">{{ $product->policies->count() }}</td>
                        <td class="center aligned">
                            <a href="#" class="green label mini ui" data-target="#editProduct{{ $product->id }}Modal" data-toggle="modal"> {{ trans('products.button.edit') }} </a>
                            <form action="{{ action('ProductController@delete', array($product->id)) }}" method="POST" style="display:inline;">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="delete label mini red ui" style="cursor:pointer;" type="submit">{{ trans('products.button.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">{{ trans('products.table.data.not_available') }}</td>
                    </tr>
                @endforelse
                </tbody>  
                <tfoot>
                    <tr>
                        <th class="center aligned ui" colspan="2">
                            {{ trans('products.table.data.pagination', array(
                                'start' => $products->total() > 0 ? $products->lastOnPreviousPage + 1 : 0,
                                'stop'  => $products->lastOnPreviousPage + $products->count(),
                                'total' => $products->total()
                            )) }}
                        </th>
                        <th class="center aligned ui" colspan="5">
                            {!! $products->render($presenter) !!}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/libs/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.initModal('div#newProductModal', true);
                $insura.helpers.initModal('div[id^="editProduct"]', false);
                $insura.helpers.initScrollbar('div.scrollbar');
                $insura.helpers.initSwal('form button.delete', {
                    confirmButtonText: '{{ trans('products.swal.warning.delete.confirm') }}',
                    text: '{{ trans('products.swal.warning.delete.text') }}',
                    title: '{{ trans('products.swal.warning.delete.title') }}'
                });
                $insura.helpers.listenForChats();
                $insura.helpers.requireDropdownFields('form div.required select');
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
