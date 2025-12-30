<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TblCohort;
use App\Models\TblStudent;

class TblCohortRegistration extends Model
{
    protected $table = 'tblcohort_registration';

    protected $primaryKey = ['studentid', 'cohort_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'studentid',
        'cohort_id',
        'is_completed',
    ];

    public function cohort()
    {
        return $this->belongsTo(TblCohort::class, 'cohort_id', 'cohort_id');
    }

    public function student()
    {
        return $this->belongsTo(TblStudent::class, 'studentid', 'studentid');
    }



        
    
