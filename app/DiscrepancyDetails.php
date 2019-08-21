<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscrepancyDetails extends Model
{
    protected $table = 'disc_det';
    protected $fillable = [
        'master_id',
        'reg_date',
        'iso_number',
        'auditor',
        'audit_to',
        'inc_reason',
        'corr_desc_short',
        'corr_desc',
        'kd_person',
        'need_plan',
        'kd_status',
        'kd_close_plan',
        'kd_close_fact',
        'effect_desc',
        'eff_person',
        'eff_status',
        'status',
        'eff_close_date',
        'doc',
        'created_by',
        'deleted_by',
        'deleted_at',
    ];
}
