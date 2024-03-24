<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Attachment extends Model {
    
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
    protected $table = 'attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['filename', 'name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Register Event Listeners
    public static function boot() {
        parent::boot();
        static::deleting(function($attachment) {
            $file_storage_path = 'attachments/' . $attachment->filename;
            if(Storage::has($file_storage_path)) {
                Storage::delete($file_storage_path);
            }
        });
    }

    // Relationships
    public function attachee() {
        return $this->morphTo();
    }
    public function uploader() {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
