<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapProduct extends Model
{
    use HasFactory;
    protected $table = 'map_produk_dokumen';
    //protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $keyType = 'string';
    protected $fillable = [
        'id_jenis_produk',
        'nama_dokumen',
        'mandatory'
    ];
}
