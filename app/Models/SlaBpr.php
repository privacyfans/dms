<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaBpr extends Model
{
    use HasFactory;
    protected $table = 'log_sla_bpr';
    protected $primaryKey = 'id';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    //protected $keyType = 'string';
    protected $fillable = [
        'loan_app_no',
        'nik',
        'level_spv',
        'open_time',
        'isLocked'
    ];

  
}
