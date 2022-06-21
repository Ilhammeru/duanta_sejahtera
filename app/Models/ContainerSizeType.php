<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerSizeType extends Model
{
    use HasFactory;

    /**
     * Define table name
     * 
     * @return stringf
     */
    protected $table = 'container_size_and_type';

    /**
     * Define fillable field in database
     * 
     * @return array
     */
    protected $fillable = [
        'size',
        'type'
    ];
}
