<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'policies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['beneficiaries', 'expiry', 'payer', 'premium', 'ref_no', 'renewal', 'special_remarks', 'type'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Soft Delete timestamp
    protected $dates = ['deleted_at'];
    
    // Register Event Listeners
    public static function boot() {
        parent::boot();
        static::deleting(function($policy) {
            $policy->attachments()->delete();
            $policy->customFields()->delete();
        });
    }

    // Relationships
    public function attachments() {
        return $this->morphMany(Attachment::class, 'attachee');
    }
    public function customFields() {
        return $this->morphMany(CustomField::class, 'model');
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function client() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Scope a query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query, $type) {
        $interval_map = array(
            'annual'    => 'YEAR',
            'monthly'   => 'MONTH'
        );
        return $query->{$type}()->whereRaw("CURDATE() BETWEEN DATE_SUB(`{$this->table}`.`expiry`, INTERVAL 1 {$interval_map[$type]}) AND `{$this->table}`.`expiry`");
    }
    public function scopeAnnual($query) {
        return $query->where('type', 'annual');
    }
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
    public function scopeExpiring($query, $timeline, $days) {
        $operator = array(
            'after'     => 'ADD',
            'before'    => 'SUB'
        )[$timeline];
        return $query->whereRaw("CURDATE() BETWEEN `{$this->table}`.`expiry` AND DATE_{$operator}(`{$this->table}`.`expiry`, INTERVAL {$days} DAY)");
    }
    public function scopeInsuraFilter($query, $filters) {
        $payments_table = (new Payment)->getTable();
        $modifiers = array(
            'due_max' => function($query, $value) use($payments_table) {
                return $query->whereRaw("(`{$this->table}`.`premium` - (SELECT SUM(`{$payments_table}`.`amount`) FROM `{$payments_table}` WHERE `{$payments_table}`.`policy_id` = `{$this->table}`.`id`)) <= {$value}");
            },
            'due_min' => function($query, $value) use($payments_table) {
                return $query->whereRaw("(`{$this->table}`.`premium` - (SELECT SUM(`{$payments_table}`.`amount`) FROM `{$payments_table}` WHERE `{$payments_table}`.`policy_id` = `{$this->table}`.`id`)) >= {$value}");
            },
            'expiry_from' => function($query, $value) {
                return $query->whereRaw("`{$this->table}`.`expiry` >= DATE({$value})");
            },
            'expiry_to' => function($query, $value) {
                return $query->whereRaw("`{$this->table}`.`expiry` <= DATE({$value})");
            },
            'policy_ref' => function($query, $value) {
                return $query->whereRaw("`{$this->table}`.`ref_no` LIKE '%{$value}%'");
            },
            'premium_max' => function($query, $value) {
                return $query->whereRaw("`{$this->table}`.`premium` <= {$value}");
            },
            'premium_min' => function($query, $value) {
                return $query->whereRaw("`{$this->table}`.`premium` >= {$value}");
            },
            'product' => function($query, $value) {
                return $query->whereRaw("`{$this->table}`.`product_id` = {$value}");
            },
            'renewal_from' => function($query, $value) {
                return $query->whereRaw("`{$this->table}`.`renewal` <= DATE({$value})");
            },
            'renewal_to' => function($query, $value) {
                return $query->whereRaw("`{$this->table}`.`renewal` <= DATE({$value})");
            }
        );
        if(isset($filters['due_max']) || isset($filters['due_min'])) {
            $query->leftJoin($payments_table, "{$this->table}.id", '=', "{$payments_table}.policy_id");
        }
        foreach($filters as $filter => $value) {
            $query = $modifiers[$filter]($query, $value);
        }
        return $query->groupBy("{$this->table}.id");
    }
    public function scopeMonthly($query) {
        return $query->where('type', 'monthly');
    }
}
