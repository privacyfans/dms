<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmpResumePerhari extends Model
{
    use HasFactory;
    protected $table = 'tmp_resume_perhari';
    protected $primaryKey = 'id';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    //protected $keyType = 'string';
    protected $fillable = [
        'kode_cabang',
        'branch_name',
        'loan_app_no',
        'nama_debitur',
        'final_status',
        'date_input',
        'user_spv1',
        'user_spv2',
        'user_spv3',
        '<10',
        '<12',
        '<15',
        '<17',
        '>17',
        'frequensi',
        'waktu_frequensi',
        'pickup_bpr1',
        'bpr1',
        'pickup_bpr2',
        'bpr2',
        'total_waktu'
        
    ];
}
