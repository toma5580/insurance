<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendWelcomeEmail extends Job implements SelfHandling, ShouldQueue {
    use InteractsWithQueue, SerializesModels;

    /**
     * The token used to log in the new user.
     *
     * @var string
     */
    protected $token;

    /**
     * The recipient of the email.
     *
     * @var App\Models\User
     */
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param  string  $token
     * @param  App\Models\User  $user
     * @return void
     */
    public function __construct($token, User $user) {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $user = $this->user;
        Mail::send(
            'emails.welcome',
            array(
                'token'     => $this->token,
                'recipient' => $this->user
            ),
            function ($m) use ($user) {
                $m->subject('Your New Account!');
                $m->to($user->email, $user->first_name . ' ' . $user->last_name);
            }
        );
    }
}
