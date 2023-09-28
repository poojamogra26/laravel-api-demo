<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use App\Models\UserBillingAddress;
use App\Models\Company;
use App\Models\Client;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    public $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */                    
    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'phone_number',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['full_name', 'profile_image_url'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userBillingAddress()
    {
        return $this->hasOne(UserBillingAddress::class);
    } 

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Get the user's profile image
     */
    protected function getProfileImageUrlAttribute($value)
    {
        return $this->profile_image ? asset(Storage::url($this->profile_image)) : null;
    }

    /**
     * Get the user's full name
     */
    protected function getFullNameAttribute($value)
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }
}

