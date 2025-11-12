<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKlasifikasiDebitur extends Model
{
    use HasFactory;

    protected $table = 'master_klasifikasi_debitur';
    protected $primaryKey = 'id';

    protected $fillable = [
        'klasifikasi_debitur',
        'status'
    ];

    public $timestamps = true;
}
