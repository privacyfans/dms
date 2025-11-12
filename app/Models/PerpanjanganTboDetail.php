<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerpanjanganTboDetail extends Model
{
    use HasFactory;
    protected $table = 'perubahan_tgl_tbo_detail';
    //protected $primaryKey = 'branch_code';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    //protected $keyType = 'string';
    protected $fillable = [
        'id_perubahan',
        'loan_app_no',
        'dokumen_tbo',
        'tgl_sebelum_perubahan',
        'tgl_sesudah_perubahan',
        'nama_debitur',
        'perubahan_ke',
        'note',
       
    ];
   
    
    public function perpanjangan_detail()
    {
        return $this->hasMany(PerpanjanganTbo::class,'id','id_perubahan');
    }
}
