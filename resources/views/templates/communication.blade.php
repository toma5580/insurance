@extends('global.app')

@section('title', trans('communication.title.main'))

@section('action_buttons')
            <div class="ui right floated segment transparent page-actions">
                <button class="ui labeled icon button positive" data-target="#newTextModal" data-toggle="modal"{{ is_null($user->company->text_provider) ? ' disabled' : '' }}> <i class="ion-ios-plus-outline icon"></i> New SMS </button>
                <button class="ui labeled icon button primary" data-target="#newEmailModal" data-toggle="modal"> <i class="ion-email icon"></i> New Email </button>
            </div>
@endsection

@section('content')
        @parent
        @include('global.status')
        <div class="ui segment white">
            <table class="ui celled striped table">
                <thead>
                    <tr>
                        <th>{{ trans('communication.table.header.number') }}</th>
                        <th>{{ trans('communication.table.header.name') }}</th>
                        <th>{{ trans('communication.table.header.type') }}</th>
                        <th class="center aligned">{{ trans('communication.table.header.email') }}</th>
                        <th class="center aligned">{{ trans('communication.table.header.text') }}</th>
                        <th class="center aligned" colspan="2">{{ trans('communication.table.header.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $key => $contact)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $contact->first_name . ' ' . $contact->last_name }}</td>
                        <td>{{ studly_case($contact->role) }}</td>
                        <td class="center aligned">{{ $contact->incomingEmails->where('sender_id', $user->id)->count() + $user->incomingEmails->where('sender_id', $contact->id)->count() }}</td>
                        <td class="center aligned">{{ $contact->incomingTexts->where('sender_id', $user->id)->count() + $user->incomingTexts->where('sender_id', $contact->id)->count() }}</td>
                        <td class="center aligned">
                            <a href="{{ action('EmailController@getAll', array($contact->id)) }}" class="ui large blue label"> {{ trans('communication.table.data.action.emails') }} </a>
                        </td>
                        <td class="center aligned">
                            <a href="{{ action('TextController@getAll', array($contact->id)) }}" class="ui large green label"> {{ trans('communication.table.data.action.texts') }} </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="center aligned text-center" colspan="7">
                            {{ trans('communication.table.message.empty') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>  
                <tfoot>
                    <tr>
                        <th class="center aligned" colspan="2">
                            {{ trans('communication.table.data.pagination.showing', array(
                                'start' => $contacts->total() > 0 ? ((($contacts->currentPage() - 1) * $contacts->count()) + 1) : 0,
                                'stop'  => $contacts->currentPage() * $contacts->count(),
                                'total' => $contacts->total()
                            )) }}
                        </th>
                        <th colspan="5" class="ui center aligned">
                            {!! $contacts->render($presenter) !!}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
@endsection

@section('page_scripts')
    <script type="text/javascript">
        (function($insura, $) {
            $(document).ready(function() {
                $insura.helpers.initModal('div.modal', true);
                $insura.helpers.initDropdown('div.dropdown, select.dropdown');
                $insura.helpers.listenForChats();
                $insura.helpers.requireDropdownFields('form div.required select');
            });
        })(window.insura, window.jQuery);
    </script>
@endsection
