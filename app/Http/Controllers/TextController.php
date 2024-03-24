<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendText;
use App\Models\Company;
use App\Models\Text;
use App\Models\User;
use Illuminate\Http\Request;

class TextController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Text Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management of existing texts / SMSs. Why
    | don't you explore it?
    |
    */

    /**
     * Create a new text controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }
    
    /**
     * Get all texts related to the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $user = $request->user();
        $this->validate($request, array(
            'message'   => 'required|string',
            'recipient' => 'in:admins,brokers,clients,staff,' . $user->company->users->keyBy('id')->keys()->implode(',') . '|required'
        ));
        if(is_null($user->company->text_provider)) {
            return redirect()->back()->with('warning', trans('communication.message.warning.text', array(
                'company_name'  => $user->company->name
            )))->with('text', true)->withInput();
        }
        switch($request->recipient) {
            case 'admins':
                foreach(User::admin()->get() as $admin) {
                    if(isset($admin->phone)) {
                        $text = new Text(array(
                            'message'   => $request->message,
                            'status'    => 0
                        ));
                        $text->recipient()->associate($admin);
                        $text->sender()->associate($user);
                        $text->save();
                        $job = new SendText($text);
                        $this->dispatch($job->onQueue('texts'));
                    }
                }
                break;
            case 'brokers':
                foreach($user->company->brokers as $broker) {
                    if(isset($broker->phone)) {
                        $text = new Text(array(
                            'message'   => $request->message,
                            'status'    => 0
                        ));
                        $text->recipient()->associate($broker);
                        $text->sender()->associate($user);
                        $text->save();
                        $job = new SendText($text);
                        $this->dispatch($job->onQueue('texts'));
                    }
                }
                break;
            case 'clients':
                foreach($user->company->clients as $client) {
                    if(isset($client->phone)) {
                        $text = new Text(array(
                            'message'   => $request->message,
                            'status'    => 0
                        ));
                        $text->recipient()->associate($client);
                        $text->sender()->associate($user);
                        $text->save();
                        $job = new SendText($text);
                        $this->dispatch($job->onQueue('texts'));
                    }
                }
                break;
            case 'staff':
                foreach($user->company->staff as $staff) {
                    if(isset($staff->phone)) {
                        $text = new Text(array(
                            'message'   => $request->message,
                            'status'    => 0
                        ));
                        $text->recipient()->associate($staff);
                        $text->sender()->associate($user);
                        $text->save();
                        $job = new SendText($text);
                        $this->dispatch($job->onQueue('texts'));
                    }
                }
                break;
            default:
                $company_user = $user->company->users()->findOrFail($request->recipient);
                if(isset($company_user->phone)) {
                    $text = new Text(array(
                        'message'   => $request->message,
                        'status'    => 0
                    ));
                    $text->recipient()->associate($company_user);
                    $text->sender()->associate($user);
                    $text->save();
                    $job = new SendText($text);
                    $this->dispatch($job->onQueue('texts'));
                }
        }

        return redirect()->back()->with('status', trans('communication.message.info.sent', array(
            'type'  => 'text / SMS'
        )))->with('text', true);
    }

    /**
     * Delete a text
     *
     * @param  \App\Models\Text  $text
     * @return \Illuminate\Http\Response
     */
    public function delete(Text $text) {
        $text->delete();
        return redirect()->back()->with('status', trans('communication.message.info.deleted', array(
            'type'  => 'text / SMS'
        )));
    }

    /**
     * Get the text / SMS thread of the user and recipient
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $recipient
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request, User $recipient) {
        $user = $request->user();
        $view_data = array(
            'texts'     => $recipient->incomingTexts->where('sender_id', $user->id)->merge($recipient->outgoingTexts->where('recipient_id', $user->id))->sortByDesc('created_at'),
            'recipient' => $recipient
        );
        if($user->role === 'super') {
            $view_data['admins'] = Company::all()->map(function($company) {
                return $company->admin;
            });
        }

        return view('global.texts', $view_data);
    }
}
