<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model {
    
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
    protected $table = 'chats';

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
    
    /**
     * Scope a query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query) {
        return $query->whereIn('status', array('received', 'sent'));
    }
    public function scopeReceived($query) {
        return $query->where('status', 'received');
    }
    public function scopeSeen($query) {
        return $query->where('status', 'seen');
    }
    public function scopeSent($query) {
        return $query->where('status', 'sent');
    }
}
