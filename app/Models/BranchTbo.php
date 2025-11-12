<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchTbo extends Model
{
    use HasFactory;
    protected $table = 'branch_tbo';
    protected $primaryKey = 'branch_code';
    //public $incrementing = false;
    public $timestamps = false;
    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';
    protected $fillable = [
        'branch_code',
        'jml',
        'approve_flag',
        'purpose_jml',
        'user_approve',
        'approve_date',
        'total_pengajuan',
        'total_pencairan',
        'tbo_overdue',
        'limit_persen_tbo_overdue',
        'total_limit_overdue'

    ];

    public function branchlist()
    {
        return $this->hasOne(Branchlist::class,'branch_code','branch_code');
    }
}
