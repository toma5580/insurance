<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'custom_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['label', 'type', 'uuid', 'value'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Relationships
    public function model() {
        return $this->morphTo();
    }
}
