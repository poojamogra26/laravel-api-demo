<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Company extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = "companies";

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_number',
        'address',
        'address_2',
        'city',
        'state',
        'zipcode'
    ];

    public function getFullAddressAttribute()
    {
        $address[] = $this->address ?? '';
        $address[] = $this->address_2 ?? '';
        $address[] = $this->city ?? '';
        $address[] = $this->state ?? '';
        $address[] = $this->zip_code ?? '';
        return !empty(array_filter($address)) ? implode(', ', array_filter($address)) : '-';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
