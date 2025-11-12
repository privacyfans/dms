<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebijakanSop extends Model
{
    use HasFactory;

    protected $table = 'kebijakan_sop';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'deskripsi',
        'url',
        'status'
    ];
}
