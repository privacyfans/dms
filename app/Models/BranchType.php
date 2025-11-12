<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchType extends Model
{
    use HasFactory;

    protected $table = 'branch_type';
    //protected $primaryKey = 'cut_off';
    public $timestamps = false;
    //const CREATED_AT = 'comment_date';
    //protected $keyType = 'string';
    protected $fillable = [
        'branch_type_name',
    ];
}
