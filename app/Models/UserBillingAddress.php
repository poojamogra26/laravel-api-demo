<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserBillingAddress extends Model
{
    use HasFactory;

    protected $table = 'user_billing_addresses';

    protected $fillable = [
        'user_id',
        'address',
        'address_2',
        'city',
        'state',
        'zipcode',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
