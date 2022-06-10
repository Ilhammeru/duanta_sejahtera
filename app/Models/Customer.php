<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'name', 'address', 'phone', 'district',
        'city', 'province', 'npwp', 'pic_name',
        'pic_phone', 'deleted_at', 'email'
    ];
}
