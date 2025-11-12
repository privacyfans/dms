<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Comment extends Model
{
    use HasFactory;
    use Sortable;
    protected $table = 'comment';
    //protected $primaryKey = 'id';
    public $timestamps = false;
    const CREATED_AT = 'comment_date';
    //protected $keyType = 'string';
    protected $fillable = [
        'loan_app_no',
        'comment',
        'user_name',
        'level_spv',
        'comment_date',
        'flag_spv',
        'tbo_date',
        'reason',
    ];

    public $sortable = ['id','comment_date'];


}
