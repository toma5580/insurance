<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Text extends Model {
    
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
    protected $table = 'texts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['message', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relationships
    public function recipient() {
        return $this->belongsTo(User::class, 'recipient_id');
    }
    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
