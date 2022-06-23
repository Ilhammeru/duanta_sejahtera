<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingInContainer extends Model
{
    use HasFactory;

    protected $table = 'booking_in_container';

    protected $fillable = [
        'booking_id',
        'container_number',
        'container_seal',
        'cargo_goods',
        'container_size_type_id',
        'is_customer_container_size',
        'custom_container_size',
        'volume'
    ];

    public function sizeType():BelongsTo
    {
        return $this->belongsTo(ContainerSizeType::class, 'container_size_type_id', 'id');
    }
}
