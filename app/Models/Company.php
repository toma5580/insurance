<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Company extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['address', 'aft_api_key', 'aft_username', 'currency_code', 'email', 'email_signature', 'name', 'text_provider', 'text_signature', 'twilio_auth_token', 'twilio_number', 'twilio_sid'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    // Register Event Listeners
    public static function boot() {
        parent::boot();
        static::deleting(function($company) {
            $company->policies->each(function($policy) {
                $policy->attachments()->delete();
            });
            $company->users->each(function($user) {
                if($user->profile_image_filename !== 'default-profile.jpg') {
                    $file_storage_path = 'images/users/' . $user->profile_image_filename;
                    if(Storage::has($file_storage_path)) {
                        Storage::delete($file_storage_path);
                    }
                }
                $user->uploads->each(function($upload) {
                    $file_storage_path = 'attachments/' . $upload->filename;
                    if(Storage::has($file_storage_path)) {
                        Storage::delete($file_storage_path);
                    }
                });
                $user->attachments()->delete();
                $user->incomingEmails->merge($user->outgoingEmails)->each(function($email) {
                    $email->attachments()->delete();
                });
            });
        });
    }

    // Relationships
    public function admin() {
        return $this->hasOne(User::class)->admin();
    }
    public function brokers() {
        return $this->hasMany(User::class)->broker();
    }
    public function clients() {
        return $this->hasMany(User::class)->client();
    }
    public function payments() {
        return $this->hasManyThrough(Payment::class, User::class);
    }
    public function policies() {
        return $this->hasManyThrough(Policy::class, Product::class);
    }
    public function products() {
        return $this->hasMany(Product::class);
    }
    public function reminders() {
        return $this->hasMany(Reminder::class);
    }
    public function staff() {
        return $this->hasMany(User::class)->staff();
    }
    public function users() {
        return $this->hasMany(User::class);
    }
}
