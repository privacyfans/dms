<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenKategori extends Model
{
    use HasFactory;

    protected $table = 's_dokumen_kategori';
    protected $fillable = ['nama_kategori'];

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'kategori_id');
    }
}