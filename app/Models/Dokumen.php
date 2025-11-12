<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 's_dokumen';
    protected $fillable = ['kategori_id', 'nama_dokumen', 'is_mandatory'];

    public function kategori()
    {
        return $this->belongsTo(DokumenKategori::class, 'kategori_id');
    }

    public function subDokumen()
    {
        return $this->hasMany(SubDokumen::class, 'dokumen_id');
    }
}