<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingInContainer extends Model
{
    use HasFactory;

    protected $table = 'booking_in_container';

    protected $fillable = [
        'booking_id',
        'container_number',
        'container_seal'
    ];
}
