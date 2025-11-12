<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutOffHq extends Model
{
    use HasFactory;

    protected $table = 'cut_off_hq';
    //protected $primaryKey = 'cut_off';
    public $timestamps = false;
    //const CREATED_AT = 'comment_date';
    //protected $keyType = 'string';
    protected $fillable = [
        'cut_off',
        'approve_flag',
        'user_input',
        'date_input',
        'purpose_cut_off',
        'user_approve',
        'approve_date'
    ];
}
