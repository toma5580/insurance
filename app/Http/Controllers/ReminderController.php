<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ReminderController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    public function delete($reminder) {
        $reminder->delete();
        return response('', 200);
    }
    
    public function update(Request $request, Company $company) {
        $redirect = redirect()->back()->with('tab', 'reminders');
        if(isset($request->status)) {
            $rules = array(
                'reminders'             => 'array',
                'reminders.*'           => 'array',
                'status'                => 'accepted|sometimes'
            );
            foreach($request->reminders as $r_id => $reminder) {
                $rules['reminders.' . $r_id . '.days']      = 'integer|required';
                $rules['reminders.' . $r_id . '.id']        = 'integer';
                $rules['reminders.' . $r_id . '.message']   = 'required|string';
                $rules['reminders.' . $r_id . '.subject']   = 'max:64|required_if:' . 'reminders.' . $r_id . '.type' . ',email|string';
                $rules['reminders.' . $r_id . '.timeline']  = 'in:after,before|required';
                $rules['reminders.' . $r_id . '.type']      = 'in:email,text|required';
            }
            $this->validate($request, $rules);

            $company = $request->user()->company;
            $company->reminder_status = 1;
            $company->save();
    
            $warnings = array();
            // Loop through reminders
            foreach($request->reminders as $r_id => $r_reminder) {
                if(array_key_exists('id', $r_reminder)) {
                    try {
                        $reminder = $company->reminders()->findOrFail($r_reminder['id']);
                        $reminder->days     = $r_reminder['days'];
                        $reminder->message  = $r_reminder['message'];
                        $reminder->subject  = $r_reminder['subject'] ?: null;
                        $reminder->timeline = $r_reminder['timeline'];
                        $reminder->type     = $r_reminder['type'];
                        $reminder->save();
                    }catch(ModelNotFoundException $e) {
                        array_push($warnings, trans('settings.message.warning.reminders.update', array(
                            'id'    => $r_id
                        )));
                    }
                }else {
                    $company->reminders()->create(array(
                        'days'      => $r_reminder['days'],
                        'message'   => $r_reminder['message'],
                        'subject'   => empty($r_reminder['subject']) ? null : $r_reminder['subject'],
                        'timeline'  => $r_reminder['timeline'],
                        'type'      => $r_reminder['type']
                    ));
                }
            }

            if(count($warnings) === count($request->reminders)) {
                $redirect = $redirect->withErrors(array(
                    trans('settings.message.errors.reminders.fail')
                ));
            }
            if(count($warnings) > 0 && count($warnings) < count($request->reminders)) {
                $redirect = $redirect->with('warnings', $warnings);
            }
            if(count($warnings) === 0) {
                $redirect = $redirect->with('success', trans('settings.message.success.reminders.edit'));
            }
            
            return $redirect;
        }else {
            $company = $request->user()->company;
            $company->reminder_status = 0;
            $company->save();

            return $redirect->with('success', trans('settings.message.success.reminders.edit'));
        }
    }
}
 