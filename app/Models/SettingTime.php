<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTime extends Model
{
    use HasFactory;

    protected $table = 'setting_time';
    //protected $primaryKey = 'cut_off';
    public $timestamps = false;
    //const CREATED_AT = 'comment_date';
    //protected $keyType = 'string';
    protected $fillable = [
        'waktu'
    ];
}
