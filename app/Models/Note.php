<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model {
    
    /**
     * The default updated_at value.
     *
     * @static null
     */
    const UPDATED_AT = null;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'client_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['message'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relationships
    public function subject() {
        return $this->belongsTo(User::class, 'subject_id');
    }
    public function writer() {
        return $this->belongsTo(User::class, 'writer_id');
    }
}
