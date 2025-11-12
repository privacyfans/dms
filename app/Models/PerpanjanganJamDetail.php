<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerpanjanganJamDetail extends Model
{
    use HasFactory;
    protected $table = 'perubahan_jam_layanan_detail';
    //protected $primaryKey = 'branch_code';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    //protected $keyType = 'string';
    protected $fillable = [
        'id_perubahan',
        'loan_app_no',
        'nama_debitur',
        'note',
       
    ];

    public function perpanjangan_detail()
    {
        return $this->hasMany(PerpanjanganJam::class,'id','id_perubahan');
    }
}
