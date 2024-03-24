<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class InboxController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Inbox Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the real-time chat module used with Insura. Why
    | don't you explore it?
    |
    */

    /**
     * Create a new inbox controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Get all contacts of the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request) {
        $user = $request->user();
        $view_data = array();
        switch($user->role) {
            case 'admin':
            case 'staff':
                // All users within the agency - $user + super admin
                $view_data['contacts'] = $user->company->users()->whereNotIn('id', array($user->id))->get()->merge(User::super()->whereNotIn('id', array($user->company->admin->id))->get());
                break;
            case 'broker':
                // All staff + agency admin + invited clients + super admin
                $view_data['contacts'] = $user->company->staff->push($user->company->admin)->merge($user->invitees)->merge(User::super()->whereNotIn('id', array($user->company->admin->id))->get());
                break;
            case 'client':
                // All staff + agency admin + inviter broker + super_admin
                $view_data['contacts'] = $user->company->staff->push($user->company->admin)->merge($user->inviter()->whereNotIn('role', array('admin', 'staff'))->get())->merge(User::super()->whereNotIn('id', array($user->company->admin->id))->get());
                break;
            case 'super':
                // All users within the system - $user
                $view_data['contacts'] = User::whereNotIn('id', array($user->id))->get();
                break;
        }
        $view_data['contacts']->transform(function($contact) use($user) {
            $incoming_chats = $contact->incomingChats->where('sender_id', $user->id)->map(function($chat) {
                $chat->class = 'me';
                return $chat;
            });
            $outgoing_chats = $contact->outgoingChats->where('recipient_id', $user->id)->map(function($chat) {
                if($chat->status === 'sent') {
                    $chat->status = 'received';
                    $chat->save();
                }
                $chat->class = 'you';
                return $chat;
            });
            $contact->chats = $incoming_chats->merge($outgoing_chats)->map(function($chat) {
                $time_since_chat = strtotime(date('Y-m-d')) - strtotime($chat->created_at);
                $chat->inboxTime = $time_since_chat > -1 ? ($time_since_chat > 86400 ? date('j F Y', strtotime($chat->created_at)) : trans('inbox.label.time.yesterday')) : trans('inbox.label.time.today');
                $chat->peopleListTime = $time_since_chat > -1 ? ($time_since_chat > 86400 ? ($time_since_chat > 518400 ? ($time_since_chat > 7776000 ? date('j F Y', strtotime($chat->created_at)) : date('F j', strtotime($chat->created_at))) : date('l', strtotime($chat->created_at))) : trans('inbox.label.time.yesterday')) : $chat->created_at;
                return $chat;
            })->sortBy('created_at')->values();
            $contact->unreadChats = $contact->outgoingChats()->unread()->where('recipient_id', $user->id)->get();
            $contact->fullName = $contact->first_name . ' ' . $contact->last_name;
            return $contact;
        });
        $view_data['chatees'] = $view_data['contacts']->filter(function($contact) use($user) {
            return $contact->outgoingChats->where('recipient_id', $user->id)->count() > 0 || $contact->incomingChats->where('sender_id', $user->id)->count() > 0;
        })->map(function($chatee) {
            return $chatee->id;
        });
        if(isset($request->chatee)) {
            $view_data['chatees']->push($request->chatee);
        }else {
            $view_data['chatees']->push('X');
        }

        return view('global.inbox', $view_data);
    }
}
