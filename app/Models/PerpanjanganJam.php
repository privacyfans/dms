<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerpanjanganJam extends Model
{
    use HasFactory;
    protected $table = 'perubahan_jam_layanan';
    //protected $primaryKey = 'branch_code';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    //protected $keyType = 'string';
    protected $fillable = [
        'branch_input',
        'jam_layanan',
        'user_input',
        'date_input',
        'user_spv',
        'flag_spv',
        'date_flag_spv',
        'note_spv',
        'user_spv1',
        'flag_spv1',
        'date_flag_spv1',
        'note_spv1',
        'final_flag',
        'approve_time'
       
    ];

    public function branchlist()
    {
        return $this->hasOne(Branchlist::class,'branch_code','branch_input');
    }
}
