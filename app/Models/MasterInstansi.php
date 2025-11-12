<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInstansi extends Model
{
    use HasFactory;

    protected $table = 'master_instansi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'instansi',
        'klasifikasi_debitur',
        'status'
    ];

    public $timestamps = true;
}
