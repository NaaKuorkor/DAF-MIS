<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblCohort extends Model
{
    protected $table = "tblcohort";

    protected $fillable = [
        'cohort_name',
        'start_date',
        'end_date',
    ];

    protected function cast(): array
    {
        return [];
    }
}
