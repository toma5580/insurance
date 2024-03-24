<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model {
    
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
    protected $table = 'emails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['message', 'status', 'subject'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Register Event Listeners
    public static function boot() {
        parent::boot();
        static::deleting(function($email) {
            $email->attachments()->delete();
        });
    }

    // Relationships
    public function attachments() {
        return $this->morphMany(Attachment::class, 'attachee');
    }
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
    public function scopeCreatedIn($query, $period, $value) {
        if(is_null($value)) {
            $value = array(
                'month' => date('m'),
                'year'  => date('Y')
            )[$period];
        }
        $date_map = array(
            'month' => array(
                'begin' => "DATE('" . date('Y') . "-{$value}-01')",
                'end'   => "LAST_DAY(DATE('" . date('Y') . "-{$value}-28'))"
            ),
            'year'  => array(
                'begin' => "DATE('" . $value . "-01-01')",
                'end'   => "DATE('" . $value. "-12-31')"
            )
        )[$period];
        return $query->whereRaw("DATE(`{$this->table}`.`created_at`) BETWEEN {$date_map['begin']} AND {$date_map['end']}");
    }
}
