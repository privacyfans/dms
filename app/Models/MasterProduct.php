<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProduct extends Model
{
    use HasFactory;
    protected $table = 'master_produk';
    //protected $primaryKey = 'id';
    public $timestamps = false;
    //protected $keyType = 'string';
    protected $fillable = [
        'produk',
        'jenis_produk'
    ];
}
