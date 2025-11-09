<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblCohort;

class TblCohortRegistration extends Model
{
    protected $table = 'tblcohort_registration';

    protected $primaryKey = 'transid';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'transid',
        'studentid',
        'cohort_id',
        'is_completed',
    ];

    public function cohort()
    {
        return $this->belongsTo(TblCohort::class, 'cohort_id', 'cohort_id');
    }
}
