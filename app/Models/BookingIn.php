<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingIn extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @return string
     */
    protected $table = 'booking_in';

    /**
     * Define fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'booking_code',
        'booking_time',
        'do_reference',
        'customer_id',
        'container_size_type_id',
        'is_customer_container_size',
        'custom_container_size',
        'cargo_goods',
        'volume',
        'notes',
        'booked_by',
        'accept_by',
        'transport_company',
        'transport_plate_number',
        'service_id',
        'is_complete',
        'barcode_path'
    ];
}
