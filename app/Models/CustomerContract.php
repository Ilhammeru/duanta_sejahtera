<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerContract extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @var string
     */
    protected $table = 'customer_contract';

    /**
     * Define fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'customer_id',
        'contract_period_in_day',
        'is_auto_renewal',
        'aggreement_letter_img',
        'start_date',
        'end_date'
    ];
}
