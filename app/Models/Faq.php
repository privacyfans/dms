<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
class Faq extends Model
{
    use HasFactory;
    use SearchableTrait;


    protected $table = 'faq';
    protected $primaryKey = 'id';
    //public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    //protected $keyType = 'string';

    protected $searchable = [
        'columns' => [
        'faq.question'  => 10,
        'faq.answer'   => 10,
        'faq.id'   => 10,
        ]
    ];

    protected $fillable = [
        'question',
        'answer'
    ];
}
