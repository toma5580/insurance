<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    
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
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount', 'date', 'method'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relationships
    public function client() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function policy() {
        return $this->belongsTo(Policy::class);
    }
    
    /**
     * Scope a query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMadeWithin($query, $period, $value = null) {
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
                'end'   => "DATE('" . $value . "-12-31')"
            )
        )[$period];
        return $query->whereRaw("DATE(`{$this->table}`.`date`) BETWEEN {$date_map['begin']} AND {$date_map['end']}");
    }
}
