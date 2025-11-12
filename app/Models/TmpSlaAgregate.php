<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmpSlaAgregate extends Model
{
    use HasFactory;

    protected $table = 'tmp_sla_agregate';
    protected $primaryKey = 'id';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    //protected $keyType = 'string';
    protected $fillable = [
        'tanggal',
        'kurang_10',
        'sum_kurang10',
        'kurang_12',
        'sum_kurang12',
        'kurang_15',
        'sum_kurang15',
        'kurang_17',
        'sum_kurang17',
        'lebih_17',
        'sum_lebih17',
        'total_pengajuan',
        'total_waktu_pengajuan',
        'frekuensi',
        'frekuensi_waktu',
        'waktu_tunggu_bpr1',
        'waktu_bpr1',
        'waktu_tunggu_bpr2',
        'waktu_bpr2',
        'total_waktu_keseluruhan',
        'perbatch_ub',
        'pemenuhan_kekurangan_ub',
        'rata_waktu_tunggu_bpr1',
        'pengerjaan_bpr1',
        'rata_waktu_tunggu_bpr2',
        'pengerjaan_bpr2',
        'rata_waktu_perhari',
        'rata_waktu_perhari_ideal',
        'rata_waktu_perhari_dcrm',
        'waktu_terlama',
        'waktu_tercepat'
        
    ];
}
