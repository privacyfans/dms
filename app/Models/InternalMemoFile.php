<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalMemoFile extends Model
{
    use HasFactory;

    protected $table = 'internal_memo_files';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'internal_memo_id',
        'file_name',
        'file_path'
    ];

    /**
     * Relationship: File belongs to Internal Memo
     */
    public function internalMemo()
    {
        return $this->belongsTo(InternalMemo::class, 'internal_memo_id', 'id');
    }
}
