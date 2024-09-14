<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    use HasFactory;
    protected $tabel ="lookups";
    protected $fillable = [
        'type',
        'key',
        'value',
        'description',
    ];


    
}