<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Chats Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management of chat messages used by Insura.
    | Why don't you explore it?
    |
    */

    /**
     * Create a new chat controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Delete a chat
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function delete(Chat $chat) {
        $chat->delete();
        return redirect()->back()->with('status', trans('communication.message.info.deleted', array(
            'type'  => 'Chat'
        )));
    }

    /**
     * Stream chat messages as they become available
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function live(Request $request) {
        if($request->ajax()) {
            $user = $request->user();
            $original_received_messages = $user->outgoingChats()->received()->get();
            $original_seen_messages = $user->outgoingChats()->seen()->get();
            while(true) {

                // Check for new messages sent to the user
                $new_messages = $user->incomingChats()->sent()->orderBy('created_at', 'asc')->get();
                if($new_messages->count() > 0) {
                    // Receive the messages
                    $new_messages->transform(function($message) {
                        $message->status = 'received';
                        $message->save();
                        return $message;
                    });
                    return response()->json(array(
                        'data'  => array(
                            'insuraMessages'  => $new_messages->toJson()
                        ),
                        'event' => 'InsuraNewMessages'
                    ));
                }

                // Check for newly received messages
                $new_received_messages = $user->outgoingChats()->received()->get()->diff($original_received_messages);
                if($new_received_messages->count() > 0) {
                    return response()->json(array(
                        'data'  => array(
                            'insuraMessages'  => $new_received_messages->toJson()
                        ),
                        'event' => 'InsuraReceivedMessages'
                    ));
                }

                // Check for new seen messages
                $new_seen_messages = $user->outgoingChats()->seen()->get()->diff($original_seen_messages);
                if($new_seen_messages->count() > 0) {
                    return response()->json(array(
                        'data'  => array(
                            'insuraMessages'  => $new_seen_messages->toJson()
                        ),
                        'event' => 'InsuraSeenMessages'
                    ));
                }

                // Wait
                sleep(1);
            }
        }else {
            $response = new StreamedResponse();
            $response->headers->set('Content-Type', 'text/event-stream');
            $response->headers->set('Cache-Control', 'no-cache');
            $response->sendHeaders();
            $response->setCallback(function() use($request) {
                echo "retry: 30000\n\n"; // no retry would default to 3 seconds.
                ob_flush();
                flush();
                $last_ping = null;
                $time_offset = $request->server->get('REQUEST_TIME') % 30;
                $user = $request->user();
                $original_received_messages = $user->outgoingChats()->received()->get();
                $original_seen_messages = $user->outgoingChats()->seen()->get();
                while(true) {

                    // Check for new messages sent to the user
                    $new_messages = $user->incomingChats()->sent()->orderBy('created_at', 'asc')->get();
                    if($new_messages->count() > 0) {
                        // Receive the messages
                        $new_messages->transform(function($message) {
                            $message->status = 'received';
                            $message->save();
                            return $message;
                        });
                        if(isset($request->quiet)) {
                            unset($request->quiet); 
                        }else {
                            echo "event: InsuraNewMessages\n";
                            echo "data: {$new_messages->toJson()}\n\n";
                            ob_flush();
                            flush();
                        }
                        $time_offset = time() % 30;
                    }

                    // Check for new received messages
                    $all_received_messages = $user->outgoingChats()->received()->get();
                    $new_received_messages = $all_received_messages->diff($original_received_messages);
                    if($new_received_messages->count() > 0) {
                        echo "event: InsuraReceivedMessages\n";
                        echo "data: {$new_received_messages->toJson()}\n\n";
                        ob_flush();
                        flush();
                        // Add newly received messages
                        $original_received_messages = $all_received_messages;
                        $time_offset = time() % 30;
                    }

                    // Check for new seen messages
                    $all_seen_messages = $user->outgoingChats()->seen()->get();
                    $new_seen_messages = $all_seen_messages->diff($original_seen_messages);
                    if($new_seen_messages->count() > 0) {
                        echo "event: InsuraSeenMessages\n";
                        echo "data: {$new_seen_messages->toJson()}\n\n";
                        ob_flush();
                        flush();
                        // Add newly seen messages
                        $original_seen_messages = $all_seen_messages;
                        $time_offset = time() % 30;
                    }

                    // Send Ping every 30 seconds of inactivity
                    $time = time();
                    if($time % 30 === $time_offset && $last_ping !== $time) {
                        echo "event: InsuraPing\n";
                        echo "data: {\"time\":{$time}}\n\n";
                        ob_flush();
                        flush();
                        $last_ping = $time;
                    }

                    // Wait
                    sleep(1);
                }
            });
            $response->send();
        }
    }
    
    /**
     * Add a new message to the chat
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function see(Request $request) {
        $this->validate($request, array(
            'sender' => 'exists:users,id|integer|required'
        ));
        $user = $request->user();
        $messages_to_see = $user->incomingChats()->received()->where('sender_id', $request->sender)->get();
        $messages_to_see->each(function($message) {
            $message->status = 'seen';
            $message->save();
        });
        return response()->json($messages_to_see->toJson());
    }
    
    /**
     * Add a new message to the chat
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request) {
        $this->validate($request, array(
            'message'   => 'required|string',
            'recipient' => 'exists:users,id|integer|required'
        ));
        $user = $request->user();
        $recipient = User::find($request->recipient);
        $chatMessage = new Chat(array(
            'message'   => $request->message,
            'status'    => 'sent'
        ));
        $chatMessage->recipient()->associate($recipient);
        $chatMessage->sender()->associate($user);
        $chatMessage->save();

        return response()->json($chatMessage->toArray());
    }
}
