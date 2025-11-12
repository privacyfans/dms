<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalMemo extends Model
{
    use HasFactory;

    protected $table = 'internal_memo';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'deskripsi',
        'status'
    ];

    /**
     * Relationship: Internal Memo has many files
     */
    public function files()
    {
        return $this->hasMany(InternalMemoFile::class, 'internal_memo_id', 'id');
    }
}
