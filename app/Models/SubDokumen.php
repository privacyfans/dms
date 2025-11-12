<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDokumen extends Model
{
    use HasFactory;

    protected $table = 's_sub_dokumen';
    protected $fillable = ['dokumen_id', 'nama_sub_dokumen', 'is_mandatory'];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}