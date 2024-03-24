<?php

namespace App\Console\Commands;

use App\Jobs\SendEmail;
use App\Jobs\SendText;
use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Remind extends Command {

    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches jobs to send reminder notifications to clients about their expiring or expired insurance policies';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $companies = Company::all();
        foreach($companies as $company) {
            if(!empty($company->reminder_status)) {
                $reminders = $company->reminders;
                foreach($reminders as $reminder) {
                    $policies = $company->policies()->expiring($reminder->timeline, $reminder->days)->get();
                    foreach($policies as $policy) {
                        switch($reminder->type) {
                            case 'text':
                                if(!empty($owner->user->phone)) {
                                    $text = new Text(array(
                                        'message'   => $reminder->message,
                                        'status'    => 0
                                    ));
                                    $text->recipient()->associate($policy->client);
                                    $text->sender()->associate($reminder->company->admin);
                                    $text->save();
                                    $job = new SendText($text);
                                    $this->dispatch($job->onQueue('texts'));
                                    break;
                                }else {
                                    $reminder->subject = trans('emails.subject.forwarded_text');
                                }
                            default:
                                $email = new Email(array(
                                    'message'   => $reminder->message,
                                    'status'    => 0,
                                    'subject'   => $reminder->subject
                                ));
                                $email->recipient()->associate($this->user);
                                $email->sender()->associate($reminder->company->admin);
                                $email->save();
                                $job = new SendEmail($email, 'emails.reminder');
                                $this->dispatch($job->onQueue('emails'));
                        }
                    }
                }
            }
        }
    }
}
