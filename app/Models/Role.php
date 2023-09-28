<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrator;

class Role extends Model
{
    use HasFactory;

    protected $table = "roles";

    protected $fillable = [
        'name',
        'description',
        'status',
        'guard_name',
    ];

    public function administrator()
    {
        return $this->hasOne(Administrator::class);
    }
}



