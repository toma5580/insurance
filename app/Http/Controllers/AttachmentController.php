<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Email;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Http\Request;

class AttachmentController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Attachment Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management and update of existing attachments
    | Why don't you explore it?
    |
    */

    /**
     * Create a new attachment controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Add an attachment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $attachee
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $this->validate($request, [
            'attachee'      => 'exists:' . array(
                'client'    => 'users',
                'email'     => 'emails',
                'policy'    => 'policies'
            )[$request->attachee_type] . ',id|integer|required',
            'attachee_type' => 'in:client,email,policy|required',
            'attachment'    => 'mimes:bmp,doc,docx,gif,jpeg,jpg,png,ppt,pptx,pdf,svg,xls,xlsx|required',
            'name'          => 'max:32|required|string'
        ]);
        $attacheeClass = array(
            'client'    => new User,
            'email'     => new Email,
            'policy'    => new Policy
        )[$request->attachee_type];
        $attachee = $attacheeClass->find($request->attachee);
        $attachment = new Attachment(array(
            'name'      => $request->name
        ));
        $uploader = $request->user();
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $attachment_filename = str_random(10) . '.' . $request->file('attachment')->getClientOriginalExtension();
            try{
                $request->file('attachment')->move(storage_path('app/attachments/'), $attachment_filename);
            }catch(FileException $e) {
                return redirect()->back()->withErrors(array(
                    trans('attachments.message.errors.file', array(
                        'filename'  => $request->file('attachment')->getClientOriginalName()
                    ))
                ))->withInput();
            }
            $attachment->filename = $attachment_filename;
        }else {
            return redirect()->back()->withErrors(array(
                trans('attachments.message.errors.file', array(
                    'filename'  => $request->file('attachment')->getClientOriginalName()
                ))
            ))->withInput();
        }
        $attachment->attachee()->associate($attachee);
        $attachment->uploader()->associate($uploader);
        $attachment->save();

        return redirect()->back()->with('success', trans('attachments.message.success.added'));
    }

    /**
     * Delete an attachment
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Attachment $attachment) {
        $attachment->delete();
        return redirect()->back()->with('status', trans('attachments.message.info.deleted'));
    }
}
