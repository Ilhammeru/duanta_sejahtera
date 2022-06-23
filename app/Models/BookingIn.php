<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'notes',
        'booked_by',
        'accept_by',
        'transport_company',
        'transport_plate_number',
        'service_id',
        'billing_type_id',
        'is_complete',
        'barcode_path'
    ];

    /**
     * Define Belongs To relationship to Customer Table
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Define Belongs To relationship to User Table
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bookedBy():BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by', 'id');
    }

    /**
     * Define Belongs To relationship to Service Table
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service():BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function containers():HasMany
    {
        return $this->hasMany(BookingInContainer::class, 'booking_id', 'id');
    }
}
