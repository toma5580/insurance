<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Models\Company;
use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;

class EmailController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Email Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management of existing emails. Why don't you
    | explore it?
    |
    */

    /**
     * Create a new email controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Get all emails related to the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $user = $request->user();
        $this->validate($request, array(
            'message'   => 'required|string',
            'recipient' => 'in:admins,brokers,clients,staff,' . $user->company->users->keyBy('id')->keys()->implode(',') . '|required',
            'subject'   => 'required|string'
        ));
        switch($request->recipient) {
            case 'admins':
                foreach(User::admin()->get() as $admin) {
                    $email = new Email(array(
                        'message'   => $request->message,
                        'status'    => 0,
                        'subject'   => $request->subject
                    ));
                    $email->recipient()->associate($admin);
                    $email->sender()->associate($user);
                    $email->save();
                    $job = new SendEmail($email, 'emails.regular');
                    $this->dispatch($job->onQueue('emails'));
                }
                break;
            case 'brokers':
                foreach($user->company->brokers as $broker) {
                    $email = new Email(array(
                        'message'   => $request->message,
                        'status'    => 0,
                        'subject'   => $request->subject
                    ));
                    $email->recipient()->associate($broker);
                    $email->sender()->associate($user);
                    $email->save();
                    $job = new SendEmail($email, 'emails.regular');
                    $this->dispatch($job->onQueue('emails'));
                }
                break;
            case 'clients':
                $client;
                switch($user->role) {
                    case 'super':
                    case 'admin':
                        $clients = $user->company->clients;
                    case 'staff':
                    case 'brokers':
                        $clients = $user->invitees;
                }
                foreach($clients as $client) {
                    $email = new Email(array(
                        'message'   => $request->message,
                        'status'    => 0,
                        'subject'   => $request->subject
                    ));
                    $email->recipient()->associate($client);
                    $email->sender()->associate($user);
                    $email->save();
                    $job = new SendEmail($email, 'emails.regular');
                    $this->dispatch($job->onQueue('emails'));
                }
                break;
            case 'staff':
                foreach($user->company->staff as $staff) {
                    $email = new Email(array(
                        'message'   => $request->message,
                        'status'    => 0,
                        'subject'   => $request->subject
                    ));
                    $email->recipient()->associate($staff);
                    $email->sender()->associate($user);
                    $email->save();
                    $job = new SendEmail($email, 'emails.regular');
                    $this->dispatch($job->onQueue('emails'));
                }
                break;
            default:
                $recipient = $user->company->users()->findOrFail($request->recipient);
                $email = new Email(array(
                    'message'   => $request->message,
                    'status'    => 0,
                    'subject'   => $request->subject
                ));
                $email->recipient()->associate($recipient);
                $email->sender()->associate($user);
                $email->save();
                $job = new SendEmail($email, 'emails.regular');
                $this->dispatch($job->onQueue('emails'));
        }

        return redirect()->back()->with('status', trans('communication.message.info.sent', array(
            'type'  => 'email'
        )));
    }

    /**
     * Delete an email
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function delete(Email $email) {
        $email->delete();
        return redirect()->back()->with('status', trans('communication.message.info.deleted', array(
            'type'  => 'email'
        )));
    }

    /**
     * Get the email thread of the user and recipient
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $recipient
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request, User $recipient) {
        $user = User::withStatus()->find($request->user()->id);
        $view_data = array(
            'emails'    => $recipient->incomingEmails->where('sender_id', $user->id)->merge($recipient->outgoingEmails->where('recipient_id', $user->id))->sortByDesc('created_at'),
            'recipient' => $recipient
        );
        if($user->role === 'super') {
            $view_data['admins'] = Company::all()->map(function($company) {
                return $company->admin;
            });
        }

        return view('global.emails', $view_data);
    }
}
