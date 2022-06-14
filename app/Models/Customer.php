<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    /**
     * Attribute to define table name
     * 
     * @return string
     */
    protected $table = 'customers';

    /**
     * Attribute to define fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'name', 'address', 'phone', 'district',
        'city', 'province', 'npwp', 'pic_name',
        'pic_phone', 'deleted_at', 'email'
    ];
    
    /**
     * Define customer relationship to customer service
     *
     * @return HasOne
     */
    public function services(): HasMany
    {
        return $this->hasMany(CustomerServices::class, 'customer_id', 'id');
    }

    /**
     * Define customer relation to customer contract aggreement
     * 
     * @return HasOne
     */
    public function contract(): HasOne
    {
        return $this->hasOne(CustomerContract::class, 'customer_id', 'id');
    }

    /**
     * Define customer relation to province
     * 
     * @return HasOne
     */
    public function _province():BelongsTo
    {
        return $this->belongsTo(Province::class, 'province', 'id');
    }

    /**
     * Define customer relation to citi / regency
     * 
     * @return HasOne
     */
    public function regency():BelongsTo
    {
        return $this->belongsTo(Regency::class, 'city', 'id');
    }
}
