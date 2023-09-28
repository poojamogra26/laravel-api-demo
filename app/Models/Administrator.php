<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;


class Administrator extends Authenticatable implements JWTSubject
{
    use HasFactory, HasRoles, Notifiable;

    protected $table = 'administrators';

    protected $guard_name = 'adminapi';

    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'avatar',
        'last_login',
    ];

    protected $appends = ['full_name', 'avatar_url'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the admin's avatar
     */
    protected function getAvatarUrlAttribute($value)
    {
        return $this->avatar ? asset(Storage::url($this->avatar)) : null;
    }

    /**
     * Get the admin's full name
     */
    protected function getFullNameAttribute($value)
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    public function getJWTIdentifier()
    {
      return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
      return [];
    }
}
