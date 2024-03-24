<?php

namespace App\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Storage;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['address', 'birthday', 'commission_rate', 'email', 'first_name', 'last_name', 'locale', 'phone', 'profile_image_filename'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    // Register Event Listeners
    public static function boot() {
        parent::boot();
        static::deleting(function($user) {
            if($user->profile_image_filename !== 'default-profile.jpg') {
                Storage::delete('images/users/' . $user->profile_image_filename);
            }
            $user->uploads->each(function($upload) {
                $file_storage_path = 'attachments/' . $upload->filename;
                if(Storage::has($file_storage_path)) {
                    Storage::delete($file_storage_path);
                }
            });
            $user->attachments()->delete();
            $user->customFields()->delete();
            $user->incomingEmails->merge($user->outgoingEmails)->each(function($email) {
                $email->attachments()->delete();
            });
        });
    }

    // Relationships
    public function attachments() {
        return $this->morphMany(Attachment::class, 'attachee');
    }
    public function company() {
        return $this->belongsTo(Company::class);
    }
    public function customFields() {
        return $this->morphMany(CustomField::class, 'model');
    }
    public function incomingChats() {
        return $this->hasMany(Chat::class, 'recipient_id');
    }
    public function incomingEmails() {
        return $this->hasMany(Email::class, 'recipient_id');
    }
    public function incomingTexts() {
        return $this->hasMany(Text::class, 'recipient_id');
    }
    public function invitees() {
        return $this->hasMany(User::class, 'inviter_id');
    }
    public function inviteePayments() {
        return $this->hasManyThrough(Payment::class, User::class, 'inviter_id');
    }
    public function inviteePolicies() {
        return $this->hasManyThrough(Policy::class, User::class, 'inviter_id');
    }
    public function inviter() {
        return $this->belongsTo(User::class, 'inviter_id');
    }
    public function notes() {
        return $this->hasMany(Note::class, 'subject_id');
    }
    public function outgoingChats() {
        return $this->hasMany(Chat::class, 'sender_id');
    }
    public function outgoingEmails() {
        return $this->hasMany(Email::class, 'sender_id');
    }
    public function outgoingTexts() {
        return $this->hasMany(Text::class, 'sender_id');
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
    public function policies() {
        return $this->hasMany(Policy::class);
    }
    public function uploads() {
        return $this->hasMany(Attachment::class, 'uploader_id');
    }
    
    /**
     * Scope a query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query) {
        return $query->where('password', '!=', 'InsuraPasswordsAreLongButNeedToBeSetByInvitedUsersSuchAsThis');
    }
    public function scopeAdmin($query) {
        return $query->whereIn('role', array('admin', 'super'));
    }
    public function scopeBroker($query) {
        return $query->where('role', 'broker');
    }
    public function scopeClient($query) {
        return $query->where('role', 'client');
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
    public function scopeStaff($query) {
        return $query->where('role', 'staff');
    }
    public function scopeSuper($query) {
        return $query->where('role', 'super');
    }
    public function scopeWithStatus($query) {
        return $query->addSelect(DB::raw("`{$this->table}`.*, `{$this->table}`.`password` != 'InsuraPasswordsAreLongButNeedToBeSetByInvitedUsersSuchAsThis' AS `status`"));
    }
}
