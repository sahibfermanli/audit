<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discrepancy extends Model
{
    protected $table = 'disc_records';
    protected $fillable = [
        'item_date',
        'flt_number',
        'proc_id',
        'dep_id',
        'item_desc',
        'item_desc_short',
        'source_id',
        'need_kd',
        'status',
        'detect_person',
        'resolve_person',
        'doc',
        'created_by',
        'deleted_by',
        'deleted_at',
    ];
}
