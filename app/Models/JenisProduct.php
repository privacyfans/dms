<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisProduct extends Model
{
    use HasFactory;
    protected $table = 'master_jenis_produk';
    //protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $keyType = 'string';
    protected $fillable = [
        'jenis_produk'
    ];
}
