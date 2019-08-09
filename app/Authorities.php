<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Authorities extends Model
{
    protected $table = 'authorities';
    protected $fillable = [
        'authority',
        'created_by',
        'deleted_by',
        'deleted_at'
    ];
}
