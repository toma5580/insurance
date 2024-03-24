<?php

namespace App\Jobs;

use FreddieDfre\AfricasTalkingLaravel5\AfricasTalkingGateway;
use App\Jobs\Job;
use App\Models\Reminder;
use App\Models\Text;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Twilio\Rest\Client as TwilioClient;

class SendText extends Job implements SelfHandling, ShouldQueue {
    use InteractsWithQueue, SerializesModels;

    /**
     * The text object being sent
     *
     * @var App\Models\Text
     */
    protected $text;

    /**
     * Create a new job instance.
     *
     * @param  App\Models\Text  $text
     * @return void
     */
    public function __construct(Text $text) {
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $text = $this->text;
        $company = $text->sender->company;
        switch($company->text_provider) {
            case 'aft':
                $aft_gateway = new AfricasTalkingGateway($company->aft_api_key, $company->aft_username);
                $aft_gateway->sendMessage($text->recipient->phone, $text->message);
                $text->status = 1;
                $text->save();
                break;
            case 'twilio':
                $tw_client = new TwilioClient($company->twilio_sid, $company->twilio_auth_token);
                $tw_client->messages->create($text->recipient->phone, array(
                    "body" => $text->message,
                    "from" => $company->twilio_number
                ));
                $text->status = 1;
                $text->save();
                break;
            default:
                return;
        }
    }
}
