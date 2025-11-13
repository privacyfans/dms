<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailFileModel extends Model
{
    use HasFactory;

    protected $table = 'detail_file';
    //protected $primaryKey = 'loan_app_no';
    public $timestamps = false;
    const CREATED_AT = 'date_input';

    protected $fillable = [
        'loan_app_no',
        'file',
        'branch_dir',
        'alias',
        'score',
        'validation_status',
        'validation_status_date',
        'doc_validation_status',
        'doc_validation_date',
        'doc_validated_by',
    ];

}
