<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reminders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['days', 'message', 'subject', 'timeline', 'type'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relationships
    public function company() {
        return $this->belongsTo(Company::class);
    }
}
