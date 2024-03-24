<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Attachment Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the management and update of existing user notes
    | Why don't you explore it?
    |
    */

    /**
     * Create a new note controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Add a note
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request) {
        $this->validate($request, [
            'client'    => 'exists:users,id|integer|required',
            'message'   => 'max:2048|required|string'
        ]);
        $subject = User::find($request->client);
        $writer = $request->user();        
        $note = new Note(array(
            'message'   => str_replace("\n", '<br/>', $request->message)
        ));
        $note->subject()->associate($subject);
        $note->writer()->associate($writer);
        $note->save();

        return redirect()->back()->with('success', trans('notes.message.success.added'));
    }

    /**
     * Delete a note
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function delete(Note $note) {
        $note->delete();
        return redirect()->back()->with('status', trans('notes.message.info.deleted'));
    }
}
