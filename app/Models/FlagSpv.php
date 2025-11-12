<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlagSpv extends Model
{
    use HasFactory;
    protected $table = 'flag_spv';
    protected $primaryKey = 'id';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    //protected $keyType = 'string';
    protected $fillable = [
        'id',
        'name'
    ];
}
