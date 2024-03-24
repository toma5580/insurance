<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category', 'insurer', 'name', 'sub_category'];

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
        static::deleting(function($product) {
            $product->policies->each(function($policy) {
                $policy->attachments()->delete();
            });
        });
    }

    // Relationships
    public function company() {
        return $this->belongsTo(Company::class);
    }
    public function payments() {
        return $this->hasManyThrough(Payment::class, Policy::class);
    }
    public function policies() {
        return $this->hasMany(Policy::class);
    }
}
