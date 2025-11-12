<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchlist extends Model
{
    use HasFactory;
    protected $table = 'branchlist';
    protected $primaryKey = 'branch_code';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';
    protected $fillable = [
        'branch_code',
        'branch_name',
        'parent_branch',
        'parent_type',
    ];

    public function branchtbo()
    {
        return $this->hasMany(BranchTbo::class,'branch_code','branch_code');
    }
}
