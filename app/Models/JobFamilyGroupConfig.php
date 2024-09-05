<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFamilyGroupConfig extends Model
{
    protected $table = 'job_family_group_config';

    protected $fillable = ['job_family', 'job_family_group'];
}
