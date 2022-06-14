<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerServices extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @return string
     */
    protected $table = 'customer_service_contract';

    /**
     * Declare fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'customer_id', 'service_id',
        'billing_type_id'
    ];

    public function service():BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function billing():BelongsTo
    {
        return $this->belongsTo();
    }
}
