<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    use HasFactory;
    protected $table = 'tbl_log_ai';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'loan_app_no',
        'request',
        'request_date',
        'response',
        'response_code',
        'response_from_ai',
        'response_from_ai_date'
       
    ];

    
}
