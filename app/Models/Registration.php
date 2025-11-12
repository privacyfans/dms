<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registration';
    //protected $primaryKey = 'cut_off';
    public $timestamps = false;
    //const CREATED_AT = 'comment_date';
    //protected $keyType = 'string';
    protected $fillable = [
        'loan_app_no',
        'no_register',
        'processed',
    ];
}
