<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenEarly extends Model
{
    use HasFactory;
    protected $table = 'dokumen_early';
    //protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $keyType = 'string';
    protected $fillable = [
        'nama_dokumen',
    ];
}
